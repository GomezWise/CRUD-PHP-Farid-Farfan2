<?php
session_start();
include "../model/conexion.php";

if (!isset($_SESSION['usuario'])) {
    die("Error: sesión no iniciada.");
}

if (!isset($_GET['id_tarea'])) {
    die("Error: ID de tarea no especificado.");
}

$id_tarea = intval($_GET['id_tarea']);

// Obtener historial de cambios de la tarea
$sql = $conexion->query("
    SELECT h.*, p.nombre, p.apellido
    FROM historial_tareas h
    JOIN personas p ON h.id_usuario = p.id_persona
    WHERE h.id_tarea = $id_tarea
    ORDER BY h.fecha DESC
");

if ($sql->num_rows == 0) {
    echo "<p>No hay historial de cambios para esta tarea.</p>";
    exit();
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Historial de Cambios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<div class="container mt-4">
    <h2>Historial de Cambios</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Usuario</th>
                <th>Acción</th>
                <th>Descripción</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $sql->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['fecha'] ?></td>
                    <td><?= htmlspecialchars($row['nombre'] . ' ' . $row['apellido']) ?></td>
                    <td><?= htmlspecialchars($row['accion']) ?></td>
                    <td><?= htmlspecialchars($row['descripcion']) ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <a href="javascript:history.back()" class="btn btn-secondary">Volver</a>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
