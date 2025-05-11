<?php

if (!empty($_POST["btonRegistrar"])) {
    if (
        !empty($_POST["nombre"]) &&
        !empty($_POST["apellido"]) &&
        !empty($_POST["dni"]) &&
        !empty($_POST["fecha_nac"]) &&
        !empty($_POST["correo"]) &&
        !empty($_POST["username"]) &&
        !empty($_POST["password"]) &&
        !empty($_POST["confirmar_contraseña"]) 
    ) {
        $nombre = $_POST["nombre"];
        $apellido = $_POST["apellido"];
        $dni = $_POST["dni"];
        $fecha_nac = $_POST["fecha_nac"];
        $correo = $_POST["correo"];
        $username = $_POST["username"];
        $password = $_POST["password"];
        $confirmar_contraseña = $_POST["confirmar_contraseña"];

        // Verificar que las contraseñas coincidan
        $mostrar_error_password = false;

        if ($password !== $confirmar_contraseña) {
            $mostrar_error_password = true;
            echo "<div class='alert alert-danger'>Las contraseñas no coinciden</div>";
        } else {
            // Verificar si el DNI ya existe en la base de datos
            $sql_check_dni = $conexion->query("SELECT * FROM personas WHERE dni = '$dni'");
            if ($sql_check_dni->num_rows > 0) {
                // Si el DNI ya existe, mostrar un mensaje de error
                echo "<div class='alert alert-danger'>El DNI ingresado ya está registrado. Por favor, ingresa otro.</div>";
            } else {
                // Si el DNI no existe, proceder con el registro
                $contraseña_hash = password_hash($password, PASSWORD_DEFAULT);

                // Insertar los datos de la persona en la base de datos
                $sql = $conexion->query("INSERT INTO personas(nombre, apellido, dni, fecha_nac, correo, username, password, estado) 
                                         VALUES ('$nombre', '$apellido', '$dni', '$fecha_nac', '$correo', '$username', '$contraseña_hash', 1)");

                session_start(); 

                if ($sql) {
                    $_SESSION['mensaje_exito'] = "Registro exitoso. ¡Ahora puedes iniciar sesión!";
                    header("Location: ../login.php");
                    exit();
                } else {
                    echo '<div class="alert alert-danger">Error al registrar persona y usuario</div>';
                }
            }
        }
    } else {
        echo "<div class='alert alert-warning'>Alguno de los campos está vacío</div>";
    }
}
?>
