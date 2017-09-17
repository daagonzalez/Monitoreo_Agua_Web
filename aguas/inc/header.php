<?php // require "login/loginheader.php"; ?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <title>Aguas</title>
        <meta content="width=device-width, initial-scale=1, shrink-to-fit=no" name="viewport"></meta>
        <!-- Bootstrap CSS -->
        <link href="css/bootstrap.css" media="screen" rel="stylesheet"></link>
        <link href="css/main.css" media="screen" rel="stylesheet"></link>
        </meta>
    </head>
    <body>
        <nav class="navbar navbar-toggleable-md navbar-inverse bg-primary">
            <button aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler navbar-toggler-right" data-target="#navbarNav" data-toggle="collapse" type="button">
                <span class="navbar-toggler-icon">
                </span>
            </button>
            <a class="navbar-brand" href="index.php">
                <img alt="" class="d-inline-block align-top" height="30" src="img/logo-white.svg" width="30">
                    Aguas
                </img>
            </a>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">
                            Inicio
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="new.php">
                            Crear nuevo gráfico
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="saved.php">
                            Gráficos guardados
                        </a>
                        <li class="nav-item dropdown">
                            <a aria-expanded="false" aria-haspopup="true" class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" id="navbarDropdownMenuLink">
                                Usuario
                            </a>
                            <div aria-labelledby="navbarDropdownMenuLink" class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="help.php">
                                    Ayuda
                                </a>
                                <a class="dropdown-item" href="login/logout.php">
                                    Cerrar sesión
                                </a>
                            </div>
                        </li>
                    </li>
                </ul>
            </div>
        </nav>
    </body>
</html>