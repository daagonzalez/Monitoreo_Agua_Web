<?php
	header('Content-Type: application/json');

	require 'databaseConnection.php';

	if($_SERVER['REQUEST_METHOD']=='GET'){

		$id1=$_GET['id1'];
		if($id1){
			$collection=connectDatabaseCollection('MonitoreoAgua','puntosMuestreo',0);
		    $idBson = new MongoDB\BSON\ObjectID($id1);
			$item = $collection->find(['_id'=>$idBson]);
			//echo $item->Muestra->val_indice;
			$item=iterator_to_array($item);
			if(isset($item[0]->Muestra->val_indice)){
				unset($item[0]->Muestra->val_indice);
			}
			
			
			$item[0]->Muestra->fecha=$item[0]->Muestra->fecha->toDateTime()->format('Y-m-d H:i:s');
			echo json_encode($item);
		}
	}
?>
