<?php session_start();
//require '../vendor/autoload.php';
if (isset($_SESSION['correo'])) {
    header('Location: busqueda.php');
}


    require 'views/recuperar_view.php';
 ?>