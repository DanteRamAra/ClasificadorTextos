<?php
session_start();
require('FuncionesPHP/funciones.php');
require('funcion.php');
verificarAutenticacion();
$datosUsuario = usuario::mostrar($_SESSION['id_usuario']);

if ($_POST) {
    $categoria = $_POST['cate'] ?? 'duda'; 
    
    $publi = [
        'titulo' => $_POST['titulo'],
        'texto' => $_POST['publi'],
        'categoria' => $categoria,
        'id_usuario' => $_SESSION['id_usuario']
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

$publicaciones = publicaciones::mostrarPubli();
// Agrupar publicaciones por categoría
$publicacionesPorCategoria = [];
foreach ($publicaciones as $publicacion) {
    $categoria = strtolower($publicacion['etiqueta_publi']);
    if (!isset($publicacionesPorCategoria[$categoria])) {
        $publicacionesPorCategoria[$categoria] = [];
    }
    $publicacionesPorCategoria[$categoria][] = $publicacion;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <link rel="stylesheet" href="Estilos/stylesinicio.css" />
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs@latest"></script>
    <script src="clasificador.js"></script>
    <style>
        .panel {
            margin-bottom: 1rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
        }
        .panel-header {
            padding: 1rem;
            background-color: #ffdd00;
            color: #333;
            cursor: pointer;
            font-weight: bold;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .panel-content {
            padding: 1rem;
            background-color: white;
            display: none;
        }
        .panel.active .panel-content {
            display: block;
        }
        .toggle-icon {
            font-size: 1.2rem;
        }
        .publicacion {
            color: #000 !important;
        }
        .publicacion h3,
        .publicacion p,
        .publicacion strong {
            color: #000 !important;
        }
    </style>
</head>
<body>
    <header>
        <h1>Clasificador</h1>
        <nav>
            <a href="#contacto">Contacto</a> 
            <form method="post" action="cerrarS.php" style="display: inline;">
                <button type="submit" name="logout" class="logout-btn">Cerrar sesión</button>
            </form>
        </nav>
    </header>

    <section>
        <div class="wrapper"> 
            <h1>Bienvenido <?php echo $datosUsuario['nombre_usuario']." ".$datosUsuario['app_usuario']?></h1>
            <form id="formPublicacion" action="inicio.php" method="post">
                <div class="input-box">
                    <input type="text" name="titulo" id="titulo" placeholder="Título de la publicación" required>
                    <br><br>
                    <input type="text" name="publi" id="publi" placeholder="Escribe aquí el contenido de tu publicación..." required>
                    <br>
                </div>
                <br>
                <div id="prediccion" class="input-box">
                    <strong>Categoría sugerida:</strong><span id="categoriaPredicha"></span>
                    <input type="hidden" name="cate" id="cate" value="duda">
                </div>
                <br>
                <button type="submit" class="btn" value="Publicar">Publicar</button>
            </form>
        </div>
    </section>

    <section>
        <div class="wrapper"> 
            <h2>Publicaciones</h2>
            <div id="publicaciones">
                <?php foreach ($publicacionesPorCategoria as $categoria => $publicacionesCategoria): ?>
                    <div class="panel">
                        <div class="panel-header" onclick="togglePanel(this)">
                            <span><?php echo ucfirst($categoria) ?> (<?php echo count($publicacionesCategoria) ?>)</span>
                            <span class="toggle-icon">+</span>
                        </div>
                        <div class="panel-content">
                            <?php foreach ($publicacionesCategoria as $publicacion): ?>
                                <div class="publicacion">
                                    <h3>Titulo: <?php echo ($publicacion['titulo_publi']); ?></h3>
                                    <p><strong>Autor: </strong> <?php echo ($publicacion['nombre_usuario'].' '.$publicacion['app_usuario'].' '.$publicacion['apm_usuario']); ?></p>
                                    <p><strong>Fecha: </strong> <?php echo ($publicacion['fecha_publi']); ?></p>
                                    <p><strong>Categoría: </strong> <?php echo ($publicacion['etiqueta_publi']); ?></p>
                                    <p><strong>Contenido: </strong><?php echo ($publicacion['contenido_publi']); ?></p>
                                    <hr>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <script>
        function togglePanel(header) {
            const panel = header.parentElement;
            panel.classList.toggle('active');
            const icon = header.querySelector('.toggle-icon');
            icon.textContent = panel.classList.contains('active') ? '-' : '+';
        }
        
        // Opcional: Abrir el primer panel por defecto
        document.addEventListener('DOMContentLoaded', function() {
            const firstPanel = document.querySelector('.panel');
            if (firstPanel) {
                firstPanel.classList.add('active');
                firstPanel.querySelector('.toggle-icon').textContent = '-';
            }
        });
    </script>
</body>
</html>