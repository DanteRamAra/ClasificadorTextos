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
    <link rel="stylesheet" href="Estilos/stylesregister.css" />
</head>

<body>
    <header>
    <h1 Class="">Clasificador</h1>
    <nav>
      <a href="#Home">Inicio</a>
      <a href="inicioS.php">Iniciar Sesion</a>
      <a href="register.php">Registro</a>
      <a href="#contacto">Contacto</a> 
    </nav>
    </header>

    <br>
    <div class="wrapper"> 
        <form action="register.php" method="post" id="registro">
            <h1>Registro</h1>
            <h3>Ingresa los siguientes datos:</h2>
            <div class="input-box">
                <input type="text" name="name" id="nombre" placeholder="Nombre">
            </div>
            <div class="input-box">
                <input type="text" name="app" id="" placeholder="Apellido Paterno">
            </div>
              <div class="input-box">
                <input type="text" name="apm" id="" placeholder="Apellido Materno">
            </div>
            <div class="input-box">
                <input type="date" name="fechaNac" id="" >
            </div>
            
            <div class="input-box">
                <input type="email" name="email" id="" placeholder="Email">
            </div>
              <div class="input-box">
                <input type="password" name="pass" id="pass" placeholder="Password">
            </div>
            <div class="input-box">
                <input type="submit" value="Enviar">
            </div>
        </form>
    </div>


</body>
</html>

