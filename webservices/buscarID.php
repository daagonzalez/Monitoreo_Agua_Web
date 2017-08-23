<?php
header('Content-Type: application/json');

require 'databaseConnection.php';


$collection=connectDatabase('MonitoreaAgua','puntosMuestreo',0);


$_id = $_POST['_id'];

$dato = new MongoDB\BSON\ObjectID($_id);

//$documentos = array();
$datos = $collection->findOne(array('_id' => $dato));



$response = array();
$response["success"] = false;
if($datos != null ){
    $response["success"] = true;
    $response["documentos"] = $datos;    
}



echo json_encode($response);

?>