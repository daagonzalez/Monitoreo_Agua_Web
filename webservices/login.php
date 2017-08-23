<?php
header('Content-Type: application/json');

require 'databaseConnection.php';

$collection=connectDatabaseCollection('MonitoreoAgua','usuarios',1);


    $correo = filter_var(strtolower($_POST['correo']), FILTER_SANITIZE_STRING);
    $password = $_POST['password'];
    //$password = hash('sha512', $password);
    
    $datos = $collection->findOne(array('$and' => array(array('correo' => $correo), array('password' => $password) )));

    $response = array();
    $response["success"] = false;

    
    if (!is_null($datos)) {
        if($datos['validado'] == true){
            $response["success"] = true;
            $response["correo"] = $correo;
            $response["password"] = $password;
        }else{
            $response["mensaje"] = "La cuenta no está validada."; 
        }
    }else{
        $response["mensaje"] = "Datos incorrectos."; 
    }
echo json_encode($response);

?>
