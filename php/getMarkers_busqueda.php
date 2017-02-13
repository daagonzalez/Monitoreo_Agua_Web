<?php
	require '../vendor/autoload.php'; // include Composer goodies

	$client = new MongoDB\Client("mongodb://localhost:27017");
	$coleccion = $client->PuntosMuestreo->DatosCurri; //ingresar a la base de datos Peliculas

	$map = new MongoDB\BSON\Javascript("function(){emit(this.POI.nombre_estacion, this.Muestra.fecha+';'+this._id+','+this.POI.location.lat+','+this.POI.location.lng+','+this.Muestra.color)};");
	$reduce = new MongoDB\BSON\Javascript("function(key,values){".
	"var temp=0;".
	"var retornar;".
	"values.forEach(".
		"function(value){".
			"valuex = value.split(';');".
			 "if(new Date(valuex[0]).getTime()>temp){".
			 	"temp=valuex;".
			 	"retornar = valuex[1];".
			 "}".
	"});".
        "retornar = retornar.split(',');".
        'var res ={"id":retornar[0],"color":retornar[3],location:{"lat":parseFloat(retornar[1]),"lng":parseFloat(retornar[2])}};'.
	"return res;".
	"};");

	$datos = $client->PuntosMuestreo->command(array(
	    "mapreduce" => "DatosCurri", 
	    "map" => $map,
	    "reduce" => $reduce,
	    "out" => array('inline' => TRUE)));
	
	echo json_encode(iterator_to_array($datos));
?>