<?php
require '../vendor/autoload.php';
$token = $_GET['token'];
$idusuario = $_GET['idusuario'];

try {
    $connection = new MongoDB\Client;
    $database = $connection->PuntosMuestreo;
    $collection = $database->validacion;
    $collectionUsuario = $database->usuarios;
  } catch (MongoConnectionException $e) {
    echo "Error: " . $e->getMessage();
  }

  $resultado = $collection->findOne(array('token' => $token));
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Validación</title>
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
if( !is_null($resultado) ){
   if( sha1($resultado['correo']) == $idusuario ){
    $newdata = array('$set' => array("validado" => true));
    $resultado = $collectionUsuario->updateOne(array("correo" => $resultado['correo']), $newdata);
    if( !is_null($resultado) ){
      $resultado = $collection->deleteOne(array('token' => $token));

?>


              <div class="alert alert-success" role="alert">
                <ul id="errores">
                  <li>¡Cuenta validada con éxito!<br>Proceda a <a href="../../login/" class="text-sm">Iniciar Sesión</a>. </li>

<?php
}
else{
  ?>
     <div class="alert alert-danger" role="alert">
                <ul id="errores">
                  <li>¡No se ha podido validar la cuenta! Inténtelo de nuevo. <br> Si el problema persiste comuníquese con nosotros para solucionar el problema. </li>

<?php
}
?>
                </ul>
              </div>
                
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
 }
 else{
     header('Location:../../login/');
 }
?>