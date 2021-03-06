/*Código para crear un mapa de google y sobre él indicar marcadores que indican puntos de muestreo sobre calidad de agua, además en este archivo
se crean los eventos y consultas a la BD mongoDB con ayuda de PHP.*/
//--------------------------------------------VARIABLES GLOBALES-------------------------------------------------------------//
var jsonDatosBD='';//variable global con la finalidad de guardar los datos de las consultas y así poder anidar consultas. Guarda los valores en formato JSON.
var map; //mapa general 
var markers=[];//marcadores indicadores de calidad del agua
var niveles=[];//es paralelo a vector de marcadores acá se guardan las calidades del agua del marcador i, se utiliza para buscar sobre él y no sobre los marcadores
var filterMarker;//marcador movible para indicar areas de filtro
//var colors = ["null","blue","green","yellow","orange","red"];//colores asociados a cada calidad
//var calidad = ["null","excelente","buena calidad","aceptable","contaminada","fuertemente contaminada"];//nombres asociados a cada calidad
//Esta varible es muy importante ya que indica y controla los eventos de clic sobre los marcadores. Para saber en que estado está la selección.
var contadorClicks = 0; //evento de aritmetica de POIS, lleva un conteo de los clicks porque solo deben haber dos seleccionados. 
var first;//Respaldo para mantener el marcador en ser presionado primero.
var second;//Respaldo para mantener el marcador en ser presionado segundo.
/*para indexar datos traídos de la BD*/ 
//var parametrosObligatorios=["% O2","DBO","pts DBO","NH4","pts NH4"];//no utilizado
//var parametrosOpcionales=["CF","DQO","EC","PO4","GYA","Ph","SD", "Ssed", "SST","SAAM","T","Aforo","ST","pts PSO"];//no utilizado
var contentVerMas="<div><button class='btn btn-primary' style='width:200px' onclick='mostrarVerMas()'>Ver muestra</button></div>";
var contentCalcularDiferencia= "<div><button class='btn btn-primary' style='width:200px'onclick='mostrarVerMas()'>Ver muestra</button><br><button class='btn btn-success' style='width:200px' onclick='mostrarAritmetica()'>Calcular diferencia</button></div>";
//variable que se inicializa al cargar el init map, indican la ventana de información a ser cargada.
var infowindowVerMas;
var infowindowCalcularDiferencia;


//-----------------------------------------INICIALIZACION DEL MAPA----------------------------------------------------------------//
function initMap() {

	  //creación del mapa
	 map = new google.maps.Map(document.getElementById('map'), {
	    zoom: 11,
	    center: {"lat":9.876711,"lng":-84.104376},
	    radius:19,
      gestureHandling: 'cooperative'
	  });


	//marcador draggable para aplicar filtro
	 filterMarker = new google.maps.Marker({
	    map: map,
	    draggable:true,
	    icon: "data/Templatic-map-icons/arts-crafts.png",
	    title:"colocar en area de filtro",
	    position:{"lat":9.928119,"lng":-84.107810}
	  });
	  //se inicializan las ventanas de información
  infowindowVerMas = new google.maps.InfoWindow();
  infowindowCalcularDiferencia = new google.maps.InfoWindow();
	 //inserción de todos los marcadores presentes en la BD
	 insertMarker();

	  //evento para limpiar el mapa.
	  map.addListener('click', function(e) {
      //mayor a cero indica que hay algun marcador seleccionado.
      if(contadorClicks>0){
        if(contadorClicks==1){//solamente existe uno en seleccion
          //se agregó el cambio de marcador para el caso de gris que es el único que es un recurso externo
          var icon1 = "data/Templatic-map-icons/"+jsonDatosBD[first.id].color+".png";
          infowindowVerMas.close();
          first.setIcon(icon1);
        }else{//ambos están selecionados
          //se agregó el cambio de marcador para el caso de gris que es el único que es un recurso externo
          var icon1 = "data/Templatic-map-icons/"+jsonDatosBD[first.id].color+".png";
          var icon2 = "data/Templatic-map-icons/"+jsonDatosBD[second.id].color+".png";
          first.setIcon(icon1);
          second.setIcon(icon2);
          infowindowCalcularDiferencia.close();
        }
        contadorClicks=0;
      }   
  });
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
function aritmeticaPOIS(marcador) {
    if(contadorClicks<2){//Se puede seleccionar otro
        var iconColor = "data/Templatic-map-icons/default.png";
        if(contadorClicks==0){//es el primer marcador en ser seleccionado.
            first=marcador;
            //se cambia el color del marcador
            marcador.setIcon(iconColor);
            //se abre la ventana de informacion para ver más
            infowindowVerMas.setContent(contentVerMas);                              
            infowindowVerMas.open(map, marcador); 
            contadorClicks++;
        }else{//==1
            if(!(marcador.id==first.id)){//se debe dar clic sobre uno distinto.
                second=marcador;
                marcador.setIcon(iconColor);
                //se cierra el marcador de ver más
                infowindowVerMas.close();
                infowindowCalcularDiferencia.setContent(contentCalcularDiferencia);                              
                infowindowCalcularDiferencia.open(map, marcador);                 
                contadorClicks++;
            }else{//se retorna seleccionar otro ya que se dio clic sonbre el mismo, además no se aumenta el contador
                //btnWindows.setText(String.valueOf(getString(R.string.seleccionar_otro)));
            }
        }
        //return view;
    }else{//ya se han seleccionado los dos, se resetean y se llama recursivo para seleccionar el actual. 
        //En este punto está abierta la ventana de información de calcular diferencia; se debe cerrar.
        infowindowCalcularDiferencia.close();
        contadorClicks=0;
        //se agregó el cambio de marcador para el caso de gris que es el único que es un recurso externo
        var icon1 = "data/Templatic-map-icons/"+jsonDatosBD[first.id].color+".png";
        var icon2 = "data/Templatic-map-icons/"+jsonDatosBD[second.id].color+".png";

        first.setIcon(icon1);
        second.setIcon(icon2);
        aritmeticaPOIS(marcador);
    }
}

//evento para el boton de cerrar el contenedor donde se muestra la información luego de realizar un filtro de dos marcadores
$("#btnCloseArPOI").click(function(){
	$(".arPOIBig").css("display","none");
  $(".contenidoArPOIShort").text("");
});



//=================Función utilizada para mostrar la ventana con la información correspondiente al calculo de la diferencia entre los dos puntos seleccionados======
function mostrarAritmetica() {
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
}



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
          texto=texto+"<table class='tablaArPOI'><tr><th>Elemento</th><th>Diferencia</th><th>Diferencia porcentual</th></tr>";
          
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
              	var percent=dif/POITwo[key];//=((POIOne[key]/POITwo[key])*100).toFixed(0);
              	percent=percent.toFixed(2);
                texto=texto+"<tr>"+"<td>"+key+"</td>"+"<td>"+dif+"</td>"+"<td>"+percent+"</td>"+"</tr>";
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

//=================Función utilizada para mostrar los datos asociados a un marcador======
function mostrarVerMas() {
  var identificador = contadorClicks==2?jsonDatosBD[second.id].id:jsonDatosBD[first.id].id;
  var parametros = {
  	"id1" : identificador
  };
  $.ajax({
          async:true,
          data:  parametros,
          dataType:"json",
          url: "webservices/datosMarker_busqueda.php",
          success:  calcularVerMas
  });
}



function calcularVerMas(datos){
  
  var muestra = datos[0].Muestra;
  var POI = datos[0].POI;
  var texto="<h2>Datos asociados a la muestra seleccionada</h2><br><br>";
  texto=texto+"<table class='tablaArPOI'>";
  texto = texto+"<tr><th colspan='2'>Datos generales</th></tr>"
    for (var key in muestra){//Se itera sobre cada uno de los elementos
      if(key!="obligatorios"&&key!="opcionales"){
        if(key=='fotos'){
          for(var fkey in muestra[key]){
            //texto=texto+"<tr><td>"+'foto'+"</td><td>"+muestra[key][fkey]+"</td></tr>";
          }
        }else if(key=='palabras_claves'){
          for(var pckey in muestra[key]){
            //texto=texto+"<tr><td>"+'foto'+"</td><td>"+muestra[key][pckey]+"</td></tr>";
          }
        }else{
          texto=texto+"<tr><td>"+key+"</td><td>"+muestra[key]+"</td></tr>"; 
        }
          
      }/*else if(key=='obligatorios'){
        texto = texto+"<tr><th colspan='2'>Datos obligatorios</th></tr>"
        var obligatorios = muestra['obligatorios'];
        for(var key in obligatorios){
          texto=texto+"<tr><td>"+key+"</td><td>"+obligatorios[key]+"</td></tr>";
        }
      }else{//opcionales
        texto = texto+"<tr><th colspan='2'>Datos opcionales</th></tr>"
        var opcionales = muestra['obligatorios'];
        for(var key in obligatorios){
          texto=texto+"<tr><td>"+key+"</td><td>"+opcionales[key]+"</td></tr>";
        }        
      }*/
    }  
  texto=texto+"</table>";
  
  $(".contenidoArPOIShort").append(texto);
  $(".arPOIBig").css("display","block");
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


