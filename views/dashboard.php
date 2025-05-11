<?php
session_start();
include "../model/conexion.php";

if (!isset($_SESSION['usuario'])) {
    header("Location: ../views/login.php");
    exit();
}

$usuario_id = is_array($_SESSION['usuario']) ? $_SESSION['usuario']['id_persona'] : $_SESSION['usuario'];

$sql = $conexion->query("
    SELECT t.id, t.titulo, t.estado, t.fecha_limite, t.creado_por, p.nombre AS creador
    FROM tareas t
    INNER JOIN tarea_usuario tu ON t.id = tu.id_tarea
    INNER JOIN personas p ON t.creado_por = p.id_persona
    WHERE tu.id_usuario = $usuario_id AND t.estado = 'Pendiente'
");

$tareas = $sql->fetch_all(MYSQLI_ASSOC);

foreach ($tareas as &$tarea) {
    $id_tarea = $tarea['id'];
    $colaboradores = $conexion->query("
        SELECT nombre, apellido FROM personas 
        INNER JOIN tarea_usuario ON personas.id_persona = tarea_usuario.id_usuario
        WHERE tarea_usuario.id_tarea = $id_tarea AND personas.id_persona != $usuario_id
    ");
    $tarea['colaboradores'] = $colaboradores->fetch_all(MYSQLI_ASSOC);
}

$alertas = [];
foreach ($tareas as $tarea) {
    $alertas[] = "Tarea pendiente: {$tarea['titulo']}";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard</title>
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
                <h2 class="mb-0">Dashboard</h2>
                <div class="dropdown">
                    <button class="btn btn-light position-relative dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="bi bi-bell-fill"></i>
                        <?php if (is_array($alertas) && count($alertas) > 0): ?>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                <?= count($alertas) ?>
                            </span>
                        <?php endif; ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <?php foreach ($alertas as $alerta): ?>
                            <li><a class="dropdown-item" href="#"><?= $alerta ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <!-- Contenido -->
            <div class="mt-4">
                <h4>📋 Alertas</h4>
                <div id="tareas-container" class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mt-2">
                    <?php foreach ($tareas as $tarea): ?>
                        <div class="col">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title"><?= $tarea["titulo"] ?></h5>
                                    <span class="badge bg-<?= 
                                        $tarea["estado"] == "Completado" ? "success" : 
                                        ($tarea["estado"] == "En progreso" ? "warning text-dark" : "secondary") ?>">
                                        <?= $tarea["estado"] ?>
                                    </span>
                                    <p class="mt-2 mb-0">
                                        <?= $tarea["creado_por"] == $usuario_id ? "Tarea creada por ti" : "Asignada por {$tarea['creador']}" ?>
                                    </p>
                                    <?php if (!empty($tarea['colaboradores'])): ?>
                                        <small class="text-muted">Colaboras con: 
                                            <?= implode(", ", array_map(fn($c) => $c['nombre'] . " " . $c['apellido'], $tarea['colaboradores'])) ?>
                                        </small>
                                    <?php endif; ?>
                                    <a href="ver_tarea.php?id=<?= $tarea["id"] ?>"
                                        class="btn btn-sm btn-outline-info mt-2"
                                        title="Ver detalles de la tarea">
                                        <i class="bi bi-eye"></i> Ver detalles
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Contenedor de Toasts -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1055">
    <div id="toast-container"></div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.socket.io/4.5.4/socket.io.min.js"></script>
<script>
    const socket = io("http://localhost:3000");

    // Unirse a todas las tareas asignadas
    <?php foreach ($tareas as $tarea): ?>
        socket.emit("unirse_a_tarea", "<?= $tarea['id'] ?>");
    <?php endforeach; ?>

    // Escuchar notificaciones de tareas actualizadas
    socket.on("notificacion_tarea", (data) => {
        const { titulo, usuario_nombre } = data;
        const mensaje = `${usuario_nombre} actualizó la tarea: ${titulo}`;

        // Mostrar en el dropdown de notificaciones
        const dropdown = document.querySelector(".dropdown-menu");
        if (dropdown) {
            const nuevaNotificacion = document.createElement("li");
            nuevaNotificacion.innerHTML = `<a class="dropdown-item" href="#">${mensaje}</a>`;
            dropdown.prepend(nuevaNotificacion);
        }

        // Aumentar el contador de notificaciones
        const badge = document.querySelector(".position-absolute.badge");
        if (badge) {
            let count = parseInt(badge.textContent) || 0;
            badge.textContent = count + 1;
        } else {
            const button = document.querySelector(".dropdown-toggle");
            const nuevoBadge = document.createElement("span");
            nuevoBadge.className = "position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger";
            nuevoBadge.textContent = "1";
            button.appendChild(nuevoBadge);
        }

        // Mostrar toast visual
        mostrarToast(mensaje);
    });

    function mostrarToast(mensaje) {
        const toastContainer = document.getElementById("toast-container");
        const toastId = `toast-${Date.now()}`;
        const toastHTML = `
            <div id="${toastId}" class="toast align-items-center text-bg-primary border-0 mb-2" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        ${mensaje}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Cerrar"></button>
                </div>
            </div>
        `;
        toastContainer.insertAdjacentHTML("beforeend", toastHTML);
        const toastElement = document.getElementById(toastId);
        const toast = new bootstrap.Toast(toastElement, { delay: 5000 });
        toast.show();
    }
</script>
</body>
</html>
