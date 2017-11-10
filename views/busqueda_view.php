<!DOCTYPE html>
<html lang="en">


<!-- Se cargar los encabezados de la página -->
<?php require 'views/inc/header.php';?>
<body>
<!-- El header contiene los generales mediante js se cargan los propios de la sección -->
<script type="text/javascript">
    $(document).ready(
        function(){
            $('head').append('<link rel="stylesheet" type="text/css" href="css/estilo_busqueda.css">');
        }
    )
</script>    
<!-- Se carga el cover para control de login mediante firebase -->
<?php require 'views/inc/login_cover.php';?>
<!-- Carga del menú del sitio web -->
<?php require 'views/inc/menu.php'; ?>


<main class="mdl-layout__content">
    <div class="page-content">
        <div class="mdl-grid">
            <div class="main">
            <!-- SubMenu para el mapa, principalmente para anidar consultas -->
            <div class="anidar">
                <h3 class="wo-line-height bold">Calidad del agua:</h3>
                <button class="btn" value=0 id="calidad1" style="background: blue"></button>
                <button class="btn" value=0 id="calidad2" style="background: green"></button>
                <button class="btn" value=0 id="calidad3" style="background: yellow"></button>
                <button class="btn" value=0 id="calidad4" style="background: orange"></button>
                <button class="btn" value=0 id="calidad5" style="background: red"></button>
                <input type="number" min="1" id="inputFilterRadio" placeholder="Radio">
                <button class="btn botonFiltroR" onclick="aplicarFiltro(document.getElementById('inputFilterRadio').value,1)"><i class="fa fa-filter"></i></button>
                <button class="btn reset" id="reset"><i class="fa fa-eraser"></i></button>
                <button class="btn btnFiltrarArPOI"><i class="fa fa-map-marker"></i>-<i class="fa fa-map-marker"></i></button>
            </div>

            <!-- Contenedor realizan las consultas gruesas de la base de datos, se encuentra dentro del mapa -->
            <!-- <div class="buscador">
  		<ul class="mainUl">
  			<h4 class="title">Seleccione filtros</h4>
  			<ul class="childUl">			
  				<li>
  					<h4>Fecha (inicio-fin)</h4> 
  					<input type="date" class="date">  
  					<input type="date" class="date">
  				</li>
  				<li>
  					<h4>Institución</h4>    
  					<select id="institucion">
  	
  					</select>
  		  	</li>
  	          <button class="btnFiltrar"><i class="fa fa-search" style="color: blue;"></i></button>
  			</ul>
  		</ul> 
  	</div> -->

            <!-- Contenedor del mapa, cargado desde js -->
            <div id="map"></div>

            <!-- Contenedor utilizado para mostrar los resultados del evento de seleccionar dos marcadores, cargado desde js -->
            <div class="container-fluid arPOIBig">
                <div class="col-md-9 arPOIShort">
                    <button class="btn" id="btnCloseArPOI">
                        <h5>X</h5></button>
                    <br>
                    <div class="contenidoArPOIShort"></div>
                </div>
            </div>
        </div>
        </div>
    </div>

  <?php require 'views/inc/footer.php';?>
</main>
</body>


<?php require 'views/inc/firebase.php';?>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript" src="js/mapa_busqueda.js"></script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBF0VFFF-7ojo6bKf_G81kq2cazEhaB2cc&signed_in=true&callback=initMap"></script>
</html>