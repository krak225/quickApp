<?php
include("modules/printer/fpdf17/fpdf.php");
$pdf=new FPDF();
$pdf->SetTitle('Cotisation');
$pdf->AddPage();
$pdf->SetFont('Arial','',12);

// Insère un logo en haut à gauche à 300 dpi
// $pdf->Image('../../images/kraktechnologie.gif',5,10,-300);
require_once('fonctions/fonctions.php');
connexionDB();
$cotisation_id = $_GET['cotisation_id'];

//recuperer la cotisation
$sql='select * from cotisation
where cotisation_id="'.$cotisation_id.'"';
$req=mysql_query($sql);
$d=mysql_fetch_object($req);
$cotisation_libelle = $d->cotisation_libelle;

//l'entete
$pdf->SetY(8);
$pdf->Cell(190,8 ,'Cotisation: '.$cotisation_libelle , 0, 0, 'L',false, '');
//l'entete du tableau
$y=15;
$pdf->SetY($y);
$pdf->Cell(20,8 ,'N° Mle.', 1, 0, 'C',false, '');
$pdf->Cell(70,8 ,'Nom & Prénoms', 1, 0, 'C',false, '');
$pdf->Cell(25,8 ,'Télephone', 1, 0, 'C',false, '');
$pdf->Cell(25,8 ,'A payer', 1, 0, 'C',false, '');
$pdf->Cell(25,8 ,'Payé', 1, 0, 'C',false, '');
$pdf->Cell(25,8 ,'Reste', 1, 0, 'C',false, '');

//les lignes du tableau
$y=15;$c=0;
$sql='select * from forfait
		inner join cotisation using(cotisation_id)
		inner join chretien using(chretien_id)
		where cotisation_id = "'.$cotisation_id.'"
		order by cotisation_id desc
		';
$req=mysql_query($sql);

while($d=mysql_fetch_object($req)){
	// for($i=55;$i>1;$i--){
	$c++;
	$pdf->SetY($y+8*$c);
	$pdf->Cell(20,8 ,$d->chretien_matricule, 1, 0, '',false, '');
	$pdf->Cell(70,8 ,substr(utf8_decode($d->chretien_nom.' '.$d->chretien_prenoms),0,29), 1, 0, '',false, '');
	$pdf->Cell(25,8 ,$d->chretien_telephone, 1, 0, 'C',false, '');
	$pdf->Cell(25,8 ,$d->forfait_montant_a_payer, 1, 0, 'C',false, '');
	$pdf->Cell(25,8 ,$d->forfait_montant_paye, 1, 0, 'C',false, '');
	$pdf->Cell(25,8 ,$d->forfait_montant_a_payer - $d->forfait_montant_paye, 1, 0, 'C',false, '');
	// }
	
	if($c==51){
		$y=15;
		$c=0;
		$pdf->AddPage();
		$pdf->SetY($y);
		$pdf->SetFont('Arial','',12);
	}
 }



$pdf->Output();
?>