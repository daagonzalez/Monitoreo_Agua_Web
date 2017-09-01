/*Código para crear un mapa de google y sobre él indicar marcadores que indican puntos de muestreo sobre calidad de agua, además en este archivo
se crean los eventos y consultas a la BD mongoDB con ayuda de PHP.*/
//--------------------------------------------VARIABLES GLOBALES-------------------------------------------------------------//
var jsonDatosBD='';//variable global con la finalidad de guardar los datos de las consultas y así poder anidar consultas. Guarda los valores en formato JSON.
var map; //mapa general 
var markers=[];//marcadores indicadores de calidad del agua
var niveles=[];//es paralelo a vector de marcadores acá se guardan las calidades del agua del marcador i, se utiliza para buscar sobre él y no sobre los marcadores
var filterMarker;//marcador movible para indicar areas de filtro
var colors = ["null","blue","green","yellow","orange","red"];//colores asociados a cada calidad
var calidad = ["null","excelente","buena calidad","aceptable","contaminada","fuertemente contaminada"];//nombres asociados a cada calidad
var contadorClicks = 0; //evento de aritmetica de POIS, lleva un conteo de los clicks porque solo deben haber dos seleccionados. 
var first;//utilizado para indexar el vector markers al momento de tener dos.
var second;//utilizado para indexar el vector markers al momento de tener dos.
/*para indexar datos traídos de la BD*/ 
var parametrosObligatorios=["% O2","DBO","pts DBO","NH4","pts NH4"];
var parametrosOpcionales=["CF","DQO","EC","PO4","GYA","Ph","SD", "Ssed", "SST","SAAM","T","Aforo","ST","pts PSO"];
//-----------------------------------------INICIALIZACION DEL MAPA----------------------------------------------------------------//
function initMap() {

	  //creación del mapa
	 map = new google.maps.Map(document.getElementById('map'), {
	    zoom: 11,
	    center: {"lat":9.876711,"lng":-84.104376},
	    radius:19
	  });


	//marcador draggable para aplicar filtro
	 filterMarker = new google.maps.Marker({
	    map: map,
	    draggable:true,
	    icon: "data/Templatic-map-icons/arts-crafts.png",
	    title:"colocar en area de filtro",
	    position:{"lat":9.928119,"lng":-84.107810}
	  });

	 //inserción de todos los marcadores presentes en la BD
	 insertMarker();
	//map.addListener('click', function(e) {
	  //placeMarkerAndPanTo(e.latLng, map);
	//});
}

//------------------------------------------MOSTRAR LOS MARCADORES EN EL MAPA---------------------------------------------------------------//

function  insertMarker(){
//peticion ajax al servidor
  $.ajax({
      async:true,
      url: "webservices/getMarkers_busqueda.php",//devuelve un json con los marcadores que están en la base de datos.
      dataType: "json",
      success:pintar
      });
}


function pintar(jsonData){
  jsonDatosBD=jsonData;//temporal; es solo para que aparezcan los marcadores.
//se insertan en el mapa los marcadores elegidos
  for (var i = 0; i < jsonDatosBD.length; i++) {
	    markers[i] = new google.maps.Marker({
	    map: map,
	    position:jsonDatosBD[i].location,
	    title: 'Calidad del agua: '+jsonDatosBD[i].color,
	    icon:"data/Templatic-map-icons/"+jsonDatosBD[i].color+".png",
	    id:i//parametro que identifica de forma única a cada marcador, con él se puede encontrar el id real del objeto.
	  });

      //se hace una asociación indice color.
	  niveles[i]=jsonDatosBD[i].color;
      //se asocia un evento a cada marcador.
      google.maps.event.addListener(markers[i], 'click', function() {
      	aritmeticaPOIS(this);
      });
  }
}

//----------------------------------------ARITMETICA DE PUNTOS-----------------------------------------------------------------//

/*function aritmeticaPOIS(marcador) {
  //utilizado para controlar el click al momento de que ya existen marcadores seleccionados o se da click sobre el mismo
		if(marcador.id==idMarker1){//si se da click sobre uno ya seleccionado
      marcador.setIcon("data/Templatic-map-icons/"+jsonDatosBD[idMarker1].color+".png");
      contadorClicks--;
			idMarker1=-1;
		}else if(marcador.id==idMarker2){//si se da click sobre uno ya seleccionado
      marcador.setIcon("data/Templatic-map-icons/"+jsonDatosBD[idMarker2].color+".png");
      contadorClicks--;
			idMarker2=-1;
			//utilizado para controlar el click valido.
		}else if(contadorClicks==0){//es el primer click que se hace.
      contadorClicks++;
			idMarker1=marcador.id;
			marcador.setIcon("data/Templatic-map-icons/default.png");
		}else if(contadorClicks==1){
      contadorClicks++;
      (idMarker2!=-1)?idMarker1=marcador.id:idMarker2=marcador.id;//caso especifico en el que se selecciona el primer marcador luego otro, después se quita la selección del primero y se vuelve a seleccionar cayendo en este caso donde se le daba el valor de idMarker2 el cual ya estaba dado. 
			marcador.setIcon("data/Templatic-map-icons/default.png");
		}else{//caso en que ya se han insertado ambos se le debe caer encima a alguno de los dos.
			//opcional a futuro

		}
}*/

//evento para el boton de cerrar el contenedor donde se muestra la información luego de realizar un filtro de dos marcadores
$("#btnCloseArPOI").click(function(){
	$(".arPOIBig").css("display","none");
  $(".contenidoArPOIShort").text("");
});

//evento de boton filtrar, se comprueba que existan dos marcadores seleccionados.
$(".btnFiltrarArPOI").click(function(){
  console.log(filterMarker.position.lat()+","+filterMarker.position.lng());
	if(contadorClicks==2){//se permite filtrar, aquí se debe traer la información desde la BD.
        var parametros = {
        	"id1" : jsonDatosBD[first.id].id,
        	"id2" : jsonDatosBD[second.id].id
        };
        $.ajax({
                async:true,
                data:  parametros,
                dataType:"json",
                url:   'webservices/datosArPOI_busqueda.php',
                success:  calcularDiferencia
        });
	}else{//no se permite
		alert("debe seleccionar dos marcadores");
	}
});


function calcularDiferencia(datos){
  //alert(JSON.stringify(datos[0].POI.location));
  if (datos[0]&&datos[1]) {
    var elevator = new google.maps.ElevationService;
    var locations = [];
    locations.push(datos[0].POI.location);
    locations.push(datos[1].POI.location);
    var positionalRequest = {
      'locations': locations
    }
    elevator.getElevationForLocations(positionalRequest, function (results, status) {
        if (status == google.maps.ElevationStatus.OK && results[0]&&results[1]){
          var puntoBajo=0;
          var puntoAlto=0;
          if(results[0].elevation>results[1].elevation){
            puntoAlto=0;
            puntoBajo=1;
          }else{
            puntoAlto=1;
            puntoBajo=0;
          }
          var texto="";
          texto=texto+"<h1 class='tituloArPOI'>Aritmética de POI's</h1>";
          texto=texto+"<h6 class='tituloArPOI'><b>Punto más alto:</b> "+results[puntoAlto].elevation+" msnm";
          texto=texto+". <b>Ubicación:</b> "+datos[puntoAlto].POI.nombre_institucion;
          texto=texto+" <b>Estación:</b> "+datos[puntoAlto].POI.nombre_estacion+"<br>";

          texto=texto+"<br> <b>Punto más bajo:</b> "+results[puntoBajo].elevation+" msnm";
          texto=texto+". <b>Ubicación:</b> "+datos[puntoBajo].POI.nombre_institucion;
          texto=texto+" <b>Estación:</b> "+datos[puntoBajo].POI.nombre_estacion+"</h6>";
          texto=texto+"<table class='tablaArPOI'><tr><th>Elemento</th><th>Sitio 1</th> <th>Sitio 2</th><th>Resultado</th><th>%</th></tr>";
          
          //se obtienen los parametros obligatorios y opcionales para cada uno.
          var POIOne={};
          var POITwo={};
          // using jQuery extend to join documents
          $.extend(POIOne, datos[puntoBajo].Muestra.obligatorios, datos[puntoBajo].Muestra.opcionales);
          $.extend(POITwo, datos[puntoAlto].Muestra.obligatorios, datos[puntoAlto].Muestra.opcionales);
          //la lógica es iterar sobre los datos de un punto y buscar si existe ese valor en el otro para restarlo
          for (var key in POIOne){//Se itera sobre cada uno de los elementos
            if(POITwo[key]){//Si el parametro del primero también está en los obligatorios del segundo.
              var dif = POIOne[key]-POITwo[key];
              if(!isNaN(dif)){
              	dif = dif.toFixed(2);
              	var percent=((POIOne[key]/POITwo[key])*100).toFixed(0);
                texto=texto+"<tr>"+"<td>"+key+"</td>"+"<td>"+POIOne[key]+"</td>"+"<td>"+POITwo[key]+"</td>"+"<td>"+dif+"</td>"+"<td>"+percent+"</td>"+"</tr>";
              }
            }
          }
          
          texto=texto+"</table>";

          $(".contenidoArPOIShort").append(texto);
          $(".arPOIBig").css("display","block");
        }
    });
  }
}

//---------------------------------------EVENTOS DE LOS BOTONES DENTRO DE LA PÁGINA------------------------------------------------------------------//

//eventos de los botones de calidades de agua
document.getElementById("calidad1").onclick = function(){
  if(document.getElementById("calidad1").value==0){    
    for(var i=0;i<niveles.length;i++){
      if(niveles[i]=="Azul"){
      markers[i].setVisible(false);     
      }
    }
    document.getElementById("calidad1").value=1;    
  }else{
    for(var i=0;i<niveles.length;i++){
      if(niveles[i]=="Azul"){
       markers[i].setVisible(true);     
      }
    } 
    document.getElementById("calidad1").value=0;
  }
}


document.getElementById("calidad2").onclick = function(){
  if(document.getElementById("calidad2").value==0){    
  	for(var i=0;i<niveles.length;i++){
  		if(niveles[i]=="Verde"){
  		markers[i].setVisible(false);  		
  		}
  	}
    document.getElementById("calidad2").value=1; 	  
  }else{
    for(var i=0;i<niveles.length;i++){
      if(niveles[i]=="Verde"){
       markers[i].setVisible(true);     
      }
    } 
    document.getElementById("calidad2").value=0;
  }
}


document.getElementById("calidad3").onclick = function(){
  if(document.getElementById("calidad3").value==0){    
    for(var i=0;i<niveles.length;i++){
      if(niveles[i]=="Amarillo"){
      markers[i].setVisible(false);     
      }
    }
    document.getElementById("calidad3").value=1;    
  }else{
    for(var i=0;i<niveles.length;i++){
      if(niveles[i]=="Amarillo"){
       markers[i].setVisible(true);     
      }
    } 
    document.getElementById("calidad3").value=0;
  }
}


document.getElementById("calidad4").onclick = function(){
  if(document.getElementById("calidad4").value==0){    
    for(var i=0;i<niveles.length;i++){
      if(niveles[i]=="Anaranjado"){
      markers[i].setVisible(false);     
      }
    }
    document.getElementById("calidad4").value=1;    
  }else{
    for(var i=0;i<niveles.length;i++){
      if(niveles[i]=="Anaranjado"){
       markers[i].setVisible(true);     
      }
    } 
    document.getElementById("calidad4").value=0;
  }
}


document.getElementById("calidad5").onclick = function(){
  if(document.getElementById("calidad5").value==0){    
    for(var i=0;i<niveles.length;i++){
      if(niveles[i]=="Rojo"){
      markers[i].setVisible(false);     
      }
    }
    document.getElementById("calidad5").value=1;    
  }else{
    for(var i=0;i<niveles.length;i++){
      if(niveles[i]=="Rojo"){
       markers[i].setVisible(true);     
      }
    } 
    document.getElementById("calidad5").value=0;
  }
}


document.getElementById("reset").onclick = function(){
  for(var i=0;i<markers.length;i++){
    markers[i].setVisible(true);
  } 
}

//-----------------------------------------FILTRO POR RADIO-MARCADOR MOVIBLE ASOCIADO----------------------------------------------------------------//
//filtrado por radio de influencia. Se busca dentro del mapa por un radio brindado por el usuario. 
function aplicarFiltro(valor,flag){
  if (flag&&valor>=1) {//caso filtro de radio de influencia
    var dist=0;
    for(var i =0;i<markers.length;i++){
      dist = distance(markers[i].position.lat(), markers[i].position.lng(), filterMarker.position.lat(),  filterMarker.position.lng(), 'K');
      if (dist>valor) {
        markers[i].setVisible(false);
      }
    }
  }else{//caso filtro de rios
  }
}


//calcula la distancia entre dos puntos, retorna en está situación un valor en KM
function distance(lat1, lon1, lat2, lon2, unit) {
    var radlat1 = Math.PI * lat1/180;
    var radlat2 = Math.PI * lat2/180;
    var radlon1 = Math.PI * lon1/180;
    var radlon2 = Math.PI * lon2/180;
    var theta = lon1-lon2;
    var radtheta = Math.PI * theta/180;
    var dist = Math.sin(radlat1) * Math.sin(radlat2) + Math.cos(radlat1) * Math.cos(radlat2) * Math.cos(radtheta);
    dist = Math.acos(dist);
    dist = dist * 180/Math.PI;
    dist = dist * 60 * 1.1515;
    if (unit=="K") { dist = dist * 1.609344; }
    if (unit=="N") { dist = dist * 0.8684; }
    return dist;
}


//---------------------------------------------AUTOCOMPLETADO DEL DIV DONDE SE REALIZAN FILTROS------------------------------------------------------------//

//Completado de campos de filtro traídos desde la BD.

$.ajax({
        async:true,
        dataType:"json",
        url:   'php/completadoFiltro_busqueda.php',
        success:  completar
});


function completar(datos){
  //alert(datos.length);
    $("#institucion").append("<option value='hh'>seleccione</option>");  
  for(var i =0;i<datos.length;i++){
    $("#institucion").append("<option value='hh'>"+datos[i]+"</option>");  
  }
  var styles = {
    width:"80%"
  };
  $("#institucion").css( styles );
}


function aritmeticaPOIS(marcador) {
    if(contadorClicks<2){//Se puede seleccionar otro
        var iconColor = "data/Templatic-map-icons/default.png";
        if(contadorClicks==0){//es el primer marcador en ser seleccionado.
            first=marcador;
            marcador.setIcon(iconColor);
            contadorClicks++;
        }else{//==1
            if(!(marcador.id==first.id)){//se debe dar clic sobre uno distinto.
                second=marcador;
                marcador.setIcon(iconColor);
                contadorClicks++;
            }else{//se retorna seleccionar otro ya que se dio clic sonbre el mismo, además no se aumenta el contador
                //btnWindows.setText(String.valueOf(getString(R.string.seleccionar_otro)));
            }
        }
        //return view;
    }else{//ya se han seleccionado los dos, se resetean y se llama recursivo para seleccionar el actual. 
        contadorClicks=0;
        //se agregó el cambio de marcador para el caso de gris que es el único que es un recurso externo
        var icon1 = "data/Templatic-map-icons/"+jsonDatosBD[first.id].color+".png";
        var icon2 = "data/Templatic-map-icons/"+jsonDatosBD[second.id].color+".png";

        first.setIcon(icon1);
        second.setIcon(icon2);
        aritmeticaPOIS(marcador);
    }
}