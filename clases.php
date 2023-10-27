<?php
require_once "config.php";
if(!empty($_SESSION['usuario'])){
    $usu=new Usuario($_SESSION['usuario']);
}else{
	header("location: login.php");
	exit;
}
$id=$_GET['id'];
$clase=1;
if(isset($_GET['clase'])){$clase=$_GET['clase'];}
$curso=new Curso($id);
if($usu->grupo->getNombre()=="Alumnos" AND $curso->status->getDescripcion()=="Inactivo"){
	header("location: miscursos.php?mensaje=Todavía no comenzó el curso ...");
	exit;
}
require_once "header.php";
?>
<style>
	ul li div div {
		margin: auto;
	}
	.collapsible-header {
		background-color: rgba(0,0,0,.2);
	}
	.activo {
		background-color: rgba(128,0,0,.7);
	}
	.activo:hover {
		background-color: rgba(84,0,0,.9);
	}
	.activo:active {
		background-color: rgba(64,0,0,.8);
	}
</style>
    <main class="container row" id="content" style="background-color: rgba(255,255,255,.4);">
        <h4 style="text-align: center;"><?php echo $curso->getDesignacion()." / ".$curso->getFechaInicio(); ?></h4>
        <h5><?php echo "Docente : ".$curso->docente->getNombre()." ".$curso->docente->getApellido(); ?></h5>
        <h6 style="padding: 10px;">Bien venid@ <?php echo $usu->getNombre()." ".$usu->getApellido().", tenes ".$usu->getMensajesNoLeidos()." mensajes sin leer ..."; ?></h6>
			<?php for ($i=1; $i <= $curso->getDuracion(); $i++) { ?>
				<a href="javascript:void(0)" title="Clase <?php echo $i; ?>" onclick="location.href='clases.php?id=<?php echo $id."&clase=".$i; ?>'" class="btn-floating pulse<?php if($i==$clase){echo " activo";} ?>"><i class=""><?php echo $i; ?></i></a>
			<?php } ?>
<br><br>
			<h1 style="text-align: center;">Clase <?php echo $clase; ?></h1>
			<br>
			<?php $contenido=new Contenido($curso,$clase,$_SESSION['usuario']); ?>
			<h5>Objetivos</h5>
			<br>
			<h6 style="font-style: italic;font-size: 1.2em;">En esta clase verás ...</h6>
			<p><?php echo nl2br($contenido->getObjetivosIniciales()); ?></p>
			<h6 style="font-style: italic;font-size: 1.2em;">Al final de esta clase serás capáz de ...</h6>
			<p><?php echo nl2br($contenido->getObjetivosFinales()); ?></p>
			<br>
<div>
<ul class="collapsible popout" data-collapsible="accordion">
	<li>
	   	<div class="collapsible-header"><i class="material-icons">toc</i>Contenidos</div>
	   		<div class="collapsible-body">
			<?php
			//echo "<h2 style=\"text-align: center;background-color: rgba(0,0,0,.2);\">Contenidos</h2><br><br>";
			$pdf = new \TonchikTm\PdfToHtml\Pdf($contenido->getContenido(), array(
			    'pdftohtml_path' => '/usr/bin/pdftohtml',
			    'pdfinfo_path' => '/usr/bin/pdfinfo'
			));
			// get pdf info
			$pdfInfo = $pdf->getInfo();
			// get count pages
			$countPages = $pdf->countPages();
			
			// get content from one page
			$contentFirstPage = $pdf->getHtml()->getPage(1);
			
			// get content from all pages and loop for they
			foreach ($pdf->getHtml()->getAllPages() as $page) {
			    echo $page . '<br>';
			}
			?>
			</div>
		</div>
	</li>
</ul>
<ul class="collapsible popout" data-collapsible="accordion">
	<li>
	   	<div class="collapsible-header"><i class="material-icons">toc</i>Laboratorio</div>
	   		<div class="collapsible-body">
			<?php
			//echo "<h2 style=\"text-align: center;background-color: rgba(0,0,0,.2);\">Laboratorio</h2><br><br>";
			$pdf2 = new \TonchikTm\PdfToHtml\Pdf($contenido->getLaboratorio(), array(
			    'pdftohtml_path' => '/usr/bin/pdftohtml',
			    'pdfinfo_path' => '/usr/bin/pdfinfo'
			));
			// get pdf info
			$pdfInfo2 = $pdf2->getInfo();
			
			// get count pages
			$countPages2 = $pdf2->countPages();
			
			// get content from one page
			$contentFirstPage2 = $pdf2->getHtml()->getPage(1);
			
			// get content from all pages and loop for they
			foreach ($pdf2->getHtml()->getAllPages() as $page2) {
			    echo $page2 . '<br>';
			}
			?>
		</div>
	</li>
</ul>
<br><br>
			<?php for ($x=1; $x <= $curso->getDuracion(); $x++) { ?>
				<a href="javascript:void(0)" title="Clase <?php echo $x; ?>" onclick="location.href='clases.php?id=<?php echo $id."&clase=".$x; ?>'" class="btn-floating pulse<?php if($x==$clase){echo " activo";} ?>"><i class=""><?php echo $x; ?></i></a>
			<?php } ?>
		<div>
			<div class="input-field col s12 m12" style="text-align: center;">
			      <a id="scale-demo" href="#!" class="btn-floating btn-large scale-transition indigo">
					 <i id="add" class="material-icons waves-effect waves-light" onclick="location.href='miscursos.php'" title="Volver">arrow_back</i>
				  </a>
			<?php //echo "Hola !!!<br><pre>";print_r($obj->errores);echo "</pre>"; ?>
			</div>
		</div>
</div>
    </main>
<?php
require_once "footer.php";
?>