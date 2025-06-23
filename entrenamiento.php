<?php
header('Content-Type:application/json');
require ('FuncionesPHP/funciones.php');

try{
    $conn=new PDO("mysql:host=localhost;port=3306;dbname=adsclasificador",'root','');
    $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    
    $query=$conn->prepare("SELECT contenido_publi as texto, etiqueta_publi as categoria FROM publicaciones");
    $query->execute();
    
    $publicaciones=$query->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($publicaciones);
}catch(PDOException $e){
    echo json_encode(['error'=>$e->getMessage()]);
}
?>