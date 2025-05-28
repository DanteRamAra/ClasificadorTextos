<?php
    session_start();
    require ('FuncionesPHP/funciones.php');
    require ('FuncionesPHP/validacionesBack.php');
    require('funcion.php');
    verificarSesionActiva();//verifica si existe una sesion iniciada en ese momento
    if($_POST){
      //limpieza de datos en caso de tener espacios al inicio o final
      $nombreLimpio=trim($_POST['name']);
      $appLimpio=trim($_POST['app']);
      $apmLimpio=trim($_POST['apm']);
      $emailLimpio=trim($_POST['email']);
      $passLimpio=trim($_POST['pass']);

      //validaciones finales 
      $passValida=strlen($_POST['pass'])>=8;
      $emailNoExistente=!validaciones::encontrarEmail($emailLimpio);
      $emailValido=filter_var($emailLimpio,FILTER_VALIDATE_EMAIL);
      //regex
      $nombreValido=preg_match('/^[a-zA-Z\s]{3,}$/u',$nombreLimpio);
      $appValido=preg_match('/^[a-zA-Z\s]{3,}$/u',$appLimpio);
      $apmValido=preg_match('/^[a-zA-Z\s]{3,}$/u',$apmLimpio);

      if($passValida && $emailNoExistente && $emailValido && $nombreValido && $appValido && $apmValido){//validaciones back
  
        $contra=password_hash($passLimpio,PASSWORD_DEFAULT);//cifra contraseña
        $fechaNac = $_POST['fechaNac'];
      
        //arreglo para la BD
        $datos=[$nombreLimpio,$appLimpio,$apmLimpio,$fechaNac,$emailLimpio,$contra];
        $id_usuario = usuario::register($datos); //registra al usuario y saca el id

      }else{
        die("Error: Datos ingresados incorrectamente");
        exit();
      }
      
      if($id_usuario){//redirige a inicio.php
          $_SESSION['id_usuario']=$id_usuario;
          header('location:inicio.php');
          exit();

          }else{
            echo "Hubo un problema al registrar el usuario.";
          }
    }

    /*
    falta validaciones front de todo
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

    <form action="register.php" method="post" id="registro">
        <input type="text" name="name" id="nombre" placeholder="Nombre">
        <br>
        <input type="text" name="app" id="" placeholder="Apellido paterno">
        <br>
        <input type="text" name="apm" id="" placeholder="Apellido materno">
        <br>
        <input type="date" name="fechaNac" id="" >
        <br>
        <input type="email" name="email" id="" placeholder="Email">
        <br>
        <input type="password" name="pass" id="pass" placeholder="Password">

        <br>
        <input type="submit" value="Enviar">
    </form>

</body>
</html>

