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
	$designacion=$_POST['designacion'];
	$descripcion=$_POST['descripcion'];
	$duracion=$_POST['duracion'];
	$status=$_POST['status'];
	$docente=$_POST['docente'];
	$fechaInicio=$_POST['fechaInicio'];
	$fechaFinal=$_POST['fechaFinal'];
	$obj=new Curso($id);
	$obj->setDesignacion($designacion);
	$obj->setDescripcion($descripcion);
	$obj->setDuracion($duracion);
	if(empty($status)){
		$obj->errores['status'] = "Hay que seleccionar Status ...";
		$obj->errores['gen'] = "harError";
	}elseif($status==3 AND empty($fechaFinal)){
		$obj->status->__construct($status);
		$obj->setFechaFinal(date("Y-m-d"));
	}else{
		$obj->status->__construct($status);
	}
	$obj->docente->__construct($docente);
	if(!empty($fechaInicio)){$obj->setFechaInicio($fechaInicio);}
	if(!empty($fechaFinal)){$obj->setFechaFinal($fechaFinal);}
	if(!in_array("harError", $obj->errores)){
		$obj->actualizaDb();
		if($obj->errorSql['0']=="00000"){
			header("location: cursos.php");
			exit;
		}
	}
}elseif($_SERVER['REQUEST_METHOD']=="GET"){
	$id=$_GET['id'];
	$obj=new Curso($id);
	$designacion=$obj->getDesignacion();
	$descripcion=$obj->getDescripcion();
	$duracion=$obj->getDuracion();
	$status=$obj->status->getId();
	$docente=$obj->docente->getUsuario();
	$fechaInicio=$obj->getFechaInicio();
	$fechaFinal=$obj->getFechaFinal();
}

require_once "header.php";
?>
    <main class="container row" id="content" style="background-color: rgba(255,255,255,.4);">
        <h4 style="text-align: center;">Editar Curso</h4>
        <br>
        <h6 style="padding: 10px;">Bien venid@ <?php echo $usu->getNombre()." ".$usu->getApellido().", tenes ".$usu->getMensajesNoLeidos()." mensajes sin leer ..."; ?></h6>
        <form method="post" id="formulario">
		<div class="input-field col s12 m6">
			<input type="text" id="designacion" name="designacion" value="<?php echo $designacion; ?>">
			<label for="designacion">Designación ...</label>
			<input type="hidden" name="id" value="<?php echo $id; ?>">
		</div>
		<div class="input-field col s12 m6">
			<input type="text" id="descripcion" name="descripcion" value="<?php echo $descripcion; ?>">
			<label for="descripcion">Descripción ...</label>
		</div>
		<div class="input-field col s12 m6">
			<select id="duracion" name="duracion">
				<option value="1">1 Clase</option>
				<?php for ($i=2; $i <= 10; $i++) { ?>
				<option value="<?php echo $i; ?>" <?php if($duracion==$i){echo "SELECTED";} ?>><?php echo $i; ?> Clases</option>
				<?php } ?>
			</select>
			<label for="duracion">Duración ...</label>
		</div>
		<div class="input-field col s12 m6">
			<select id="status" name="status">
				<option value="">Seleccionar ...</option>
				<?php
				foreach (Db::listar("SELECT * FROM curso_status") as $filaSt){ ?>
				<option value="<?php echo $filaSt['cs_id']; ?>" <?php if($filaSt['cs_id']==$status){echo "SELECTED";} ?>><?php echo $filaSt['cs_descripcion']; ?></option>
				<?php } ?>
			</select>
			<label for="status">Status ...</label>
		</div>
		<div class="input-field col s12 m6">
			<select id="docente" name="docente">
				<option value="">Seleccionar ...</option>
				<?php
				foreach (Db::listar("SELECT * FROM clientes WHERE cli_grupo = 2 AND cli_habilitado = 1 ORDER BY cli_nombre,cli_apellido") as $filaDo){ ?>
				<option value="<?php echo $filaDo['cli_usuario']; ?>" <?php if($filaDo['cli_usuario']==$docente){echo "SELECTED";} ?>><?php echo $filaDo['cli_nombre']." ".$filaDo['cli_apellido']; ?></option>
				<?php } ?>
			</select>
			<label for="docente">Docente ...</label>
		</div>
		<div class="input-field col s12 m6">
			<input type="text" id="fechaInicio" name="fechaInicio" class="datepicker" value="<?php echo $fechaInicio; ?>" readonly>
			<label for="fechaInicio">Fecha Inicio ...</label>
		</div>
		<div class="input-field col s12 m6">
			<input type="text" id="fechaFinal" name="fechaFinal" class="datepicker" value="<?php echo $fechaFinal; ?>" readonly>
			<label for="fechaFinal">Fecha Final ...</label>
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
			<?php //echo "Hola !!!<br><pre>";print_r($obj->errores);echo "</pre>"; ?>
			</div>
		</div>
		</form>
		<br><br>
    </main>
<script src="js/jquery-ui.js"></script>    
<script>
	$(function() {
		$(".datepicker").datepicker({
			dateFormat : 'yy-mm-dd'
		});
		$.datepicker.regional['es'] = {
			closeText : 'Cerrar',
			prevText : '&#x3c;Ant',
			nextText : 'Sig&#x3e;',
			currentText : 'Hoy',
			monthNames : ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
			monthNamesShort : ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
			dayNames : ['Domingo', 'Lunes', 'Martes', 'Mi&eacute;rcoles', 'Jueves', 'Viernes', 'S&aacute;bado'],
			dayNamesShort : ['Dom', 'Lun', 'Mar', 'Mi&eacute;', 'Juv', 'Vie', 'S&aacute;b'],
			dayNamesMin : ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'S&aacute;'],
			weekHeader : 'Sm',
			dateFormat : 'dd/mm/yy',
			firstDay : 1,
			isRTL : false,
			showMonthAfterYear : false,
			yearSuffix : ''
		};
		$.datepicker.setDefaults($.datepicker.regional['es']);
	});
</script>
<?php
require_once "footer.php";
?>