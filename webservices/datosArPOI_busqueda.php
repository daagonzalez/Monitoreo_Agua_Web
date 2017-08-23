<?php
	header('Content-Type: application/json');
	require 'databaseConnection.php';

	if($_SERVER['REQUEST_METHOD']=='GET'){

		$id1=$_GET['id1'];
		$id2=$_GET['id2'];
		if($id1&&$id2){
			$collection=connectDatabaseCollection('MonitoreoAgua','puntosMuestreo',0);
			$items = $collection->find(['$or'=>[['_id'=>new MongoDB\BSON\ObjectID($id1)],['_id'=>new MongoDB\BSON\ObjectID($id2)]]]);
			$items=iterator_to_array($items);
			if(isset($items[0]->Muestra->val_indice)&&(string)$items[0]->Muestra->val_indice=='INF'){
				unset($items[0]->Muestra->val_indice);
			}
			if(isset($items[1]->Muestra->val_indice)&&(string)$items[1]->Muestra->val_indice=='INF'){
				unset($items[1]->Muestra->val_indice);
			}
			echo json_encode($items);
		}
	}
?>
