<?php
include "../model/conexion.php";

$id = $_GET["id"];

$sql = $conexion->query("SELECT * FROM personas WHERE id_persona=$id");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>CRUD PHP Y MYSQL</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.12.1/font/bootstrap-icons.min.css" />
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center p-4">MODIFICAR TABLA</h1>

        <div class="row">
            <!-- Formulario -->
            <div class="col-md-6 mx-auto">
                <form method="POST" class="">
                    <input type="hidden" name="id" value="<?= $_GET["id"] ?>">

                    <?php include "../controllers/modificar_persona.php"; ?>

                    <?php while ($datos = $sql->fetch_object()) { ?>
                        <div class="mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" class="form-control" name="nombre" value="<?= $datos->nombre ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Apellido</label>
                            <input type="text" class="form-control" name="apellido" value="<?= $datos->apellido ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">DNI</label>
                            <input type="text" class="form-control" name="dni" value="<?= $datos->dni ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Fecha de nacimiento</label>
                            <input type="date" class="form-control" name="fecha_nac" value="<?= $datos->fecha_nac ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Correo</label>
                            <input type="email" class="form-control" name="correo" value="<?= $datos->correo ?>" required>
                        </div>

                        <!-- Usuario y Contraseña -->
                        <div class="mb-3">
                            <label class="form-label">Usuario</label>
                            <input type="text" class="form-control" name="username" value="<?= $datos->username ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Contraseña</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password" name="password" value="<?= $datos->password ?>" required>
                                <button type="button" class="btn btn-outline-secondary" id="togglePassword" onclick="togglePassword()">
                                    <i class="bi bi-eye-slash" id="eye-icon"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Opción para cambiar la contraseña
                        <div class="mb-3">
                            <input type="checkbox" id="cambiarContrasena" onclick="mostrarCamposContrasena()">
                            <label for="cambiarContrasena">Cambiar contraseña</label>
                        </div> -->

                        <!-- Campo Confirmar Contraseña (se muestra si el usuario quiere cambiar la contraseña) -->
                        <div id="confirmarContrasenaDiv" class="mb-3">
                            <label class="form-label">Confirmar Contraseña</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="confirmar_contraseña" name="confirmar_contraseña" value="<?= $datos->password ?>" required>
                                <button type="button" class="btn btn-outline-secondary" id="togglePassword" onclick="togglePassword()">
                                    <i class="bi bi-eye-slash" id="eye-icon"></i>
                                </button>
                            </div>
                        </div>


                        <button type="submit" class="btn btn-primary" name="btonModificar" value="ok">
                            <i class="bi bi-send"></i> Modificar
                        </button>

                        <a href="index.php" class="btn btn-secondary" value="ok">
                        Volver
                    </a>
                    <?php } ?>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle (with Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Coloca el script aquí al final -->
    <script>
        // Función para mostrar/ocultar la contraseña
        function togglePassword() {
            var passwordField = document.getElementById("password");
            var eyeIcon = document.getElementById("eye-icon");

            // Cambiar el tipo de campo y el icono
            if (passwordField.type === "password") {
                passwordField.type = "text";  // Mostrar contraseña
                eyeIcon.classList.remove("bi-eye-slash");
                eyeIcon.classList.add("bi-eye");
            } else {
                passwordField.type = "password";  // Ocultar contraseña
                eyeIcon.classList.remove("bi-eye");
                eyeIcon.classList.add("bi-eye-slash");
            }
        }

        // // Función para mostrar el campo "Confirmar Contraseña" cuando se selecciona cambiar la contraseña
        // function mostrarCamposContrasena() {
        //     var cambiarContrasena = document.getElementById("cambiarContrasena");
        //     var confirmarContrasenaDiv = document.getElementById("confirmarContrasenaDiv");

        //     if (cambiarContrasena.checked) {
        //         confirmarContrasenaDiv.style.display = "block";
        //     } else {
        //         confirmarContrasenaDiv.style.display = "none";
        //     }
        // }
    </script>
</body>

</html>
