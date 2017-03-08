	<!-- Menú principal -->
  <nav class="navbar navbar-dark navbar-fixed-top bg-inverse margenNav" id="mainNav">
			<button class="navbar-toggler hidden-lg-up" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation"></button>
      <div class="collapse navbar-toggleable-md" id="navbarResponsive">
      <a class="navbar-brand" href="index.php"><h3>Monitoreo</h3></a>
      <div>
      <ul class="nav navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="Insercion.php">Inserción</a>
        </li>
        <li class="nav-item active">
          <a class="nav-link" href="busqueda.php">Búsqueda <span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Actualización</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Borrado</a>
        </li>
      </ul>
      </div>
      <form class="form-inline float-lg-right">
          <input class="form-control btn btn-outline-primary" type="button" value="Entrar" onclick="location='login.php'"/>
      </form>
    </div>
		</nav>