<?php 
require '../databaseConnection.php';
	if($_SERVER["REQUEST_METHOD"]=="GET"){
		$objId=$_GET['objid'];
		$collection=connectDatabase('MonitoreaAgua','puntosMuestreo',0);

		$item = $collection->findOne(['_id'=>new MongoDB\BSON\ObjectID($objId)]);
		$item = iterator_to_array($item);
		$obligatorios=$item['Muestra']['obligatorios'];
		$opcionales=$item['Muestra']['opcionales'];
		$location=$item["POI"]["location"];
		$date =$item["Muestra"]['fecha']->toDateTime()->format('d-m-Y');
		$estacion =$item["POI"]['nombre_estacion'];
		$institucion=$item["POI"]['nombre_institucion'];
		$author=$item["Muestra"]['usuario'];
		require 'view_author.php';
	}
	
 ?>