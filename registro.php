<?php session_start();
//require '../vendor/autoload.php';
if (isset($_SESSION['correo'])) {
	header('Location: busqueda.php');
}
/*$errores= '';
$mensaje= '';

function validarCorreo($dato){
	if (!filter_var($dato, FILTER_VALIDATE_EMAIL)) 
	{
		$GLOBALS['errores'] .= '<li>Formato de correo incorrecto.</li>';
	}
	return $dato;

}
function validarContrasenna($dato)
{
	if(strlen($dato) < 6)
	{
		 $GLOBALS['errores'] .= '<li>La contraseña es demasiado corta.</li>';
	}
	else if(!preg_match('/(?=\d)/', $dato)) 
	{
		$GLOBALS['errores'] .= '<li>La contraseña debe contener al menos un digito.</li>';
	}
	else if(!preg_match('/(?=[a-z])/', $dato)) 
	{
		$GLOBALS['errores'] .= '<li>La contraseña debe contener al menos una minuscula.</li>';
	}
	else if(!preg_match('/(?=[A-Z])/', $dato)) 
	{
		$GLOBALS['errores'] .= '<li>La contraseña debe contener al menos una mayuscula.</li>';
	}
	return $dato;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$nombre = filter_var($_POST['nombre'], FILTER_SANITIZE_STRING);
	$correo = validarCorreo(filter_var(strtolower($_POST['correo']), FILTER_SANITIZE_STRING));
	$password = validarContrasenna($_POST['contrasenna']);
	$password2 = $_POST['verificacion'];

	//echo "$nombre . $correo . $password . $password2";

	if (empty($nombre) or empty($correo) or empty($password) or empty($password2)) {
		$errores .= '<li>Por favor rellena todos los datos correctamente</li>';
	}else{
		try {
			$connection = new MongoDB\Client;
			$database = $connection->PuntosMuestreo;
			$collection = $database->usuarios;

		} catch (MongoConnectionException $e) {
			echo "Error: " . $e->getMessage();
		}

		$datos = $collection->findOne(array('correo' => $correo),array('correo' => 1));
		if (!is_null($datos)) {
			$errores .= '<li>El nombre de correo ya existe</li>';
		}

		$password = hash('sha512', $password);
		$password2 = hash('sha512', $password2);

		//echo "$nombre . $correo . $password . $password2";

		if ($password != $password2) {
			$errores .= '<li>Las contraseñas no son iguales</li>';
		}
	}

	if ($errores == '') {
		try {
			$documento = array();
			$documento['nombre'] = $nombre;
			$documento['correo'] = $correo;
			$documento['password'] = $password;
			$documento['validado'] = False;
			$status = $collection->insertOne($documento, array('safe' => true));
			header('Location: ../login/');
		} catch(MongoCursorException $e){
			die("Ha fallado la insercion ". $e->getMessage());
		} catch (MongoException $e){
			die('No se han podido insertar los datos ' . $e->getMessage());
		}

	}

}*/

	require 'views/registro_view.php';

 ?>