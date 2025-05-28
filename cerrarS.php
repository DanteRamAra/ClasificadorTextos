 <?php
    if (isset($_POST['logout'])) {
        session_start();
        session_destroy();
        header("Location: inicioS.php");
        exit();
    }
?>