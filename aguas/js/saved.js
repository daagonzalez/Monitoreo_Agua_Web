function haceXTiempo(date) {
  var segundos = Math.floor((new Date() - date) / 1000);

  var intervalo = Math.floor(segundos / 31536000);
  if (intervalo > 1) {
    return "Hace " + intervalo + " años";
  }
  
  intervalo = Math.floor(segundos / 2592000);
  if (intervalo > 1) {
    return "Hace " + intervalo + " meses";
  }
  
  intervalo = Math.floor(segundos / 86400);
  if (intervalo > 1) {
    return "Hace " + intervalo + " d&iacuteas";
  }
  
  intervalo = Math.floor(segundos / 3600);
  if (intervalo > 1) {
    return "Hace " + intervalo + " horas";
  }
  
  intervalo = Math.floor(segundos / 60);
  if (intervalo > 1) {
    return "Hace " + intervalo + " minutos";
  }
  
  return "Hace " + Math.floor(segundos) + " segundos";
}

function strTipoGrafico(tipo) {
	if (tipo == "Area")
		return "de &aacuterea";
	else if (tipo == "XY")
		return "de tipo XY";
	else
		return "de burbuja";
}

function strTipoConsulta(tipo) {
	if (tipo == "Fechas")
		return "rango de fechas";
	else
		return "punto de muestreo espec&iacutefico";
}

function listarGraficos() {
	var lista = [];
	$.ajax({
        url: "baseDatos/getGraficos.php?usuario=" + email_google,
        async: false,
        dataType: 'json',
        success: function(data) {
            lista = data;
        }
    });
	var html = "";
	for (var i = 0 ; i < lista.length ; i++) {
		html += "<a class=\"list-group-item list-group-item-action flex-column align-items-start\" href=\"view.php?idGrafico=" + lista[i].fechaCreacion + "\">" +
					"<div class=\"d-flex w-100 justify-content-between\">" +
						"<h5 class=\"mb-1\">" +
							lista[i].nombreGrafico +
						"</h5>" +
						"<small>" +
							haceXTiempo(new Date(lista[i].fechaCreacion)) +
						"</small>" +
					"</div>" +
					"<p class=\"mb-1\">" +
						lista[i].descripcion +
					"</p>" +
					"<small class=\"text-muted\">" +
						"Gráfico " + strTipoGrafico(lista[i].tipoGrafico) + ", consultado por " + strTipoConsulta(lista[i].tipoConsulta) +
					"</small>" +
			"</a>";
	}
// <a class=\"list-group-item list-group-item-action flex-column align-items-start\" href=\"#\">
	// <div class=\"d-flex w-100 justify-content-between\">
		// <h5 class=\"mb-1\">
			// List group item heading
		// </h5>
		// <small class=\"text-muted\">
			// 3 days ago
		// </small>
	// </div>
	// <p class=\"mb-1\">
		// Donec id elit non mi porta gravida at eget metus. Maecenas sed diam eget risus varius blandit.
	// </p>
	// <small class=\"text-muted\">
		// Donec id elit non mi porta.
	// </small>
// </a>
// <a class=\"list-group-item list-group-item-action flex-column align-items-start\" href=\"#\">
	// <div class=\"d-flex w-100 justify-content-between\">
		// <h5 class=\"mb-1\">
			// List group item heading
		// </h5>
		// <small class=\"text-muted\">
			// 3 days ago
		// </small>
	// </div>
	// <p class=\"mb-1\">
		// Donec id elit non mi porta gravida at eget metus. Maecenas sed diam eget risus varius blandit.
	// </p>
	// <small class=\"text-muted\">
		// Donec id elit non mi porta.
	// </small>
// </a>";
document.getElementById('listaGraficos').innerHTML = html;
}