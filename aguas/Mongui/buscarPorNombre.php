<?php
require 'mongui.php';

/**
* Imprime un JSON con los documentos que tengan por nombre del punto de muestreo el parámetro ingresado
**/
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  if (isset($_GET['nombre'])) {
    //Si se invocó el PHP con un parámetro nombre
    $nombre = $_GET['nombre'];
    $datos  = Mongui::getPorNombre($nombre);
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
        'mensaje' => 'Se necesita especificar un nombre v&aacutelido',
      )
    );
  }
}
?>
