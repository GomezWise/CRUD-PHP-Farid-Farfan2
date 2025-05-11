
<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

include "../model/conexion.php";

// Función para registrar cambios en el historial
// function registrar_historial($conexion, $id_tarea, $accion, $creado_por) {
//     $query = "INSERT INTO historial_tareas (id_tarea, accion, creado_por) VALUES (?, ?, ?)";
//     $stmt = $conexion->prepare($query);
//     $stmt->bind_param("isi", $id_tarea, $accion, $creado_por);
//     $stmt->execute();
//     $stmt->close();

   
// }

// Obtener el método de la solicitud
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'POST':
        // Crear una nueva tarea
        $data = json_decode(file_get_contents("php://input"), true);
        if (!$data || !isset($data['titulo']) || !isset($data['fecha_limite']) || !isset($data['creado_por'])) {
            http_response_code(400);
            echo json_encode(["error" => "Datos incompletos"]);
            exit();
        }

        $titulo = $data['titulo'];
        $descripcion = isset($data['descripcion']) ? $data['descripcion'] : '';
        $fecha_limite = $data['fecha_limite'];
        $creado_por = $data['creado_por'];

        $query = "INSERT INTO tareas (titulo, descripcion, fecha_limite, creado_por) VALUES (?, ?, ?, ?)";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("sssi", $titulo, $descripcion, $fecha_limite, $creado_por);

        if ($stmt->execute()) {
            $id_tarea = $stmt->insert_id;
            // registrar_historial($conexion, $id_tarea, 'creada', $creado_por);
            echo json_encode(["success" => true, "id_tarea" => $id_tarea]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Error al crear la tarea"]);
        }
        $stmt->close();
        break;

    case 'GET':
        // Leer tareas
        if (isset($_GET['id'])) {
            // Obtener una tarea específica
            $id_tarea = $_GET['id'];
            $query = "SELECT * FROM tareas WHERE id = ?";
            $stmt = $conexion->prepare($query);
            $stmt->bind_param("i", $id_tarea);
            $stmt->execute();
            $result = $stmt->get_result();
            $tarea = $result->fetch_assoc();
            echo json_encode($tarea);
            $stmt->close();
        } else {
            // Obtener todas las tareas
            $query = "SELECT * FROM tareas";
            $result = $conexion->query($query);
            $tareas = [];
            while ($row = $result->fetch_assoc()) {
                $tareas[] = $row;
            }
            echo json_encode($tareas);
        }
        break;

    case 'PUT':
        // Actualizar una tarea
        $data = json_decode(file_get_contents("php://input"), true);
   
        $tarea_id = $data['id'];
        $titulo = $data['titulo'];
        $descripcion = isset($data['descripcion']) ? $data['descripcion'] : '';
        $fecha_limite = $data['fecha_limite'];
        

        $query = "UPDATE tareas SET titulo = ?, descripcion = ?, fecha_limite = ?, creado_por = ? WHERE id = ?";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("sssii", $titulo, $descripcion, $fecha_limite, $creado_por, $tarea_id);

        if ($stmt->execute()) {
            // registrar_historial($conexion, $tarea_id, 'actualizada', $usuario_id);
            echo json_encode(["success" => true]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Error al actualizar la tarea"]);
        }
        $stmt->close();
        break;

    case 'DELETE':
        // Eliminar una tarea
        if (!isset($_GET['id']) || !isset($_GET['creado_por'])) {
            http_response_code(400);
            echo json_encode(["error" => "Datos incompletos"]);
            exit();
        }

        $id_tarea = $_GET['id'];
        $creado_por = $_GET['creado_por'];

        $query = "DELETE FROM tareas WHERE id = ?";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("i", $id_tarea);

        if ($stmt->execute()) {
            registrar_historial($conexion, $id_tarea, 'eliminada', $creado_por);
            echo json_encode(["success" => true]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Error al eliminar la tarea"]);
        }
        $stmt->close();
        break;

    default:
        http_response_code(405);
        echo json_encode(["error" => "Método no permitido"]);
        break;
}
?>
