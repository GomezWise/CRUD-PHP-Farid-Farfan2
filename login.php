<!DOCTYPE html>
<html lang="en">

<?php
session_start();

if (isset($_SESSION['mensaje_exito'])) {
    echo "<div class='alert alert-success text-center'>{$_SESSION['mensaje_exito']}</div>";
    unset($_SESSION['mensaje_exito']); // Limpia el mensaje después de mostrarlo
}
?>


<?php
// Incluir el controlador que maneja la lógica de login
include "controllers/ControllerLogin.php"; 
?>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>

<body>
    <div class="container mt-5">
        <h3 class="text-center">Iniciar sesión</h3>

        <div class="row">
            <!-- Formulario de Login -->
            <div class="col-md-4 offset-md-4">
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" class="form-control" name="usuario" required />
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Contraseña</label>
                        <input type="password" class="form-control" name="contraseña" required />
                    </div>

                    <button type="submit" class="btn btn-primary" name="btnLogin">
                        <i class="bi bi-lock"></i> Iniciar sesión
                    </button>

                    <!-- Botón para registrar -->
                    <a href="views/registrar_login.php" class="btn btn-secondary" value="ok">
                        Registrarme
                    </a>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle (with Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
