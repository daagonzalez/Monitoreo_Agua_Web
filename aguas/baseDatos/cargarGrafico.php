<?php
include('graficosUsuario.php');
/**
* Obtiene los metadatos del grafico del ID ingresado en la tabla graficosUsuario
**/
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  if (isset($_GET['idGrafico'])) {
	$idGrafico = $_GET['idGrafico'];
	$datos = graficosUsuario::getGraficoPorID($idGrafico);
	
	print json_encode($datos);
  } else {
    print json_encode(
      array(
        'mensaje' => 'Ingrese el ID del gráfico a recuperar'
      )
    );
  }
}
?>
