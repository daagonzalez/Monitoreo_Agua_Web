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
    
    //$idUsr,$nombreGrafico,$descripcion,$tipoConsulta,null,null,$puntoMuestreo,$tipoGrafico,$primerPar,null
    public static function insertarGrafico($idUsr,$nombreGrafico,$descripcion,$tipoConsulta,$fechaInicio,$fechaFinal,$puntoMuestreo,$tipoGrafico,$primerPar,$segundoPar){
      $myfile = fopen("Prueba.txt", "w");
      fwrite($myfile, "Iniciando");
      $documento = array();
      
      $documento["usuario"] = $idUsr;
      fwrite($myfile, "Saco ID");
      $documento["nombreGrafico"] = $nombreGrafico;
      fwrite($myfile, "Saco NombreGrafico");
      $documento["descripcion"] = $descripcion;
      fwrite($myfile, "Descripcion");
      $documento["tipoConsulta"] = $tipoConsulta;
      fwrite($myfile, "tipo");
      if(!is_null($fechaInicio)){
        $fechaInicio = new MongoDB\BSON\UTCDateTime($fechaInicio->getTimeStamp()*1000);
        fwrite($myfile, "Cambie Fecha1");
      }
      fwrite($myfile, "No Cambie Fecha1");
      if(!is_null($fechaFinal)){
        $fechaFinal = new MongoDB\BSON\UTCDateTime($fechaFinal->getTimeStamp()*1000);
        fwrite($myfile, "Cambie Fecha2");
      }
      fwrite($myfile, "No Cambie Fecha2");
      $documento["fechaInicio"] = $fechaInicio;
      fwrite($myfile, "Fecha1");
      $documento["fechaFinal"] = $fechaFinal;
      fwrite($myfile, "Fecha2");
      $documento["puntoMuestreo"] = $puntoMuestreo;
      fwrite($myfile, "Punto");
      $documento["tipoGrafico"] = $tipoGrafico;
      fwrite($myfile, "Tipo Grafico");
      $documento["primerPar"] = $primerPar;
      fwrite($myfile, "Primer par");
      $documento["segundoPar"] = $segundoPar;
      fwrite($myfile, "Segundo par");
      
      $response = array();
      $response["success"] = false;
      try {
              $cliente = connectDatabaseClient('MonitoreoAgua',1);
              fwrite($myfile, "Se conecto");
              
              $insRec = new MongoDB\Driver\BulkWrite;
              fwrite($myfile, "BulkWrite");
  
              $_id = $insRec->insert($documento);
              fwrite($myfile, "_id");
              $writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000);
              fwrite($myfile, "writeConcern");
              $result = $cliente->executeBulkWrite('MonitoreoAgua.graficosUsuario', $insRec, $writeConcern);
              fwrite($myfile, "Realizó consulta");
          if (!is_null($result)) {
              $response["success"] = true;
              fclose($myfile);
          } else {
              $response["mensaje"] = "El registro falló. result = null";
              fclose($myfile);
          }
      } catch (MongoCursorException $e) {
          $response["mensaje"] = "El registro falló. MongoCursorException";
          fclose($myfile);
      } catch (MongoException $e) {
          $response["mensaje"] = "El registro falló. MongoException";
          fclose($myfile);
      } catch (MongoConnectionException $e) {
          $response["mensaje"] = "La conexión falló. MongoConnectionException";
          fclose($myfile);
      }
  
      return $response;
      
      
      
      
    }
    
    public static function getGraficosPorIDUsuario($idUsuario){
      $collection=connectDatabaseCollection('MonitoreoAgua','graficosUsuario',0);
      
      $parte3 = array('usuario' => $idUsuario);
      
      $query = $parte3;
      
      $options = ['sort' => ['_id' => 1]];

      $cursor = $collection->find($query, $options);
      return ($cursor);
      
    }
    
    public static function getGraficoPorID($idGrafico){
      $collection=connectDatabaseCollection('MonitoreoAgua','graficosUsuario',0);
      $dato = new MongoDB\BSON\ObjectID($id);
      $parte3 = array('_id' => $dato);
      
      $query = $parte3;
      
      $options = ['sort' => ['_id' => 1]];

      $cursor = $collection->find($query, $options);
      return ($cursor);
      
    }
    
    public static function eliminarGrafico($id) {
      
      $client = connectDatabaseClient('MonitoreoAgua',1);
      $response["success"] = false;
      
      try {
      $delRec = new MongoDB\Driver\BulkWrite;
      
      $dato = new MongoDB\BSON\ObjectID($id);
      
      
      $delRec->delete(['_id' =>$dato], ['limit' => 1]);
      $writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000);
      $result = $client->executeBulkWrite('MonitoreoAgua.graficosUsuario', $delRec, $writeConcern);
      
      if($result->getDeletedCount()){
      $response["success"] = true;
      //echo 'deleted';
      }
      
      } catch(MongoCursorException $e){
      
      $response["mensaje"] = "Falló al borrar el documento.";
      } catch (MongoException $e){
      $response["mensaje"] = "Falló al borrar el documento.";
      }
    }
    
    
  }
  ?>
