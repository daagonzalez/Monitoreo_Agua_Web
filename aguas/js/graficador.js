var datosGrafico = "";
var indFechaAct = 0;
var tamannofechas = 0;
var elGrafico;
var datosBurbuja = [];
var fechas = [];
var fechasN = [];
var indices = [];
var indices2 = [];
var nombres = [];
var colores = [];
var coloresUsados = [];
var datasets = [];
var graficoGenerado = false;
var graficoNombre = false;
var graficoNuevo = true;
var myTimer;

/**
*
**/
function generarColor(){
		
	if(colores.length == 0)
	{
		var color1 = (Math.floor(Math.random() * 256));
		var color2 = (Math.floor(Math.random() * 256));
		var color3 = (Math.floor(Math.random() * 256));
		var colorPunto = "rgba(" + color1 + "," + color2 + "," + color3 + ","+ 0.4 +")";
		var colorUsado = color1 + "," + color2 + "," + color3;
		coloresUsados.push(colorUsado);
		colores.push(colorPunto);
	}
	else {
		var color1 = (Math.floor(Math.random() * 256));
		var color2 = (Math.floor(Math.random() * 256));
		var color3 = (Math.floor(Math.random() * 256));
		while(colorRepetido(color1,color2,color3)){
			var color1 = (Math.floor(Math.random() * 256));
			var color2 = (Math.floor(Math.random() * 256));
			var color3 = (Math.floor(Math.random() * 256));
		}
		var colorPunto = "rgba(" + color1 + "," + color2 + "," + color3 + ","+ 0.4 +")";
		var colorUsados = color1 + "," + color2 + "," + color3;
		coloresUsados.push(coloresUsados);
		colores.push(colorPunto);		
	}	
		
}
	
/**
*
**/	
function colorRepetido(color1,color2,color3){
	var repetido = false;
	var tamano = 0;
	while(tamano < colores.length && !repetido){
		var color = colores[tamano].split(",");
		if(color[0] == color1 && color[1] == color2 && color[2]==color3){
			repetido = true;
		}
		else {
			tamano++;
		}
	}
	return repetido; 
}

/**
* Crea el dataset para el graficador cuando se pide un gráfico de burbuja
* @return Un arreglo con los datos a graficar
**/
function datosBurbujaF() {	
	colores = [];
	coloresUsados = [];
	if (!graficoNombre) {
		for (var i = 0 ; i < fechasN.length ; i++) {
			datasets = [];
			for (var c = 0; c < nombres.length; c++)
				generarColor();
			for (var j = 0 ; j < nombres.length ; j++) {
				var datos = [];
				for (var t = 0 ; t < fechasN.length ; t++) {
					if (t == i) {
						datos.push({
							"x": fechasN[i],
							"y": indices[j][i],
							"r": indices2[j][i]
						});
					}
					else {
						datos.push({
							"x": fechasN[t],
							"y": null,
							"r": null
						})
					}
				}
				var array = {
					"label": nombres[j], 
					"data" : datos,
				"backgroundColor": colores[j]
				};
				datasets.push(array);
			}
			datosBurbuja[i] = {datasets: datasets};
		}
		return datosBurbuja[indFechaAct];		
	} else {
		generarColor();
		datasets = [];
		for (var i = 0 ; i < fechasN.length ; i++) {
			var array = {
				"x": fechasN[i],
				"y": indices[0][i],
				"r": indices2[0][i]
			};
			datasets.push(array);
		}
		return {datasets: [{
				label: nombres[0],
				data: datasets,
				backgroundColor: colores[0]
				}]
		};
	}
}

/**
* Crea el dataset para el graficador cuando se pide un gráfico por área
* @return Un arreglo con los datos a graficar
**/
function datosAreaF() {
	colores = [];
	coloresUsados = [];
	datasets = [];
	for (var i = 0; i < nombres.length; i++)
		generarColor();
	
	var datasets = [];
	for (var i = 0; i < nombres.length; i++) {
		var array = {"label": nombres[i], "backgroundColor": colores[i], "data":indices[i]};
		datasets.push(array);
	}
	return {labels: fechas,
			datasets: datasets};
}

/**
* Crea el dataset para el graficador cuando se pide un gráfico XY
* @return Un arreglo con los datos a graficar
**/
function datosXYF() {
	colores = [];
	coloresUsados = [];
	datasets = [];
	for (var i = 0; i < nombres.length; i++)
		generarColor();
	
	var datasets = [];
	for (var i = 0; i < nombres.length; i++) {
		var array = {"label": nombres[i], "borderColor": colores[i], "fill": false, "data":indices[i]};
		datasets.push(array);
	}
	return {labels: fechas,
			datasets: datasets};
}

/**
* Cambia los datos mostrados en el gráfico de burbuja, según la fecha elegida
* @param objeto de la fecha a la que se le hizo clic
**/
function cambiarScrollFecha(objeto) {
	var idObjeto = objeto.id;
	/**Obtener índice de la fecha elegida**/
	var encontrado = false;
	var i = 0;
	while (!encontrado) {
		if (idObjeto == "fecha" + i) {
			encontrado = true;
		} else {
			i++;
		}
	}
	if (indFechaAct != i) {
		//Si no está marcado ya al que se le dio clic
		document.getElementById("fecha"+indFechaAct).className = 'liFechas'; //Desmarcar el que estaba seleccionado
		document.getElementById(idObjeto).className = 'liFechasSelected'; //Marcar el nuevo seleccionado
		indFechaAct = i;

		/**Cambiar el dataset del gráfico**/
		elGrafico.config.data = datosBurbuja[indFechaAct];
		elGrafico.update();
	}
}

/**
* Crea una lista con todas las fechas de la consulta para el gráfico de burbuja
**/
function llenarScrollFechas() {
	/** Crear el HTML con las fechas **/
	var elHtml = "<ul class=\"ulFechas\">\n";
	for (var i = 0 ; i < fechasN.length ; i++) {
		var laFecha = new Date(Number(fechasN[i]));
		laFecha.setHours(laFecha.getHours()+24);
		elHtml += "<li class=\"liFechas\" id=\"fecha" + i + "\" onclick=\"cambiarScrollFecha(this)\">" + laFecha.toDateString() + "</li>\n";
	}
	elHtml += "</ul>";
	/** Aplicar el HTML y estilos al contenedor **/
	document.getElementById('scrollFechas').style.display = 'block'; //Se hace visible
	document.getElementById('scrollFechas').innerHTML = elHtml;
	document.getElementById('fecha'+indFechaAct).className = 'liFechasSelected';
}

function avanzarBurbuja() {
	if (indFechaAct <=fechasN.length) {
		indFechaAct++;
		elGrafico.config.data = datosBurbuja[indFechaAct];
		elGrafico.update();
	} else {
		window.clearInterval(myTimer);
	}
}

/**
* Crea un gráfico según el tipo de consulta realizada y sus respectivos parámetros
**/
function graficar(tConsulta) {
	document.getElementById('scrollFechas').style.display = 'none'; //Ocultar scroll de fechas
	if (graficoNuevo) {
		document.getElementById("btnGuardarGraf").disabled = false;		
	}
	//Reinicializar canvas del gráfico
	$('#myChart').remove();
	$('#canvasGrafico').append('<canvas id="myChart" width="100%" height="15"></canvas>');
	
	/** Inicializar variables **/
	datosBurbuja = [];
	fechas = [];
	fechasN = [];
	indices = [];
	indices2 = [];
	nombres = [];
	var tipoGrafico = '';
	var ctx = document.getElementById('myChart').getContext('2d');
	ctx.canvas.width = 1200;
	ctx.canvas.height = 400;
	var arrayOfObjects = [];
	if (tConsulta == "Fechas") graficoNombre = false;
	else if (tConsulta == "Nombres") graficoNombre = true;

	/** Obtener variables de la página **/
	var fechaIni = new Date(document.getElementById('fechaI').value).getTime();
	var fechaFin = new Date(document.getElementById('fechaF').value).getTime();
	var parametro = document.getElementById("parametro").value;
	var parametro2 = document.getElementById("parametro2").value;
	if (document.getElementById("btnArea").checked || document.getElementById("btnXY").checked) {
		tipoGrafico = 'line';
	} else {
		tipoGrafico = 'bubble';
	}

	/**
	* Realizar consulta con Mongo para obtener los datos
	* Se guarda en arrayOfObjects
	**/
	if (graficoNombre) {
		//Si se quiere graficar por punto de muestreo
		var lugar = document.getElementById("punto").value;
		$.ajax({
			url: "Mongui/buscarPorNombre.php?nombre=" + lugar + "&par1=" + parametro +"&par2=" + parametro2,
			async: false,
			dataType: 'json',
			success: function(data) {
				arrayOfObjects = data;
			}
		});
	} else {
		//Si se quiere graficar por rango de fechas
		$.ajax({
			url: "Mongui/buscarPorFechas.php?fechaIni=" + fechaIni + "&fechaFin=" + fechaFin + "&par1=" + parametro +"&par2=" + parametro2,
			async: false,
			dataType: 'json',
			success: function(data) {
				arrayOfObjects = data;
			}
		});
	}
	// console.log(arrayOfObjects);

	/**
	* Llena los arreglos con los valores según los parámetros elegidos
	* @param El nombre del punto cuyo parámetro se quiere obtener
	**/
	function obtenerParametro(nombre) {
		var aux = [];
		var aux2 = [];
		for (var i = 0; i < arrayOfObjects.length; i++) {
			var object = arrayOfObjects[i];
			if (object.POI.nombre_estacion == nombre) {
				if(object.Muestra.obligatorios.hasOwnProperty(parametro)){
					aux[fechasN.indexOf(Number(object.Muestra.fecha.$date.$numberLong))] = object.Muestra.obligatorios[parametro];
					aux2[fechasN.indexOf(Number(object.Muestra.fecha.$date.$numberLong))] = object.Muestra.obligatorios[parametro2];
				}else if(object.Muestra.opcionales.hasOwnProperty(parametro)){
					aux[fechasN.indexOf(Number(object.Muestra.fecha.$date.$numberLong))] = object.Muestra.opcionales[parametro];
					aux2[fechasN.indexOf(Number(object.Muestra.fecha.$date.$numberLong))] = object.Muestra.opcionales[parametro2];
				}else{
					aux[fechasN.indexOf(Number(object.Muestra.fecha.$date.$numberLong))] = "Sin Dato";
					aux2[fechasN.indexOf(Number(object.Muestra.fecha.$date.$numberLong))] = "Sin Dato";
				}
			}
		}
		indices.push(aux);
		indices2.push(aux2);
	}

	/**
	* Guardar en el arreglo de nombres todos los puntos de muestreo no repetidos,
	* en el arreglo de fechas las fechas no repetidas y
	* en el arreglo de fechasN las fechas en formato UNIX
	**/
	for (var i = 0; i < arrayOfObjects.length; i++) {
		var object = arrayOfObjects[i];
		if (!nombres.includes(object.POI.nombre_estacion)) {
			nombres.push(object.POI.nombre_estacion);
		}
		if (!fechasN.includes(Number(object.Muestra.fecha.$date.$numberLong))) {
			fechasN.push(Number(object.Muestra.fecha.$date.$numberLong));
		}
		var fechaObj = new Date(Number(object.Muestra.fecha.$date.$numberLong));
		fechaObj.setHours(fechaObj.getHours()+24);
		var fechai = fechaObj.toDateString();
		if (!fechas.includes(fechai)) {
			fechas.push(fechai);
		}
	}
	

	/** Llenar los arreglos de parámetros para cada punto de muestreo **/
	for (var i = 0; i < nombres.length; i++) {
		obtenerParametro(nombres[i]);
	}

	/** Formar el dataset para el gráfico a partir de los datos obtenidos **/
	var data = {};
	if (document.getElementById("btnBurbuja").checked) {
		data = datosBurbujaF();
	} else if (document.getElementById("btnXY").checked) {
		data = datosXYF();
	} else {
		data = datosAreaF();
	}

	/** Establecer las opciones del gráfico **/
	if (tipoGrafico == 'bubble' && graficoNombre) {
		//Si el gráfico es de burbuja y por punto de muestreo, mostrar las fechas en el eje X
		var opciones = {
			scales: {
			yAxes: [{
				ticks: {
					beginAtZero: true
				},
				scaleLabel: {
					display: true,
					labelString: parametro
				}
			}],
			xAxes: [{
				ticks: {
					userCallback: function(valor) {
						var fechaObj = new Date(Number(valor));
						fechaObj.setHours(fechaObj.getHours()+24);
						return fechaObj.toDateString();
					}
				}
			}]
		}};
		myTimer = setInterval(avanzarBurbuja, 1000);
	} else if (tipoGrafico != 'bubble' || graficoNombre) {
		//Si el gráfico no es de burbuja o si es por nombre mostrar normalmente
		var opciones = {
			scales: {
			yAxes: [{
				ticks: {
					beginAtZero: true
				},
				scaleLabel: {
					display: true,
					labelString: parametro
				}
			}]
		}
			
		};
		document.getElementById('scrollFechas').style.display = 'none';
	} else {
		//Si el gráfico es de burbuja no mostrar las etiquetas del eje X
		var opciones = {
			scales: {
				yAxes: [{
					ticks: {
						beginAtZero: true
					},
					scaleLabel: {
						display: true,
						labelString: parametro
					}
				}],
				xAxes: [{
					display: false
				}]
			},
			 animation: {
                    duration: 10,
                    easing: 'easeOutElastic'
                }
		};
		myTimer = setInterval(avanzarBurbuja, 1000);
	}

	/** Crear el gráfico **/
	elGrafico = new Chart(ctx, {
		type: tipoGrafico,
		data: data,
		options: opciones
	});

	/** Mostrar y Ocultar elementos según el tipo de consulta realizada **/
	//Desactivar el botón del tipo de consulta realizada
	if (graficoNombre) {
		document.getElementById('btnGraficoFecha').disabled = false;
		document.getElementById('btnGraficoNombre').disabled = true;
	} else {
		document.getElementById('btnGraficoFecha').disabled = true;
		document.getElementById('btnGraficoNombre').disabled = false;
	}
	//Mostar el textbox para el nombre y los botones para guardar
	document.getElementById('infoGrafico').style.display = 'block';
	document.getElementById('botonesGuardar').style.display = 'block';
	graficoGenerado = true;
}

/**
* Mostar u ocultar caja para segundo parámetro, según tipo de gráfico
* @param Tipo de gráfico seleccionado
**/
function cambiarTipoGrafico(tipoGrafico) {
	if (tipoGrafico == 'area' || tipoGrafico == 'xy') {
		document.getElementById('comboParametros2').style.display = 'none';
	} else {
		document.getElementById('comboParametros2').style.display = 'block';
	}
	if (graficoGenerado) 
		if (graficoNombre)
			graficar("Nombres",true);
		else
			graficar("Fechas",true);
}
