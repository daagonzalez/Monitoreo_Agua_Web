<?php

header('Content-Type: application/json');
 
$interDir='';
$ruta = $_SERVER['DOCUMENT_ROOT'].$interDir.'/aguas/Mongui/mongui.php';
$myfile = fopen("testfile.txt", "w");
fwrite($myfile, $ruta);



require $ruta; 



include('graficosUsuario.php');
/**
* Inserta los metadatos del grafico en la tabla graficosUsuario, de acuerdo al tipo de consulta realizada 
**/
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['nombreGrafico']) && isset($_POST['descripcion']) &&  isset($_POST['tipoGrafico']) && isset($_POST['primerPar']) && isset($_POST['tipoConsulta'])) {

	//session_start();
    $idUsr = $_POST['usuario'];
    $nombreGrafico = $_POST['nombreGrafico'];
	$descripcion = $_POST['descripcion'];
    $tipoGrafico = $_POST['tipoGrafico'];
    $primerPar = $_POST['primerPar'];
    $tipoConsulta = $_POST['tipoConsulta'];
    fwrite($myfile, "\nSaco datos");


    if ($tipoConsulta == "Fechas") {
      if (isset($_POST['fechaInicio']) && isset($_POST['fechaFinal'])) {
        
        $fechaInicio = $_POST['fechaInicio'];
        $fechaFinal = $_POST['fechaFinal'];

        if ($tipoGrafico == "Burbuja") {
          if (isset($_POST['segundoPar'])) {
            $segundoPar = $_POST['segundoPar'];
			//Insertar un gráfico de burbujas, consultado por rango de fechas
            $datos = Mongui::insertarGrafico($idUsr,$nombreGrafico,$descripcion,$tipoConsulta,$fechaInicio,$fechaFinal,null,$tipoGrafico,$primerPar,$segundoPar);

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
		      fwrite($myfile, "\nAntes1");
          $datos = Mongui::insertarGrafico($idUsr,$nombreGrafico,$descripcion,$tipoConsulta,$fechaInicio,$fechaFinal,null,$tipoGrafico,$primerPar,null);
          fwrite($myfile, "\nInsertar un gráfico consultado por rango de fechas");
          fclose($myfile);
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
            $datos = Mongui::insertarGrafico($idUsr,$nombreGrafico,$descripcion,$tipoConsulta,null,null,$puntoMuestreo,$tipoGrafico,$primerPar,$segundoPar);
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
		      fwrite($myfile, "\nAntes2");
          $datos = Mongui::insertarGrafico($idUsr,$nombreGrafico,$descripcion,$tipoConsulta,null,null,$puntoMuestreo,$tipoGrafico,$primerPar,null);
          fwrite($myfile, "\nInsertar un gráfico, consultado por punto de muestreo");
          fclose($myfile);
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
