<?php

require '../../vendor/autoload.php';
function generarLinkTemporal($idusuario){
  // Se genera una cadena para validar el cambio de contraseña
  $cadena = $idusuario.rand(1,9999999).date('Y-m-d');
  $token = sha1($cadena);

  try {
    $connection = new MongoDB\Client;
    $database = $connection->PuntosMuestreo;
    $collection = $database->restore;
  } catch (MongoConnectionException $e) {
    echo "Error: " . $e->getMessage();
  }

  $documento = array();
  $documento['correo'] = $idusuario;
  $documento['token'] = $token;
  //Agregar un timestamp para que tenga fecha de caducidad
  //¡PENDIENTE!
  //$documento['creado'] = new MongoDB\BSON\UTCDatetime($unix_timestamp * 1000);
  $resultado = $collection->insertOne($documento, array('safe' => true));
 
   // $conexion = new mysqli('localhost', 'root', '', 'ejemplobd');
   // // Se inserta el registro en la tabla tblreseteopass
   // $sql = "INSERT INTO tblreseteopass (idusuario, username, token, creado) VALUES($idusuario,'$username','$token',NOW());";
   // $resultado = $conexion->query($sql);
   if(!is_null($resultado)){
      // Se devuelve el link que se enviara al usuario

    //Por el momento hay que agregar el :8081 despues del localhost, ya que no se puede poner en el link.!
      $enlace = $_SERVER["SERVER_NAME"].'/proyectoJavier/recuperar/php/restablecer.php?idusuario='.sha1($idusuario).'&token='.$token;
      return $enlace;
   }
   else
      return FALSE;
}
 
function enviarEmail( $email, $link ){
   $mensaje = '<html>
     <head>
        <title>Restablezca su contraseña</title>
     </head>
     <body>
       <p>Hemos recibido una petición para restablecer la contraseña de su cuenta.</p>
       <p>Si realizó esta petición, haga clic en el siguiente enlace, si no hizo esta petición puedes ignorar este correo.</p>
       <p>
         <strong>Enlace para restablecer su contraseña</strong><br>
         <a href="'.$link.'"> Restablecer contraseña </a>
       </p>
     </body>
    </html>';
 
   $cabeceras = 'MIME-Version: 1.0' . "\r\n";
   $cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
   $cabeceras .= 'From: Monitoreo Aguas <monitoreoaguacr@gmail.com>' . "\r\n";
   // Se envia el correo al usuario
   mail($email, "Recuperar contraseña", $mensaje, $cabeceras);
}

$correo = filter_var(strtolower($_POST['correo']), FILTER_SANITIZE_STRING);
 
$respuesta = new stdClass();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Restablecer contraseña</title>
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
 
if( $correo != "" ){



  try {
    $connection = new MongoDB\Client;
    $database = $connection->PuntosMuestreo;
    $collection = $database->usuarios;
  } catch (MongoConnectionException $e) {
    echo "Error: " . $e->getMessage();
  }
  $datos = $collection->findOne(array('correo' => $correo));
  if (is_null($datos)) {
    ?>
           <div class="alert alert-danger" role="alert">
              <ul id="errores">
                <li> No existe una cuenta asociada a ese correo. </li>
              </ul>
            </div>
<?php
  }else{
    //Correo del usuario, Token, timestamp
    //Necesito sacar el correo del query que hice.

    //$respuesta->mensaje = '<div class="alert alert-success"> Correo= ' . $datos['correo'] . ' </div>';

    $linkTemporal = generarLinkTemporal( $datos['correo']);
    if($linkTemporal){
      enviarEmail( $correo, $linkTemporal );
      ?>
           <div class="alert alert-success" role="alert">
              <ul id="errores">
                <li> Un correo ha sido enviado a su cuenta de email con las instrucciones para restablecer la contraseña. </li>
              </ul>
            </div>
<?php
    }
  }
}else{
  ?>
           <div class="alert alert-danger" role="alert">
              <ul id="errores">
                <li> Debes introducir el email de la cuenta. </li>
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


