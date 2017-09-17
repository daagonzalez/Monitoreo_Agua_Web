/**
* Crea una tag HTML para la opción del comboBox de puntos según el nombre ingresado
* @param Campo del JSON de puntos con un punto específico
* @return la tag que representa la opción del punto ingresado
**/
function llenarComboPunto(Punto) {
	return "<option value=\"" + Punto.nombre + "\">" + Punto.nombre + "</option>"
}

/**
* Parsea un JSON para crear un HTML que representa un comboBox con los puntos
* @param puntos El JSON con los puntos
* @param html Hilera con el HTML que contiene el encabezado del comboBox
* @return El HTML con el comboBox
**/
function parsearPuntos(puntos,html) {
	var nombres = [];
	for (var i = 0; i < puntos.length; i++) {
		if (!nombres.includes(puntos[i].nombre)) {
			nombres.push(puntos[i].nombre);
			html += llenarComboPunto(puntos[i]);
		}
	}
	return html;
}

/**
* Crea una tag HTML para la opción del comboBox de parámetros según el parámetro ingresado
* @param Hilera con el nombre de un parámetro
* @return la tag que representa la opción del parámetro ingresado
**/
function llenarComboParametro(Parametro) {
	return "<option value=\"" + Parametro + "\">" + Parametro + "</option>"
}

/**
* Parsea un JSON para crear un HTML que representa un comboBox con los parámetros
* @param parametros El JSON con los parámetros
* @param html Hilera con el HTML que contiene el encabezado del comboBox
* @return El HTML con el comboBox
**/
function parsearParametros(parametros,html) {
	var lParametros = [];
	for (var i = 0; i < parametros.length; i++) {
		for (var key in parametros[i]) {
			if (typeof parametros[i][key] == 'number') {
				if (!lParametros.includes(key)) {
					lParametros.push(key);
					html += llenarComboParametro(key);
				}
			}
		}
	}
	return html;
}

/**
* Crea el código HTML para un comboBox con los puntos de muestreo y lo coloca en la sección correspondiente
**/
function llenarPuntos() {
	/** Obtener los datos de la base de MongoDB **/
	var json = {};
	
	$.ajax({
      async:true,
      url: "Mongui/getPuntos.php",//devuelve un json con los marcadores que están en la base de datos.
      dataType: "json",
      success:pintar,
      error: function () {
        alert("Hola");
      },
      });
	
	/*$.ajax({
		url: "Mongui/getPuntos.php",
		async: true,
		dataType: 'json',
		success: pintar,
		error: function () {
        alert("Hola");
      },
	});*/
	
}

function pintar(jsonData){
	/** Generar el HTML **/
	var html = "Punto de muestreo: <select id=\"punto\" onchange=\"if(graficoGenerado && graficoNombre) graficar();\">";
	html = parsearPuntos(jsonData,html);
	html += "</select>";
	/** Colocar el HTML en la sección correspondiente **/
	document.getElementById('comboPuntos').innerHTML = html;
}

/**
* Crea el código HTML para dos comboBox con los parámetros y lo coloca en las secciones correspondientes
**/
function llenarParametros() {
	/** Obtener los datos de la base de MongoDB **/
	var json = {};
	$.ajax({
		url: "Mongui/getPuntos.php",
		async: false,
		dataType: 'json',
		success: function(data) {
			json = data;
		}
	});
	/** Generar los HTML **/
	var html = "Parámetro a analizar: <select id=\"parametro\" onchange=\"if(graficoGenerado) graficar();\">";
	var html2 = "Segundo Parámetro a analizar: <select id=\"parametro2\" onchange=\"if(graficoGenerado) graficar();\">";
	html = parsearParametros(json,html);
	html2 = parsearParametros(json,html2);
	html += "</select>";
	html2 += "</select>";
	/** Colocar los HTML en las secciones correspondientes **/
	document.getElementById('comboParametros').innerHTML = html;
	document.getElementById('comboParametros2').innerHTML = html2;
	document.getElementById('comboParametros2').style.display = 'none';
}
