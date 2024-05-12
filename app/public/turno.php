<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];

    // Conexión a la base de datos (debes ajustar los datos de conexión)
    $mysqli = new mysqli("localhost", "root", "", "paciente_turnos");

    // Verificar la conexión
    if ($mysqli->connect_error) {
        die("Error en la conexión: " . $mysqli->connect_error);
    }

    // Preparar la consulta SQL
    $sql = "INSERT INTO turnos (nombre, email, telefono, fecha, hora) VALUES (?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($sql);

    // Vincular parámetros y ejecutar la consulta
    $stmt->bind_param("sssss", $nombre, $email, $telefono, $fecha, $hora);
    if ($stmt->execute()) {
        $response = ['message' => 'Turno solicitado correctamente.'];
    } else {
        $response = ['message' => 'Error al solicitar el turno.'];
    }

    // Cerrar la conexión
    $stmt->close();
    $mysqli->close();

    // Devolver respuesta JSON
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    http_response_code(405);
    echo 'Método no permitido';
}
?>

