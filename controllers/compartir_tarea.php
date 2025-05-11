<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

include "../model/conexion.php";

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['id_tarea']) || !isset($data['id_usuario'])) {
    http_response_code(400);
    echo json_encode(["error" => "Datos incompletos"]);
    exit();
}

$id_tarea = $data['id_tarea'];
$id_usuario = $data['id_usuario'];

// Verificar si ya está compartida
$check = $conexion->prepare("SELECT id FROM tarea_usuario WHERE id_tarea = ? AND id_usuario = ?");
$check->bind_param("ii", $id_tarea, $id_usuario);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    echo json_encode(["message" => "La tarea ya está compartida con este usuario"]);
    $check->close();
    exit();
}
$check->close();

// Insertar relación
$query = "INSERT INTO tarea_usuario (id_tarea, id_usuario) VALUES (?, ?)";
$stmt = $conexion->prepare($query);
$stmt->bind_param("ii", $id_tarea, $id_usuario);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Tarea compartida correctamente"]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Error al compartir la tarea"]);
}
$stmt->close();
?>
