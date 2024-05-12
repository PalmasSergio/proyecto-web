<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos de la solicitud POST
    $nombre = $_POST['nombre'] ?? '';
    $email = $_POST['email'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $fecha = $_POST['fecha'] ?? '';
    $hora = $_POST['hora'] ?? '';

    // Verifica si alguno de los campos está vacío
    if (empty($nombre) || empty($email) || empty($telefono) || empty($fecha) || empty($hora)) {
        $response = ['message' => 'Por favor, complete todos los campos.'];
    } else {
        // Conexión a la base de datos
        $mysqli = new mysqli("localhost", "root", "", "pacientes_turnos");

        // Verificar la conexión
        if ($mysqli->connect_errno) {
            $response = ['message' => 'Error en la conexión a la base de datos: ' . $mysqli->connect_error];
        } else {
            // Preparar la consulta SQL
            $sql = "INSERT INTO turnos (nombre, email, telefono, fecha, hora) VALUES (?, ?, ?, ?, ?)";
            $stmt = $mysqli->prepare($sql);

            // Verificar si la preparación de la consulta tuvo éxito
            if ($stmt) {
                // Vincular parámetros y ejecutar la consulta
                $stmt->bind_param("sssss", $nombre, $email, $telefono, $fecha, $hora);
                if ($stmt->execute()) {
                    // Obtener el ID del turno insertado
                    $turno_id = $mysqli->insert_id;
                    
                    // Comprobante de turno
                    $comprobante = [
                        'nombre' => $nombre,
                        'email' => $email,
                        'telefono' => $telefono,
                        'fecha' => $fecha,
                        'hora' => $hora,
                        'turno_id' => $turno_id // ID del turno
                    ];

                    // Mensaje de éxito con el comprobante
                    $response = ['message' => 'Turno solicitado correctamente.', 'comprobante' => $comprobante];
                } else {
                    $response = ['message' => 'Error al solicitar el turno: ' . $stmt->error];
                }
                // Cerrar la consulta preparada
                $stmt->close();
            } else {
                $response = ['message' => 'Error en la preparación de la consulta: ' . $mysqli->error];
            }

            // Cerrar la conexión
            $mysqli->close();
        }
    }

    // Devolver respuesta JSON
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    http_response_code(405);
    echo 'Método no permitido';
}
?>
