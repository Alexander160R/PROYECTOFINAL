<?php
require 'db.php';

try {
    $stmt = $pdo->query("SELECT DATABASE() AS db_name");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Conectado a la base de datos: " . $result['db_name'];
} catch (PDOException $e) {
    echo "Error en la conexión: " . $e->getMessage();
}
?>
