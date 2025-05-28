<?php
class validaciones{
    public static function encontrarEmail($email){ //existencia de email
        try {
            $conn=new PDO("mysql:host=localhost;port=3306;dbname=adsclasificador",'root','');
            $consult=$conn->prepare("select id_usuario,correo_usuario,password_usuario from usuarios where correo_usuario=:email ");
            $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            $consult->execute([':email'=>$email]);
            $resultado=$consult->fetch();
            return $resultado;
        } catch (PDOException $error) {
             error_log("Error de BD: ".$error->getMessage());
            return false;
            
        }
    }
    public static function validarInicio($datos){
       try {
            $conn=new PDO("mysql:host=localhost;port=3306;dbname=adsclasificador",'root','');
            $consult=$conn->prepare("select id_usuario from usuarios where correo_usuario=:correo_usu && password_usuario=:pass_usuario");
            $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            $consult->execute(array(
            ':correo_usu'=>$datos[0],
            ':pass_usuario'=>$datos[1]  
            ));
            $resultado = $consult->fetch(PDO::FETCH_ASSOC);
            return $resultado ? $resultado['id_usuario'] : false;
        } catch (PDOException $error) {
            echo "conexion erronea".$error->getMessage();
        }  
    }
}

?>

