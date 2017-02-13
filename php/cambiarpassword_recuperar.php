<?php
require '../../vendor/autoload.php';
$password1 = $_POST['password1'];
$password2 = $_POST['password2'];
$idusuario = $_POST['idusuario'];
$token = $_POST['token'];
 
if( $password1 != "" && $password2 != "" && $idusuario != "" && $token != "" ){
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

  try {
    $connection = new MongoDB\Client;
    $database = $connection->PuntosMuestreo;
    $collection = $database->restore;
    $collectionUsuario = $database->usuarios;
  } catch (MongoConnectionException $e) {
    echo "Error: " . $e->getMessage();
  }

  $resultado = $collection->findOne(array('token' => $token));
  if( !is_null($resultado) ){
    if( sha1($resultado['correo']) == $idusuario ){

         if( $password1 === $password2 ){
          
            $password = hash('sha512', $password1);
            $newdata = array('$set' => array("password" => $password));
            $resultado = $collectionUsuario->updateOne(array("correo" => $resultado['correo']), $newdata);

            
            if(!is_null($resultado)){
              $resultado = $collection->deleteOne(array('token' => $token));
?>
              <div class="alert alert-success" role="alert">
                <ul id="errores">
                  <li> La contraseña se actualizó con éxito. </li>
                </ul>
              </div>
              <div class="form-group">
                            <a href="../../login/" class="btn btn-default btn-block m-t-md">Volver a Inicio de Sesión</a>
                        </div>
<?php
            }
            else{
?>
            <div class="alert alert-danger" role="alert">
              <ul id="errores">
                <li> Ocurrió un error al actualizar la contraseña, intentalo más tarde. </li>
              </ul>
            </div>
<?php
            }
         }
         else{
?>
           <div class="alert alert-danger" role="alert">
              <ul id="errores">
                <li> Las contraseñas no coinciden. </li>
              </ul>
            </div>
<?php
         }
      }
      else{
?>
        <div class="alert alert-danger" role="alert">
          <ul id="errores">
            <li> El token no es válido. </li>
          </ul>
        </div>
<?php
      }
   }
   else{
?>
      <div class="alert alert-danger" role="alert">
          <ul id="errores">
            <li> El token no es válido. </li>
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
<?php
}
else{
   header('Location:../../login/');
}
?>