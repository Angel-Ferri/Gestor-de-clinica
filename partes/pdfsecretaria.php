<?php
require('li/fpdf.php');
include('conexion.php');

if (isset($_POST['dni'])) {
    $dni = $_POST['dni'];

    // Obtener datos del historial clínico
    $traigohistorial = $conexion->query("SELECT * FROM `historial_clinico` WHERE dni = '$dni'");
    $triagohis = $traigohistorial->fetch();

    if (!$triagohis) {
        echo "No se encontró el historial.";
        exit;
    }

    $diagnostico = $triagohis['diagnostico'];
    $fechadiagnostico = $triagohis['fecha'];
    $imgadj = $triagohis['imgenesadj']; // Nombre de la imagen almacenada en la base de datos
    $dnidoctor1 = $triagohis['dnidoctor'];

    // Datos del paciente
    $paci = $conexion->query("SELECT * FROM `pacientes` WHERE dni = '$dni'");
    $pacien = $paci->fetch();
    $nomyap = $pacien['noyap'];

    // Crear el PDF
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, "Diagnostico", 0, 1, 'C');
    $pdf->Ln(10);

    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(0, 10, "Datos del paciente", 0, 1, 'L');
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, "Nombre y apellido: " . $nomyap , 0, 1);
    $pdf->Cell(0, 10, "DNI: " . $dni, 0, 1);

    $pdf->Ln(10);
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(0, 10, "Datos del Diagnostico", 0, 1);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, "Fecha: " . $fechadiagnostico, 0, 1);
    $pdf->Cell(0, 10, "DNI del Doctor: " . $dnidoctor1, 0, 1);

    $pdf->Ln(10);
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(0, 10, "Informe", 0, 1);
    $pdf->SetFont('Arial', '', 12);
    $pdf->MultiCell(0, 10, $diagnostico);

    // Verificar si existe la imagen
    if (!empty($imgadj) && file_exists('uploads/' . $imgadj)) {
        // Si la imagen existe en el directorio 'uploads', la agregamos al PDF
        $y = $pdf->GetY(); // Obtener la posición Y actual
        $pdf->Cell(0, 10, "Imagen adjunta", 0, 1, 'L');
        $pdf->SetY($y + 10); // Ajustamos la posición Y para no sobreponer texto e imagen
        $pdf->Image('uploads/' . $imgadj, 10, $pdf->GetY(), 50); // Ajusta la posición de la imagen
    } else {
        $pdf->Cell(0, 10, "No se encontró imagen adjunta", 0, 1, 'L');
    }

    $pdf->Output('D', 'Historial_Clinico_' . $dni . '.pdf');
    exit;
} else {
    echo "No se recibió un DNI válido.";
}
?>
