<?php
require '../vendor/autoload.php';
    try {
        $connection = new MongoDB\Client;
        $database = $connection->PuntosMuestreo;
        $collection = $database->usuarios;
    } catch (MongoConnectionException $e) {
        echo "Error: " . $e->getMessage();
    }

    $correo = filter_var(strtolower($_POST['correo']), FILTER_SANITIZE_STRING);
    $password = $_POST['contraseña'];
    $password = hash('sha512', $password);
    
    $datos = $collection->findOne(array('$and' => array(array('correo' => $correo), array('password' => $password) )));

    $response = array();
    $response["success"] = false;

    
    if (!is_null($datos)) {
        if($datos['validado'] == true){
            $response["success"] = true;
            $response["correo"] = $correo; 
        }
    }
echo json_encode($response);

?>
