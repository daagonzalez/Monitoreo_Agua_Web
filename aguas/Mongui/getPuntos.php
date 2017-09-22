<?php

header('Content-Type: application/json');
 
$interDir='';

require $_SERVER['DOCUMENT_ROOT'].$interDir.'/webservices/databaseConnection.php';

/**
* Imprime un JSON con todos los documentos de la colección "sitiosMuestreo"
**/
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
	$collection=connectDatabaseCollection('MonitoreoAgua','puntosMuestreo',0);
	
    $cursor = $collection->find();
    
    $cursor = iterator_to_array($cursor);
    
    foreach($cursor as $item){
    	if(isset($item->Muestra->val_indice)){
			unset($item->Muestra->val_indice);
		}
    }

	echo json_encode($cursor);
}
?>
