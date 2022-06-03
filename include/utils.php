<?php

/*
 * Función que conecta con la base de datos utilizando los parámetros
 * definidos en el archivo config/settings.php
 */

function connect() {
    $pdoConn = false;
    try {
        $pdoConn = new PDO(DB_DSN, DB_USER, DB_PASSWD,
                array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    } catch (PDOException $e) {
        "Error en la línea: " . $e->getLine() .
        "<br>Error:<br>" . $e->getMessage();
    }
    return $pdoConn;
}

function limpiaTexto($texto) {
    //Convierte toda la etiqueta HTML posible para evitar XSS
    $resultado = htmlentities($texto);
    // Limpiar si estamos usando Cadenas
    $resultado = filter_var($texto, FILTER_SANITIZE_STRING);
    return $resultado;
}

function sanearNums($num) {
    //Conviertae toda la etiqueta HTML posible para evitar XSS
    $resultado = htmlentities($num);
    return $resultado;
}

