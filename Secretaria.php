<?php
session_start(); // Iniciar sesión
$dnisecretaria = $_SESSION['dnitrabajador'];

include('partes/conexion.php');

$datossecretaria = $conexion->query("SELECT * FROM `trabajadores` WHERE dnitrabajador = '$dnisecretaria'");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secretaria</title>
    <link rel="stylesheet" href="estilos/estilos.css">
</head>
<body>
    <div class="barra">
        <h1 class="titulo">Clinica</h1>
        <a href="principal.html"><p class="botones">Inicio</p></a>
        <a href="doctores.php"><p class="botones">Doctores</p></a>
        <a href="paciente.php"><p class="botones">Pacientes</p></a>
        <a href="contactos.php"><p class="botones">Contactos</p></a>
    </div>

    <?php
    if (isset($_SESSION['dnitrabajador'])) {
        foreach ($datossecretaria as $dato) {
            echo '<p class="bienvenido">Hola, ' . $dato['nombre'] . ' ' . $dato['apellido'] . ', espero tengas una buena jornada</p>';
        }
    } else {
        echo "No has iniciado sesión.";
        header("Location: login.php");
        exit;
    }
    ?>
    <div class="secretariabody">
        <div class="horisecretaria">
            <a class="botonsecretaria" href=""><p class="psecre">Cargar Doctor</p></a>
            <a class="botonsecretaria" href=""><p class="psecre">Descargar Historial</p></a>
            <a class="botonsecretaria" href=""><p class="psecre">Tabla de pacientes</p></a>
        </div>

        <div class="muestrosecre">
            <form method="post" class="formucargadoc">
                <div class="formui">
                    <p>Ingresar al nuevo doctor</p>
                    <p>Nombre</p>
                    <input type="text" name="nombredoc">
                    <p>Apellido</p>
                    <input type="text" name="apellidodoc">
                    <p>Dni</p>
                    <input type="number" name="dnidoc">
                    <p>Fecha nacimiento</p>
                    <input type="date" name="fechanacdoc">
                    <p>Teléfono</p>
                    <input type="number" name="telefeno">
                </div>
                <div class="formud">
                    <p>Cargo</p>
                    <input type="text" name="cargo">
                    <p>Especialidad</p>
                    <input type="text" name="especialidad">
                    <p>Correo</p>
                    <input type="email" name="correo">
                    <p>Contraseña</p>
                    <input type="password" name="contra">
                    <br>
                    <input type="submit" value="Cargar" class="capro" name="cargar">
                </div>
                <?php
                if (isset($_POST['cargar'])) {
                    $nombredoc = $_POST['nombredoc'];
                    $apellidodoc = $_POST['apellidodoc'];
                    $dnidoc = $_POST['dnidoc'];
                    $fechanacidoc = $_POST['fechanacdoc'];
                    $telefonodoc = $_POST['telefeno'];
                    $cargodoc = $_POST['cargo'];
                    $especialidad = $_POST['especialidad'];
                    $correo = $_POST['correo'];
                    $password = $_POST['contra'];

                    $testdnidoc = $conexion->query("SELECT * FROM `doctores` WHERE dnidoctor = '$dnidoc'");

                    if ($testdnidoc->rowCount() == 0) {
                        $conexion->query("INSERT INTO `doctores` (`ID`, `Especialidad`, `dnidoctor`, `nombre`, `apellido`, `nacimiento`, `telefono`, `cargo`, `Correo`, `passworddoc`, `imagen`) 
                                          VALUES (NULL, '$especialidad', '$dnidoc', '$nombredoc', '$apellidodoc', '$fechanacidoc', '$telefonodoc', '$cargodoc', '$correo', '$password', NULL)");
                        echo "Fue cargado con éxito";
                    } else {
                        echo "Este doctor ya está cargado";
                    }
                }
                ?>
            </form>

            <div class="Historialclinico">
                <p>Todos los historiales clínicos</p>
                <form method="post">
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
                                $traigohistorial = $conexion->query("SELECT * FROM `historial_clinico` WHERE dni = '$soli'");

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
                                $triagohissinif = $conexion->query("SELECT * FROM `historial_clinico`");
                                foreach ($triagohissinif as $a23) {
                                    echo '<div class="cajahis">
                                            <p>Diagnóstico de ' . $a23['dni'] . ' - Fecha: ' . $a23['fecha'] . '</p>
                                            <form method="post" action="partes/pdfsecretaria.php" target="_blank">
                                                <input type="hidden" name="dni" value="' . $a23['dni'] . '">
                                                <input type="submit" name="descargar" value="Descargar">
                                            </form>
                                        </div>';
                                }
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
