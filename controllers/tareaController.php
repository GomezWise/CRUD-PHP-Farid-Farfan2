<?php
session_start();
include "../model/conexion.php";
require '../vendor/autoload.php'; // Asegúrate de que esta ruta sea correcta

use ElephantIO\Client;
use ElephantIO\Engine\SocketIO\Version2X;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['usuario'])) {
        die("Error: sesión no iniciada.");
    }

    $usuario_id = is_array($_SESSION['usuario']) ? $_SESSION['usuario']['id_persona'] : $_SESSION['usuario'];

    if (empty($_POST["titulo"]) || empty($_POST["fecha_limite"])) {
        die("Error: Título y fecha límite son obligatorios.");
    }

    $titulo = $conexion->real_escape_string($_POST["titulo"]);
    $descripcion = $conexion->real_escape_string($_POST["descripcion"]);
    $fecha_limite = $conexion->real_escape_string($_POST["fecha_limite"]);

    $stmt = $conexion->prepare("INSERT INTO tareas (titulo, descripcion, fecha_limite, creado_por) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $titulo, $descripcion, $fecha_limite, $usuario_id);

    if (!$stmt->execute()) {
        die("Error al insertar tarea: " . $stmt->error);
    }

    $id_tarea = $stmt->insert_id;

    $stmt_asignacion = $conexion->prepare("INSERT INTO tarea_usuario (id_tarea, id_usuario) VALUES (?, ?)");
    $stmt_asignacion->bind_param("ii", $id_tarea, $usuario_id);
    if (!$stmt_asignacion->execute()) {
        die("Error al asignar tarea al creador: " . $stmt_asignacion->error);
    }

    if (!empty($_POST['usuarios'])) {
        $stmt_compartir = $conexion->prepare("INSERT INTO tarea_usuario (id_tarea, id_usuario) VALUES (?, ?)");
        foreach ($_POST['usuarios'] as $otro_usuario_id) {
            if ($otro_usuario_id != $usuario_id) {
                $stmt_compartir->bind_param("ii", $id_tarea, $otro_usuario_id);
                $stmt_compartir->execute();
            }
        }
    }

    // 🔔 Emitir evento a Socket.IO
    try {
        $client = new Client(new Version2X('http://localhost:3000'));
        $client->initialize();
        $client->emit('tarea_actualizada', [
            'id' => $id_tarea,
            'titulo' => $titulo,
            'usuario_id' => $usuario_id
        ]);
        $client->close();
    } catch (Exception $e) {
        error_log("Error al emitir evento Socket.IO: " . $e->getMessage());
    }

    // Obtener nombre del usuario
$usuario_result = $conexion->query("SELECT nombre, apellido FROM personas WHERE id_persona = $usuario_id");
$usuario = $usuario_result->fetch_assoc();
$nombre_completo = $usuario ? $usuario['nombre'] . ' ' . $usuario['apellido'] : 'Usuario desconocido';

// Registrar historial de creación
$descripcion_cambio = "El usuario $nombre_completo creó la tarea: $titulo";
$conexion->query("
    INSERT INTO historial_tareas (id_tarea, id_usuario, accion, descripcion)
    VALUES ($id_tarea, $usuario_id, 'creación', '$descripcion_cambio')
");


    header("Location: ../views/tareas.php");
    exit();
} else {
    echo "Acceso no permitido.";
}
?>
