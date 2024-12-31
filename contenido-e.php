<?php
require_once "config.php";
if(!empty($_SESSION['usuario'])){
    $usu=new Usuario($_SESSION['usuario']);
}else{
	header("location: login.php");
	exit;
}
if($_SERVER['REQUEST_METHOD']=="POST"){
	$id=$_POST['id'];
	$clase=$_POST['clase'];
	$claseNro=$_POST['claseNro'];
	$curso=new Curso($id);
	$contenido=new Contenido($curso,$claseNro,$_SESSION['usuario']);
	$objetivosIniciales=$_POST['objetivosIniciales'];
	$objetivosFinales=$_POST['objetivosFinales'];
	$fecha=$_POST['fecha'];
	if(empty($fecha)){$fecha=date("Y-m-d H:i:s");}
	$contenidoFile=$_FILES['contenidoFile'];
	$laboratorioFile=$_FILES['laboratorioFile'];
	$contenidoTiposPermitidos = array('application/pdf');
	$laboratorioTiposPermitidos = array('application/pdf');
	if(!empty($contenidoFile['tmp_name'])){
		if($contenidoFile['size'] > 7340032){
			$contenido->errores['gen']="harError";
			$contenido->errores['contenido']="El archivo tiene mas de 7Mb";
		}
		elseif(!in_array($contenidoFile['type'],$contenidoTiposPermitidos)){
			$contenido->errores['gen']="harError";
			$contenido->errores['contenido']="El archivo tiene que ser .pdf ó .zip";
		}
	}
	if(!empty($laboratorioFile['tmp_name'])){
		if($laboratorioFile['size'] > 7340032){
			$contenido->errores['gen']="harError";
			$contenido->errores['laboratorio']="El archivo tiene mas de 7Mb";
		}
		elseif(!in_array($laboratorioFile['type'],$laboratorioTiposPermitidos)){
			$contenido->errores['gen']="harError";
			$contenido->errores['laboratorio']="El archivo tiene que ser .pdf ó .zip";
		}
	}
	$contenido->setObjetivosIniciales($objetivosIniciales);
	$contenido->setObjetivosFinales($objetivosFinales);
	//$contenido->setClaseNro($clase);
	//$contenido->usuario->__construct($_SESSION['usuario']);
	if(!in_array("harError", $contenido->errores)){
		if(!empty($contenidoFile['tmp_name'])){
			$neContenido=$contenidoFile['name'];
			$nombreContenido = time().str_replace("%","-",str_replace(" ","-",$neContenido));
			$nombreContenido2=$nombreContenido;
			chmod($contenidoFile['tmp_name'],0755);
			move_uploaded_file($contenidoFile['tmp_name'], 'infor/'.$nombreContenido);
			$ruta='infor/'.$nombreContenido2;
			$contenido->setContenidos($ruta);
		}
		if(!empty($laboratorioFile['tmp_name'])){
			$neLaboratorio=$laboratorioFile['name'];
			$nombreLaboratorio = time().str_replace("%","-",str_replace(" ","-",$neLaboratorio));
			chmod($laboratorioFile['tmp_name'],0755);
			move_uploaded_file($laboratorioFile['tmp_name'], 'infor/'.$nombreLaboratorio);
			$laboratorioFile['ruta']='infor/'.$nombreLaboratorio;
			$contenido->setLaboratorio($laboratorioFile['ruta']);
		}
		$contenidoId=$contenido->getId();
		if(empty($contenidoId)){
			$contenido->setFecha(date("Y-m-d H:i:s"));
			$contenido->agregaADb();
		}else{
			$contenido->setFecha($fecha);
			$contenido->actualizaDb();
		}
		if($contenido->errorSql['0'] == "00000"){
			header("location: cursos.php");
			exit;
		}
	}
}elseif($_SERVER['REQUEST_METHOD']=="GET"){
	$id=$_GET['id'];
	if(isset($_GET['clase'])){
		$clase=$_GET['clase'];
	}else{
		$clase=1;
	}
	$curso=new Curso($id);
	$contenido=new Contenido($curso,$clase,$_SESSION['usuario']);
	$objetivosIniciales=$contenido->getObjetivosIniciales();
	$objetivosFinales=$contenido->getObjetivosFinales();
	$claseNro=$contenido->getClaseNro();
	$fecha=$contenido->getFecha();
	$contenidoFile=$contenido->getContenido();
	$laboratorioFile=$contenido->getLaboratorio();
}
require_once "header.php";
?>
    <main class="container row" id="content" style="background-color: rgba(255,255,255,.4);">
        <h4 style="text-align: center;">Contenidos</h4>
        <h6 style="padding: 10px;">Bien venid@ <?php echo $usu->getNombre()." ".$usu->getApellido().", tenes ".$usu->getMensajesNoLeidos()." mensajes sin leer ..."; ?></h6>
        <h5><?php echo $curso->getDesignacion()." / ".$curso->getFechaInicio()." / Docente : ".$curso->docente->getNombre()." ".$curso->docente->getApellido(); ?></h5>
        <h6>
        	<a href="<?php echo "contenido-e.php?id=".$id."&clase=1"; ?>">Clase 1</a>
        	<?php for ($x=2; $x <= $curso->getDuracion(); $x++) { ?>
        	, <a href="<?php echo "contenido-e.php?id=".$id."&clase=".$x; ?>">Clase <?php echo $x; ?></a>
        	<?php } ?>
        </h6>
        <form method="post" id="formulario" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <input type="hidden" name="clase" value="<?php echo $clase; ?>">
        <input type="hidden" name="fecha" value="<?php echo $fecha; ?>">
		<div class="input-field col s12 m6">
			<textarea style="min-height: 60px;" id="objetivosIniciales" name="objetivosIniciales"><?php echo $objetivosIniciales; ?></textarea>
			<label for="objetivosIniciales">En esta clase verás ...</label>
		</div>
		<div class="input-field col s12 m6">
			<textarea style="min-height: 60px;" id="objetivosFinales" name="objetivosFinales"><?php echo $objetivosFinales; ?></textarea>
			<label for="objetivosFinales">Al final de esta clase serás capáz de ...</label>
		</div>
		<div class="input-field col s12 m6">
			<select id="claseNro" name="claseNro">
				<?php for ($i=1; $i <= $curso->getDuracion(); $i++) { ?>
				<option value="<?php echo $i; ?>" <?php if($claseNro==$i){echo "SELECTED";} ?>><?php echo "Clase ".$i; ?></option>
				<?php } ?>
			</select>
			<label for="claseNro">Clase Nro.</label>
		</div>
		<div class="input-field col s12 m6">
			<input type="file" id="contenidoFile" name="contenidoFile" value="<?php echo $contenidoFile; ?>">
			<label for="contenidoFile">Contenido (PDF) ...
			</label>
			<a style="<?php if(empty($contenidoFile)){echo "display: none;";} ?>" id="scale-demo" href="#!" class="btn-floating btn-small scale-transition indigo">
				<i id="" class="material-icons waves-effect waves-light" onclick="window.open('<?php echo $contenido->getContenido(); ?>','_blank')" title="Contenido">picture_as_pdf</i></a>
		</div>
		<div class="input-field col s12 m6">
			<input type="file" id="laboratorioFile" name="laboratorioFile" value="<?php echo $laboratorioFile; ?>">
			<label for="laboratorioFile">Laboratorio (PDF) ...</label>
			<a style="<?php if(empty($laboratorioFile)){echo "display: none;";} ?>" id="scale-demo" href="#!" class="btn-floating btn-small scale-transition indigo">
				<i id="" class="material-icons waves-effect waves-light" onclick="window.open('<?php echo $contenido->getLaboratorio(); ?>','_blank')" title="Laboratorio">picture_as_pdf</i></a>
		</div>
		
		<div class="input-field col s12 m12">
			<div class="input-field col s12 m6" style="text-align: center;">
		          <a id="scale-demo" href="#!" class="btn-floating btn-large scale-transition green">
					 <i id="add" class="material-icons waves-effect waves-light" onclick="document.forms['formulario'].submit();" title="Grabar">send</i>
				  </a>
			</div>
			<div class="input-field col s12 m6" style="text-align: center;">
			      <a id="scale-demo" href="#!" class="btn-floating btn-large scale-transition indigo">
					 <i id="add" class="material-icons waves-effect waves-light" onclick="location.href='cursos.php'" title="Volver">arrow_back</i>
				  </a>
<?php  
//echo "<pre>";
//print_r($contenido->errores);
//echo "<br>";
//print_r($contenido->errorSql);
//echo $laboratorioFile['type']."<br>";
//echo $contenidoFile['type']."<br>";
//echo "</pre>";
?>
			</div>
		</div>
        </form>
    </main>
<?php
require_once "footer.php";
?>