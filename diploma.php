<?php
require_once "config.php";
if(!empty($_SESSION['usuario'])){
    $usu=new Usuario($_SESSION['usuario']);
}else{
	header("location: login.php");
	exit;
}
$id=$_GET['id'];
$curso=new Curso($id);
$usuario=$_SESSION['usuario'];
$encuesta=new Encuesta($curso,$usuario);
//$calificacion=new Calificacion($usuario,$id);
$fechaFinal=$curso->getFechaFinal();
if(empty($fechaFinal)){
	//echo "<script>window.close();</script>";
	header("location: miscursos.php?mensaje=El diploma estara listo al terminar el curso y contestar la encuesta de calidad ...");
	exit;
}elseif(count($encuesta->loteEncuesta->lote)==0){
	header("location: miscursos.php?mensaje=El diploma estara listo al contestar la encuesta de calidad ...");
	exit;
}
class MYPDF extends TCPDF {
	//Page header
	public function Header() {
		// get the current page break margin
		$bMargin = $this->getBreakMargin();
		// get current auto-page-break mode
		$auto_page_break = $this->AutoPageBreak;
		// disable auto-page-break
		$this->SetAutoPageBreak(false, 0);
		// set bacground image
		$img_file = K_PATH_IMAGES.'diploma.jpg';
		$this->Image($img_file, 0, 0, 297, 210, '', '', '', false, 300, '', false, false, 0);
		// restore auto-page-break status
		$this->SetAutoPageBreak($auto_page_break, $bMargin);
		// set the starting point for the page content
		$this->setPageMark();
	}
}
$pdf = new MYPDF("L", PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator("Teccam S.R.L.");
$pdf->SetAuthor('José Luis Villaronga');
$pdf->SetTitle('Certificación');
$pdf->SetSubject('Diploma Curso');
$pdf->SetKeywords('Teccam,Cursos,Capacitación');
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', "24"));
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(0);
$pdf->SetFooterMargin(0);
$pdf->setPrintFooter(false);
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}
$pdf->SetFont('times', 'BI', 36);
$pdf->AddPage();
$nombre=$usu->getNombre()." ".$usu->getApellido();
$txt = <<<EOD
$nombre
EOD;
$nombreCurso=$curso->getDesignacion()." / ".$curso->getFechaInicio();
$txt2 = <<<EOD
$nombreCurso
EOD;
$pdf->Write(120, $txt, '', 0, 'C', true, 0, false, false, 0);
$pdf->Write(0, $txt2, '', 0, 'C', true, 0, false, false, 0);
$pdf->Output('diploma.pdf', 'I');
