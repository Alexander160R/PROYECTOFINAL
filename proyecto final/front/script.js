const apiUrl = 'http://localhost/proyecto%20final/api/task.php';

// Cargar tareas al inicio
function loadTasks() {
  fetch(apiUrl)
    .then(response => response.json())
    .then(data => {
      if (Array.isArray(data)) {
        const taskList = document.getElementById('task-list');
        taskList.innerHTML = ''; // Limpiar lista

        data.forEach(task => {
          const li = document.createElement('li');
          li.innerHTML = `
            ID: ${task.id} - Tarea: ${task.title}
            <button onclick="editTask(${task.id}, '${task.title}')">Editar</button>
            <button onclick="deleteTask(${task.id})">Eliminar</button>
          `;
          taskList.appendChild(li);
        });
      } else {
        console.log('No se encontraron tareas.');
      }
    })
    .catch(error => {
      console.error('Error al cargar las tareas:', error);
    });
}

// Agregar tarea
document.getElementById('task-form').addEventListener('submit', function(event) {
  event.preventDefault();
  const title = document.getElementById('task-title').value;

  fetch(apiUrl, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({ title: title }),
  })
  .then(response => response.json())
  .then(data => {
    console.log(data.message);
    loadTasks(); // Recargar tareas
    document.getElementById('task-title').value = ''; // Limpiar input
  })
  .catch(error => {
    console.error('Error al agregar la tarea:', error);
  });
});

// Editar tarea
function editTask(id, currentTitle) {
  const newTitle = prompt('Editar tarea:', currentTitle);
  if (newTitle) {
    fetch(`${apiUrl}?id=${id}`, {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ title: newTitle }),
    })
    .then(response => response.json())
    .then(data => {
      console.log(data.message);
      loadTasks(); // Recargar tareas
    })
    .catch(error => {
      console.error('Error al actualizar la tarea:', error);
    });
  }
}

// Eliminar tarea
function deleteTask(id) {
  if (confirm('¿Estás seguro de que quieres eliminar esta tarea?')) {
    fetch(`${apiUrl}?id=${id}`, {
      method: 'DELETE',
    })
    .then(response => response.json())
    .then(data => {
      console.log(data.message);
      loadTasks(); // Recargar tareas
    })
    .catch(error => {
      console.error('Error al eliminar la tarea:', error);
    });
  }
}

// Cargar las tareas cuando la página cargue
loadTasks();
