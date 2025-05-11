<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

include "../model/conexion.php"; // Conexión con mysqli

$usuario_id = $_SESSION['usuario']; // Asegúrate de que este índice exista

// Obtener tareas asignadas al usuario
$stmt = $conexion->prepare("
    SELECT t.titulo, t.estado 
    FROM tareas t
    INNER JOIN tarea_usuario tu ON t.id = tu.id_tarea
    WHERE tu.id_usuario = ?
");

$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$resultado = $stmt->get_result();
$tareas = [];

while ($fila = $resultado->fetch_assoc()) {
    $tareas[] = $fila;
}

// Generar alertas a partir de las tareas
$alertas = [];
foreach ($tareas as $tarea) {
    $alertas[] = "Tarea asignada: {$tarea['titulo']} ({$tarea['estado']})";
}
?>
