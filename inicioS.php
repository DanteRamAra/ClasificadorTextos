<?php
session_start();
require('FuncionesPHP/validacionesBack.php');
require('funcion.php');
verificarSesionActiva();

$errorCredenciales = false;

if ($_POST) {
    $email = $_POST['email'];
    $contra = $_POST['pass'];
    $usuario = validaciones::encontrarEmail($email);
    $emailValido = filter_var($email, FILTER_VALIDATE_EMAIL);

    if ($emailValido) {
        if ($usuario && password_verify($contra, $usuario['password_usuario'])) {
            session_regenerate_id(true);
            $_SESSION['id_usuario'] = $usuario['id_usuario'];
            header('location: inicio.php');
            exit();
        } else {
            $errorCredenciales = true;
        }
    } else {
        $errorCredenciales = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Registro</title>
    <link rel="stylesheet" href="Estilos/stylesinicioS.css" />
</head>
<body>
    <header>
        <h1>Clasificador</h1>
        <nav>
            <a href="index.php">Inicio</a>
            
            <a href="register.php">Registro</a>
            <a href="#contacto">Contacto</a>
        </nav>
    </header>

    <section> 
        .
    </section>

    <div class="wrapper"> 
        <form action="" method="post">
            <h1>Iniciar Sesión</h1>
            <div class="input-box">
                <input type="email" name="email" id="" placeholder="Email">
                <i class="bx bsd-user"></i>
            </div>
            <div class="input-box">
                <input type="password" name="pass" id="" placeholder="Password">
                <i class="bx bsx-lock-alt"></i> 
            </div>
            <div class="remember-forgot">
                <label><input type="checkbox"> Remember me</label>
            </div> 
            <button type="submit" class="btn" value="Enviar">Login</button>
            <div class="register-link">
                <p>¿No tienes una cuenta? <a href="register.php">Regístrate</a></p>
            </div>
        </form>
    </div>

    <?php if ($errorCredenciales): ?>
        <!-- Flotante para error -->
        <div id='flotante' style='
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            padding: 1rem 2rem;
            background: #ff4d4d;
            color: white;
            font-weight: bold;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.3);
            z-index: 9999;
            opacity: 0;
            animation: fadein 0.5s forwards;
        '>
            ❌ Credenciales incorrectas
        </div>
        <style>
            @keyframes fadein { to { opacity: 1; } }
            @keyframes fadeout { to { opacity: 0; } }
        </style>
        <script>
            setTimeout(() => {
                const flotante = document.getElementById('flotante');
                flotante.style.animation = 'fadeout 0.5s forwards';
                setTimeout(() => flotante.remove(), 500); // espera a que acabe la animación para eliminar
            }, 3000); // desaparece a los 3 segundos
        </script>
    <?php endif; ?>
</body>
</html>
