<?php
$server="localhost";
$user="root";
$pass="";
try {
    $conn=new PDO("mysql:host=$server;port=3306;dbname=adsclasificador",$user,$pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    echo "conexion exitosa";
} catch (PDOException $error) {
    echo "conexion erronea".$error->getMessage();
}

?>