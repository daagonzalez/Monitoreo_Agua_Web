<?php
require 'databaseConnection.php';
require 'metodosBasicosYDeBasesDeDatos.php';


//Sacar Datos
$correo = filter_var(strtolower($_POST['usuario']), FILTER_SANITIZE_STRING);
$indice = $_POST['Indice'];
$fecha = $_POST['fecha'];
$orig_date = new DateTime($fecha);
$fecha = new MongoDB\BSON\UTCDateTime($orig_date->getTimeStamp()*1000);

$flag = $_POST['flag'];
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
$Biodiversidad = validarDato($_POST['Biodiversidad']);
$nombre_institucion = filter_var($_POST['nombre_institucion'], FILTER_SANITIZE_STRING);
$nombre_estacion = filter_var($_POST['nombre_estacion'], FILTER_SANITIZE_STRING);
$kit_desc = $_POST['kit_desc'];
$lat = (float) $_POST['lat'];
$lng = (float) $_POST['lng'];
$alt = (float) $_POST['alt'];
$pais = filter_var($_POST['country'], FILTER_SANITIZE_STRING);
$area_admin_1 = filter_var($_POST['area_admin_1'], FILTER_SANITIZE_STRING);
$area_admin_2 =  filter_var($_POST['area_admin_2'], FILTER_SANITIZE_STRING);
$area_admin_3 =  filter_var($_POST['area_admin_3'], FILTER_SANITIZE_STRING);



$valor_ind = calc_indice_holandes($PO2, $DBO, $NH4);
$color = calc_color_holandes($valor_ind);

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
$opcionales['Biodiversidad'] = $Biodiversidad;

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
$datos_geograficos['pais'] = $pais;
$datos_geograficos['area_administrativa_1'] = $area_admin_1;
$datos_geograficos['area_administrativa_2'] = $area_admin_2;
$datos_geograficos['area_administrativa_3'] = $area_admin_3;

$POI['nombre_institucion'] = $nombre_institucion;
$POI['nombre_estacion'] = $nombre_estacion;
$POI['kit_desc'] = $kit_desc;
$POI['location'] = $location;
$POI['datos_geograficos'] = $datos_geograficos;

$documento['Muestra'] = $Muestra;
$documento['POI'] = $POI;

//inserción del documento a la base de datos


$response = array();

if (strcmp($flag, "true") == 0) {
    $_id = $_POST['obj_id'];

    //foto_editable - palabras_clave_foto_editable
    $fotos = array();
    $indice_foto = 1;
    $palabras_clave = array();
    if (isset($_POST['foto_editable0'])) {
        $nombre = "foto".$indice_foto;
        $fotos[$nombre] = $_POST['foto_editable0'];
        $palabras_clave[$nombre] = explode(" ", $_POST['palabras_clave_foto_editable0']);
        $indice_foto = $indice_foto + 1;
    }
    if (isset($_POST['foto_editable1'])) {
        $nombre = "foto".$indice_foto;
        $fotos[$nombre] = $_POST['foto_editable1'];
        $palabras_clave[$nombre] = explode(" ", $_POST['palabras_clave_foto_editable1']);
        $indice_foto = $indice_foto + 1;
    }
    if (isset($_POST['foto_editable2'])) {
        $nombre = "foto".$indice_foto;
        $fotos[$nombre] = $_POST['foto_editable2'];
        $palabras_clave[$nombre] = explode(" ", $_POST['palabras_clave_foto_editable2']);
        $indice_foto = $indice_foto + 1;
    }
    if (isset($_POST['foto_editable3'])) {
        $nombre = "foto".$indice_foto;
        $fotos[$nombre] = $_POST['foto_editable3'];
        $palabras_clave[$nombre] = explode(" ", $_POST['palabras_clave_foto_editable3']);
        $indice_foto = $indice_foto + 1;
    }
    $response = modificarDocumento($_id, $documento, $fotos, $palabras_clave, $indice, $valor_ind, $color);
} else {
    if (!is_null($indice)) {
        $fotos = array();
        $indice_foto = 1;
        $palabras_clave = array();
        if (isset($_POST['foto0'])) {
            $nombre = "foto".$indice_foto;
            $fotos[$nombre] = $_POST['foto0'];
            $palabras_clave[$nombre] = explode(" ", $_POST['palabras_clave_foto0']);
            $indice_foto = $indice_foto + 1;
        }
        if (isset($_POST['foto1'])) {
            $nombre = "foto".$indice_foto;
            $fotos[$nombre] = $_POST['foto1'];
            $palabras_clave[$nombre] = explode(" ", $_POST['palabras_clave_foto1']);
            $indice_foto = $indice_foto + 1;
        }
        if (isset($_POST['foto2'])) {
            $nombre = "foto".$indice_foto;
            $fotos[$nombre] = $_POST['foto2'];
            $palabras_clave[$nombre] = explode(" ", $_POST['palabras_clave_foto2']);
            $indice_foto = $indice_foto + 1;
        }
        if (isset($_POST['foto3'])) {
            $nombre = "foto".$indice_foto;
            $fotos[$nombre] = $_POST['foto3'];
            $palabras_clave[$nombre] = explode(" ", $_POST['palabras_clave_foto3']);
            $indice_foto = $indice_foto + 1;
        }
        $response = insertarDocumento($documento, $fotos, $palabras_clave, $indice, $valor_ind, $color);
    } else {
        $response["success"] = false;
        $response["mensaje"] = "El registro falló. indice = null";
    }
}

    
echo json_encode($response);
