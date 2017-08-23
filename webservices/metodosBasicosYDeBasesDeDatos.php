<?php
require 'databaseConnection.php';
require 'QROverlap.php';

define(DS, DIRECTORY_SEPARATOR);


//Método para calcular el valor del indice NSF
function calc_indice_NSF($PO2, $DBO, $CF, $pH)
{
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


//Método para calcular el valor del indice NSF
function calc_indice_GLOBAL($PO2, $DBO, $CF, $pH)
{
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

//Método para calcular el índece Holandés
function calc_indice_holandes($PO2, $DBO, $NH4)
{
    $puntos = 0;
  //validacion PO2
    if ($PO2 >= 91 && $PO2 <= 100) {
        $puntos += 1;
    } elseif (($PO2 >= 71 && $PO2 <= 90)||($PO2 >= 111 && $PO2 <= 120)) {
        $puntos += 2;
    } elseif (($PO2 >= 51 && $PO2 <= 70)||($PO2 >= 121 && $PO2 <= 130)) {
        $puntos += 3;
    } elseif ($PO2 >= 31 && $PO2 <= 50) {
        $puntos += 4;
    } else {
        $puntos += 5;
    }
  //validacion DBO
    if ($DBO <= 3.0) {
        $puntos += 1;
    } elseif ($DBO >= 3.1 && $DBO <= 6.0) {
        $puntos += 2;
    } elseif ($DBO >= 6.1 && $DBO <= 9.0) {
        $puntos += 3;
    } elseif ($DBO >= 9.1 && $DBO <= 15.0) {
        $puntos += 4;
    } else {
        $puntos += 5;
    }
  //validacion NH4
    if ($NH4 < 0.50) {
        $puntos += 1;
    } elseif ($NH4 >= 0.50 && $NH4 <= 1.0) {
        $puntos += 2;
    } elseif ($NH4 >= 1.1 && $NH4 <= 2.0) {
        $puntos += 3;
    } elseif ($NH4 >= 2.1 && $NH4 <= 5.0) {
        $puntos += 4;
    } else {
        $puntos += 5;
    }
    return $puntos;
}


//Método para calcular el color asociado al valor del índice NSF
function calc_color_NSF($puntos)
{
    if ($puntos >= 91 && $puntos <= 100) {
        $respuesta = "Azul";
    } elseif ($puntos >= 71 && $puntos <= 90) {
        $respuesta = "Verde";
    } elseif ($puntos >= 51 && $puntos <= 70) {
        $respuesta = "Amarillo";
    } elseif ($puntos >= 26 && $puntos <= 50) {
        $respuesta = "Anaranjado";
    } else {
        $respuesta = "Rojo";
    }
    return $respuesta;
}


//Método para calcular el color asociado al valor del índice NSF
function calc_color_GLOBAL($puntos)
{
    if ($puntos >= 91 && $puntos <= 100) {
        $respuesta = "Azul";
    } elseif ($puntos >= 71 && $puntos <= 90) {
        $respuesta = "Verde";
    } elseif ($puntos >= 51 && $puntos <= 70) {
        $respuesta = "Amarillo";
    } elseif ($puntos >= 26 && $puntos <= 50) {
        $respuesta = "Anaranjado";
    } else {
        $respuesta = "Rojo";
    }
    return $respuesta;
}

//Método para calcular el color asociado al valor del índice
function calc_color_holandes($puntos)
{
    if ($puntos == 3) {
        $respuesta = "Azul";
    } elseif ($puntos >= 4 && $puntos <= 6) {
        $respuesta = "Verde";
    } elseif ($puntos >= 7 && $puntos <= 9) {
        $respuesta = "Amarillo";
    } elseif ($puntos >= 10 && $puntos <= 12) {
        $respuesta = "Anaranjado";
    } else {
        $respuesta = "Rojo";
    }
    return $respuesta;
}


//Método para validar que un dato es entero o flotante, si está vacio entonces ingresa ND = No definido
function validarDato($dato)
{
    if (is_numeric($dato)) {
        if (ctype_digit($dato)) {
            return (int) $dato;
        } else {
            return (float) $dato;
        }
    } else {
        $temp = 'ND';
        return $temp;
    }
}


//Método para insertar las imagenes en el documento de MongoDB.
function insertarImagenes($_id, $fotos, $palabras_clave)
{
    $count = count($fotos);
    if ($count > 0) {
        @mkdir("..".DS."pictures".DS."$_id".DS, 0777);
        $ruta = "http://monitoreoagua.ucr.ac.cr/pictures/".$_id."/";
        $destino = "..".DS."pictures".DS."$_id".DS;
        $fotos_array = array();
        $palabras_clave_array = array();


        for ($i = 0; $i < $count; $i++) {
            $base = $fotos["foto".($i+1)];
            $binary = base64_decode($base);
            header('Content-Type: bitmap; charset=utf-8');
            $nombre_foto = $i. ".jpg";
            $ruta_foto = $i.".jpg";
            $file = fopen($destino.$ruta_foto, 'wb');
            $palabras_clave_array[$i] = $palabras_clave["foto".($i+1)];
            $fotos_array[$i] = $ruta.$nombre_foto;
            fwrite($file, $binary);
            fclose($file);
        }

        chmod("..".DS."pictures".DS."$_id".DS, 0777);

        try {
            $cliente = connectDatabaseClient('MonitoreoAgua',1);

            $updRec = new MongoDB\Driver\BulkWrite;
            $updRec2 = new MongoDB\Driver\BulkWrite;
            $obj_id = new MongoDB\BSON\ObjectId($_id);

            $updRec->update(['_id' => $obj_id], ['$set' => ['Muestra.fotos' => $fotos_array]], ['multi' => false, 'upsert' => false]);
            $updRec2->update(['_id' => $obj_id], ['$set' => ['Muestra.palabras_claves' => $palabras_clave_array]], ['multi' => false, 'upsert' => false]);
            $writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000);
            $result = $cliente->executeBulkWrite('MonitoreoAgua.puntosMuestreo', $updRec, $writeConcern);
            $result2 = $cliente->executeBulkWrite('MonitoreoAgua.puntosMuestreo', $updRec2, $writeConcern);
            if ($result->getModifiedCount()) {
                agregarQR($_id, $count);
            }
        } catch (MongoConnectionException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}

//Método para insertar un documento a MongoDB
function insertarDocumento($documento, $fotos, $palabras_clave, $indice, $valor_ind, $color)
{
    $response = array();
    $response["success"] = false;
    try {
            $cliente = connectDatabaseClient('MonitoreoAgua',1);

            
            $insRec = new MongoDB\Driver\BulkWrite;

            $_id = $insRec->insert($documento);
            $writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000);
            $result = $cliente->executeBulkWrite('MonitoreoAgua.puntosMuestreo', $insRec, $writeConcern);
        if (!is_null($result)) {
            $response['indice'] = $indice;
            $response['valor'] = $valor_ind;
            $response['color'] = $color;
            $response["success"] = true;

            $_id = $_id->__toString();

            insertarImagenes($_id, $fotos, $palabras_clave);
        } else {
            $response["mensaje"] = "El registro falló. result = null";
        }
    } catch (MongoCursorException $e) {
        $response["mensaje"] = "El registro falló. MongoCursorException";
    } catch (MongoException $e) {
        $response["mensaje"] = "El registro falló. MongoException";
    } catch (MongoConnectionException $e) {
        $response["mensaje"] = "La conexión falló. MongoConnectionException";
    }

    return $response;
}



function modificarDocumento($_id, $documento, $fotos, $palabras_clave, $indice, $valor_ind, $color)
{
    $response = array();
    $response["success"] = false;

    try {
            $cliente = connectDatabaseClient('MonitoreoAgua',1);

        
            $updRec = new MongoDB\Driver\BulkWrite;
            $obj_id = new MongoDB\BSON\ObjectId($_id);

            $updRec->update(['_id' => $obj_id], ['$set' => $documento], ['multi' => false, 'upsert' => false]);
            $writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000);
            $result = $cliente->executeBulkWrite('MonitoreoAgua.puntosMuestreo', $updRec, $writeConcern);
        if ($result->getModifiedCount()) {
            $response['indice'] = $indice;
            $response['valor'] = $valor_ind;
            $response['color'] = $color;
            $response["success"] = true;
            $file = fopen("debugeditar2.txt", 'w');
            fwrite($file, "Inserte el documento\n");
            fclose($file);
            modificarImagenes($_id, $fotos, $palabras_clave);
        } else {
            $response["mensaje"] = "El registro falló.";
        }
    } catch (MongoConnectionException $e) {
        $response["mensaje"] = "El registro falló.";
    }

    return $response;
}


function modificarImagenes($_id, $fotos, $palabras_clave)
{
    $fileModificar = fopen("debugeditar3.txt", 'w');
    fwrite($fileModificar, "Estoy en modificar\n");
    
    $count = count($fotos);
    if ($count > 0) {
        //@mkdir("..".DS."pictures".DS."$_id".DS, 0777);
        fwrite($fileModificar, "Count $count \n");
        $ruta = "http://monitoreoagua.ucr.ac.cr/pictures/".$_id."/";
        $destino = "..".DS."pictures".DS."$_id".DS;
        $fotos_array = array();
        $palabras_clave_array = array();
        $fotos_editar_array = array();
        $indiceEditar = 0;
        $pattern = "/^http:\/\/monitoreoagua.ucr.ac.cr\/pictures\//";

        for ($i = 0; $i < $count; $i++) {
            $base = $fotos["foto".($i+1)];
            fwrite($fileModificar, "Foto ".($i + 1)." \n");
            preg_match($pattern, $base, $matches, PREG_OFFSET_CAPTURE);
            $cantidad = count($matches);
            fwrite($fileModificar, "Count matches $cantidad \n");
            if ($cantidad > 0) {
                fwrite($fileModificar, "Se cumple el pattern $base \n");
                //Imagen ya está.
                $palabras_clave_array[$i] = $palabras_clave["foto".($i+1)];
                $fotos_array[$i] = $base;
                fwrite($fileModificar, "Guardó en el array \n");
            } else {
                fwrite($fileModificar, "No se cumple el pattern $base \n");
                $binary = base64_decode($base);
                header('Content-Type: bitmap; charset=utf-8');
                $nombre_foto = $i. ".jpg";
                $ruta_foto = $i.".jpg";
                $file = fopen($destino.$ruta_foto, 'wb');
                $palabras_clave_array[$i] = $palabras_clave["foto".($i+1)];
                $fotos_array[$i] = $ruta.$nombre_foto;
                fwrite($file, $binary);
                fclose($file);
                $fotos_editar_array[$indiceEditar] = $nombre_foto;
                $indiceEditar += 1;
                fwrite($fileModificar, "Guardo la foto $nombre_foto \n");
            }
        }

        chmod("..".DS."pictures".DS."$_id".DS, 0777);

        try {
            $cliente = connectDatabaseClient('MonitoreoAgua',1);
            
            

            $updRec = new MongoDB\Driver\BulkWrite;
            $updRec2 = new MongoDB\Driver\BulkWrite;
            $obj_id = new MongoDB\BSON\ObjectId($_id);

            $updRec->update(['_id' => $obj_id], ['$set' => ['Muestra.fotos' => $fotos_array]], ['multi' => false, 'upsert' => false]);
            $updRec2->update(['_id' => $obj_id], ['$set' => ['Muestra.palabras_claves' => $palabras_clave_array]], ['multi' => false, 'upsert' => false]);
            $writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000);
            $result = $cliente->executeBulkWrite('MonitoreoAgua.puntosMuestreo', $updRec, $writeConcern);
            $result2 = $cliente->executeBulkWrite('MonitoreoAgua.puntosMuestreo', $updRec2, $writeConcern);
            if ($result->getModifiedCount()) {
                if ($indiceEditar > 0) {
                    fwrite($fileModificar, "Modifiqué el archivo. Envio a Johan\n");
                    agregarQRNuevas($_id, $indiceEditar, $fotos_editar_array);
                }
            }
        } catch (MongoConnectionException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    fclose($fileModificar);
}
