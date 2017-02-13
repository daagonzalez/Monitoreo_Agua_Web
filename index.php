<?php
session_start();
$_SESSION['correo']='johan';
if (isset($_SESSION['correo'])) {
	header('Location: busqueda.php');
}else {
	header('Location: login.php');
}

?>