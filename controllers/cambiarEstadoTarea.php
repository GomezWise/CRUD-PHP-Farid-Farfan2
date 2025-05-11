<?php
session_start();
include "../model/conexion.php";
require '../vendor/autoload.php'; // Asegúrate de que la ruta a Composer sea correcta

use ElephantIO\Client;
use ElephantIO\Engine\SocketIO\Version2X;

header('Content-Type: application/json'); // Siempre devolver JSON

if (!isset($_SESSION['usuario'])) {
    http_response_code(401);
    echo json_encode(["error" => "Acceso denegado."]);
    exit();
}

if (isset($_GET['id']) && isset($_GET['estado'])) {
    $id_tarea = intval($_GET['id']);
    $nuevo_estado = $_GET['estado'];

    // Validar estado permitido
    $estados_validos = ['Pendiente', 'En progreso', 'Completado'];
    if (!in_array($nuevo_estado, $estados_validos)) {
        http_response_code(400);
        echo json_encode(["error" => "Estado no válido."]);
        exit();
    }

    // Actualizar tarea
    $stmt = $conexion->prepare("UPDATE tareas SET estado = ? WHERE id = ?");
    $stmt->bind_param("si", $nuevo_estado, $id_tarea);

    if ($stmt->execute()) {
        $usuario_id = is_array($_SESSION['usuario']) ? $_SESSION['usuario']['id_persona'] : $_SESSION['usuario'];
        $accion = "Cambio de estado";
        $descripcion = $conexion->real_escape_string("Estado cambiado a '$nuevo_estado'");

        // Guardar en historial
        $conexion->query("INSERT INTO historial_tareas (id_tarea, id_usuario, accion, descripcion)
                          VALUES ($id_tarea, $usuario_id, '$accion', '$descripcion')");

        // Emitir evento a Socket.IO
        try {
            $client = new Client(new Version2X('http://localhost:3000'));
            $client->initialize();
            $client->emit('tarea_actualizada', [
                'id' => $id_tarea,
                'estado' => $nuevo_estado,
                'usuario_id' => $usuario_id
            ]);
            $client->close();
        } catch (Exception $e) {
            error_log("Error al emitir evento Socket.IO: " . $e->getMessage());
        }

        echo json_encode(["success" => true, "message" => "Estado actualizado"]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Error al actualizar el estado"]);
    }

    exit();
} else {
    http_response_code(400);
    echo json_encode(["error" => "Parámetros incompletos."]);
    exit();
}
?>
