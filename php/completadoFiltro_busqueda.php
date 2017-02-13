<?php
	require '../vendor/autoload.php'; // include Composer goodies

	$client = new MongoDB\Client("mongodb://localhost:27017");
	$coleccion = $client->PuntosMuestreo->DatosCurri; //ingresar a la base de datos Peliculas
	$result=$coleccion->distinct("POI.nombre_institucion");
	echo json_encode($result);
?>