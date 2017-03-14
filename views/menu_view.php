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
        <div class="form-inline float-lg-right menu_form">



            <?php
            if (!isset($_SESSION['correo'])) {
                ?>

                <a class="form-control btn btn-outline-primary " href="login.php">Iniciar Sesión</a>

                <?php
            }else{
                ?>

                    <span id="id_nomb_usuario" class="nombre_usuario">Hola, <?php echo ($_SESSION['correo']) ?></span>

                    <a class="form-control btn btn-outline-primary" href="login.php">Cerrar Sesión</a>

                    <?php
            } 
        ?>

        </div>

    </div>
</nav>
