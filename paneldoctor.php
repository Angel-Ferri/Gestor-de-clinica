<?php
session_start(); // Iniciar sesión
include('parteS/conexion.php'); // Asegúrate de iniciar la sesión antes de incluir conexión.php
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
           <a href="#perfil">
                <div class="botonlogindoctor">
                    <p>Perfil</p>
                </div>
            </a>
            <a href="#turno">
                <div class="botonlogindoctor">
                    <p>Turnos</p>
                </div>
            </a>
            <a href="paneldoctor2.php">
                <div class="botonlogindoctor">
                     <p>Historial</p>
                </div>
            </a>
            <a href="paneldoctor2.php">
                <div class="botonlogindoctor">
                    <p>General diagnostico</p>
                </div>
            </a>
        </div>
        </div>
        <div class="doctordatospanel">
        <?php
        // Verifica si los datos están disponibles en la sesión
        if (isset($_SESSION['nombre_doctor']) && isset($_SESSION['dnidoctor'])) {
            echo ' <h2 class="muestrodatosdocpanel"> Bienvenido, Dr. ' . htmlspecialchars($_SESSION['nombre_doctor']) . ' - DNI: ' . htmlspecialchars($_SESSION['dnidoctor']) . '</h2>';
        } else {
            echo "No has iniciado sesión.";
            header("Location: login.php");
            exit; // Detenemos la ejecución si no ha iniciado sesión
        }

        $nombredoctor = $_SESSION['nombre_doctor'];
        $dnidoctornum = $_SESSION['dnidoctor'];

        // Asegúrate de que la conexión esté bien establecida
        if ($conexion) {
            // Prepara y ejecuta la consulta para obtener los datos del doctor
          $trigo = $conexion->query("SELECT imagen FROM doctores WHERE dnidoctor = '$dnidoctornum' ");
    
            // Verifica si se obtuvieron resultados
            if ($trigo->rowCount() > 0) {
                // Recuperar la imagen
                $logindatodoc = $trigo->fetch();
                // Manejo de la subida de la imagen
                if (isset($_FILES['actualizofotodoc']) && $_FILES['actualizofotodoc']['error'] == 0) {
                    $fotoactualizada = $_FILES['actualizofotodoc']['name'];
                    
                    $rutaDestino = 'C:/xampp/htdocs/Tercer trimestre/Clinica/uploads/' . $fotoactualizada;

                    // Mueve el archivo subido a la carpeta deseada
                    if (move_uploaded_file($_FILES['actualizofotodoc']['tmp_name'], $rutaDestino)) {
                        // Actualiza el campo 'imagen' de la tabla
                        $trigo = $conexion->query("UPDATE doctores SET imagen = '$fotoactualizada' WHERE dnidoctor = '$dnidoctornum'");
                        echo "Foto actualizada con éxito.";
                    } else {
                        echo "Error al subir la imagen.";
                    }
                    
                }
            } else {
                echo "No se encontraron datos para el doctor.";
            }
        } else {
            echo "Error en la conexión con la base de datos.";
        }
        echo"<br>";
        echo '<img class="perfildocpanel" src="uploads/' . htmlspecialchars($logindatodoc['imagen']) . '" alt="Perfil del Doctor">';
        ?>
        <form id="perfil" method="POST" enctype="multipart/form-data">
        <p class="textpanel" >Si desea cambiar la foto de perfil presione en "seleccionar foto</p>
        <input type="file" name="actualizofotodoc" required />
        <input class="enviarlogindoc" type="submit" name="enviarlogindoc" value="Confirmar cambio">
        </form>
            <div id="turno" class="historialdeturnosdoc">
                <h3>Turnos pendientes</h3>
                <?php

                 $dnidoc = isset($_POST['dnidoc']);
                 $traigoturnos = $conexion->query("SELECT * FROM `turno_si` WHERE dnidoctor = '$dnidoctornum'");

                 if ($traigoturnos->rowCount()>= 1) {                    
                    foreach ($traigoturnos as $turnos) {
                        echo '<div class="histoturnodoc">';
                        echo '<p>Turno con</p>';
                        echo '<p>N° de turno: ' . htmlspecialchars($turnos['id']) . '</p>'; 
                        echo '<p>Paciente: ' . htmlspecialchars($turnos['noyap']) . '</p>'; 
                        echo '<p>DNI: ' . htmlspecialchars($turnos['dni']) . '</p>';
                        echo '<p>Fecha y Hora: ' . htmlspecialchars($turnos['fechayhora']) . '</p>';
                        echo '<p>Atención: ' . htmlspecialchars($turnos['servicio']) . '</p>';
                        echo '<br>';
                        // Formulario para eliminar el turno
                        echo '<form action="" method="post">';
                        echo '<input type="hidden" name="turno_id" value="' . htmlspecialchars($turnos['id']) . '">';
                        echo '<input class="eliminar" type="submit" value="Eliminar este turno" name="enviar">';
                        echo '</form>';
                        echo '</div';
                        $dnieliminado = $turnos['dni'];
                        $fechayhoraelimi = $turnos['fechayhora'];
                        $servicioelimi = $turnos['servicio'];

                        $conexion->query("INSERT INTO `turnos_eliminados` (`id`, `dni`, `dnidoctor`, `fechayhora`, `eliminado_por`, `servicio`) 
                        VALUES (NULL, '$dnieliminado', '$dnidoc', '$fechayhoraelimi', '$dnidoc', '$servicioelimi')");
                }
                // Eliminar turno
                if (isset($_POST['enviar']) && isset($_POST['turno_id'])) {
                    $turno_id = $_POST['turno_id'];
                    
                    //Elimina el turno seleccionado
                    $eliminar = $conexion->query("DELETE FROM `turno_si` WHERE id = '$turno_id'");
                    
                    if ($eliminar->rowCount() > 0) {
                        echo "<p>Turno con N° $turno_id fue eliminado correctamente.</p>";
                    } else {
                        echo "<p>Error: No se pudo eliminar el turno con ID $turno_id.</p>";
                    }
                }

            }else {
                echo'No tiene turnos';
            }
                ?>
            </div>
        </div>
    </div>
</body>
</html>