<?php
session_start();
header("location: index.php");
 // Eliminamos la sesion.
    session_unset();
?>
