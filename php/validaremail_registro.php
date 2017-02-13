<?php

require '../../vendor/autoload.php';

function validarCorreo($dato){
  if (!filter_var($dato, FILTER_VALIDATE_EMAIL)) 
  {
    return false;
  }
  return $dato;

}
function validarContrasenna($dato)
{
  if(strlen($dato) < 6)
  {
     return false;
  }
  else if(!preg_match('/(?=\d)/', $dato)) 
  {
    return false;
  }
  else if(!preg_match('/(?=[a-z])/', $dato)) 
  {
    return false;
  }
  else if(!preg_match('/(?=[A-Z])/', $dato)) 
  {
    return false;
  }
  return $dato;
}

function generarLinkTemporal($idusuario){
  // Se genera una cadena para validar el cambio de contraseña
  $cadena = $idusuario.rand(1,9999999).date('Y-m-d');
  $token = sha1($cadena);

  try {
    $connection = new MongoDB\Client;
    $database = $connection->PuntosMuestreo;
    $collectionValidacion = $database->validacion;
  } catch (MongoConnectionException $e) {
    echo "Error: " . $e->getMessage();
  }

  $documento = array();
  $documento['correo'] = $idusuario;
  $documento['token'] = $token;
  //Agregar un timestamp para que tenga fecha de caducidad
  //¡PENDIENTE!
  //$documento['creado'] = new MongoDB\BSON\UTCDatetime($unix_timestamp * 1000);
  $resultado = $collectionValidacion->insertOne($documento, array('safe' => true));
 
   // $conexion = new mysqli('localhost', 'root', '', 'ejemplobd');
   // // Se inserta el registro en la tabla tblreseteopass
   // $sql = "INSERT INTO tblreseteopass (idusuario, username, token, creado) VALUES($idusuario,'$username','$token',NOW());";
   // $resultado = $conexion->query($sql);
   if(!is_null($resultado)){
      // Se devuelve el link que se enviara al usuario

    //Por el momento hay que agregar el :8081 despues del localhost, ya que no se puede poner en el link.!
      $enlace = $_SERVER["SERVER_NAME"].'/proyectoJavier/registro/php/validar.php?idusuario='.sha1($idusuario).'&token='.$token;
      return $enlace;
   }
   else
      return FALSE;
}
 
function enviarEmail( $email, $link ){
   $mensaje = '<html>
     <head>
        <title>Validación de cuenta</title>
     </head>
     <body>
       <p>Gracias por abrir una cuenta con Monitoreo de Aguas.</p>
       <p>Si realizó esta petición, haga clic en el siguiente enlace, si no hizo esta petición puedes ignorar este correo.</p>
       <p>
         <strong>Enlace para validar la cuenta.</strong><br>
         <a href="'.$link.'"> Validar cuenta </a>
       </p>
     </body>
    </html>';
 
   $cabeceras = 'MIME-Version: 1.0' . "\r\n";
   $cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
   $cabeceras .= 'From: Monitoreo Aguas <monitoreoaguacr@gmail.com>' . "\r\n";
   // Se envia el correo al usuario
   return mail($email, "Validar cuenta", $mensaje, $cabeceras);
}

$nombre = filter_var($_POST['nombre'], FILTER_SANITIZE_STRING);
$correo = validarCorreo(filter_var(strtolower($_POST['correo']), FILTER_SANITIZE_STRING));
$password = validarContrasenna($_POST['contrasenna']);
$password2 = $_POST['verificacion'];
 
$respuesta = new stdClass();

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>
<div class="container">
    <div class="row">
        <div class='col-md-3'></div>
        <div class="col-md-6">
            <div class="login-box well">

<?php
if( !empty($nombre) or !empty($correo) or !empty($password) or !empty($password2) or $correo != false or $password != false ){

  try {
    $connection = new MongoDB\Client;
    $database = $connection->PuntosMuestreo;
    $collection = $database->usuarios;
    $collectionValidacion = $database->validacion;
  } catch (MongoConnectionException $e) {
    echo "Error: " . $e->getMessage();
  }
  $datos = $collection->findOne(array('correo' => $correo),array('correo' => 1));
  if (!is_null($datos)) {
    ?>
           <div class="alert alert-danger" role="alert">
              <ul id="errores">
                <li> El nombre de correo ya existe. Intente con otro correo o seleccione este <a href="../../recuperar/" class="text-sm">link </a>para restablecer la contraseña.</li>
              </ul>
            </div>
<?php
  }else{
    $password = hash('sha512', $password);
    $password2 = hash('sha512', $password2);

    //echo "$nombre . $correo . $password . $password2";

    if ($password != $password2) {
      ?>
           <div class="alert alert-danger" role="alert">
              <ul id="errores">
                <li>Las contraseñas no son iguales.</li>
              </ul>
            </div>
<?php
    }

    try {
      $documento = array();
      $documento['nombre'] = $nombre;
      $documento['correo'] = $correo;
      $documento['password'] = $password;
      $documento['validado'] = False;
      $status = $collection->insertOne($documento, array('safe' => true));
      
    } catch(MongoCursorException $e){
      die("Ha fallado la insercion ". $e->getMessage());
    } catch (MongoException $e){
      die('No se han podido insertar los datos ' . $e->getMessage());
    }


    //Correo del usuario, Token, timestamp
    //Necesito sacar el correo del query que hice.

    //$respuesta->mensaje = '<div class="alert alert-success"> Correo= ' . $datos['correo'] . ' </div>';

    $linkTemporal = generarLinkTemporal( $correo);
    if($linkTemporal){
      $respuesta = enviarEmail( $correo, $linkTemporal );
      if($respuesta){
        ?>
           <div class="alert alert-success" role="alert">
              <ul id="errores">
                <li>¡Cuenta agregada con éxito!<br>Un correo de verificación ha sido enviado a su cuenta de email. </li>
              </ul>
            </div>
        <?php
      }else{
        $collectionValidacion->deleteOne(array('token' => $token));
        $collection->deleteOne(array('correo' => $correo));
        ?>
           <div class="alert alert-success" role="alert">
              <ul id="errores">
                <li>Se ha producido un error! Vuelva a intentarlo.</li>
              </ul>
            </div>
        <?php
      }
    }else{
      $collection->deleteOne(array('correo' => $correo));
      ?>
           <div class="alert alert-success" role="alert">
              <ul id="errores">
                <li>Se ha producido un error! Vuelva a intentarlo.</li>
              </ul>
            </div>
<?php
    }
  }
}else{
  ?>
           <div class="alert alert-danger" role="alert">
              <ul id="errores">
                <li>No se han insertado los datos correctamente, intente de nuevo.</li>
              </ul>
            </div>
<?php
}
?>
        </div>
        </div>
        <div class='col-md-3'></div>
    </div>
</div>

    <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</body>
</html>


