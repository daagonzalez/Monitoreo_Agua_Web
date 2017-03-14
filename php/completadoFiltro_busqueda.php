<?php
	require '../vendor/autoload.php'; // include Composer goodies

	try {
	$client = new MongoDB\Client("mongodb://localhost:27017");
		$coleccion = $client->PuntosMuestreo->DatosCurri; //ingresar a la base de datos Peliculas
	} catch (MongoConnectionException $e) {
		echo "Error: " . $e->getMessage();
	}
	$result=$coleccion->distinct("POI.nombre_institucion");
	echo json_encode($result);
?>