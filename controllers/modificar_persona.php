<?php
session_start();
include "../model/conexion.php";

if (!empty($_POST["btonModificar"])) {
    $id = $_POST["id"];
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $dni = $_POST["dni"];
    $fecha_nac = $_POST["fecha_nac"];
    $correo = $_POST["correo"];
    $username = $_POST["username"];
    $password = $_POST["password"];

    $confirmar_contraseña = $_POST["confirmar_contraseña"] ?? null;

    // Si se quiere cambiar la contraseña
    if (!empty($confirmar_contraseña)) {
        if ($password !== $confirmar_contraseña) {
            echo "<div class='alert alert-danger'>Las contraseñas no coinciden.</div>";
            return;
        }
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $sql = $conexion->query("UPDATE personas SET 
            nombre='$nombre', apellido='$apellido', dni='$dni',
            fecha_nac='$fecha_nac', correo='$correo', username='$username', password='$password_hash' 
            WHERE id_persona=$id");
    } else {
        $sql = $conexion->query("UPDATE personas SET 
            nombre='$nombre', apellido='$apellido', dni='$dni',
            fecha_nac='$fecha_nac', correo='$correo', username='$username' 
            WHERE id_persona=$id");
    }

    if ($sql) {
        $_SESSION['mensaje'] = "Usuario modificado correctamente.";
        header("Location: index.php");
        exit();
    } else {
        echo "<div class='alert alert-danger'>Error al modificar el usuario.</div>";
    }
}
?>
