<?php
	header('Content-Type: application/json');
	
	require 'databaseConnection.php';

	//indica los campos de filtro, se obtienen por medio del metodo GET y se agregan a la consulta solamente los campos que se enviaron
	//El punto que indica a cual documento pertenece se representa con una coma ya que no se puede enviar punto. 
	$filtros=["Muestra,usuario","POI,nombre_institucion","POI,nombre_estacion","POI,kit_desc","Muestra,indice_usado","POI,datos_geograficos,area_administrativa_1","POI,datos_geograficos,area_administrativa_2","POI,datos_geograficos,area_administrativa_3"];

	$ver_todos = false;//indica si retornar todos los valores o solo los más recientes.
	//variables que indican si se espera resultados más recientes o todos.
	if(isset($_POST['ver_todos'])){
		if($_POST['ver_todos']==true){
			$ver_todos = true;
		}
	}

	$consulta = [];//arreglo que guarda los parametros que contienen la consulta
	$contador = 0;//posiciones dentro del arreglo de consulta

	//se itera sobre los valores de filtro para saber que valores se tienen y que valores no.
	for($i=0;$i<count($filtros);$i++){
		if(isset($_POST[$filtros[$i]])){
			$key = str_replace(',', '.', $filtros[$i]);
			$value = $_POST[$filtros[$i]];
			
			$regex = new MongoDB\BSON\Regex ($value,'s');
			$element = [$key=>$regex];
			$consulta[$contador] = $element;
			$contador = $contador+1;
		}
	}

	//al ser la fecha dos opciones posibles, se obtiene por separado y se analizan las 3 posibilidades
	//opcion 1: vienen ambos parametros, se crea una especie de between
	//opcion 2: solamente viene la fecha inferior, se obtienen todos los valores superiores a dicha fecha
	//opcion 3: solamente viene la fecha superior, se obtienen todos los valores inferiores a dicha fecha
	if(isset($_POST["fecha_inicial"])&&isset($_POST["fecha_final"])){//caso 1
		//Se obtienen y convierten las fechas que vienen desde android
		$value1 = new DateTime($_POST["fecha_inicial"]);
		$value2 = new DateTime($_POST["fecha_final"]);
		$value1 = new MongoDB\BSON\UTCDateTime($value1->getTimeStamp()*1000);
		$value2 = new MongoDB\BSON\UTCDateTime($value2->getTimeStamp()*1000);
		//se agrega la sección a la consulta
		$element = ['Muestra.fecha'=>['$gte'=>$value1,'$lte'=>$value2]];
		$consulta[$contador] = $element;	
	}elseif(isset($_POST["fecha_inicial"])){//caso 2
		//Se obtienen y convierten las fechas que vienen desde android
		$value = new DateTime($_POST["fecha_inicial"]);
		$value = new MongoDB\BSON\UTCDateTime($value->getTimeStamp()*1000);
		$element = ['Muestra.fecha'=>['$gte'=>$value]];
		$consulta[$contador]=$element;	
	}elseif(isset($_POST["fecha_final"])){//caso 3
		$value = new DateTime($_POST["fecha_final"]);
		$value = new MongoDB\BSON\UTCDateTime($value->getTimeStamp()*1000);
		$element = ['Muestra.fecha'=>['$lte'=>$value]];
		$consulta[$contador] = $element;	
	}


	//se separa la consulta por medio del operador OR para cada uno de los campos presentes generados anteriormente.
	$consulta = ['$and'=>$consulta];
	$collection=connectDatabaseCollection('MonitoreoAgua','puntosMuestreo',0);
	if($collection!=0){
	  	//se ejecuta la consulta y se guardan los resultados en la variable result.
		if($ver_todos){
			$result = $collection->aggregate([
				['$match'=>$consulta],
	        	[ '$sort' => ['Muestra.fecha'=> -1]]
		    ]);	
		}else{
			$result = $collection->aggregate([
				['$match'=>$consulta],
		        [ '$sort' => ['Muestra.fecha'=> -1]],
		        [ '$group' => ['_id' => '$POI.nombre_estacion', 'id'=>['$first'=>'$_id'],'color' => ['$first' => '$Muestra.color'],'location'=>['$first'=>'$POI.location']/*,'fecha'=>['$first'=>'$Muestra.fecha']*/  ] ]
		    ]);
		}
	    
	    $result =iterator_to_array($result);
		$result[count($result)] =['success'=>true];
		foreach ($result as $doc) {
			$doc["id"]=(string)$doc["id"];
			//$doc["fecha"]=(string)$doc["fecha"];
		}
	}else{
		$result[0] = ['success'=>false];
	}

	echo json_encode($result);
?>