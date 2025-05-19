<?php

    if($_POST){
    $email=$_POST['email'];
    $pass=$_POST['pass'];
   
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
    <form action="inicioS.php" method="post">
        <input type="email" name="email" id="" placeholder="Email">
        <br>
        <input type="password" name="pass" id="" placeholder="Password">
        <br>
        <input type="submit" value="Enviar">
    </form>
</body>
</html>

