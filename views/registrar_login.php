<!DOCTYPE html>
<html lang="en">

<?php
include "../model/conexion.php";
include "../controllers/registrar_persona.php"; // Este archivo maneja el registro de persona
include "../controllers/eliminar_persona.php"; // Este archivo maneja la eliminación de personas
?>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>CRUD PHP Y MYSQL</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.12.1/font/bootstrap-icons.min.css" />
    
    <style>
        /* Centrado de formulario usando Flexbox */
        .center {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh; /* Ocupa toda la altura de la pantalla */
        }

        .form-container {
            width: 100%;
            max-width: 500px; /* Limitar el tamaño máximo del formulario */
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f9f9f9;
        }
    </style>
</head>

<body>
<div class="container mt-5 center">
    <!-- Formulario para registrar persona, usuario y contraseña -->
    <div class="form-container">
        <form method="POST">
            <h3 class="text-center">Registrar persona y usuario</h3>

            <div class="mb-3">
                <label class="form-label">Nombre</label>
                <input type="text" class="form-control" name="nombre" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Apellido</label>
                <input type="text" class="form-control" name="apellido" required>
            </div>
            <div class="mb-3">
                <label class="form-label">DNI</label>
                <input type="text" class="form-control" name="dni" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Fecha de nacimiento</label>
                <input type="date" class="form-control" name="fecha_nac" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Correo</label>
                <input type="email" class="form-control" name="correo" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Usuario</label>
                <input type="text" class="form-control" name="username" required>
            </div>
            <div class="mb-3">
    <label class="form-label">Contraseña</label>
    <div class="input-group">
        <input type="password" class="form-control <?php if ($mostrar_error_password) echo 'is-invalid'; ?>" id="password" name="password" required>
        <button type="button" class="btn btn-outline-secondary">
            <i class="bi bi-eye-slash" id="eye-icon"></i>
        </button>
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Confirmar Contraseña</label>
    <div class="input-group">
        <input type="password" class="form-control <?php if ($mostrar_error_password) echo 'is-invalid'; ?>" id="confirmPassword" name="confirmar_contraseña" required>
        <button type="button" class="btn btn-outline-secondary">
            <i class="bi bi-eye-slash" id="eye-icon-confirm"></i>
        </button>
    </div>
</div>


<button type="submit" class="btn btn-primary" name="btonRegistrar" value="ok">
    <i class="bi bi-send"></i> Registrar
</button>


            <a href="../login.php" class="btn btn-secondary" value="ok">
                        Volver
                    </a>

        </form>
    </div>
</div>

    <!-- Bootstrap JS Bundle (with Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Función para mostrar/ocultar la contraseña

//         function validacion_caracteres(event) {
//     var passwordField = document.getElementById("password");
//     var password = passwordField.value;

//     if (password.length < 15) {
//         event.preventDefault(); // Detiene el envío del formulario
//         alert("La contraseña debe tener al menos 15 caracteres.");
//         return false;
//     }

//     return true;
// }


        function togglePassword() {
            var passwordField = document.getElementById("password");
            var eyeIcon = document.getElementById("eye-icon");

            if (passwordField.type === "password") {
                passwordField.type = "text";
                eyeIcon.classList.remove("bi-eye-slash");
                eyeIcon.classList.add("bi-eye");
            } else {
                passwordField.type = "password";
                eyeIcon.classList.remove("bi-eye");
                eyeIcon.classList.add("bi-eye-slash");
            }
        }

        // Función para mostrar/ocultar la confirmación de la contraseña
        function toggleConfirmPassword() {
            var confirmPasswordField = document.getElementById("confirmPassword");
            var eyeIconConfirm = document.getElementById("eye-icon-confirm");

            if (confirmPasswordField.type === "password") {
                confirmPasswordField.type = "text";
                eyeIconConfirm.classList.remove("bi-eye-slash");
                eyeIconConfirm.classList.add("bi-eye");
            } else {
                confirmPasswordField.type = "password";
                eyeIconConfirm.classList.remove("bi-eye");
                eyeIconConfirm.classList.add("bi-eye-slash");
            }
        }
    </script>
</body>

</html>
