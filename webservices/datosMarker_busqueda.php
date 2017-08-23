<?php
	header('Content-Type: application/json');

	require 'databaseConnection.php';

	if($_SERVER['REQUEST_METHOD']=='GET'){

		$id1=$_GET['id1'];
		if($id1){
			$collection=connectDatabaseCollection('MonitoreaAgua','puntosMuestreo',0);
		    $idBson = new MongoDB\BSON\ObjectID($id1);
			$item = $collection->find(['_id'=>$idBson]);
			//echo $item->Muestra->val_indice;
			$item=iterator_to_array($item);
			if(isset($item[0]->Muestra->val_indice)){
				unset($item[0]->Muestra->val_indice);
			}
			echo json_encode($item);

		}
	}
?>
