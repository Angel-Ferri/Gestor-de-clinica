<?php
 include('parteS/conexion.php');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="estilos/estilos.css">
    <link rel="shortcut icon" href="estilos/img/medical-team.png" type="image/x-icon">
    <title>Clinica</title>
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

    <div class="logindoctorcaja">
        <h2>LOGIN</h2>
        <form action="" method="POST">
            <p>Ingrese su DNI</p>
            <input class="logindoctor" type="text" name="logindoctordni" required>

            <p>Ingrese su contraseña</p>
            <input class="logindoctor" type="password" name="logindoctorcontra" required>
            
            <br>
            <input class="enviarlogindoc" type="submit" name="enviarlogindoc" value="Entrar">
        </form>
        <?php
session_start(); // Iniciar la sesión

if (isset($_POST['enviarlogindoc'])) {  
    // Verifica si se ha enviado el formulario
    $dnilogin = $_POST['logindoctordni'];
    $contralogin = $_POST['logindoctorcontra'];

    // Consulta para doctores
    $logindoctor = $conexion->query("SELECT * FROM doctores WHERE dnidoctor = '$dnilogin' AND passworddoc = '$contralogin'");
    
    // Verifica si el doctor existe
    if ($logindoctor->rowCount() == 1) {
        $doctor = $logindoctor->fetch(PDO::FETCH_ASSOC);  // Obtén los datos del doctor como un array asociativo

        // Guarda el nombre y el DNI del doctor en la sesión
        $_SESSION['nombre_doctor'] = $doctor['nombre'];
        $_SESSION['dnidoctor'] = $doctor['dnidoctor'];

        // Redirecciona al panel del doctor
        header("Location: paneldoctor.php");
        exit(); 
    }

    // Consulta para trabajadores (por ejemplo, secretarias)
    $secreatira = $conexion->query("SELECT * FROM trabajadores WHERE dnitrabajador = '$dnilogin' AND passwordtrabajador = '$contralogin'");

    // Verifica si el trabajador existe
    if ($secreatira->rowCount() == 1) {
        $secreta = $secreatira->fetch(PDO::FETCH_ASSOC);  // Obtén los datos del trabajador como un array asociativo

        // Guarda el nombre y el DNI del trabajador en la sesión
        $_SESSION['nombre_trabajador'] = $secreta['nombre'];
        $_SESSION['dnitrabajador'] = $secreta['dnitrabajador'];

        // Redirecciona al panel de la secretaria
        header("Location: Secretaria.php");
        exit();
    } else {
        echo "DNI o contraseña incorrectos.";
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
