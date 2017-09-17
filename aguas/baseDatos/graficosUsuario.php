<?php
include('mysql_connect.php');
/**
* Clase que contiene los métodos de las consultas para manipular los metadatos de los gráficos
**/
class graficosUsuario extends dbConn
{
	/**
	* Retorna la fecha de creación, el nombre, la descripción y el tipo de los gráficos del usuario de ID ingresado
	* @param id del usuario
	* @return JSON con los resultados
	**/
    public function getGraficosPorIDUsuario($idUsr) {
        try {
            $dbMySQL = new dbConn;

            $stmt = $dbMySQL->conn->prepare("SELECT fechaCreacion, nombreGrafico, descripcion, tipoGrafico, tipoConsulta FROM graficosusuario WHERE idUsuario = :myid ORDER BY fechaCreacion");
            $stmt->bindParam(':myid', $idUsr);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            $result = "Error: " . $e->getMessage();
        }

        return $result;
    }

	/**
	* Retorna los datos del gráfico del ID ingresado
	* @param id del gráfico
	* @return JSON con los resultados
	**/
    public function getGraficoPorID($idGraf) {
      try {
          $dbMySQL = new dbConn;
			
          $stmt = $dbMySQL->conn->prepare("SELECT * FROM graficosusuario WHERE fechaCreacion = :myid");
          $stmt->bindParam(':myid', $idGraf);
          $stmt->execute();
          $result = $stmt->fetch(PDO::FETCH_ASSOC);

      } catch (PDOException $e) {
          $result = "Error: " . $e->getMessage();
      }

      return $result;
    }
	
	/**
	* Inserta un gráfico con los parámetros ingresados
	* @param idUsr el ID del usuario
	* @param nombreGrafico el nombre del gráfico
	* @param fechaCreacion la fecha de creación del gráfico
	* @param tipoConsulta el tipo de consulta realizada
	* @param fechaInicio la fecha inicial del rango de consulta
	* @param fechaFinal la fecha final del rango de consulta
	* @param puntoMuestreo el punto de muestreo de la consulta
	* @param tipoGrafico el tipo de gráfico
	* @param primerPar el primer parámetro de consulta
	* @param segundoPar el segundo parámetro de consulta
	* @return true si se insertó correctamente
	**/
    public function insertarGrafico($idUsr,$nombreGrafico,$descripcion,$tipoConsulta,$fechaInicio,$fechaFinal,$puntoMuestreo,$tipoGrafico,$primerPar,$segundoPar) {
      try {
        $dbMySQL = new dbConn;
        $stmt = $dbMySQL->conn->prepare("INSERT INTO graficosUsuario(idUsuario,nombreGrafico,descripcion,tipoConsulta,fechaInicio,fechaFinal,puntoMuestreo,tipoGrafico,primerParametro,segundoParametro)
        VALUES (:idU,:nGrafico,:descripcion,:tConsulta,:fInicio,:fFinal,:pMuestreo,:tGrafico,:pPar,:sPar)");
        $stmt->bindParam(':idU',$idUsr);
        $stmt->bindParam(':nGrafico',$nombreGrafico);
        $stmt->bindParam(':descripcion',$descripcion);
        $stmt->bindParam(':tConsulta',$tipoConsulta);
        $stmt->bindParam(':fInicio',$fechaInicio);
        $stmt->bindParam(':fFinal',$fechaFinal);
        $stmt->bindParam(':pMuestreo',$puntoMuestreo);
        $stmt->bindParam(':tGrafico',$tipoGrafico);
        $stmt->bindParam(':pPar',$primerPar);
        $stmt->bindParam(':sPar',$segundoPar);
        $stmt->execute();

        $err = '';
      } catch (PDOException $e) {
        $err = "Error: " . $e->getMessage();
      }
      if ($err == '') {
          $success = 'true';
      } else {
          $success = $err;
      };
      return $success;
    }

	/**
	* Modifica los datos de un gráfico con los parámetros ingresados
	* @param idGrafico el ID del gráfico
	* @param nombreGrafico el nombre del gráfico
	* @param tipoConsulta el tipo de consulta realizada
	* @param fechaInicio la fecha inicial del rango de consulta
	* @param fechaFinal la fecha final del rango de consulta
	* @param puntoMuestreo el punto de muestreo de la consulta
	* @param tipoGrafico el tipo de gráfico
	* @param primerPar el primer parámetro de consulta
	* @param segundoPar el segundo parámetro de consulta
	* @return true si se realizó el cambio correctamente
	**/
    public function modificarGrafico($fechaCreacion,$nombreGrafico,$descripcion,$tipoConsulta,$fechaInicio,$fechaFinal,$puntoMuestreo,$tipoGrafico,$primerPar,$segundoPar) {
      try {
        $dbMySQL = new dbConn;
		if ($tipoConsulta == "Fechas") {
			$stmt = $dbMySQL->conn->prepare("UPDATE graficosUsuario
			SET nombreGrafico = :nGrafico,
				descripcion = :descripcion,
				tipoConsulta = :tConsulta,
				fechaInicio = :fInicio,
				fechaFinal = :fFinal,
				tipoGrafico = :tGrafico,
				primerParametro = :pPar,
				segundoParametro = :sPar
			WHERE fechaCreacion = :idGraf");
			$stmt->bindParam(':idGraf',$fechaCreacion);
			$stmt->bindParam(':nGrafico',$nombreGrafico);
			$stmt->bindParam(':descripcion',$descripcion);
			$stmt->bindParam(':tConsulta',$tipoConsulta);
			$stmt->bindParam(':fInicio',$fechaInicio);
			$stmt->bindParam(':fFinal',$fechaFinal);
			$stmt->bindParam(':tGrafico',$tipoGrafico);
			$stmt->bindParam(':pPar',$primerPar);
			$stmt->bindParam(':sPar',$segundoPar);
		} else {
			$stmt = $dbMySQL->conn->prepare("UPDATE graficosUsuario
			SET nombreGrafico = :nGrafico,
				descripcion = :descripcion,
				tipoConsulta = :tConsulta,
				puntoMuestreo = :pMuestreo,
				tipoGrafico = :tGrafico,
				primerParametro = :pPar,
				segundoParametro = :sPar
			WHERE fechaCreacion = :idGraf");
			$stmt->bindParam(':idGraf',$idGrafico);
			$stmt->bindParam(':nGrafico',$nombreGrafico);
			$stmt->bindParam(':descripcion',$descripcion);
			$stmt->bindParam(':tConsulta',$tipoConsulta);
			$stmt->bindParam(':pMuestreo',$puntoMuestreo);
			$stmt->bindParam(':tGrafico',$tipoGrafico);
			$stmt->bindParam(':pPar',$primerPar);
			$stmt->bindParam(':sPar',$segundoPar);
		}
        
        $stmt->execute();

        $err = '';
      } catch (PDOException $e) {
        $err = "Error: " . $e->getMessage();
      }
      if ($err == '') {
          $success = 'true';
      } else {
          $success = $err;
      };
      return $success;
    }

	/**
	* Elimina el gráfico de ID ingresado
	* @param el ID del gráfico
	* @return true si se eliminó correctamente
	**/
    public function eliminarGrafico($id) {
      try {
        $dbMySQL = new dbConn;
        $stmt = $dbMySQL->conn->prepare("DELETE FROM graficosUsuario WHERE fechaCreacion = :idGraf");
        $stmt->bindParam(':idGraf',$id);
        $stmt->execute();

        $err = '';
      } catch (PDOException $e) {
        $err = "Error: " . $e->getMessage();
      }
      if ($err == '') {
          $success = 'true';
      } else {
          $success = $err;
      };
      return $success;
    }
}
