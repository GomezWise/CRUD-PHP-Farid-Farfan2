<?php
// Encabezados para permitir solicitudes desde cualquier origen y definir tipo de contenido
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

// Leer el cuerpo de la solicitud
$data = json_decode(file_get_contents("php://input"), true);

// Validar que se recibieron los datos necesarios
if (!$data || !isset($data['username']) || !isset($data['password'])) {
    http_response_code(400);
    echo json_encode(["error" => "Datos incompletos"]);
    exit();
}

// Conexión a la base de datos
include "../model/conexion.php";


$username = $data['username'];
$password = $data['password'];

// Buscar el usuario en la base de datos
$query = "SELECT * FROM personas WHERE username = ?";
$stmt = $conexion->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $user = $result->fetch_assoc();

    // Verificar la contraseña
    if (password_verify($password, $user['password'])) {
        echo json_encode([
            "success" => true,
            "user" => [
                "id_persona" => $user['id_persona'],
                "username" => $user['username'],
                "nombre" => $user['nombre']
            ]
        ]);
    } else {
        http_response_code(401);
        echo json_encode(["error" => "Contraseña incorrecta"]);
    }
} else {
    http_response_code(404);
    echo json_encode(["error" => "Usuario no encontrado"]);
}
?>
