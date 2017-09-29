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
    public static function getPorRangoFechas($fI, $fF, $par1, $par2)
    {
      //$collection = Database::getInstance()->getDb()->sitiosMuestreo;
      $collection=connectDatabaseCollection('MonitoreoAgua','puntosMuestreo',0);
      $fInicial   = new MongoDB\BSON\UTCDateTime($fI);
      $fFinal     = new MongoDB\BSON\UTCDateTime($fF);
      
      $parte1 = array( '$or' => array( array('Muestra.obligatorios.$par1' => ['$ne' =>"ND"]), array('Muestra.opcionales.$par1' => ['$ne' =>"ND"]) ) );
      $parte2 = array( '$or' => array( array('Muestra.obligatorios.$par2' => ['$ne' =>"ND"]), array('Muestra.opcionales.$par2' => ['$ne' =>"ND"]) ) );
      $parte3     = array('Muestra.fecha' => array('$gt' => $fInicial, '$lte' => $fFinal));
      
      $query = array( '$and' => array( $parte3, array( '$and' => array( $parte1, $parte2  ))  ) );
      
      $options    = ['sort' => ['Muestra.fecha' => 1]];
      
      

      $cursor = $collection->find($query, $options);
      return ($cursor);
    }

    /**
    * Retorna todos los documentos de la colección "sitiosMuestreo" cuya nombre sea [nombre]
    * @param nombre del punto a buscar
    * @return MongoCursor con el resultado de la consulta
    **/
    public static function getPorNombre($nombre,$par1,$par2)
    {
      //$collection = Database::getInstance()->getDb()->sitiosMuestreo;
      $collection=connectDatabaseCollection('MonitoreoAgua','puntosMuestreo',0);
      
      $parte1 = array( '$or' => array( array('Muestra.obligatorios.$par1' => ['$ne' =>"ND"]), array('Muestra.opcionales.$par1' => ['$ne' =>"ND"]) ) );
      $parte2 = array( '$or' => array( array('Muestra.obligatorios.$par2' => ['$ne' =>"ND"]), array('Muestra.opcionales.$par2' => ['$ne' =>"ND"]) ) );
      //array( '$and' => array( array('Muestra.usuario' => $correo), array('Muestra.fecha' => ['$gte' =>  $mongo_date, '$lte'=> $mongo_date_today]) ) );
      $parte3 = array('POI.nombre_estacion' => $nombre);
      
      $query = array( '$and' => array( $parte3, array( '$and' => array( $parte1, $parte2  ))  ) );
      
      
      
      $options    = ['sort' => ['Muestra.fecha' => 1]];

      $cursor = $collection->find($query, $options);
      return ($cursor);
    }
  }
  ?>
