<?php
//require 'mongui.php';

 header('Content-Type: application/json');
 
 //echo "Holaaaaa";
  
  $interDir='';
  require $_SERVER['DOCUMENT_ROOT'].$interDir.'/webservices/databaseConnection.php';


/**
* Imprime un JSON con todos los documentos de la colección "sitiosMuestreo"
**/
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
	$collection=connectDatabaseCollection('MonitoreoAgua','puntosMuestreo',0);
    $cursor     = $collection->find();
    //echo "Holaaaaa";
	
	//$datos = Mongui::getAll();
	if ((string) $cursor->getID() != '') {
		echo json_encode(iterator_to_array($cursor));
	} else {
		print json_encode(
			array(
				'mensaje' => 'Ha ocurrido un error obteniendo los datos',
			)
		);
	}
}
?>
