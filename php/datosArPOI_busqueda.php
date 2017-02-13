<?php
	header('Content-Type: application/json');
	require '../vendor/autoload.php'; // include Composer goodies
	if($_SERVER['REQUEST_METHOD']=='GET'){

		$id1=$_GET['id1'];
		$id2=$_GET['id2'];
		if($id1&&$id2){
			$client = new MongoDB\Client("mongodb://localhost:27017");
			$coleccion = $client->PuntosMuestreo->DatosCurri; //ingresar a la base de datos Peliculas
			$items = $coleccion->find(['$or'=>[['_id'=>new MongoDB\BSON\ObjectID($id1)],['_id'=>new MongoDB\BSON\ObjectID($id2)]]]);
			//echo json_encode(array('1'=>2,'2'=>3));
			
			echo json_encode(iterator_to_array($items));
		}
	}
?>