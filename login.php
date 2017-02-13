<?php session_start();
require 'vendor/autoload.php';
if (isset($_SESSION['correo'])) {
    header('Location: busqueda.php');
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $correo = filter_var(strtolower($_POST['correo']), FILTER_SANITIZE_STRING);
    $password = $_POST['contraseña'];
    $password = hash('sha512', $password);

    $errores= '';

    if (empty($correo) or empty($password)) {
        $errores .= '<li>Por favor rellena todos los datos correctamente</li>';
    }else{
        try {
            $connection = new MongoDB\Client;
            $database = $connection->PuntosMuestreo;
            $collection = $database->usuarios;

        } catch (MongoConnectionException $e) {
            echo "Error: " . $e->getMessage();
        }

        $datos = $collection->findOne(array('$and' => array(array('correo' => $correo), array('password' => $password) )));
        if (is_null($datos)) {
            $errores .= '<li>Datos incorrectos.</li>';
        }else{
            if($datos['validado'] == true){
                $_SESSION['correo'] = $correo;
                header('Location: index.php');
            }else{
                $errores .= '<li>La cuenta no ha sido validada aún.</li>';
            }
            
        }
    }

    
}

    require 'views/login_view.php';
 ?>