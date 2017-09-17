<?php
include('graficosUsuario.php');
/**
* Obtiene las fechas, nombres y descripciones de los graficos del usuario del ID ingresado
**/
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
	session_start();
	$idUsuario = $_SESSION['idUsuario'];
	$datos = graficosUsuario::getGraficosPorIDUsuario($idUsuario);

	print json_encode($datos);
}
?>
