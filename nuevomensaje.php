<?php
require_once "config.php";
if(!empty($_SESSION['usuario'])){
    $usu=new Usuario($_SESSION['usuario']);
}else{
	header("location: login.php");
	exit;
}
$id=$_GET['id'];
$mensaje=new Mensaje($id);
if($_SERVER['REQUEST_METHOD']=="POST"){
	$asunto=$_POST['asunto'];
	$receiver=$_POST['receiver'];
	$tipo=$_POST['tipo'];
	$cuerpo=$_POST['cuerpo'];
	$fecha=date("Y-m-d H:i:s");
	$sender=$_SESSION['usuario'];
	$leido=0;
	$obj=new Mensaje();
	$obj->sender->__construct($sender);
	$obj->setReceiver($receiver);
	$obj->tipo->__construct($tipo);
	$obj->setFecha($fecha);
	$obj->setLeido($leido);
	$obj->setAsunto($asunto);
	$obj->setCuerpo($cuerpo);
	if(!in_array("harError", $obj->errores)){
		$obj->agregaADb();
		if($obj->errorSql['0']=="00000"){
			header("location: mensajes.php");
			exit;
		}
	}
}else{
	$asunto;
	$receiver;
	$tipo;
	$cuerpo;
	if(!empty($_GET['id'])){
		$id=$_GET['id'];
		$mensaje=new Mensaje($id);
		$receiver=$mensaje->sender->getUsuario();
		$tipo=$mensaje->tipo->getId();
		$asunto="RE:".$mensaje->getAsunto();
		$cuerpo="\n\n\n----------------------------------------------------------------------\n";
		$cuerpo.="Mensaje de: ".$mensaje->sender->getNombre()." ".$mensaje->sender->getApellido()."\n";
		$cuerpo.="Asunto: ".$mensaje->getAsunto()."\n";
		$cuerpo.="De Fecha: ".$mensaje->getFecha()."\n";
		$cuerpo.="----------------------------------------------------------------------\n";
		$cuerpo.=$mensaje->getCuerpo();
		$fecha=$mensaje->getFecha();
	}
}
require_once "header.php";
?>
    <main class="container row" id="content" style="background-color: rgba(255,255,255,.4);">
        <h4 style="padding-left: 10px;">Nuevo mensaje ...</h4>
        <h6 style="padding: 0 10px 0 10px;">Bien venid@ <?php echo $usu->getNombre()." ".$usu->getApellido().", tenes ".$usu->getMensajesNoLeidos()." mensajes sin leer ..."; ?></h6>
		<form method="post" id="formulario">
		<div class="input-field col s12 m4">
			<input type="text" id="asunto" name="asunto" value="<?php echo $asunto; ?>">
			<label for="asunto">Asunto ...</label>
		</div>
		<div class="input-field col s12 m4">
			<select id="receiver" name="receiver">
				<option value="">Seleccionar ...</option>
				<?php
				foreach (Db::listar("SELECT cli_nombre,cli_apellido,cli_usuario,gru_nombre FROM clientes AS c,grupos AS g WHERE c.cli_grupo = g.cli_grupo AND cli_usuario <> '".$_SESSION['usuario']."'") as $filaRes){ ?>
				<option value="<?php echo $filaRes['cli_usuario']; ?>" <?php if($filaRes['cli_usuario']==$receiver){echo "SELECTED";} ?>><?php echo $filaRes['cli_nombre']." ".$filaRes['cli_apellido'].", ".$filaRes['gru_nombre']; ?></option>
				<?php } ?>
			</select>
			<label for="receiver">Destinatario ...</label>
		</div>
		<div class="input-field col s12 m4">
			<select id="tipo" name="tipo">
				<?php
				foreach (Db::listar("SELECT * FROM mensaje_tipo") as $filaTi){ ?>
				<option value="<?php echo $filaTi['ment_id']; ?>" <?php if($filaTi['ment_id']==$tipo){echo "SELECTED";} ?>><?php echo $filaTi['ment_value']; ?></option>
				<?php } ?>
			</select>
			<label for="tipo">Prioridad ...</label>
		</div>
		<div class="input-field col s12 m12">
			<textarea id="cuerpo" name="cuerpo" style="width: 100%;min-height: 200px;background-color: rgba(255,255,255,.8)"><?php echo $cuerpo; ?></textarea>
		</div>
		<div class="input-field col s12 m6" style="padding: 10px 0 10px 0;text-align: center;">
			<a id="scale-demo" href="#!" class="btn-floating btn-small scale-transition green">
				<i id="add" class="material-icons waves-effect waves-light" onclick="document.forms['formulario'].submit();" title="Enviar ...">send</i>
			</a>
		</div>
		<div class="input-field col s12 m6" style="padding: 10px 0 10px 0;text-align: center;">
			<a id="scale-demo" href="#!" class="btn-floating btn-small scale-transition blue">
				<i id="add" class="material-icons waves-effect waves-light" onclick="location.href='mensajes.php'" title="Volver">keyboard_backspace</i>
			</a>
			<?php echo "<pre>";print_r($obj->errorSql);echo "</pre>"; ?>
		</div>
		</form>		
    </main>
<script>
	document.getElementById('cuerpo').focus();
</script>
<?php
require_once "footer.php";
?>