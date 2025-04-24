<?php
require('li/fpdf.php');
session_start(); // Iniciar sesión
include('conexion.php');

if (isset($_POST['guardar'])) {
    $dni = $_POST['dnipaci'];
    $fechaactual = date("Y-m-d");
    $imgegenes = $_FILES['img']['name'];
    $img = $_FILES['img']['tmp_name'];
    $textdiagnostico = $_POST['diagnos'];
    $dnidoctor = $_SESSION['dnidoctor'];  
    $nomyap = $_POST['noyap'];

    $conexion->query("INSERT INTO `historial_clinico` (`id`, `dni`, `dnidoctor`, `diagnostico`, `fecha`, `imgenesadj`) 
                    VALUES (NULL, '$dni', '$dnidoctor', '$textdiagnostico', '$fechaactual', '$imgegenes')");

    // Verificar si se ha subido una imagen
    $imagenTmp = $_FILES['img']['tmp_name'];
    $imagenNombre = $_FILES['img']['name'];
    $directorioDestino = 'uploads'; 

    // Mover la imagen a un directorio accesible
    $rutaDestino = $directorioDestino . '/' . $imagenNombre;
    move_uploaded_file($imagenTmp, $rutaDestino);

    // Crear una nueva instancia de FPDF
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);
    
    // Añadir contenido al PDF
    $pdf->Cell(0, 10, "Diagnostico", 0, 1, 'C');
    $pdf->Ln(10);
    //$pdf->Cell(0, 10, $fecha, 0, 1, 'C');
    
    
    // Añadir el resto del contenido
    $pdf->Ln(10);
    $pdf->SetFont('Arial', 'B', 14);
    
    // Datos del paciente
    $pdf->Cell(0, 10, "Datos del paciente", 0, 1, 'L');
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, "Nombre y apellido: ____" . $nomyap . "_____", 0, 1, 'L');
    $pdf->Cell(0, 10, "DNI: ___" . $dni . "___", 0, 1, 'L');
    
    // Datos del diagnóstico
    $pdf->Ln(10);
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(0, 10, "Datos del Diagnostico", 0, 1, 'L');
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, "Fecha: ____" . $fechaactual . "____", 0, 1, 'L');
    $pdf->Cell(0, 10, "DNI del Doctor: ___" . $dnidoctor . "___", 0, 1, 'L');
    $pdf->Cell(0, 10, "Firma: __________________", 0, 1, 'L');
    
    
    
    // Informe
    $pdf->Ln(10);
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(0, 10, "Informe", 0, 1, 'L');
    $pdf->SetFont('Arial', '', 12);
    $pdf->MultiCell(0, 10, $textdiagnostico);

    // Obtener la posición actual del cursor en el eje Y
    $y = $pdf->GetY();
    $pdf->Cell(0, 10, "Imagenes adjuntas", 0, 1, 'L');
    // Ajustar la posición Y y colocar la imagen
    $pdf->SetY($y + 10); // Ajusta el valor de 10 para el espacio entre texto y la imagen
    $pdf->Image($rutaDestino, 10, $pdf->GetY(), 50); // Coloca la imagen después del texto
    
    // Generar el archivo PDF y forzar la descarga
    $pdf->Output('D', 'archivo_generado.pdf');
    exit;
}
?>

