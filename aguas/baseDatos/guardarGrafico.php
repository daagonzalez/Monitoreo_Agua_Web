<?php
include('graficosUsuario.php');
/**
* Inserta los metadatos del grafico en la tabla graficosUsuario, de acuerdo al tipo de consulta realizada 
**/
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['nombreGrafico']) && isset($_POST['descripcion']) &&  isset($_POST['tipoGrafico']) && isset($_POST['primerPar']) && isset($_POST['tipoConsulta'])) {

	session_start();
    $idUsr = $_SESSION['idUsuario'];
    $nombreGrafico = $_POST['nombreGrafico'];
	$descripcion = $_POST['descripcion'];
    $tipoGrafico = $_POST['tipoGrafico'];
    $primerPar = $_POST['primerPar'];
    $tipoConsulta = $_POST['tipoConsulta'];

    if ($tipoConsulta == "Fechas") {
      if (isset($_POST['fechaInicio']) && isset($_POST['fechaFinal'])) {
        
        $fechaInicio = $_POST['fechaInicio'];
        $fechaFinal = $_POST['fechaFinal'];

        if ($tipoGrafico == "Burbuja") {
          if (isset($_POST['segundoPar'])) {
            $segundoPar = $_POST['segundoPar'];
			//Insertar un gráfico de burbujas, consultado por rango de fechas
            $datos = graficosUsuario::insertarGrafico($idUsr,$nombreGrafico,$descripcion,$tipoConsulta,$fechaInicio,$fechaFinal,null,$tipoGrafico,$primerPar,$segundoPar);

            print json_encode($datos);
          } else {
            print json_encode(
              array(
                'mensaje' => 'Se necesita especificar un segundo punto de muestreo para este tipo de gráfico'
              )
            );
          }
        } else {
		  //Insertar un gráfico consultado por rango de fechas
          $datos = graficosUsuario::insertarGrafico($idUsr,$nombreGrafico,$descripcion,$tipoConsulta,$fechaInicio,$fechaFinal,null,$tipoGrafico,$primerPar,null);
          print json_encode($datos);
        }
      } else {
        print json_encode(
          array(
            'mensaje' => 'Se necesitan especificar fechas validas'
          )
        );
      }
    } else {
      if (isset($_POST['puntoMuestreo'])) {
        $puntoMuestreo = $_POST['puntoMuestreo'];
        if ($tipoGrafico == "Burbuja") {
          if (isset($_POST['segundoPar'])) {
            $segundoPar = $_POST['segundoPar'];
			//Insertar un gráfico de burbuja, consultado por punto de muestreo
            $datos = graficosUsuario::insertarGrafico($idUsr,$nombreGrafico,$descripcion,$tipoConsulta,null,null,$puntoMuestreo,$tipoGrafico,$primerPar,$segundoPar);
            print json_encode($datos);
          } else {
            print json_encode(
              array(
                'mensaje' => 'Se necesita especificar un segundo punto de muestreo para este tipo de gráfico'
              )
            );
          }
        } else {
		  //Insertar un gráfico, consultado por punto de muestreo
          $datos = graficosUsuario::insertarGrafico($idUsr,$nombreGrafico,$descripcion,$tipoConsulta,null,null,$puntoMuestreo,$tipoGrafico,$primerPar,null);
          print json_encode($datos);
        }
      } else {
        print json_encode(
          array(
            'mensaje' => 'Se necesita especificar un punto de muestreo válido'
          )
        );
      }
    }
  } else {
    print json_encode(
      array(
        'mensaje' => 'Faltan parametros para poder guardar el grafico'
      )
    );
  }
}
?>
