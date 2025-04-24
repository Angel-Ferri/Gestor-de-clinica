<?php
  include('parteS/conexion.php');
  $doctor = $conexion->query("SELECT * FROM `doctores`");
  $doctorte = $conexion->query("SELECT * FROM `doctores`");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="estilos/estilos.css">
    <title>Clinica</title>
</head>
<body>
<div class="barra">
        <img src="estilos/img/medical-team.png" class="iconclini">
        <h1 class="titulo">Clinica</h1>        
        <a href="principal.html"><p class="botones">Inicio</p></a>
        <a href="doctores.php"><p class="botones">Doctores</p></a>
        <a href="paciente.php"><p class="botones">Pacientes</p></a>
        <a href="login.php"><p class="botones">Login</p></a>
        <a href="contactos.php"><p class="botones">Contactos</p></a>
    </div>
    <div class="conte">
        <a class="botonlogin" href="login.php"><p class="botonlogintexto">Login</p></a>
        <h1 class="listados">Lista de doctores</h1>
        <div class="contedoctores">
            <!--<div class="enlinetarjeta"> -->
            <?php
// Prepara y ejecuta la consulta
$prevdoc = $conexion->query("SELECT * FROM `doctores`");
//$prevdoc->execute(); // Ejecutar la consulta
//$doctores = $prevdoc->fetchAll(PDO::FETCH_ASSOC); // Obtener todos los resultados

// Recorre los resultados
foreach ($prevdoc as $datdoc) {
    echo '<div class="tarjetadoctor">'; // ABRE tarjetadoctor

    echo '<div class="datosdoc">'; // ABRE datosdoc

    // TRAE LA IMG
    echo '<div class="fotodoc">'; // Abro fotodoc
    echo '<img class="perfildoc" src="uploads/' . htmlspecialchars($datdoc['imagen']) . '" alt="Imagen del Doctor"/>';
    echo '</div>'; // Cierro fotodoc

    // TRAE EL TEXTO
    echo '<div class="texdoc">'; // ABRE texdoc
    echo '<h4 class="datdoc">Nombre: ' . htmlspecialchars($datdoc['nombre']) . '</h4>';
    echo '<h4 class="datdoc">Apellido: ' . htmlspecialchars($datdoc['apellido']) . '</h4>';
    echo '<h4 class="datdoc">DNI: ' . htmlspecialchars($datdoc['dnidoctor']) . '</h4>';
    echo '<h4 class="datdoc">N° de doc: ' . htmlspecialchars($datdoc['ID']) . '</h4>';
    echo '<h4 class="datdoc">Especialidad: ' . htmlspecialchars($datdoc['Especialidad']) . '</h4>';
    echo '<h4 class="datdoc">Correo: ' . htmlspecialchars($datdoc['Correo']) . '</h4>';
    echo '<h4 class="datdoc">Teléfono: ' . htmlspecialchars($datdoc['telefono']) . '</h4>';
    echo '</div>'; // Cierro texdoc
    echo '</div>'; // Cierro datosdoc
    echo '</div>'; // Cierro tarjetadoctor
}
?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<footer class="barrainferior">
<p class="textoflo">Numero de la Clinica: Whatsapp:(2657)-777777 Fijo:(02657) 42-7777
</p>
<p class="textoflo">Direccion: San lorenzo 1807</p>
</footer>
</body>
</html>
