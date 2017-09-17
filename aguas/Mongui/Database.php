<?php

//require_once 'mongo_login.php';
//require 'vendor/autoload.php';
$interDir='';
//$interDir='/Proyectos/Web-Projects-Importants/Monitoreo_Agua_Web';//Para compu Johan
require $_SERVER['DOCUMENT_ROOT'].$interDir.'/vendor/autoload.php';


/**
* Representa un objeto de la conexión con la base de datos de MongoDB
*/
class Database
{
  private static $db = null; //Objeto que contiene la instancia de la base de datos
  private static $conexion; //MongoDB Client para la conexión con la base de datos

  /**
  * Construye el objeto
  */
  final private function __construct()
  {
    self::getDb();
  }

  /**
  * Retorna la instancia del objeto si esta existe, sino, la crea
  * @return El objeto que contiene la instancia de la base de datos
  */
  public static function getInstance()
  {
    if (self::$db === null) {
      self::$db = new self();
    }
    return self::$db;
  }

  /**
  * Retorna un MongoDB Client para la conexión con la base de datos
  * @return un MongoDB Client
  */
  public function getDb()
  {
    if (self::$conexion == null) {
		  self::$conexion = new MongoDB\Client("mongodb://localhost:27017");
  	}
    // self::$conexion = new MongoDB\Client();
    return self::$conexion;//->jm;
  }

  /**
  * Evita la clonación del objeto
  */
  final protected function __clone()
  {}

  /**
  * Destruye el objeto
  */
  public function _destructor()
  {
    self::$conexion = null;
  }
}
?>
