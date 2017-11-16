<?php
  $interDir='';
  $grafica =  $_SERVER['DOCUMENT_ROOT'].$interDir."aguas/";
?>

<!-- Always shows a header, even in smaller screens. -->
  <div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
    <header class="mdl-layout__header">
      <div class="mdl-layout__header-row">
        <!-- Title -->
        <span class="mdl-layout-title"><a href="../busqueda.php">Monitoreo de aguas UCR</a></span>
        <!-- Add spacer, to align navigation to the right -->
        <div class="mdl-layout-spacer"></div>
        <!-- Navigation. We hide it in small screens. -->
        <nav class="mdl-navigation mdl-layout--large-screen-only">
          <a class="mdl-navigation__link" href='../busqueda.php'>Mapa</a>
          <a class="mdl-navigation__link" href='saved.php'>Gráficos guardados</a>
          <a class="mdl-navigation__link" href='new.php'>Crear Nuevo Gráfico</a>          
          <button class="btn-logout background_primario mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--primary">
              <i class="button-left-icon material-icons">account_circle</i>Cerrar Sesión
          </button>
        </nav>
      </div>
    </header>
    <div class="mdl-layout__drawer mdl-layout--small-screen-only">
      <nav class="mdl-navigation">
        
        <a class="mdl-navigation__link" href='../busqueda.php'>Mapa</a>
          <a class="mdl-navigation__link" href='saved.php'>Gráficos guardados</a>
          <a class="mdl-navigation__link" href='new.php'>Crear Nuevo Gráfico</a>
        <span class="btn-logout mdl-navigation__link">
            <i class="button-left-icon material-icons">account_circle</i>Cerrar Sesión
        </span>
        
      </nav>
    </div>
