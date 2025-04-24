<?php
include('parteS/conexion.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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

<form method="post" class="formulaturno">
    <div class="inline-form-turno">
        <h1 class="ti-solicitud">SOLICITUD DE TURNO</h1>
        <p class="p-solicitud">Nombre y apellido</p>
        <input type="text" class="input-formu-solic" placeholder="EJ: Carlos Perez" name="nombreyapellido">
        <p class="p-solicitud">Dni</p>
        <input type="number" name="dni" class="input-formu-solic" placeholder="EJ: 12345678">
        <p class="p-solicitud">Fecha de nacimiento</p>
        <input type="date" class="input-formu-solic" name="fechanacimiento">
        <p class="p-solicitud">Obra social</p>
        <select class="input-formu-solic" name="obrasocial">
            <option value="" disabled selected>Selecciona tu obra social</option>
            <option>No tengo obra social</option>
            <option>Si, Es STIA(Sindicato de la industria de la alimentacion)</option>
            <option>Si, Es SMATA(Sindicato de Mecánicos y Afines del Transporte Automotor de la República Argentina)</option>
            <option>Si, Es UON(Unión del Personal Civil de la Nación)</option>
            <option>Si, Es Osprera(OBRA SOCIAL DEL PERSONAL RURAL Y ESTIBADORES DE LA REPUBLICA ARGENTINA)</option>
            <option>Si, Es Pami(Instituto Nacional de Servicios Sociales para Jubilados y Pensionados)</option>
        </select>

        <p class="p-solicitud">Ingresa 2 Contactos</p>
        <input type="number" class="input-formu-solic" name="contacto1" placeholder="EJ: 1234-123456" pattern="\d{4}-\d{6}">
        <input type="number" class="input-formu-solic" name="contacto2" placeholder="EJ: 1234-123456" pattern="\d{4}-\d{6}">
    </div>

    <div class="inline-fonmulario-turno">
        <p class="p-solicitud">Servicios que ofrecemos</p>
        <select name="servicio" class="input-formu-solic">
            <option value="" disabled selected>Servicios</option>
            <option>Urgencias y Emergencias</option>
            <option>Medicina General</option>
            <option>Pediatría</option>
            <option>Ginecología y Obstetricia</option>
            <option>Cardiología</option>
            <option>Oncología</option>
            <option>Fisioterapia y Rehabilitación</option>
            <option>Medico Crónico</option>
            <option>Psicología</option>
            <option>Nutricionista</option>
            <option>Control Sano</option>
            <option>Cardiólogo</option>
            <option>Diabetólogo</option>
            <option>Dermatología</option>
            <option>Odontología</option>
            <option>Neumología</option>
            <option>Traumatología</option>    
        </select>
        <p class="p-solicitud">Doctores disponibles</p>
        <select name="Doctores" class="input-formu-solic">
            <option value="" disabled selected>Doctores</option>
            <?php
            $doc = $conexion->query("SELECT * FROM `doctores`");
            foreach ($doc as $datdoc) {
                echo '<option value="'. htmlspecialchars($datdoc['dnidoctor']) .'">'. htmlspecialchars($datdoc['nombre'])  .' , '. htmlspecialchars($datdoc['apellido'])  .' , '. htmlspecialchars($datdoc['Especialidad'])  .'</option>';
            }
            ?>
        </select>
        <p class="p-solicitud">Turno solicitado</p>
        <label for="hora">Hora:</label>
        <input class="horayfecha" type="time" id="hora" name="hora" min="08:00" max="20:00" required>
            <br>
        <label for="fechaturno">Fecha:</label>
        <input class="horayfecha" type="date" id="fechaturno" name="fechaturno" required>

        <br>
        <input class="input-formu-solic-enviado" name="enviarsolicitud" type="submit" value="Enviar">
        <br>
        <?php
        if (isset($_POST['enviarsolicitud'])) {
            $nombreyapellido = $_POST['nombreyapellido'];
            $dni = $_POST['dni'];
            $fechanacimiento = $_POST['fechanacimiento'];
            $obrasocial = $_POST['obrasocial'];
            $contacto1 = $_POST['contacto1'];
            $contacto2 = $_POST['contacto2'];
            $servicio = $_POST['servicio'];
            $doctor = $_POST['Doctores'];
            $fechaturno = $_POST['fechaturno'];
            $horaturno = $_POST['hora'];
            $fechayhora = $fechaturno . ' ' . $horaturno;

            // Validación de hora y fecha
            $horaValida = ($horaturno >= '08:00' && $horaturno <= '20:00');
            $diaSemana = date('N', strtotime($fechaturno));
            $fechaValida = ($diaSemana >= 1 && $diaSemana <= 5); // 1 = lunes, 5 = viernes

            if ($horaValida && $fechaValida) {
                if (!empty($nombreyapellido)) {
                        // Verificación si el turno está en horario laboral
                        if ($dni) {
                        // Verificación si el paciente ya existe en la base de datos
                            $testpaciente = $conexion->query("SELECT * FROM `pacientes` WHERE `dni` = '$dni'");

                            if ($testpaciente->rowCount() == 0) {
                                // Inserción en la tabla `pacientes` si el DNI no existe
                                $conexion->query("INSERT INTO `pacientes` (`id`, `noyap`, `dni`, `fecha_nacimiento`, `obra_social`, `contacto1`, `contacto2`) 
                                                    VALUES (NULL, '$nombreyapellido', '$dni', '$fechanacimiento', '$obrasocial', '$contacto1', '$contacto2')");
                                echo "Paciente registrado exitosamente.";
                            } else {
                                echo '<div class="atencion">Este DNI ya está cargado</div>';
                            }
                        }
                    // Verificación de duplicado en `fechayhora`
                    $testfechahora = $conexion->query("SELECT * FROM `turno_si` WHERE `dnidoctor` = '$doctor' AND `fechayhora` = '$fechayhora'");

                    if ($testfechahora->rowCount() == 0) {
                        // Inserción en la tabla `turno_si`
                        $conexion->query("INSERT INTO `turno_si` (`id`, `dni`, `dnidoctor`, `fechayhora`, `noyap`, `servicio`) 
                        VALUES (NULL, '$dni', '$doctor', '$fechayhora', '$nombreyapellido', '$servicio')");

                        echo "<div class='aceptado'>Turno cargado para el día: $fechaturno a la hora: $horaturno</div>";
                    } else {
                        echo "<div class='nopuede'>Ya existe un turno en esa fecha y hora.</div>";
                    }
                }
            }
        }
        ?>
    </div>
</form>

<footer class="barrainferior">
    <p class="textoflo">Numero de la Clinica: Whatsapp:(2657)-777777 Fijo:(02657) 42-7777</p>
    <p class="textoflo">Direccion: San lorenzo 1807</p>
</footer>
</body>
</html>
