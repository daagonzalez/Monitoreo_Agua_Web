<?php
require '../vendor/autoload.php';


//Método para calcular el índece Holandés
function calc_indice($PO2, $DBO, $NH4){
    $puntos = 0;
  //validacion PO2
  if($PO2 >= 91 && $PO2 <= 100){
    $puntos += 1;
  }elseif(($PO2 >= 71 && $PO2 <= 90)||($PO2 >= 111 && $PO2 <= 120)){
    $puntos += 2;
  }elseif(($PO2 >= 51 && $PO2 <= 70)||($PO2 >= 121 && $PO2 <= 130)){
    $puntos += 3;
  }elseif($PO2 >= 31 && $PO2 <= 50){
    $puntos += 4;
  }else{
    $puntos += 5;
  }
  //validacion DBO
  if($DBO <= 3.0){
    $puntos += 1;
  }elseif($DBO >= 3.1 && $DBO <= 6.0){
    $puntos += 2;
  }elseif($DBO >= 6.1 && $DBO <= 9.0){
    $puntos += 3;
  }elseif($DBO >= 9.1 && $DBO <= 15.0){
    $puntos += 4;
  }else{
    $puntos += 5;
  }
  //validacion NH4
  if($NH4 < 0.50){
    $puntos += 1;
  }elseif($NH4 >= 0.50 && $NH4 <= 1.0){
    $puntos += 2;
  }elseif($NH4 >= 1.1 && $NH4 <= 2.0){
    $puntos += 3;
  }elseif($NH4 >= 2.1 && $NH4 <= 5.0){
    $puntos += 4;
  }else{
    $puntos += 5;
  }
    return $puntos;
     
}

//Método para calcular el color asociado al valor del índice
function calc_color($puntos){
    if($puntos == 3 ){
    $respuesta = "Azul";
  }elseif($puntos >= 4 && $puntos <= 6){
    $respuesta = "Verde";
  }elseif($puntos >= 7 && $puntos <= 9){
    $respuesta = "Amarillo";
  }elseif($puntos >= 10 && $puntos <= 12){
    $respuesta = "Anaranjado";
  }else{
    $respuesta = "Rojo";
  }
    return $respuesta;
}

function validarDato($dato){
  if(is_numeric($dato)){
    if(ctype_digit($dato)){
      return (int) $dato;
    }else{
      return (float) $dato;
    }
  }else{
    $temp = 'ND';
    return $temp;
  }    
}


//Intenta conectarse con la base de datos
    try {
        $connection = new MongoDB\Client;
        $database = $connection->PuntosMuestreo;
        $collection = $database->DatosCurri;
    } catch (MongoConnectionException $e) {
        echo "Error: " . $e->getMessage();
    }


    //Sacar Datos
    $correo = filter_var(strtolower($_POST['usuario']), FILTER_SANITIZE_STRING);
    $indice = $_POST['Indice'];
    $fecha = $_POST['fecha'];
    $temp_agua = validarDato($_POST['temp_agua']);
    $velocidad_agua = validarDato($_POST['velocidad_agua']);
    $area_cauce_rio = validarDato($_POST['area_cauce_rio']);
    $PO2 = (float) $_POST['PO2'];
    $DBO = (float) $_POST['DBO'];
    $NH4 = (float) $_POST['NH4'];
    $CF = validarDato($_POST['CF']);
    $pH = validarDato($_POST['pH']);
    $DQO = validarDato($_POST['DQO']);
    $EC = validarDato($_POST['EC']);
    $PO4 = validarDato($_POST['PO4']);
    $GYA = validarDato($_POST['GYA']);
    $SD = validarDato($_POST['SD']);
    $Ssed = validarDato($_POST['Ssed']);
    $SST = validarDato($_POST['SST']);
    $SAAM = validarDato($_POST['SAAM']);
    $T = validarDato($_POST['T']);
    $Aforo = validarDato($_POST['Aforo']);
    $ST = validarDato($_POST['ST']);
    $Fosfato = validarDato($_POST['Fosfato']);
    $Nitrato = validarDato($_POST['Nitrato']);
    $Turbidez = validarDato($_POST['Turbidez']);
    $Sol_totales = validarDato($_POST['Sol_totales']);
    $nombre_institucion = filter_var($_POST['nombre_institucion'], FILTER_SANITIZE_STRING);
    $nombre_estacion = filter_var($_POST['nombre_estacion'], FILTER_SANITIZE_STRING);
    $kit_desc = $_POST['kit_desc'];
    $lat = (float) $_POST['lat'];
    $lng = (float) $_POST['lng'];
    $alt = (float) $_POST['alt'];
    $cod_prov = (int) $_POST['cod_prov'];
    $cod_cant = (int) $_POST['cod_cant'];
    $cod_dist = (int) $_POST['cod_dist'];
    $cod_rio = (int) $_POST['cod_rio'];

    $valor_ind = calc_indice($PO2, $DBO, $NH4);
    $color = calc_color($valor_ind);

    //Definición de documentos
    $documento = array();
    $Muestra = array();
    $POI = array();
    $obligatorios = array();
    $opcionales = array();
    $location = array();
    $datos_geograficos = array();

    //Insercion de datos a documentos
    $obligatorios['% O2'] = $PO2;
    $obligatorios['DBO'] = $DBO;
    $obligatorios['NH4'] = $NH4;

    $opcionales['DQO'] = $DQO;
    $opcionales['EC'] = $EC;
    $opcionales['PO4'] = $PO4;
    $opcionales['GYA'] = $GYA;
    $opcionales['SD'] = $SD;
    $opcionales['Ssed'] = $Ssed;
    $opcionales['SST'] = $SST;
    $opcionales['SAAM'] = $SAAM;
    $opcionales['T'] = $T;
    $opcionales['Aforo'] = $Aforo;
    $opcionales['ST'] = $ST;
    $opcionales['CF'] = $CF;
    $opcionales['pH'] = $pH;    
    $opcionales['Fosfato'] = $Fosfato;
    $opcionales['Nitrato'] = $Nitrato;
    $opcionales['Turbidez'] = $Turbidez;
    $opcionales['Sol_totales'] = $Sol_totales;

    $Muestra['usuario'] = $correo;
    $Muestra['fecha'] = $fecha;
    $Muestra['indice_usado'] = $indice;
    $Muestra['val_indice'] = $valor_ind;
    $Muestra['color'] = $color;
    $Muestra['temp_agua'] = $temp_agua;
    $Muestra['velocidad_agua'] = $velocidad_agua;
    $Muestra['area_cauce_rio'] = $area_cauce_rio;
    $Muestra['obligatorios'] = $obligatorios;
    $Muestra['opcionales'] = $opcionales;

    $location['lat'] = $lat;
    $location['lng'] = $lng;

    $datos_geograficos['alt'] = $alt;
    $datos_geograficos['cod_prov'] = $cod_prov;
    $datos_geograficos['cod_cant'] = $cod_cant;
    $datos_geograficos['cod_dist'] = $cod_dist;
    $datos_geograficos['cod_rio'] = $cod_rio;

    $POI['nombre_institucion'] = $nombre_institucion;
    $POI['nombre_estacion'] = $nombre_estacion;
    $POI['kit_desc'] = $kit_desc;
    $POI['location'] = $location;
    $POI['datos_geograficos'] = $datos_geograficos;
    
    $documento['Muestra'] = $Muestra;
    $documento['POI'] = $POI;
    
    //inserción del documento a la base de datos


    $response = array();
    $response["success"] = false;

    if(!is_null($indice)){
        try { 
            $status = $collection->insertOne($documento, array('safe' => true));
            $response['indice'] = $indice;
            $response['valor'] = $valor_ind;
            $response['color'] = $color;
            $response["success"] = true;

        } catch(MongoCursorException $e){
            $response["mensaje"] = "El registro falló."; 
        } catch (MongoException $e){
            $response["mensaje"] = "El registro falló."; 
        }
        
    }else{
        $response["mensaje"] = "El registro falló.";
    }

    
echo json_encode($response);

?>
