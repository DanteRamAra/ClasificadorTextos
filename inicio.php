<?php
    session_start();
    require ('FuncionesPHP/funciones.php');
    require('funcion.php');
    verificarAutenticacion();
    $datosUsuario = usuario::mostrar($_SESSION['id_usuario']);
    if ($_POST) {
        $publi=[
            $titulo=$_POST['titulo'],
            $texto=$_POST['publi'],
            $categoria=$_POST['cate'],
            $id=$_SESSION['id_usuario']
        ];
        publicaciones::publicar($publi);
        header('location:inicio.php');

    }
    /*
    validacion formulario
    */ 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1> Bienvenido <?php echo $datosUsuario['nombre_usuario']?></h1>
    <form action="inicio.php" method="post">
        <input type="text" name="titulo" id="" placeholder="Titulo">
        <br>
        <input type="text" name="publi" id="" placeholder="Texto">
        <br>
        <input type="text" name="cate" id="" placeholder="categoria">
        <input type="submit" value="Enviar">
    </form>
</body>
</html>