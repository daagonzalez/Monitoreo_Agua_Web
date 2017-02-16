<?php
require '../vendor/autoload.php';
    try {
        $connection = new MongoDB\Client;
        $database = $connection->PuntosMuestreo;
        $collection = $database->usuarios;
    } catch (MongoConnectionException $e) {
        echo "Error: " . $e->getMessage();
    }

    $nombre = filter_var($_POST['nombre'], FILTER_SANITIZE_STRING);
    $correo = filter_var(strtolower($_POST['correo']), FILTER_SANITIZE_STRING);
    $password = $_POST['contraseña'];
    $password = hash('sha512', $password);
    
    $datos = $collection->findOne(array('correo' => $correo),array('correo' => 1));

    $response = array();
    $response["success"] = false;

    
    if (!is_null($datos)) {
            $response["mensaje"] = "El correo ya existe."; 
    }else{
        if (empty($correo) or empty($password)) {
            $response["mensaje"] = "Ingrese los campos de nombre y correo.";
        }else{
            try {
            
                $documento = array();
                $documento['nombre'] = $nombre;
                $documento['correo'] = $correo;
                $documento['password'] = $password;
                $documento['validado'] = True;
                $status = $collection->insertOne($documento, array('safe' => true));
                $response["success"] = true;

            } catch(MongoCursorException $e){
              $response["mensaje"] = "El registro falló."; 
            } catch (MongoException $e){
              $response["mensaje"] = "El registro falló."; 
            }
        }
        
    }

    echo json_encode($response);

?>
