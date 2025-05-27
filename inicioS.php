<?php
    session_start();

    require ('FuncionesPHP/funciones.php');
    require('funcion.php');
    verificarSesionActiva();

    if($_POST){
      $email=$_POST['email'];
      $contra=$_POST['pass'];
      $usuario=validaciones::encontrarEmail($email);
      
      if($usuario && password_verify($contra, $usuario['password_usuario'])) {
          session_regenerate_id(true); 
          $_SESSION['id_usuario'] = $usuario['id_usuario'];
          
          header('location: inicio.php');
          exit();
      } else {
        echo "usuario no encontrado";
      }
    }
    /*
    Comprobar existencia del usuario (DB).
    Validar contraseña (password_verify).
    Bloquear tras intentos fallidos.
   Generar token seguro (JWT/sesión).
    */ 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="https://bootswatch.com/5/flatly/bootstrap.min.css">
</head>
<body>
<nav class="navbar navbar-expand-lg bg-primary" data-bs-theme="dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Clasificador</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarColor01">
      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <a class="nav-link active" href="index.php">Home
            <span class="visually-hidden">(current)</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="inicioS.php">login</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="register.php">Registro</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
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

