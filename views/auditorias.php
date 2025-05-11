<!DOCTYPE html>
<html lang="en">

<?php
include "../model/conexion.php";

// Consulta para obtener los registros de auditoría
$sql = $conexion->query("SELECT 
            a.id,
            a.tabla_afectada,
            a.accion,
            a.registro_id,
            p.dni AS dni_persona,
            a.usuario,
            a.fecha,
            a.detalle
        FROM 
            auditoria a
        JOIN 
            personas p ON a.registro_id = p.id_persona");
?>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Auditoría</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.12.1/font/bootstrap-icons.min.css" />
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center p-4">Historial de Auditoría</h1>

        <!-- Botón para regresar al index.php -->
        <div class="text-center mb-4">
            <a href="index.php" class="btn btn-primary">
                <i class="bi bi-arrow-left"></i> Volver a Inicio
            </a>
        </div>

        <div class="row">
            <div class="col-md-12">
                <!-- Tabla de auditoría -->
                <table class="table table-bordered table-striped">
                    <thead class="table-dark text-center">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Tabla Afectada</th>
                            <th scope="col">Acción</th>
                            <th scope="col">DNI</th>
                            <th scope="col">Usuario</th>
                            <th scope="col">Fecha</th>
                            <th scope="col">Detalles</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        <?php while ($datos = $sql->fetch_object()) { ?>
                            <tr>
                                <th scope="row"><?= $datos->id ?></th>
                                <td><?= $datos->tabla_afectada ?></td>
                                <td><?= $datos->accion ?></td>
                                <td><?= $datos->dni_persona ?></td>
                                <td><?= $datos->usuario ?></td>
                                <td><?= $datos->fecha ?></td>
                                <td><?= $datos->detalle ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle (with Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
