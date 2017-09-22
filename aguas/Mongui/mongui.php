<?php
//require 'Database.php';

header('Content-Type: application/json');
 
$interDir='';

require $_SERVER['DOCUMENT_ROOT'].$interDir.'/webservices/databaseConnection.php';

$collection = null;

class Mongui
{
  /**
  *   Clase que contiene los métodos de las consultas con la base de MongoDB
  **/
  public function __construct()
  {}

    /**
    * Retorna todos los documentos de la colección "sitiosMuestreo"
    * @return MongoCursor con el resultado de la consulta
    **/
    public static function getAll()
    {
      //$collection = Database::getInstance()->getDb()->sitiosMuestreo;
      $collection=connectDatabaseCollection('MonitoreoAgua','puntosMuestreo',0);
      $cursor     = $collection->find();
      return ($cursor);
    }

    /**
    * Retorna todos los documentos de la colección "sitiosMuestreo" cuya fecha esté entre fI y fF
    * @param fI Fecha Inicial
    * @param fF Fecha Final
    * @return MongoCursor con el resultado de la consulta
    **/
    public static function getPorRangoFechas($fI, $fF)
    {
      //$collection = Database::getInstance()->getDb()->sitiosMuestreo;
      $collection=connectDatabaseCollection('MonitoreoAgua','puntosMuestreo',0);
      $fInicial   = new MongoDB\BSON\UTCDateTime($fI);
      $fFinal     = new MongoDB\BSON\UTCDateTime($fF);
      $query      = array('Muestra.fecha' => array('$gt' => $fInicial, '$lte' => $fFinal));
      $options    = ['sort' => ['Muestra.fecha' => 1]];

      $cursor = $collection->find($query, $options);
      return ($cursor);
    }

    /**
    * Retorna todos los documentos de la colección "sitiosMuestreo" cuya nombre sea [nombre]
    * @param nombre del punto a buscar
    * @return MongoCursor con el resultado de la consulta
    **/
    public static function getPorNombre($nombre)
    {
      //$collection = Database::getInstance()->getDb()->sitiosMuestreo;
      $collection=connectDatabaseCollection('MonitoreoAgua','puntosMuestreo',0);
      $query      = array('POI.nombre_estacion' => $nombre);
      $options    = ['sort' => ['Muestra.fecha' => 1]];

      $cursor = $collection->find($query, $options);
      return ($cursor);
    }
  }
  ?>
