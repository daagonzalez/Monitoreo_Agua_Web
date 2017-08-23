<?php
header('Content-Type: application/json');

require 'databaseConnection.php';

$client = connectDatabaseClient('MonitoreoAgua',1);

$_id = $_POST['_id'];

$response = array();
$response["success"] = false;

try {
$delRec = new MongoDB\Driver\BulkWrite;

$dato = new MongoDB\BSON\ObjectID($_id);


$delRec->delete(['_id' =>$dato], ['limit' => 1]);
$writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000);
$result = $client->executeBulkWrite('MonitoreoAgua.puntosMuestreo', $delRec, $writeConcern);

if($result->getDeletedCount()){
$response["success"] = true;
//echo 'deleted';
}

} catch(MongoCursorException $e){

$response["mensaje"] = "Falló al borrar el documento.";
} catch (MongoException $e){
$response["mensaje"] = "Falló al borrar el documento.";
}

echo json_encode($response);

?>

