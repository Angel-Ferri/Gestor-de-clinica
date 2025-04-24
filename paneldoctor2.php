<?php
session_start(); // Iniciar sesión
include('parteS/conexion.php'); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="estilos/estilos.css">
    <link rel="shortcut icon" href="estilos/img/medical-team.png" type="image/x-icon">
    <title>Panel doctor</title>
</head>
<body>
    <div class="barra">
        <img src="estilos/img/medical-team.png" class="iconclini">
        <h1 class="titulo">Clinica</h1>        
        <a href="principal.html"><p class="botones">Inicio</p></a>
        <a href="doctores.php"><p class="botones">Doctores</p></a>
        <a href="paciente.php"><p class="botones">Pacientes</p></a>
        <a href="contactos.php"><p class="botones">Contactos</p></a>
    </div>
    <div class="contedoctor">
        <div class="columnamenu">
           <a href="paneldoctor.php">
                <div class="botonlogindoctor">
                    <p>Perfil</p>
                </div>
            </a>
            <a href="paneldoctor.php">

                <div class="botonlogindoctor">
                    <p>Turnos</p>
                </div>
            </a>
            <a href="#generaldiagnostico">
                <div class="botonlogindoctor">
                    <p>General diagnostico</p>
                </div>
            </a>
            <a href="#historial">
                <div class="botonlogindoctor">
                     <p>Historial</p>
                </div>
            </a>
        </div>
        </div>
        <div class="doctordatospanel">
            <?php
            // Verifica si los datos están disponibles en la sesión
            if (isset($_SESSION['nombre_doctor']) && isset($_SESSION['dnidoctor'])) {
                echo '<h2 class="muestrodatosdocpanel"> Bienvenido, Dr. ' . htmlspecialchars($_SESSION['nombre_doctor']) . ' - DNI: ' . htmlspecialchars($_SESSION['dnidoctor']) . '</h2>';
            } else {
                echo "No has iniciado sesión.";
                header("Location: login.php");
                exit; // Detenemos la ejecución si no ha iniciado sesión
            }
            ?>
            <div class="hojadoc">
                <form id="generaldiagnostico" method="post" enctype="multipart/form-data" action="partes/pdf.php" >
                    <h1>Diagnostico</h1>
                    <h3>Ingrese los datos del paciente</h3>
                    <p>Nombre y apellido del paciente</p>
                    <select name="noyap" class="input-formu-solic">
                        <option value="" disabled selected>NOMBRE Y APELLIDO DE LOS PACIENTES</option>
                        <?php
                            // Trae los datos del paciente
                            $dnidoctor = $_SESSION['dnidoctor'];
                            $datospacientes = $conexion->query("SELECT * FROM `turno_si` WHERE dnidoctor = '$dnidoctor'");

                            // Muestro los datos
                            foreach ($datospacientes as $datopaci) {
                                echo '<option>' . htmlspecialchars($datopaci['noyap']) . '</option>';
                            }
                        ?>
                    </select>

                    <p>DNI del paciente</p>
                    <select name="dnipaci" class="input-formu-solic" required>
                        <option value="" disabled selected>DNI DEL PACIENTE</option>
                        <?php
                            // Trae los datos del paciente
                            $dnidoctor = $_SESSION['dnidoctor'];
                            $datospacientes = $conexion->query("SELECT * FROM `turno_si` WHERE dnidoctor = '$dnidoctor'");

                            // Muestro los datos
                            foreach ($datospacientes as $datopaci) {
                                echo '<option value="' . htmlspecialchars($datopaci['dni']) . '">' . htmlspecialchars($datopaci['dni']) . ' - ' . htmlspecialchars($datopaci['noyap']) . '</option>';
                            }
                        ?>
                    </select>
                    <br>
                    <p>Diagnostico</p>
                    <textarea name="diagnos" class="textdiagnostico"></textarea>
                    <br>
                    <input type="file" name="img" accept="image/*"><br>
                    <input type="submit" value="Guardar" class="guardar" name="guardar" required>
                </form>
            </div> 
            <form method="post" id="historial">
                    <p>Solicitar historial específico</p>
                    <input type="number" name="descargardni">
                    <input type="submit" value="Enviar" name="soli">
                </form>
             <div class="Cajahistoral">
                    <div class="cajahistorial">
                        <p class="tithisto">Historial</p>
                        <?php
                        if (isset($_POST['soli']) && !empty($_POST['descargardni'])) {
                            $soli = $_POST['descargardni'];
                            
                            $traigohistorial = $conexion->query("SELECT * FROM `historial_clinico` WHERE dni = '$soli' AND dnidoctor = '$dnidoctor' ");

                            if ($traigohistorial->rowCount() == 0) {
                                echo "<div class='nopuede'>Este paciente no existe</div>";
                            } else {
                                echo "<div class='cajares'>Listo, estos son todos sus diagnósticos</div>";
                                foreach ($traigohistorial as $triagohis) {
                                    echo '<div class="cajahis">
                                            <p>Diagnóstico de ' . $triagohis['dni'] . ' - Fecha: ' . $triagohis['fecha'] . '</p>
                                            <form method="post" action="partes/pdfsecretaria.php" target="_blank">
                                                <input type="hidden" name="dni" value="' . $triagohis['dni'] . '">
                                                <input type="submit" name="descargar" value="Descargar">
                                            </form>
                                        </div>';
                                }
                            }
                        }else {
                            $triagohis34 = $conexion->query("SELECT * FROM `historial_clinico` WHERE dnidoctor = '$dnidoctor' ");
                            if ($triagohis34 == 0) {
                                echo '<p class="alerta">No tienes diagnosticos</p>';
                            }
                            else {
                                foreach ($triagohis34 as $datosindni) {
                                    echo '<div class="cajahis">
                                    <p>Diagnóstico de ' . $datosindni['dni'] . ' - Fecha: ' . $datosindni['fecha'] . '</p>
                                    <form method="post" action="partes/pdfsecretaria.php" target="_blank">
                                        <input type="hidden" name="dni" value="' . $datosindni['dni'] . '">
                                        <input type="submit" name="descargar" value="Descargar">
                                    </form>
                                </div>';
                                }
                            }
                        }
?>

        </div>
    </div>
</body>
</html>
