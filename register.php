<?php

    if($_POST){
    $name=$_POST['name'];
    $app=$_POST['app'];
    $apm=$_POST['apm'];
    $f=$_POST['fechaNac'];
    $email=$_POST['email'];
    $pass=$_POST['pass'];
    echo $name."<br>";
    echo $app."<br>";
    echo $apm."<br>";
    echo $f."<br>";
    echo $email."<br>";
    echo $pass;
    }    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
</head>
<body>
    <form action="register.php" method="post">
        <input type="text" name="name" id="" placeholder="Nombre">
        <br>
        <input type="text" name="app" id="" placeholder="Apellido paterno">
        <br>
        <input type="text" name="apm" id="" placeholder="Apellido materno">
        <br>
        <input type="date" name="fechaNac" id="">
        <br>
        <input type="email" name="email" id="" placeholder="Email">
        <br>
        <input type="password" name="pass" id="" placeholder="Password">
        <br>
        <input type="submit" value="Enviar">
    </form>
</body>
</html>

