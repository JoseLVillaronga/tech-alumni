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
	$usuario=$_POST['usuario'];
	$curso=new Curso($id);
	$calificacion=new Calificacion($usuario,$id);
	$calificacionValue=$_POST['calificacionValue'];
	$departamento=$_POST['departamento'];
	$calificacion->value->__construct($calificacionValue);
	$calificacion->departamento->__construct($departamento);
	if(count($calificacion->lote)==0){
		$calificacion->setFecha(date("Y-m-d H:i:s"));
		$calificacion->agregaADb();
		if($calificacion->errorSql['0']=="00000"){
			header("location: inscripciones.php?id=".$id);
			exit;
		}
	}else{
		$calificacion->actualizaDb();
		if($calificacion->errorSql['0']=="00000"){
			header("location: inscripciones.php?id=".$id);
			exit;
		}
	}
}else{
	$id=$_GET['curso'];
	$usuario=$_GET['usuario'];
	$curso=new Curso($id);
	$calificacion=new Calificacion($usuario,$id);
	$calificacionValue=$calificacion->value->getId();
	$departamento=$calificacion->departamento->getId();
}
require_once "header.php";
?>
    <main class="container row" id="content" style="background-color: rgba(255,255,255,.4);">
        <h4 style="text-align: center;">Calificación de <?php echo $usuario.", curso \"".$curso->getDesignacion()."\" de fecha ".$curso->getFechaInicio(); ?></h4>
        <br>
        <h6 style="padding: 10px;">Bien venid@ <?php echo $usu->getNombre()." ".$usu->getApellido().", tenes ".$usu->getMensajesNoLeidos()." mensajes sin leer ..."; ?></h6>
        <form method="post" id="formulario">
        	<input type="hidden" name="id" value="<?php echo $id; ?>">
        	<input type="hidden" name="usuario" value="<?php echo $usuario; ?>">
			<div class="input-field col s12 m6">
				<select id="calificacionValue" name="calificacionValue">
					<option value="">Seleccionar ...</option>
					<?php
					foreach (Db::listar("SELECT * FROM calificacion_value") as $filaCa){ ?>
					<option value="<?php echo $filaCa['calv_id']; ?>" <?php if($filaCa['calv_id']==$calificacionValue){echo "SELECTED";} ?>><?php echo $filaCa['calv_value']; ?></option>
					<?php } ?>
				</select>
				<label for="calificacionValue">Calificación ...</label>
			</div>
			<div class="input-field col s12 m6">
				<select id="departamento" name="departamento">
					<option value="">Seleccionar ...</option>
					<?php
					foreach (Db::listar("SELECT * FROM departamento") as $filaDe){ ?>
					<option value="<?php echo $filaDe['dep_id']; ?>" <?php if($filaDe['dep_id']==$departamento){echo "SELECTED";} ?>><?php echo $filaDe['dep_nombre']; ?></option>
					<?php } ?>
				</select>
				<label for="departamento">Departamento ...</label>
			</div>
		<div class="input-field col s12 m12">
			<div class="input-field col s12 m6" style="text-align: center;">
		          <a id="scale-demo" href="#!" class="btn-floating btn-large scale-transition green">
					 <i id="add" class="material-icons waves-effect waves-light" onclick="document.forms['formulario'].submit();" title="Grabar">send</i>
				  </a>
			</div>
			<div class="input-field col s12 m6" style="text-align: center;">
			      <a id="scale-demo" href="#!" class="btn-floating btn-large scale-transition indigo">
					 <i id="add" class="material-icons waves-effect waves-light" onclick="location.href='inscripciones.php?id=<?php echo $id; ?>'" title="Volver">arrow_back</i>
				  </a>
			<?php //echo "Hola !!!<br><pre>";print_r($obj->errores);echo "</pre>"; ?>
			</div>
		</div>
        </form>
	<br>
    </main>
<?php
require_once "footer.php";
?>