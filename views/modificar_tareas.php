
<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

include "../model/conexion.php";

$usuario_id = is_array($_SESSION['usuario']) ? $_SESSION['usuario']['id_persona'] : $_SESSION['usuario'];

if (!isset($_GET['id'])) {
    die("ID de tarea no especificado.");
}

$id_tarea = intval($_GET['id']);

// Obtener datos de la tarea
$resultado = $conexion->query("SELECT * FROM tareas WHERE id = $id_tarea");
$tarea = $resultado->fetch_assoc();

if (!$tarea) {
    die("Tarea no encontrada.");
}

// Obtener colaboradores actuales
$colaboradores_actuales = [];
$colaboradores_query = $conexion->query("SELECT id_usuario FROM tarea_usuario WHERE id_tarea = $id_tarea AND id_usuario != $usuario_id");
while ($col = $colaboradores_query->fetch_assoc()) {
    $colaboradores_actuales[] = $col['id_usuario'];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Editar Tarea</title>
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
        <nav class="col-md-2 d-none d-md-block sidebar p-3 bg-dark text-white">
            <h4 class="text-center mb-4">Menú</h4>
            <ul class="nav flex-column">
                <a class="nav-link text-white" href="dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a>
                <a class="nav-link text-white" href="tareas.php"><i class="bi bi-list-task"></i> Tareas</a>
                <li class="nav-item mt-4">
                    <a class="btn btn-danger w-100" href="../login.php"><i class="bi bi-box-arrow-right"></i> Cerrar sesión</a>
                </li>
            </ul>
        </nav>

        <!-- Contenido principal -->
        <main class="col-md-10 ms-sm-auto px-md-4">
            <div class="topbar py-3 border-bottom">
                <h2>Editar Tarea</h2>
            </div>

            <div class="mt-4">
                <form method="POST" action="../controllers/actualizarTareaController.php">
                    <input type="hidden" name="id" value="<?= $tarea['id'] ?>">

                    <div class="mb-3">
                        <label for="titulo" class="form-label">Título</label>
                        <input type="text" class="form-control" id="titulo" name="titulo" value="<?= htmlspecialchars($tarea['titulo']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3"><?= htmlspecialchars($tarea['descripcion']) ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="fecha_limite" class="form-label">Fecha Límite</label>
                        <input type="date" class="form-control" id="fecha_limite" name="fecha_limite" value="<?= $tarea['fecha_limite'] ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Compartir con otros usuarios</label>
                        <div class="row">
                            <?php
                            $usuarios = $conexion->query("SELECT id_persona, nombre, apellido FROM personas WHERE id_persona != $usuario_id");
                            while ($usuario = $usuarios->fetch_assoc()):
                                $checked = in_array($usuario['id_persona'], $colaboradores_actuales) ? "checked" : "";
                            ?>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="colaboradores[]" value="<?= $usuario['id_persona'] ?>" id="user<?= $usuario['id_persona'] ?>" <?= $checked ?>>
                                        <label class="form-check-label" for="user<?= $usuario['id_persona'] ?>">
                                            <?= htmlspecialchars($usuario['nombre'] . " " . $usuario['apellido']) ?>
                                        </label>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Actualizar Tarea</button>
                    <a href="tareas.php" class="btn btn-secondary ms-2">Cancelar</a>
                    <button type="button" class="btn btn-info ms-2" data-bs-toggle="modal" data-bs-target="#historialModal">
                        <i class="bi bi-clock-history"></i> Ver historial
                    </button>
                </form>
            </div>
        </main>
    </div>
</div>

<!-- Modal para mostrar el historial -->
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var historialModal = document.getElementById('historialModal');
        historialModal.addEventListener('show.bs.modal', function (event) {
            var modalBody = document.getElementById('historialContent');
            var tareaId = <?= $tarea['id'] ?>;
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'mostrar_historial.php?id_tarea=' + tareaId, true);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    modalBody.innerHTML = xhr.responseText;
                } else {
                    modalBody.innerHTML = 'Error al cargar el historial.';
                }
            };
            xhr.send();
        });
    });
</script>
</body>
</html>
