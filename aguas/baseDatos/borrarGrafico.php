<?php
include('graficosUsuario.php');
/**
* Obtiene los metadatos del grafico del ID ingresado en la tabla graficosUsuario
**/
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['idGrafico'])) {
	$idGrafico = $_POST['idGrafico'];
	$datos = graficosUsuario::eliminarGrafico($idGrafico);
	
	print json_encode($datos);
  } else {
    print json_encode(
      array(
        'mensaje' => 'Ingrese el ID del gráfico a eliminar'
      )
    );
  }
}
?>
