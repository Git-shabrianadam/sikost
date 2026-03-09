<?php

require_once '../lib/fpdf/fpdf.php';

$pdf = new FPDF();

$pdf->AddPage();

$pdf->SetFont('Courier','B',16);
$pdf->Cell(0,10,'FPDF is working!',0,1,'C');
$pdf->Cell(0,10,'PDF bekerja jika aflah sudah menikah',0,1,'C');
$pdf->Output();