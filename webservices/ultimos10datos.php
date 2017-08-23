<?php
header('Content-Type: application/json');
require 'databaseConnection.php';



    $collection=connectDatabaseCollection('MonitoreoAgua','puntosMuestreo',0);


    $correo = filter_var(strtolower($_POST['correo']), FILTER_SANITIZE_STRING);
    //$correo = "kenca23@gmail.com";


$date = new DateTime();
$today = new DateTime();
$date->sub(new DateInterval('P10D'));
$mongo_date = new MongoDB\BSON\UTCDateTime($date->getTimeStamp()*1000);

$mongo_date_today = new MongoDB\BSON\UTCDateTime($today->getTimeStamp()*1000);

//echo "Fechas entre el ";
//echo $date->format('Y-m-d') . " y el ";
//echo $today->format('Y-m-d') . "\n";

$query = array( '$and' => array( array('Muestra.usuario' => $correo), array('Muestra.fecha' => ['$gte' =>  $mongo_date, '$lte'=> $mongo_date_today]) ) );

$projection = array("_id" => true, "Muestra.fecha" => true, "Muestra.indice_usado" => true, "Muestra.val_indice" => true, "Muestra.color" => true  );

$datos = $collection->find($query, $projection);

$documentos = array();
$documento = array();


foreach ($datos as $doc) {
    $documento["_id"] = (string)$doc['_id'];
    //echo $documento["_id"];
    //echo "\n";
    //$fecha = date(preg_replace('`(?<!\\\\)u`', $doc['Muestra']['fecha']->msec, 'Y-M-d H:i:s.u'), $doc['Muestra']['fecha']->msec);
    $documento["fecha"] = $doc['Muestra']['fecha']->toDateTime()->format('d-m-Y');
    
     //date('Y-m-d', $doc['Muestra']['fecha']->msec);
    //$fecha = new MongoDB\BSON\UTCDateTime($doc['Muestra']['fecha']);
    
    //$documento["fecha"] = $fecha.toString();
    //echo $documento["fecha"];
    //echo "\n";
    $documento["indice_usado"] = $doc['Muestra']['indice_usado'] ;
    //echo $documento["indice_usado"];
    //echo "\n";
    $documento["val_indice"] = $doc['Muestra']['val_indice'] ;
    //echo $documento["val_indice"];
    //echo "\n";
    $documento["color"] = $doc['Muestra']['color'] ;
    //echo $documento["color"];
    //echo "\n";

    array_push($documentos, $documento);


}

//var_dump($doc);
/*

$documento["_id"] = $doc['_id'];
    $documento["fecha"] =  date('Y-m-d', $doc['Muestra']['fecha']->sec);
    echo $documento["fecha"] . "\n"
    $documento["indice_usado"] = $doc['Muestra']['indice_usado'] ;
    echo $documento["indice_usado"] . "\n"
    $documento["val_indice"] = $doc['Muestra']['val_indice'] ;
    echo $documento["val_indice"] . "\n"
    $documento["color"] = $doc['Muestra']['color'] ;
    echo $documento["color"] . "\n"

    array_push($documentos, $var);

*/

$response = array();
$response["success"] = false;
if(count($documentos) > 0 ){
    $response["success"] = true;
    $response["documentos"] = $documentos;    
}

//echo "Datos Mostrados con Correo y fecha \n";

//foreach ($datos as $dato){

//echo sprintf("Fecha: %s%s.\n", dato['Muestra.fecha'], PHP_EOL);

//}

echo json_encode($response);




?>
