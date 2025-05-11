<?php
session_start();
include "../model/conexion.php";
require '../vendor/autoload.php'; // Asegúrate de que esta ruta sea correcta

use ElephantIO\Client;
use ElephantIO\Engine\SocketIO\Version2X;

// Verificar si se recibió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validar sesión
    if (!isset($_SESSION['usuario'])) {
        die("Error: sesión no iniciada.");
    }

    // Validar campos requeridos
    if (empty($_POST["id"]) || empty($_POST["titulo"]) || empty($_POST["fecha_limite"])) {
        die("Error: Faltan datos obligatorios.");
    }

    $id_tarea = intval($_POST["id"]);
    $titulo = $conexion->real_escape_string($_POST["titulo"]);
    $descripcion = $conexion->real_escape_string($_POST["descripcion"]);
    $fecha_limite = $conexion->real_escape_string($_POST["fecha_limite"]);

    // Obtener ID del usuario actual
    $usuario_id = is_array($_SESSION['usuario']) ? $_SESSION['usuario']['id_persona'] : $_SESSION['usuario'];

    // Actualizar la tarea
    $sql = "UPDATE tareas 
            SET titulo = '$titulo', descripcion = '$descripcion', fecha_limite = '$fecha_limite'
            WHERE id = $id_tarea";

    if (!$conexion->query($sql)) {
        die("Error al actualizar la tarea: " . $conexion->error);
    }

    // Eliminar colaboradores anteriores (excepto el creador)
    $conexion->query("DELETE FROM tarea_usuario WHERE id_tarea = $id_tarea AND id_usuario != $usuario_id");

    // Insertar nuevos colaboradores
    if (!empty($_POST['colaboradores'])) {
        foreach ($_POST['colaboradores'] as $colaborador_id) {
            $colaborador_id = intval($colaborador_id);
            $conexion->query("INSERT IGNORE INTO tarea_usuario (id_tarea, id_usuario) VALUES ($id_tarea, $colaborador_id)");
        }
    }

    // Obtener nombre del usuario
    $usuario_result = $conexion->query("SELECT nombre, apellido FROM personas WHERE id_persona = $usuario_id");
    $usuario = $usuario_result->fetch_assoc();
    $nombre_completo = $usuario ? $usuario['nombre'] . ' ' . $usuario['apellido'] : 'Usuario desconocido';

    // Registrar historial de actualización
    $descripcion_cambio = "El usuario $nombre_completo actualizó la tarea: $titulo";
    $conexion->query("
        INSERT INTO historial_tareas (id_tarea, id_usuario, accion, descripcion)
        VALUES ($id_tarea, $usuario_id, 'actualización', '$descripcion_cambio')
    ");

    // Emitir evento a Socket.IO
    try {
        $client = new Client(new Version2X('http://localhost:3000'));
        $client->initialize();
        $client->emit('tarea_actualizada', [
            'id' => $id_tarea,
            'titulo' => $titulo,
            'usuario_id' => $usuario_id,
            'usuario_nombre' => $nombre_completo
        ]);
        $client->close();
    } catch (Exception $e) {
        error_log("Error al emitir evento Socket.IO: " . $e->getMessage());
    }

    // Redirigir al listado de tareas
    header("Location: ../views/tareas.php");
    exit();
} else {
    echo "Acceso no permitido.";
}
?>
