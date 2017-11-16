<?php
	require 'vendor/autoload.php'; // include Composer goodies

	//indica los campos de filtro, se obtienen por medio del metodo GET y se agregan a la consulta solamente los campos que se enviaron
	//El punto que indica a cual documento pertenece se representa con una coma ya que no se puede enviar punto. 
	$filtros=["Muestra,usuario","POI,nombre_institucion","POI,nombre_estacion","POI,kit_desc","Muestra,indice_usado","POI,datos_geograficos,area_administrativa_1","POI,datos_geograficos,area_administrativa_2","POI,datos_geograficos,area_administrativa_3"];

	//variables que indican si se espera resultados más recientes o todos.

	$consulta = [];//arreglo que guarda los parametros que contienen la consulta
	$contador = 0;//posiciones dentro del arreglo de consulta

	//se itera sobre los valores de filtro para saber que valores se tienen y que valores no.
	for($i=0;$i<count($filtros);$i++){
		if(isset($_GET[$filtros[$i]])){
			$key = str_replace(',', '.', $filtros[$i]);
			$value=$_GET[$filtros[$i]];
			$regex = new MongoDB\BSON\Regex ($value,'s');
			$element=[$key=>$regex];
			$consulta[$contador]=$element;
			$contador=$contador+1;
		}
	}

	//al ser la fecha dos opciones posibles, se obtiene por separado y se analizan las 3 posibilidades
	//opcion 1: vienen ambos parametros, se crea una especie de between
	//opcion 2: solamente viene la fecha inferior, se obtienen todos los valores superiores a dicha fecha
	//opcion 3: solamente viene la fecha superior, se obtienen todos los valores inferiores a dicha fecha
	if(isset($_GET["fecha_inicial"])&&isset($_GET["fecha_final"])){//caso 1
		$value1=$_GET["fecha_inicial"];
		$value2=$_GET["fecha_final"];
		$element=['Muestra.fecha'=>['$gt'=>$value1,'$lt'=>$value2]];
		$consulta[$contador]=$element;	
	}elseif(isset($_GET["fecha_inicial"])){//caso 2
		$value=$_GET["fecha_inicial"];
		$element=['Muestra.fecha'=>['$gt'=>$value]];
		$consulta[$contador]=$element;	
	}elseif(isset($_GET["fecha_final"])){//caso 3
		$value=$_GET["fecha_final"];
		$element=['Muestra.fecha'=>['$lt'=>$value]];
		$consulta[$contador]=$element;	
	}

	//se separa la consulta por medio del operador OR para cada uno de los campos presentes generados anteriormente.
	$consulta = ['$and'=>$consulta];

	//se crea una instancia de la conexión.
  	try {
		$client = new MongoDB\Client("mongodb://localhost:27017");
		$collection = $client->PuntosMuestreo->DatosCurri; //ingresar a la base de datos Peliculas
	  	//se ejecuta la consulta y se guardan los resultados en la variable result.
		$result = $collection->aggregate([
			['$match'=>$consulta],
	        [ '$sort' => ['Muestra.fecha'=> -1]],
	        [ '$group' => ['_id' => '$POI.nombre_estacion', 'id'=>['$first'=>'$_id'],'color' => ['$first' => '$Muestra.color'],'location'=>['$first'=>'$POI.location']/*,'fecha'=>['$first'=>'$Muestra.fecha']*/  ] ]
	    ]);

	    $result =iterator_to_array($result);
		foreach ($result as $doc) {
			$doc["id"]=(string)$doc["id"];
			//$doc["fecha"]=(string)$doc["fecha"];
		}

		$result['state'] =	true;
  	} catch (MongoConnectionException $e) {
		$result['state'] =	false;
    	//echo "Error: " . $e->getMessage();
  	}

	echo json_encode($result);
?>