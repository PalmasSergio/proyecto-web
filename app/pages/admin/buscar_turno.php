<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Obtener el ID del turno a buscar
    $turno_id = $_GET['turno_id'] ?? '';

    if (!empty($turno_id)) {
        // Conexión a la base de datos
        $mysqli = new mysqli("localhost", "root", "", "pacientes_turnos");

        // Verificar la conexión
        if ($mysqli->connect_errno) {
            $message = 'Error en la conexión a la base de datos: ' . $mysqli->connect_error;
        } else {
            // Consultar el turno por ID
            $sql = "SELECT * FROM turnos WHERE id = ?";
            $stmt = $mysqli->prepare($sql);

            if ($stmt) {
                $stmt->bind_param("i", $turno_id);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    // Mostrar información del turno
                    $turno = $result->fetch_assoc();
                    $message = 'Turno encontrado:';
                    $comprobante = [
                        'nombre' => $turno['nombre'],
                        'email' => $turno['email'],
                        'telefono' => $turno['telefono'],
                        'fecha' => $turno['fecha'],
                        'hora' => $turno['hora'],
                        'turno_id' => $turno['id']
                    ];
                } else {
                    $message = 'No se encontró ningún turno con ese ID.';
                }

                $stmt->close();
            } else {
                $message = 'Error en la preparación de la consulta: ' . $mysqli->error;
            }

            $mysqli->close();
        }
    } else {
        $message = 'Por favor, ingrese un ID de turno.';
    }
} else {
    http_response_code(405);
    $message = 'Método no permitido';
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado de Búsqueda</title>
    <style>
           body {
            font-family: Arial, sans-serif;
            background-color: #f0f8ff; /* Fondo celeste */
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1, h2 {
            color: #333;
        }

        form {
            margin-bottom: 20px;
        }

        /* Estilos para el resultado de búsqueda */
        .message {
            background-color: #fff;
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .result {
            background-color: #f0f0f0;
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <h1>Resultado de Búsqueda</h1>
    <p><?php echo $message; ?></p>
    <?php if (isset($comprobante)) : ?>
        <h2>Comprobante del Turno:</h2>
        <ul>
            <li>ID: <?php echo $comprobante['turno_id']; ?></li>
            <li>Nombre: <?php echo $comprobante['nombre']; ?></li>
            <li>Email: <?php echo $comprobante['email']; ?></li>
            <li>Teléfono: <?php echo $comprobante['telefono']; ?></li>
            <li>Fecha: <?php echo $comprobante['fecha']; ?></li>
            <li>Hora: <?php echo $comprobante['hora']; ?></li>
        </ul>
    <?php endif; ?>
</body>
</html>
