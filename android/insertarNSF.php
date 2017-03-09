<?php
require '../vendor/autoload.php';


//Método para calcular el valor del indice NSF
function calc_indice($PO2,$DBO,$CF,$pH){
    $valorPO2 = -13.55+(1.17*$PO2);
    $valorPO2 = $valorPO2*0.31;
    $valorDBO = 96.67-(7*$DBO);
    $valorDBO = $valorDBO*0.19;
    $valorCF = 97.2-(26.6*log10($CF));
    $valorCF = $valorCF*0.28;
    $valorpH = 316.96-(29.85*$pH);
    $valorpH = $valorpH*0.22;
    
    $respuesta = $valorPO2 + $valorDBO + $valorCF+ $valorpH;
    return $respuesta;
     
}
//Método para calcular el color asociado al valor del índice NSF
function calc_color($puntos){
    if($puntos >= 91 && $puntos <= 100){
        $respuesta = "Azul";
    }elseif($puntos >= 71 && $puntos <= 90){
        $respuesta = "Verde";
    }elseif($puntos >= 51 && $puntos <= 70){
        $respuesta = "Amarillo";
    }elseif($puntos >= 26 && $puntos <= 50){
        $respuesta = "Anaranjado";
    }else{
        $respuesta = "Rojo";
    }
    return $respuesta;
}

//Método para validar que un dato es entero o flotante, si está vacio entonces ingresa ND = No definido
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
    $CF = (float) $_POST['CF'];
    $pH = (float) $_POST['pH'];
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
    $NH4 = validarDato($_POST['NH4']);
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

    $valor_ind = calc_indice($PO2,$DBO,$CF,$pH);
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
    $obligatorios['CF'] = $CF;
    $obligatorios['pH'] = $pH;

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
    $opcionales['NH4'] = $NH4;
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
