<?php
session_start();
require('FuncionesPHP/funciones.php');
require('funcion.php');
verificarAutenticacion();
$datosUsuario=usuario::mostrar($_SESSION['id_usuario']);

if ($_POST){
    $categoria=$_POST['cate']??'duda'; 
    
    $publi=[
        'titulo'=>$_POST['titulo'],
        'texto'=>$_POST['publi'],
        'categoria'=>$categoria,
        'id_usuario'=>$_SESSION['id_usuario']
    ];
    publicaciones::publicar([
        $publi['titulo'],
        $publi['texto'],
        $publi['categoria'],
        $publi['id_usuario']
    ]);
    header('location:inicio.php');
    exit();
}
    $publicaciones=publicaciones::mostrarPubli()    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs@latest"></script>
    <script src="clasificador.js"></script>
</head>
<body>
    <h1>Bienvenido <?php echo $datosUsuario['nombre_usuario']." ".$datosUsuario['app_usuario']?></h1>
    
    <form id="formPublicacion" action="inicio.php" method="post">
        <input type="text" name="titulo" id="titulo" placeholder="Título de la publicación" required>
        <br><br>
        <textarea name="publi" id="publi" placeholder="Escribe aquí el contenido de tu publicación..." required></textarea>
        <br>
        <div id="prediccion">
            <strong>Categoría sugerida:</strong><span id="categoriaPredicha"></span>
            <input type="hidden" name="cate" id="cate" value="duda">
        </div>
        <br>
        <input type="submit" value="Publicar">
    </form>

        <h2>Publicaciones</h2>
        <div id="publicaciones">
            <?php foreach($publicaciones as $publicacion): ?>
                <div class="publicacion">
                    <h3><?php echo ($publicacion['titulo_publi']); ?></h3>
                    <p><strong>Autor:</strong> <?php echo ($publicacion['nombre_usuario'].' '.$publicacion['app_usuario'].' '.$publicacion['apm_usuario']); ?></p>
                    <p><strong>Fecha:</strong> <?php echo ($publicacion['fecha_publi']); ?></p>
                    <p><strong>Categoría:</strong> <?php echo ($publicacion['etiqueta_publi']); ?></p>
                    <p><?php echo ($publicacion['contenido_publi']); ?></p>
                    <hr>
                </div>
            <?php endforeach; ?>
        </div>


    <form method="post" action="cerrarS.php">
        <input type="submit" name="logout" value="Cerrar sesión">
    </form>
</body>
</html>