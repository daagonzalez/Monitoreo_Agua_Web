<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Recuperar contraseña</title>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <link rel="stylesheet" href="css/estilo_recuperar.css">
</head>

<body>
    <div class="container">
        <div class="row">
            <div class='col-md-3'></div>
            <div class="col-md-6">
                <div class="login-box well">
                    <form id="frmRestablecer" action="php/validaremail_recuperar.php" method="POST" name="login">
                        <legend>Recuperar contraseña</legend>
                        <div class="form-group">
                            <label for="username-email">E-mail</label>
                            <input value='' name="correo" id="email" placeholder="E-mail" type="email" class="form-control" required/>
                        </div>
                        <div class="form-group">
                            <input class="btn btn-default btn-block m-t-md" type="submit" value="Recuperar">
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
                    </form>

                </div>
            </div>
            <div class='col-md-3'></div>
        </div>
    </div>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

    <script>
        $(document).ready(function() {
            $("#frmRestablecer").submit(function(event) {
                event.preventDefault();
                $.ajax({
                    url: 'php/validaremail_recuperar.php',
                    type: 'post',
                    dataType: 'json',
                    data: $("#frmRestablecer").serializeArray()
                }).done(function(respuesta) {
                    $("#mensaje").html(respuesta.mensaje);
                    $("#email").val('');
                });
            });
        });
    </script>

</body>

</html>