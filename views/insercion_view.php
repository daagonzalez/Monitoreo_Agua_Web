<!DOCTYPE html>
<html lang="en">
<!-- Se cargar los encabezados de la página -->
<?php require 'views/inc/header.php';?>
    <!-- El header contiene los generales mediante js se cargan los propios de la sección -->

    <body>
        <!-- Se carga el cover para control de login mediante firebase -->
        <?php require 'views/inc/login_cover.php';?>
            <!-- Carga del menú del sitio web -->
            <?php require 'views/inc/menu.php'; ?>
                <div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
                    <main class="mdl-layout__content">
                        <div class="page-content">
                            <div class="mdl-grid">
                                <?php if ($action === 'ensennarForm'): ?>
                                    <?php if($mensaje === 'exitosa'):?>
                                        <div class="alert alert-success" role="alert">
                                            <strong>Archivo agregado con exito!</strong>
                                        </div>
                                        <?php elseif($mensaje === 'noExitosa'): ?>
                                            <div class="alert alert-danger" role="alert">
                                                <strong>No se logro insertar!</strong> revise los datos enviados.
                                            </div>
                                            <?php endif; ?>
                                                <div class="container principal">
                                                    <div class="infoB">
                                                        <h5 class="titulo">Información básica</h5>
                                                    </div>
                                                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" onsubmit="return validateForm()">
                                                        <div class="form-group row">
                                                            <label for="institucion" class="col-xs-3 col-form-label">Institución:</label>
                                                            <div class="col-xs-9">
                                                                <input class="form-control" type="text" id="institucion" name="institucion" required>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label for="email" class="col-xs-3 col-form-label">Email:</label>
                                                            <div class="col-xs-9">
                                                                <input class="form-control" type="email" placeholder="example@example.com" id="email" name="email" required="">
                                                            </div>
                                                        </div>
                                                        <!--				<div class="form-group row">
                           <label for="kit" class="col-xs-2 col-form-label">Kit:</label>
                           <div class="col-xs-10">
                           	<select class="form-control" id="kit" name="kit" required>
                                  <option selected disabled hidden value = "nada">Escoge un kit<option>
                           				<option>LaMotte</option>
                           				<option>Pasco</option>
                                    <option>Prof</option>
                           	</select>
                           </div>
                           </div>
                            -->
                                                        <div class="form-group row">
                                                            <label for="estacion" class="col-xs-3 col-form-label">Estación:</label>
                                                            <div class="col-xs-9">
                                                                <select class="form-control" id="estacion" name="estacion">
                                                                    <option>Estación 1</option>
                                                                    <option>Estación 2</option>
                                                                    <option>Estación 3</option>
                                                                    <option>Estación 4</option>
                                                                    <option>Estación 5</option>
                                                                    <option>Estación 6</option>
                                                                    <option>Estación 7</option>
                                                                    <option>Estación 8</option>
                                                                    <option>Estación 9</option>
                                                                    <option>Estación 10</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label for="nombre" class="col-xs-3 col-form-label">Nombre:</label>
                                                            <div class="col-xs-9">
                                                                <input class="form-control" type="text" id="nombre" name="nombre">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label for="fecha" class="col-xs-3 col-form-label">Fecha:</label>
                                                            <div class="col-xs-9">
                                                                <input class="form-control" type="date" placeholder="ej: 24/11/2016" id="fecha" name="fecha">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label for="hora" class="col-xs-3 col-form-label">Hora:</label>
                                                            <div class="col-xs-9">
                                                                <input class="form-control" type="time" placeholder="ej: 16:00" id="hora" name="hora">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label for="latitud" class="col-xs-3 col-form-label">Latitud:</label>
                                                            <div class="col-xs-9">
                                                                <input class="form-control" type="text" id="latitud" name="latitud">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label for="longitud" class="col-xs-3 col-form-label">Longitud:</label>
                                                            <div class="col-xs-9">
                                                                <input class="form-control" type="text" id="longitud" name="longitud">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label for="indice" class="col-xs-3 col-form-label">Índice:</label>
                                                            <div class="col-xs-9">
                                                                <select class="form-control" id="indice" name="indice" onchange="cambio()" required>
                                                                    <option selected disabled hidden value="nada">Escoge un índice</option>
                                                                    <option value="holandes">Holandés</option>
                                                                    <option value="WQIB">WQIB</option>
                                                                    <option value="NSF">NSF</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="verticalLine">
                                                            <h5 class="titulo">Información Obligatoria</h5>
                                                        </div>
                                                        <div class="form-group row" id="divNada">
                                                            <small id="nada" class="form-text text-muted col-xs-12">
                           No has escogido un indice.
                           </small>
                                                        </div>
                                                        <div style="display:none;" id="divPO2" class="form-group row">
                                                            <label for="pO2" class="col-xs-3 col-form-label">% O2:</label>
                                                            <div class="col-xs-9">
                                                                <input class="form-control" type="text" id="pO2" name="pO2">
                                                            </div>
                                                        </div>
                                                        <div style="display:none;" id="divCF" class="form-group row">
                                                            <label for="cf" class="col-xs-3 col-form-label">CF:</label>
                                                            <div class="col-xs-9">
                                                                <input class="form-control" type="text" id="cf" name="cf">
                                                            </div>
                                                        </div>
                                                        <div style="display:none;" id="divDBO" class="form-group row">
                                                            <label for="dbo" class="col-xs-3 col-form-label">DBO:</label>
                                                            <div class="col-xs-9">
                                                                <input class="form-control" type="text" id="dbo" name="dbo">
                                                            </div>
                                                        </div>
                                                        <div style="display:none;" id="divNH4" class="form-group row">
                                                            <label for="nh4" class="col-xs-3 col-form-label">NH4:</label>
                                                            <div class="col-xs-9">
                                                                <input class="form-control" type="text" id="nh4" name="nh4">
                                                            </div>
                                                        </div>
                                                        <div style="display:none;" id="divPH" class="form-group row">
                                                            <label for="ph" class="col-xs-3 col-form-label">pH:</label>
                                                            <div class="col-xs-9">
                                                                <input class="form-control" type="text" id="ph" name="ph">
                                                            </div>
                                                        </div>
                                                        <div style="display: none" id="divFosfato" class="form-group row">
                                                            <label for="fosfato" class="col-xs-3 col-form-label">Fosfato:</label>
                                                            <div class="col-xs-9">
                                                                <input class="form-control" type="text" id="fosfato" name="fosfato">
                                                            </div>
                                                        </div>
                                                        <div style="display: none" id="divNitrato" class="form-group row">
                                                            <label for="nitrato" class="col-xs-3 col-form-label">Nitrato:</label>
                                                            <div class="col-xs-9">
                                                                <input class="form-control" type="text" id="nitrato" name="nitrato">
                                                            </div>
                                                        </div>
                                                        <div style="display:none;" id="divT" class="form-group row">
                                                            <label for="t" class="col-xs-3 col-form-label">T:</label>
                                                            <div class="col-xs-9">
                                                                <input class="form-control" type="text" id="t" name="t">
                                                            </div>
                                                        </div>
                                                        <div style="display:none;" id="divTurbidez" class="form-group row">
                                                            <label for="turbidez" class="col-xs-3 col-form-label">Turbidez:</label>
                                                            <div class="col-xs-9">
                                                                <input class="form-control" type="text" id="turbidez" name="turbidez">
                                                            </div>
                                                        </div>
                                                        <div style="display:none;" id="divSolTot" class="form-group row">
                                                            <label for="solTot" class="col-xs-3 col-form-label">Sólidos totales:</label>
                                                            <div class="col-xs-9">
                                                                <input class="form-control" type="text" id="solTot" name="solTot">
                                                            </div>
                                                        </div>
                                                        <div class="verticalLine">
                                                            <h5 class="titulo">Información del kit</h5>
                                                        </div>
                                                        <div style="display:none;" class="form-group row">
                                                            <label for="color" class="col-xs-3 col-form-label">Color:</label>
                                                            <div class="col-xs-9">
                                                                <select class="form-control" id="color" name="color">
                                                                    <option>Azul</option>
                                                                    <option>Verde</option>
                                                                    <option>Amarillo</option>
                                                                    <option>Anaranjado</option>
                                                                    <option>Rojo</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div style="display:none;" class="form-group row">
                                                            <label for="indHol" class="col-xs-3 col-form-label">I-Hol:</label>
                                                            <div class="col-xs-9">
                                                                <input class="form-control" type="text" id="indHol" name="indHol" aria-describedby="ayudaIndHol">
                                                                <small id="ayudaIndHol" class="form-text text-muted">
                              Debe ser un dato entero entre 1 y 15.
                              </small>
                                                            </div>
                                                        </div>
                                                        <div style="display:none;" id="divNH4_O" class="form-group row">
                                                            <label for="nh4O" class="col-xs-3 col-form-label">NH4:</label>
                                                            <div class="col-xs-9">
                                                                <input class="form-control" type="text" id="nh4O" name="nh4O">
                                                            </div>
                                                        </div>
                                                        <div style="display:none;" id="divCF_O" class="form-group row">
                                                            <label for="cfO" class="col-xs-3 col-form-label">CF:</label>
                                                            <div class="col-xs-9">
                                                                <input class="form-control" type="text" id="cfO" name="cfO">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label for="dqo" class="col-xs-3 col-form-label">DQO:</label>
                                                            <div class="col-xs-9">
                                                                <input class="form-control" type="text" id="dqo" name="dqo">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label for="ec" class="col-xs-3 col-form-label">EC:</label>
                                                            <div class="col-xs-9">
                                                                <input class="form-control" type="text" id="ec" name="ec">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label for="po4" class="col-xs-3 col-form-label">PO4:</label>
                                                            <div class="col-xs-9">
                                                                <input class="form-control" type="text" id="po4" name="po4">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label for="gya" class="col-xs-3 col-form-label">GYA:</label>
                                                            <div class="col-xs-9">
                                                                <input class="form-control" type="text" id="gya" name="gya">
                                                            </div>
                                                        </div>
                                                        <div style="display:none;" id="divPH_O" class="form-group row">
                                                            <label for="phO" class="col-xs-3 col-form-label">pH:</label>
                                                            <div class="col-xs-9">
                                                                <input class="form-control" type="text" id="phO" name="phO">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label for="sd" class="col-xs-3 col-form-label">SD:</label>
                                                            <div class="col-xs-9">
                                                                <input class="form-control" type="text" id="sd" name="sd">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label for="ssed" class="col-xs-3 col-form-label">Ssed:</label>
                                                            <div class="col-xs-9">
                                                                <input class="form-control" type="text" id="ssed" name="ssed">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label for="sst" class="col-xs-3 col-form-label">SST:</label>
                                                            <div class="col-xs-9">
                                                                <input class="form-control" type="text" id="sst" name="sst">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label for="st" class="col-xs-3 col-form-label">ST:</label>
                                                            <div class="col-xs-9">
                                                                <input class="form-control" type="text" id="st" name="st">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label for="saam" class="col-xs-3 col-form-label">SAAM:</label>
                                                            <div class="col-xs-9">
                                                                <input class="form-control" type="text" id="saam" name="saam">
                                                            </div>
                                                        </div>
                                                        <div style="display:none;" id="divT_O" class="form-group row">
                                                            <label for="tO" class="col-xs-3 col-form-label">T:</label>
                                                            <div class="col-xs-9">
                                                                <input class="form-control" type="text" id="tO" name="tO">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label for="aforo" class="col-xs-3 col-form-label">Aforo:</label>
                                                            <div class="col-xs-9">
                                                                <input class="form-control" type="text" id="aforo" name="aforo">
                                                            </div>
                                                        </div>
                                                        <div style="display: none" id="divFosfato_O" class="form-group row">
                                                            <label for="fosfatoO" class="col-xs-3 col-form-label">Fosfato:</label>
                                                            <div class="col-xs-9">
                                                                <input class="form-control" type="text" id="fosfatoO" name="fosfatoO">
                                                            </div>
                                                        </div>
                                                        <div style="display: none" id="divNitrato_O" class="form-group row">
                                                            <label for="nitratoO" class="col-xs-3 col-form-label">Nitrato:</label>
                                                            <div class="col-xs-9">
                                                                <input class="form-control" type="text" id="nitratoO" name="nitratoO">
                                                            </div>
                                                        </div>
                                                        <div style="display:none;" id="divTurbidez_O" class="form-group row">
                                                            <label for="turbidezO" class="col-xs-3 col-form-label">Turbidez:</label>
                                                            <div class="col-xs-9">
                                                                <input class="form-control" type="text" id="turbidezO" name="turbidezO">
                                                            </div>
                                                        </div>
                                                        <div style="display:none;" id="divSolTot_O" class="form-group row">
                                                            <label for="solTotO" class="col-xs-3 col-form-label">Sólidos totales:</label>
                                                            <div class="col-xs-9">
                                                                <input class="form-control" type="text" id="solTotO" name="solTotO">
                                                            </div>
                                                        </div>
                                                        <input type="submit" class="btn btn-primary" name="btn_agregar" id="btn_agregar" value="Agregar" />
                                                    </form>
                                                </div>
                                                <?php endif; ?>
                            </div>
                            <?php require 'views/inc/footer.php';?>
                        </div>
                    </main>
                </div>
                <!-- jQuery first, then Tether, then Bootstrap JS. -->
                <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js" integrity="sha384-3ceskX3iaEnIogmQchP8opvBy3Mi7Ce34nWjpBIwVTHfGYWQS9jwHDVRnpKKHJg7" crossorigin="anonymous"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.3.7/js/tether.min.js" integrity="sha384-XTs3FgkjiBgo8qjEjBk0tGmf3wPrWtA6coPfQDfFEY8AnYJwjalXCiosYRBIBZX8" crossorigin="anonymous"></script>
                <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/js/bootstrap.min.js" integrity="sha384-BLiI7JTZm+JWlgKa0M0kGRpJbF2J8q+qreVrKBC47e3K6BW78kGLrCkeRX6I9RoK" crossorigin="anonymous"></script>
                <script type="text/javascript">
                    window.onload = function() {
                        document.body.style.marginTop = document.getElementById('mainNav').clientHeight + 10 + "px";
                    };
                </script>
				<script type="text/javascript">
					$(document).ready(
						function(){
							$('head').append('<link rel="stylesheet" type="text/css" href="css/estilo_insercion.css">');
						}
				)
				</script>
    </body>

</html>