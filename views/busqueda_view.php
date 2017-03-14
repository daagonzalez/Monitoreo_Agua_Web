<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, user-scalable=no,
	 initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

    <!-- <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge"> -->

    <!-- Inserción de iconos -->

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/css/bootstrap.min.css" integrity="sha384-AysaV+vQoT3kOAXZkl02PThvDr8HYKPZhNT5h/CXfBThSRXQ6jW5DO2ekP5ViFdi" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="css/estilo_busqueda.css">
    <link rel="stylesheet" type="text/css" href="css/estilo_menu.css">
    <link rel="stylesheet" type="text/css" href="css/estilo_footer.css">
    <title>Muestreo de Agua</title>
</head>

<body>

    <?php 
    require 'views/menu_view.php';
   ?>
        <!-- Contenedor de toda la página -->
        <div class="main">
            <!-- SubMenu para el mapa, principalmente para anidar consultas -->
            <div class="anidar">
                <h3>Calidad del agua:</h3>
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

        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script type="text/javascript" src="js/mapa_busqueda.js"></script>
        <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBF0VFFF-7ojo6bKf_G81kq2cazEhaB2cc&signed_in=true&callback=initMap"></script>
        <!-- jQuery first, then Tether, then Bootstrap JS. -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js" integrity="sha384-3ceskX3iaEnIogmQchP8opvBy3Mi7Ce34nWjpBIwVTHfGYWQS9jwHDVRnpKKHJg7" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.3.7/js/tether.min.js" integrity="sha384-XTs3FgkjiBgo8qjEjBk0tGmf3wPrWtA6coPfQDfFEY8AnYJwjalXCiosYRBIBZX8" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/js/bootstrap.min.js" integrity="sha384-BLiI7JTZm+JWlgKa0M0kGRpJbF2J8q+qreVrKBC47e3K6BW78kGLrCkeRX6I9RoK" crossorigin="anonymous"></script>
</body>
<?php 
    require 'views/footer_view.php';
   ?>

</html>
