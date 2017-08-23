<?php
	header('Content-Type: application/json');

	require 'databaseConnection.php';

	$collection=connectDatabaseCollection('MonitoreoAgua','puntosMuestreo',0);

	//Quitar comentarios para obtener timeStamp	  
	$result = $collection->aggregate([
        [ '$sort' => ['Muestra.fecha'=> -1]],
        [ '$group' => ['_id' => '$POI.nombre_estacion', 'id'=>['$first'=>'$_id'],'color' => ['$first' => '$Muestra.color'],'location'=>['$first'=>'$POI.location']/*,'fecha'=>['$first'=>'$Muestra.fecha']*/  ] ]
    ]);
    
    $result =iterator_to_array($result);
	foreach ($result as $doc) {
		$doc["id"]=(string)$doc["id"];
		//$doc["fecha"]=(string)$doc["fecha"];
	}

    echo json_encode($result);
?>
