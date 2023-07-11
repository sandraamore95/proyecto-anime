<?php

 class bConecta{

public static function conexion(){
    try{
    $conexion = new PDO('mysql:host='. Config::$mvc_bd_hostname . ';dbname=' . Config::$mvc_bd_nombre . '', Config::$mvc_bd_usuario, Config::$mvc_bd_clave);
     // Realiza el enlace con la BD en utf-8
    $conexion->exec("set names utf8");
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }catch(Exception $e){
        die("Error" .$e->getMessage());
        echo "Linea del error " . $e->getLine();
        error_log($e->getMessage() . microtime() . PHP_EOL, 3, "logExceptio.txt");
    }
    return $conexion;
    }

}
?>