<!DOCTYPE html>
<html lang="es">
<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

include "../model/conexion.php";
$usuario_id = is_array($_SESSION['usuario']) ? $_SESSION['usuario']['id_persona'] : $_SESSION['usuario'];

$sql = $conexion->query("SELECT t.id, t.titulo, t.estado, t.fecha_limite,
           GROUP_CONCAT(p.nombre SEPARATOR ', ') AS colaboradores
    FROM tareas t
    INNER JOIN tarea_usuario tu ON t.id = tu.id_tarea
    INNER JOIN personas p ON tu.id_usuario = p.id_persona
    WHERE t.id IN (
        SELECT id_tarea FROM tarea_usuario WHERE id_usuario = $usuario_id
    )
    GROUP BY t.id");

$tareas = $sql->fetch_all(MYSQLI_ASSOC);

// ✅ Agrupar tareas por estado
$tareasPorEstado = [
    'Pendiente' => [],
    'En progreso' => [],
    'Completado' => [],
];

foreach ($tareas as $tarea) {
    $estado = $tarea['estado'];
    $tareasPorEstado[$estado][] = $tarea;
}

// Notificaciones
$alertas = [];
foreach ($tareas as $tarea) {
    $alertas[] = "Tarea asignada: {$tarea['titulo']} ({$tarea['estado']})";
}
?>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tareas</title>
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
                <h2 class="mb-0">Tareas</h2>
                <div class="dropdown">
                    <button class="btn btn-light position-relative dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="bi bi-bell-fill"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="noti-count">
                            <?= count($alertas) ?>
                        </span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" id="noti-list">
                        <?php foreach ($alertas as $alerta): ?>
                            <li><a class="dropdown-item" href="#"><?= $alerta ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <!-- Tareas -->
            <div class="mt-4 d-flex justify-content-between align-items-center">
                <h4>📋 Tareas asignadas</h4>
                <a href="registrar_tareas.php" class="btn btn-success">
                    <i class="bi bi-plus-circle"></i> Nueva tarea
                </a>
            </div>

            <!-- Secciones por estado -->
            <?php foreach (['Pendiente', 'En progreso', 'Completado'] as $estado): ?>
                <?php if (!empty($tareasPorEstado[$estado])): ?>
                    <h5 class="mt-5">
                        <?= $estado == 'Pendiente' ? '🕓 Pendientes' : ($estado == 'En progreso' ? '⏳ En progreso' : '✅ Completadas') ?>
                    </h5>
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mt-2">
                        <?php foreach ($tareasPorEstado[$estado] as $tarea): ?>
                            <div class="col">
                                <div class="card shadow-sm">
                                    <div class="card-body">
                                        <h5 class="card-title"><?= $tarea["titulo"] ?></h5>
                                        <p class="card-text mb-1">
                                            <strong>Estado:</strong>
                                            <span class="badge bg-<?= $tarea["estado"] == "Completado" ? "success" : ($tarea["estado"] == "En progreso" ? "warning text-dark" : "secondary") ?>">
                                                <?= $tarea["estado"] ?>
                                            </span>
                                        </p>
                                        <p class="card-text mb-1"><strong>Fecha límite:</strong> <?= $tarea["fecha_limite"] ?></p>
                                        <p class="card-text mb-2">
                                            <strong>Colaboradores:</strong>
                                            <?= !empty($tarea["colaboradores"]) ? $tarea["colaboradores"] : "<span class='text-muted'>Ninguno</span>" ?>
                                        </p>
                                        <div class="d-flex justify-content-end gap-2">
                                            <button class="btn btn-sm btn-outline-warning" title="Marcar como En progreso"
                                                    onclick="cambiarEstadoTarea(<?= $tarea['id'] ?>, 'En progreso')">
                                                <i class="bi bi-hourglass-split"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-success" title="Marcar como Completado"
                                                    onclick="cambiarEstadoTarea(<?= $tarea['id'] ?>, 'Completado')">
                                                <i class="bi bi-check-circle"></i>
                                            </button>
                                            <a href="modificar_tareas.php?id=<?= $tarea["id"] ?>" class="btn btn-sm btn-outline-primary" title="Editar tarea">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button type="button" class="btn btn-info ms-2" data-bs-toggle="modal" data-bs-target="#historialModal" data-tarea-id="<?= $tarea['id'] ?>">
                                                <i class="bi bi-clock-history"></i> Ver historial
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>

            <!-- Modal de historial -->
            <div class="modal fade" id="historialModal" tabindex="-1" aria-labelledby="historialModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="historialModalLabel">Historial de cambios</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div id="historialContent">Cargando historial...</div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.socket.io/4.7.2/socket.io.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var historialModal = document.getElementById('historialModal');
        historialModal.addEventListener('show.bs.modal', function (event) {
            var modalBody = document.getElementById('historialContent');
            var tareaId = event.relatedTarget.getAttribute('data-tarea-id');
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'mostrar_historial.php?id_tarea=' + tareaId, true);
            xhr.onload = function () {
                modalBody.innerHTML = xhr.status === 200 ? xhr.responseText : 'Error al cargar el historial.';
            };
            xhr.send();
        });
    });

    const socket = io('http://localhost:3000');

    socket.on('notificacion_tarea', function (data) {
        const dropdown = document.getElementById('noti-list');
        const badge = document.getElementById('noti-count');

        const li = document.createElement('li');
        li.innerHTML = `<a class="dropdown-item text-primary" href="#">🔔 ${data.mensaje}</a>`;
        dropdown.prepend(li);
        badge.textContent = parseInt(badge.textContent) + 1;
    });

    function cambiarEstadoTarea(id, estado) {
        fetch(`../controllers/cambiarEstadoTarea.php?id=${id}&estado=${estado}`)
            .then(res => {
                if (res.ok) {
                    socket.emit('tarea_actualizada', {
                        tareaId: id,
                        mensaje: `La tarea #${id} fue actualizada a "${estado}".`
                    });
                    location.reload();
                }
            });
    }
</script>
</body>
</html>
