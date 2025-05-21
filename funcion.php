<?php

function verificarSesionActiva() {
    if(isset($_SESSION['id_usuario'])) {
        header('Location: inicio.php');
        exit();
    }
}

function verificarAutenticacion() {
    if(!isset($_SESSION['id_usuario'])) {
        header('Location: index.php');
        exit();
    }
}


?>