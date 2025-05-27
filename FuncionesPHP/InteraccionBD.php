<?php

class usuario{

    public static function register($datos){
        try {
            $conn=new PDO("mysql:host=localhost;port=3306;dbname=adsclasificador",'root','');
            $consult=$conn->prepare("insert into usuarios (id_usuario,nombre_usuario,app_usuario,apm_usuario,fechaNac_usuario,correo_usuario,password_usuario,rol_usuario) values (null,:nom,:app,:apm,:fecha,:correo,:pass,'Alumno') ");
            $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            $consult->execute(array(
             ':nom' => htmlspecialchars($datos[0], ENT_QUOTES, 'UTF-8'), 
            ':app' => htmlspecialchars($datos[1], ENT_QUOTES, 'UTF-8'),
            ':apm' => htmlspecialchars($datos[2], ENT_QUOTES, 'UTF-8'),
            ':fecha'=>$datos[3],
            ':correo' => filter_var($datos[4], FILTER_SANITIZE_EMAIL),
            ':pass'=>$datos[5]   
            ));
            return $conn->lastInsertId();
        } catch (PDOException $error) {
            echo "conexion erronea".$error->getMessage();
        }
    }
    
    public static function mostrar($id_usuario){
        try {
            $conn = new PDO("mysql:host=localhost;port=3306;dbname=adsclasificador", 'root', '');
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $consult = $conn->prepare("SELECT nombre_usuario, app_usuario, apm_usuario, fechaNac_usuario FROM usuarios  WHERE id_usuario = :id_usu");
            $consult->execute([':id_usu' => $id_usuario]);
            return $consult->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $error) {
            echo "Conexión errónea: " . $error->getMessage();
        }
    }
}

class publicaciones{
     public static function publicar($datos){
        try {
            $conn=new PDO("mysql:host=localhost;port=3306;dbname=adsclasificador",'root','');
            $consult=$conn->prepare("insert into publicaciones (id_publi,titulo_publi,contenido_publi,fecha_publi,etiqueta_publi,id_usuario) values (null,:titulo,:cont,Now(),:etiqueta,:id_usu) ");
            $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            $consult->execute(array(
            ':titulo'=>$datos[0],
            ':cont'=>$datos[1],
            ':etiqueta'=>$datos[2],
            ':id_usu'=>$datos[3]
            ));
            return $conn->lastInsertId();
        } catch (PDOException $error) {
            echo "conexion erronea".$error->getMessage();
        }
    }
}

