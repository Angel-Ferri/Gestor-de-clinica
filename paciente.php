<?php
    include('parteS/conexion.php');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="estilos/estilos.css">
    <title>Paciente</title>
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
    <div class="tabladeturno">
        <form action="" method="post" class="dniinp">
        <h3 class="titulodni">INTRODUCE TU DNI:</h3>
        <input type="number" name="dnipaciente" class="input-solic-dni">
        <input class="enviar" type="submit" value="Enviar" name="enviar">
    </form>
    <?php
    $introducedni = '';
    if (isset($_POST["enviar"])) {
        if (!empty($_POST['dnipaciente'])) {
            $introducedni = isset($_POST['dnipaciente']) ? $_POST['dnipaciente'] : '';
        }
    }

$turnconfir = $conexion->query("SELECT * FROM `pacientes` WHERE `dni` = '$introducedni'");

if ($introducedni && $turnconfir) {
    echo '<h2>Datos del paciente</h2>';
    foreach ($turnconfir as $datot) {   
        echo '<p> Nombre y Apellido: ' . $datot['noyap'] . '</p>'; // Mostramos el NOMBRE Y APELLIDO
        echo '<p> Dni: ' . $datot['dni'] . '</p>'; // Mostramos el servicio
    }
    $turndate = $conexion->query("SELECT * FROM `turno_si` WHERE `dni` = '$introducedni'");
    foreach ($turndate as  $turno) {   
        echo '<h2>Turnos Registrados</h2>';
        echo '<div class="cajaturno">';
        echo '<p class="turnofecha"> Turno para: ' . $turno['servicio'] . '</p>'; // Mostramos el servicio
        echo '<p class="turnofecha"> Turno para: ' . $turno['fechayhora'] . "</p>"; // Mostramos el turno
        echo "</div>";
        }
    }

    ?>
    </div>
<footer class="barrainferior">
<p class="textoflo">Numero de la Clinica: Whatsapp:(2657)-777777 Fijo:(02657) 42-7777
</p>
<p class="textoflo">Direccion: San lorenzo 1807</p>
</footer>
</body>
</html>