<?php
require '../vendor/autoload.php';
$token = $_GET['token'];
$idusuario = $_GET['idusuario'];

try {
    $connection = new MongoDB\Client;
    $database = $connection->PuntosMuestreo;
    $collection = $database->restore;
  } catch (MongoConnectionException $e) {
    echo "Error: " . $e->getMessage();
  }

  $resultado = $collection->findOne(array('token' => $token));

 
if( !is_null($resultado) ){
   if( sha1($resultado['correo']) == $idusuario ){
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
                    <form id="frmRestablecer" action="cambiarpassword.php"  method= "POST" name="login">
                        <legend>Recuperar contraseña</legend>
                        <div class="form-group">
                            <label for="password">Contraseña</label>
                            <input id="password" name="password1" value='' placeholder="Contraseña" type="password" class="form-control" required pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}" title="La contraseña debe de contener al menos 6 caracteres, incluyendo letras minúsculas, mayúsculas y números." onchange="this.setCustomValidity(this.validity.patternMismatch ? this.title : '');
  if(this.checkValidity()) form.verificacion.pattern = this.value;"/>
                        </div>
                        <div class="form-group">
                            <label for="password2">Repite la contraseña</label>
                            <input title="Escriba la misma contraseña" id="password2" name="password2" value='' placeholder="Contraseña" type="password" class="form-control" required pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}" onchange="this.setCustomValidity(this.validity.patternMismatch ? this.title : '');"/>
                        </div>
                        <input type="hidden" name="token" value="<?php echo $token ?>">
                        <input type="hidden" name="idusuario" value="<?php echo $idusuario ?>">
                        <div class="form-group">
                            <input class="btn btn-default btn-block m-t-md" type="submit" value="Restablecer">
                        </div>
                    </form>
                
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