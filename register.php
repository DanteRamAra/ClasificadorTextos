<?php
    session_start();
    require ('FuncionesPHP/funciones.php');
    require('funcion.php');

    verificarSesionActiva();
    if($_POST){
        $contra=hash('sha256',$_POST['pass']);
         if (strlen($_POST['pass']) < 8) {
        
          die("Error: La contraseña debe tener al menos 8 caracteres.");
        } 
        $datos=[$name=$_POST['name'],
        $app=$_POST['app'],
        $apm=$_POST['apm'],
        $fechaNac=$_POST['fechaNac'],
        $email=$_POST['email'],
        $contra];
        
        $id_usuario = usuario::register($datos);

        if ($id_usuario) {
            session_start();
            $_SESSION['id_usuario'] = $id_usuario;
            header('location:inicio.php');
            exit();
        } else {
            echo "Hubo un problema al registrar el usuario.";
        }
    }
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

    <form action="register.php" method="post" id="registro">
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
        <input type="password" name="pass" id="pass" placeholder="Password">
        <small id="password-error" style="color: red; display: none;">
        La contraseña debe tener al menos 8 caracteres.
        </small>
        <br>
        <input type="submit" value="Enviar">
    </form>
    <script src=validacionesJS/registro.js></script>
</body>
</html>

