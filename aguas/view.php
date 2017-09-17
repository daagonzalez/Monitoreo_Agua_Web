<!-- Guardar el id del Gráfico en una variable -->
<?php
	if ($_SERVER['REQUEST_METHOD'] == 'GET') {
	  if (isset($_GET['idGrafico'])) {
		  echo '<script> var idGrafico = "' . $_GET['idGrafico'] . '";</script>';
	  }
	} 
?>

<!-- Scripts requeridos -->
<?php require "inc/header.php";?>
<script src="http://code.jquery.com/jquery-3.2.1.min.js"
  integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
  crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.4/Chart.min.js"></script>
<script src="js/llenarComboPuntos.js"></script>
<script src="js/grafiMySQL.js"></script>
<script src="js/graficador.js"></script>
<script> graficoNuevo = false; </script>

<!-- Contenedor principal -->
<div class="container mt-4">

  <!-- Parámetros -->
  <div class="row">
    <div class="col-md-6">
      <form action="/action_page.php">
        <div id="comboPuntos"></div>
        <script>llenarPuntos();</script><br>
        Fecha Inicial: <input type="date" id="fechaI" value="2014-01-01" onchange="if(graficoGenerado && !graficoNombre) graficar('Fechas');"><br>
        Fecha Final: <input type="date" id="fechaF" value="2015-12-31" onchange="if(graficoGenerado && !graficoNombre) graficar('Nombres');"><br>
      </form>
      <button class="button btn btn-primary mt-4" id="btnGraficoFecha" onclick="graficar('Fechas');">Consulta por Fechas</button>
      <button class="button btn btn-primary mt-4" id="btnGraficoNombre" onclick="graficar('Nombres');">Consulta por Punto de Muestreo</button>
      <br>
      <br>
    </div>
    <div class="col-md-5">
      <div id="comboParametros"></div>
      <div id="comboParametros2"></div>
      <script>llenarParametros();</script>
      <br>
      Tipo de gr&aacutefico:&nbsp;
      <input type="radio" id= "btnArea" name="tipoGrafico" value="area" checked="true" onclick="cambiarTipoGrafico('area');"> Por área &nbsp;
      <input type="radio" id= "btnXY" name="tipoGrafico" value="xy" onclick="cambiarTipoGrafico('xy');"> XY &nbsp;
      <input type="radio" id= "btnBurbuja" name="tipoGrafico" value="burbuja" onclick="cambiarTipoGrafico('burbuja');"> Burbuja
    </div>
  </div>

  <!-- Información del gráfico -->
  <script> var vNombre = "Gráfico sin título"; var vDescripcion = "";</script>
  <div id =infoGrafico style="display: none">
	  <div class="row" id=textboxNombreGrafico>
		Nombre del gr&aacutefico:&nbsp<input type="text" id="nombreGrafico" value="Gráfico sin título" size=120 onchange="if(this.value != vNombre) {document.getElementById('btnModGraf').disabled = false; vNombre = this.value;}">
	  </div>
	  <div class="row" id=textboxDescripcion>
		Descripci&oacuten del gr&aacutefico:&nbsp<input type="text" id="descripcionGrafico" size=120 onchange="if(this.value != vDescripcion) {document.getElementById('btnModGraf').disabled = false; vDescripcion = this.value;}">
	  </div>
  </div>

  <!-- Canvas del gráfico -->
  <div class=row id="canvasGrafico">
    <canvas id="myChart" width="100%" height="15"></canvas>
  </div>
  <!-- Menu con las fechas para el gráfico de burbuja -->
  <div class="scrollmenu" id="scrollFechas">
  </div>

  <!-- Botones para guardar el gráfico -->
  <div class=row id=botonesGuardar style="display: none">

    <div class="col-md-6">
      <script>
      function graficoAImagen(){
        var win=window.open();
        win.document.write("<b>" + document.getElementById("nombreGrafico").value + "</b><br><img src='"+myChart.toDataURL("image/png")+"'/>");
      }
      </script>
      <button onclick="eliminarGrafi()" class="button btn btn-primary mt-4">Eliminar gr&aacutefico</button>
      <button onclick="modificarGrafi()" id="btnModGraf" class="button btn btn-primary mt-4">Guardar Cambios</button>
    </div>
  </div>

  </div>

  <!-- Graficar el gráfico cargado -->
  <script>cargarGrafi();</script>

<?php require "inc/footer.php";?>
