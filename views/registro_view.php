<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Registro</title>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="x-ua-compatible" content="ie=edge">


    <!-- <script src="https://code.jquery.com/jquery-3.1.1.js" integrity="sha256-16cdPddA6VdVInumRGo6IbivbERE8p7CQR3HzTBuELA=" crossorigin="anonymous"></script>
    <script type="text/javascript" src="js/validacion.js"></script> -->
    <link rel="stylesheet" href="css/estilo_registro.css">
</head>

<body>
    <div class="container">
        <div class="row">
            <div class='col-md-3'></div>
            <div class="col-md-6">
                <div class="login-box well">
                    <form action="php/validaremail_registro.php" method="POST" name="registrar" id="registro">
                        <legend>Registro</legend>
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input value='' name="nombre" id="nombre" placeholder="Nombre" type="text" class="form-control" />
                        </div>
                        <div class="form-group">
                            <label for="email">E-mail</label>
                            <input value='' name="correo" id="email" placeholder="E-mail" type="email" class="form-control" />
                        </div>
                        <div class="form-group">
                            <label for="password">Contraseña</label>
                            <input id="password" name="contrasenna" value='' placeholder="Contraseña" type="password" class="form-control" required pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}" title="La contraseña debe de contener al menos 6 caracteres, incluyendo letras minúsculas, mayúsculas y números." onchange="this.setCustomValidity(this.validity.patternMismatch ? this.title : '');
  if(this.checkValidity()) form.verificacion.pattern = this.value;" />
                        </div>
                        <div class="form-group">
                            <label for="password2">Repite la contraseña</label>
                            <input title="Escriba la misma contraseña" id="password2" name="verificacion" value='' placeholder="Contraseña" type="password" class="form-control" required pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}" onchange="this.setCustomValidity(this.validity.patternMismatch ? this.title : '');" />
                        </div>
                        <div class="form-group">
                            <!-- <i id="boton" class="btn btn-default btn-login-submit btn-block m-t-md" onclick="
                            login.submit()  
                            "><b>Registrarse</b></i> -->
                            <input class="btn btn-default btn-block m-t-md" type="submit" value="Registrarse">
                        </div>

                        <?php if(!empty($errores)): ?>
                            <div class="alert alert-danger" role="alert">
                                <ul id="errores">
                                    <?php echo $errores; ?>
                                </ul>
                            </div>
                            <?php endif; ?>
                                <?php if(!empty($mensaje)): ?>
                                    <div class="alert alert-success" role="alert">
                                        <ul id="mensaje">
                                            <?php echo $mensaje; ?>
                                        </ul>
                                    </div>
                                    <?php endif; ?>

                                        <div class="form-group">
                                            <p class="text-center m-t-xs text-sm">¿Ya tienes cuenta?</p>
                                            <a href="login.php" class="btn btn-default btn-block m-t-md">Iniciar Sesión</a>
                                        </div>

                    </form>

                </div>
            </div>
            <div class='col-md-3'></div>
        </div>
    </div>



    <!-- Latest compiled and minified CSS -->
    <script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.1.1.min.js"></script>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

    <script>
        $(document).ready(function() {
            $("#registro").submit(function(event) {
                event.preventDefault();
                $.ajax({
                    url: 'php/validaremail_registro.php',
                    type: 'post',
                    dataType: 'json',
                    data: $("#registro").serializeArray()
                }).done(function(respuesta) {
                    $("#mensaje").html(respuesta.mensaje);
                    $("#email").val('');
                });
            });
        });

    </script>

</body>

</html>
