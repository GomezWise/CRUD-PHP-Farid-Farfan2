<?php
include "model/conexion.php"; // Asegúrate de que este archivo contenga la conexión a la base de datos

// Verificar si el formulario fue enviado
if (isset($_POST['btnLogin'])) {
    $usuario = $_POST['usuario']; // Nombre de usuario ingresado
    $contraseña = $_POST['contraseña']; // Contraseña ingresada

    // Consultamos si el usuario existe en la base de datos
    $query = "SELECT * FROM personas WHERE username = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        // Si el usuario existe, obtenemos la fila
        $user = $result->fetch_assoc();

        // Verificamos si la contraseña es correcta
        if (password_verify($contraseña, $user['password'])) {
            // Contraseña correcta, iniciar sesión
            session_start();
            $_SESSION['usuario'] = [
                'id_persona' => $user['id_persona'],
                'username' => $user['username'],
                'nombre' => $user['nombre']
            ];

            // Redirigir al dashboard
            header("Location: views/dashboard.php");
            exit();
        } else {
            echo "<div class='alert alert-danger'>Contraseña incorrecta</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>El usuario no existe</div>";
    }
}
?>
