<?php
require 'mongui.php';

/**
* Imprime un JSON con los documentos que estén dentro del rango de fechas ingresado como parámetro
**/
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  if (isset($_GET['fechaIni']) && isset($_GET['fechaFin'])) {
    //Si se invocó el PHP con ambos parámetros
    $fechaIni = $_GET['fechaIni'];
    $fechaFin = $_GET['fechaFin'];
    $datos    = Mongui::getPorRangoFechas($fechaIni, $fechaFin);
    if ((string) $datos->getID() != '') {
      //Si el ID de lo obtenido no es nulo
      print json_encode(iterator_to_array($datos));
    } else {
      print json_encode(
        array(
          'mensaje' => 'Ha ocurrido un error obteniendo los datos',
        )
      );
    }
  } else {
    print json_encode(
      array(
        'mensaje' => 'Se necesita especificar una fecha inicial y una fecha final v&aacutelidas',
      )
    );
  }
}
?>
