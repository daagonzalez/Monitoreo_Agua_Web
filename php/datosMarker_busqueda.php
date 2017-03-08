<?php
	require '../vendor/autoload.php'; // include Composer goodies
	if($_SERVER['REQUEST_METHOD']=='GET'){

		$id1=$_GET['id1'];
		if($id1){
			$client = new MongoDB\Client("mongodb://localhost:27017");
			$coleccion = $client->PuntosMuestreo->DatosCurri; //ingresar a la base de datos Peliculas
			$item = $coleccion->find(['_id'=>new MongoDB\BSON\ObjectID($id1)]);
			//echo json_encode(array('1'=>2,'2'=>3));
			
			echo json_encode(iterator_to_array($item));
		}
	}
?>