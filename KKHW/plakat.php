<?php
session_start();
ob_start();
include("dbconnect.php");
define("FPDF_FONTPATH","fpdf/font/");
require "fpdf/fpdf.php";
$ip = getenv ( 'REMOTE_ADDR' );
$datum = date("Y-m-d H:i");

// Neü Klasse myPDF auf Basis der FPDF Klasse

$bildnummer="./image/".$_GET[bdnr];

$pdf=new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Cell(40,10,'Hallo Welt!');
$pdf->Image($bildnummer , 10, 10, 190, 270, 'PNG');
$pdf->Output();
mysql_close();
?>

