<?php require "inc/header.php";?>
<!-- Scripts requeridos -->
<script src="https://code.jquery.com/jquery-3.2.1.min.js"
  integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
  crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.4/Chart.min.js"></script>
<script src="js/llenarComboPuntos.js"></script>

<!-- Contenedor principal -->
<div class="container mt-4">

  <!-- Parámetros -->
  <div class="row">
    <div class="col-md-6">
      <form action="/action_page.php">
        <div id="comboPuntos"></div>
        <script>llenarPuntos();</script><br>
        Fecha Inicial: <input type="date" id="fechaI" onchange="if(graficoGenerado && !graficoNombre) graficar('Fechas');"><br>
        Fecha Final: <input type="date" id="fechaF"onchange="if(graficoGenerado && !graficoNombre) graficar('Nombres');"><br>
      </form>
      <script src="js/graficador.js"></script>
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
      <!--<input type="radio" id= "btnBurbuja" name="tipoGrafico" value="burbuja" onclick="cambiarTipoGrafico('burbuja');"> Burbuja-->
    </div>
  </div>

  <!-- Información del gráfico -->
  <script> var vNombre = "Gráfico sin título"; var vDescripcion = "";</script>
  <div id =infoGrafico style="display: none">
	  <div class="row" id=textboxNombreGrafico>
		Nombre del gr&aacutefico:&nbsp<input type="text" id="nombreGrafico" value="Gráfico sin título" size=120 onchange="if(this.value != vNombre) {document.getElementById('btnGuardarGraf').disabled = false; vNombre = this.value;}">
	  </div>
	  <div class="row" id=textboxDescripcion>
		Descripci&oacuten del gr&aacutefico:&nbsp<input type="text" id="descripcionGrafico" size=120 onchange="if(this.value != vDescripcion) {document.getElementById('btnGuardarGraf').disabled = false; vDescripcion = this.value;}">
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
        win.document.write("<b>" + document.getElementById("nombreGrafico").value + "</b><br><img src='"+myChart.toDataURL("image/jpge")+"'/>");
      }
      </script>
      <script src="js/grafiMySQL.js"></script>
      <button onclick="graficoAImagen()" class="button btn btn-primary mt-4">Guardar como imagen</button>
      <button onclick="guardarGrafi()" id="btnGuardarGraf" class="button btn btn-primary mt-4">Guardar Gr&aacutefico</button>
    </div>
  </div>

  </div>


  <!-- <div class="row">
  <div class="col-12">
  <h3>
  Nuevo gráfico
</h3>
<hr class="my-3">
</hr>
</div>
<div class="col-md-3">
<form>
<h5 class="mt-4">
Elegir tipo de gráfico
</h5>
<div class="form-group">
<select class="form-control" id="exampleSelect1">
<option>
Líneas
</option>
<option>
Barras
</option>
<option>
Burbujas
</option>
</select>
</div>
<hr class="my-3">
<h5 class="mt-4">
Consulta por nombre
</h5>
<div class="form-group">
<input class="form-control" id="nombre" placeholder="Localización" type="text">
</input>
</div>
<button class="btn btn-primary" onclick="graficarPorNombre()">
Generar
</button>
<h5 class="mt-4">
Consulta por fechas
</h5>
<div class="form-group">
<label for="example-date-input">
Fecha inicial
</label>
<input class="form-control" id="fechaI" type="date" value="2017-01-01">
</input>
</div>
<div class="form-group">
<label for="example-date-input">
Fecha final
</label>
<input class="form-control" id="fechaF" type="date" value="2017-01-01">
</input>
</div>
<button class="btn btn-primary" onclick="graficar()">
Generar
</button>
<h5 class="mt-4">
Parámetros a considerar
</h5>
<div class="form-check">
<label class="form-check-label">
<input class="form-check-input mr-2" type="checkbox" value="">
Parámetro
</input>
</label>
</div>
<div class="form-check">
<label class="form-check-label">
<input class="form-check-input mr-2" type="checkbox" value="">
Parámetro
</input>
</label>
</div>
<button class="btn btn-primary mt-4" type="submit">
Generar
</button>
</hr>
</form>
</div>
<div class="col-md-9">
<canvas height="15" id="myChart" width="100%">
</canvas>
</div>
</div> -->

<?php require "inc/footer.php";?>
