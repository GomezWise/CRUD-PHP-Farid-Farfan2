<!DOCTYPE html>
<html lang="es">
<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
?>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Crear Tarea</title>
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
                <h2>Crear Nueva Tarea</h2>
            </div>

            <div class="mt-4">
                <form method="POST" action="../controllers/tareaController.php" class="card p-4 shadow-sm">
                    <div class="mb-3">
                        <label for="titulo" class="form-label">Título</label>
                        <input type="text" class="form-control" id="titulo" name="titulo" required>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="fecha_limite" class="form-label">Fecha Límite</label>
                        <input type="date" class="form-control" id="fecha_limite" name="fecha_limite" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Compartir con otros usuarios</label>
                        <div class="row">
                            <?php
                            include '../model/conexion.php';
                            $query = "SELECT id_persona, nombre, apellido FROM personas WHERE estado = 1";
                            $result = $conexion->query($query);
                            while ($row = $result->fetch_assoc()) {
                                echo "
                                <div class='col-md-4'>
                                    <div class='form-check'>
                                        <input class='form-check-input' type='checkbox' name='usuarios[]' value='{$row['id_persona']}' id='user{$row['id_persona']}'>
                                        <label class='form-check-label' for='user{$row['id_persona']}'>
                                            {$row['nombre']} {$row['apellido']}
                                        </label>
                                    </div>
                                </div>";
                            }
                            ?>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success"><i class="bi bi-check-circle"></i> Guardar Tarea</button>
                    <a href="tareas.php" class="btn btn-secondary ms-2">Cancelar</a>
                </form>
            </div>
        </main>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
