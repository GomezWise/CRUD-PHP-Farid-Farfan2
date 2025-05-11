<?php

    if(!empty($_GET["id"])) {

        $id = $_GET["id"];

        $sql = $conexion->query("UPDATE personas set estado=0 where id_persona=$id");

        if ($sql) {
            echo '<div class="alert alert-success">Persona eliminado correctamente</div>';
        } else {
            echo '<div class="alert alert-danger">Error al modificar persona</div>';
        }
    }

?>
