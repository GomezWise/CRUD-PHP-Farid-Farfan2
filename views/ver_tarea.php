<!DOCTYPE html>
<?php
session_start();
include "../model/conexion.php";

if (!isset($_SESSION['usuario'])) {
    header("Location: ../views/login.php");
    exit();
}

$tarea_id = isset($_GET['id']) ? $_GET['id'] : null;

if ($tarea_id) {
    $sql = $conexion->query("
        SELECT t.id, t.titulo, t.descripcion, t.estado, t.fecha_limite, t.creado_por, p.nombre AS creador
        FROM tareas t
        INNER JOIN personas p ON t.creado_por = p.id_persona
        WHERE t.id = $tarea_id
    ");
    $tarea = $sql->fetch_assoc();

    if ($tarea) {
        // Obtener los colaboradores de la tarea
        $colaboradores = $conexion->query("
            SELECT nombre, apellido FROM personas
            INNER JOIN tarea_usuario ON personas.id_persona = tarea_usuario.id_usuario
            WHERE tarea_usuario.id_tarea = $tarea_id
        ");
        $tarea['colaboradores'] = $colaboradores->fetch_all(MYSQLI_ASSOC);
    } else {
        header("Location: dashboard.php");
        exit();
    }
} else {
    header("Location: dashboard.php");
    exit();
}
?>

<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Ver Tarea</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.12.1/font/bootstrap-icons.min.css" />
    <style>
        body {
            background-color: #f8f9fa;
        }
        .sidebar {
            height: 100vh;
            background-color: #343a40;
            color: white;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 10px;
        }
        .sidebar a:hover {
            background-color: #495057;
            border-radius: 5px;
        }
        .topbar {
            background-color: white;
            padding: 10px 20px;
            border-bottom: 1px solid #dee2e6;
        }
        .card {
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Menú lateral -->
        <nav class="col-md-2 d-none d-md-block sidebar p-3">
            <h4 class="text-center mb-4">Menú</h4>
            <ul class="nav flex-column">
                <li class="nav-item mb-2">
                    <a class="nav-link" href="dashboard.php">
                        <i class="bi bi-list-task"></i> Dashboard
                    </a>
                    <a class="nav-link" href="tareas.php">
                        <i class="bi bi-list-task"></i> Tareas
                    </a>
                </li>
                <li class="nav-item mt-4">
                    <a class="btn btn-danger w-100" href="../login.php">
                        <i class="bi bi-box-arrow-right"></i> Cerrar sesión
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Contenido principal -->
        <main class="col-md-10 ms-sm-auto px-md-4">
            <!-- Barra superior -->
            <div class="topbar d-flex justify-content-between align-items-center">
                <h2 class="mb-0">Detalles de la Tarea</h2>
            </div>

            <!-- Contenido -->
            <div class="mt-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="bi bi-file-earmark-text"></i> <?= $tarea['titulo'] ?></h4>
                    </div>
                    <div class="card-body">
                        <p><i class="bi bi-card-text"></i> <strong>Descripción:</strong> <?= $tarea['descripcion'] ?></p>
                        <p><i class="bi bi-hourglass-split"></i> <strong>Estado:</strong> 
                            <span class="badge bg-<?= $tarea['estado'] === 'Pendiente' ? 'warning' : ($tarea['estado'] === 'En Progreso' ? 'info' : 'success') ?>">
                                <?= $tarea['estado'] ?>
                            </span>
                        </p>
                        <p><i class="bi bi-calendar-event"></i> <strong>Fecha límite:</strong> <?= $tarea['fecha_limite'] ?></p>
                        <p><i class="bi bi-person-circle"></i> <strong>Creado por:</strong> <?= $tarea['creador'] ?></p>

                        <hr>
                        <h5><i class="bi bi-people-fill"></i> Colaboradores:</h5>
                        <?php if (!empty($tarea['colaboradores'])): ?>
                            <ul class="list-group list-group-flush">
                                <?php foreach ($tarea['colaboradores'] as $colaborador): ?>
                                    <li class="list-group-item">
                                        <i class="bi bi-person"></i> <?= $colaborador['nombre'] . ' ' . $colaborador['apellido'] ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <div class="alert alert-secondary mt-2">No tiene colaboradores asignados.</div>
                        <?php endif; ?>

                        <a href="dashboard.php" class="btn btn-outline-primary mt-4">
                            <i class="bi bi-arrow-left-circle"></i> Volver al Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
