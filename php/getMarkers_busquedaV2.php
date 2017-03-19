<?php
    require '../vendor/autoload.php'; // include Composer goodies
	  try {
		$client = new MongoDB\Client("mongodb://localhost:27017");
		$coleccion = $client->PuntosMuestreo->DatosCurri; //ingresar a la base de datos Peliculas
	  } catch (MongoConnectionException $e) {
	    echo "Error: " . $e->getMessage();
	  }
	  
    $collection = $client->PuntosMuestreo->DatosCurri; //ingresar a la base de datos Peliculas
    $result = $collection->aggregate([
        [ '$sort' => ['Muestra.fecha'=> 1]],
        [ '$group' => ['_id' => '$POI.nombre_estacion', 'id'=>['$first'=>'$_id'],'color' => ['$first' => '$Muestra.color'],'location'=>['$first'=>'$POI.location']  ] ]
    ]);
    
    $result =iterator_to_array($result);
	foreach ($result as $doc) {
		$doc["id"]=(string)$doc["id"];
	}

    echo json_encode($result);
?>
