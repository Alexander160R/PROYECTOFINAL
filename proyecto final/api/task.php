<?php
// Habilitar la visualización de errores
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Permitir solicitudes desde cualquier dominio (CORS)
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');  // Permitir Content-Type en los encabezados
header('Content-Type: application/json');

// Manejar la solicitud OPTIONS (solicitud preflight de CORS)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    // Respondemos con un código 200 y los encabezados necesarios para la pre-solicitud CORS
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    header('Access-Control-Allow-Headers: Content-Type');
    exit(0); // Salir aquí para evitar la ejecución de más código
}

// Conectar a la base de datos
$host = 'localhost';
$db = 'exafinal';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Manejar el método GET (para listar tareas)
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $sql = "SELECT * FROM tasks";  // Asegúrate de que el nombre de la tabla sea correcto ('tasks')
    $result = $conn->query($sql);

    $tasks = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $tasks[] = $row;
        }
        echo json_encode($tasks);
    } else {
        echo json_encode(array("message" => "No se encontraron tareas."));
    }
}

// Manejar el método POST (para crear tarea)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['title'])) {
        $title = $data['title'];
        $sql = "INSERT INTO tasks (title) VALUES ('$title')";  // Asegúrate de que el nombre de la tabla sea correcto ('tasks')

        if ($conn->query($sql) === TRUE) {
            echo json_encode(array("message" => "Tarea creada con éxito."));
        } else {
            echo json_encode(array("message" => "Error al crear tarea: " . $conn->error));
        }
    } else {
        echo json_encode(array("message" => "El campo 'title' es obligatorio."));
    }
}

// Manejar el método PUT (para actualizar tarea)
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    // Obtener el ID de la tarea a actualizar desde la URL
    $id = $_GET['id'];
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['title'])) {
        $title = $data['title'];
        $sql = "UPDATE tasks SET title='$title' WHERE id=$id";  // Asegúrate de que el nombre de la tabla sea correcto ('tasks')

        if ($conn->query($sql) === TRUE) {
            echo json_encode(array("message" => "Tarea actualizada con éxito."));
        } else {
            echo json_encode(array("message" => "Error al actualizar tarea: " . $conn->error));
        }
    } else {
        echo json_encode(array("message" => "El campo 'title' es obligatorio."));
    }
}

// Manejar el método DELETE (para eliminar tarea)
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    $id = $_GET['id'];
    $sql = "DELETE FROM tasks WHERE id=$id";  // Asegúrate de que el nombre de la tabla sea correcto ('tasks')

    if ($conn->query($sql) === TRUE) {
        echo json_encode(array("message" => "Tarea eliminada con éxito."));
    } else {
        echo json_encode(array("message" => "Error al eliminar tarea: " . $conn->error));
    }
}

$conn->close();
?>
