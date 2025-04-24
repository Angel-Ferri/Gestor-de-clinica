<?php
    try {
        $conexion = new PDO('mysql:host=localhost;dbname=clínica','root', '');
     //   echo "conexion ok";
    } catch (PDOException $e) {
        echo 'Falló la conexión: ' . $e->getMessage();
    }

?>