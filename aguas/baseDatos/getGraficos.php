<?php

header('Content-Type: application/json');
 
$interDir='';
$ruta = $_SERVER['DOCUMENT_ROOT'].$interDir.'/aguas/Mongui/mongui.php';

//include('graficosUsuario.php');
/**
* Obtiene las fechas, nombres y descripciones de los graficos del usuario del ID ingresado
**/
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
	session_start();
	$idUsuario = $_GET['idUsuario'];
	$datos = Mongui::getGraficosPorIDUsuario($idUsuario);

	print json_encode($datos);
}
?>
