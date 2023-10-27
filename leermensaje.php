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

require_once "header.php";
?>
    <main class="container row" id="content" style="background-color: rgba(255,255,255,.4);">
        <h4 style="padding-left: 10px;">Asunto : <?php echo $mensaje->getAsunto(); ?></h4>
        <h6 style="padding: 0 10px 0 10px;">Bien venid@ <?php echo $usu->getNombre()." ".$usu->getApellido().", tenes ".$usu->getMensajesNoLeidos()." mensajes sin leer ..."; ?></h6>
		<h5 class="input-field col s12 m6">Mensaje de: <?php echo $mensaje->sender->getNombre()." ".$mensaje->sender->getApellido(); ?></h5>
		<h5 class="input-field col s12 m6">De fecha : <?php echo $mensaje->getFecha(); ?></h5>
		<div class="input-field col s12 m12">
			<textarea style="width: 100%;min-height: 200px;background-color: rgba(255,255,255,.8)"><?php echo $mensaje->getCuerpo(); ?></textarea>
		</div>
		<div class="input-field col s12 m4" style="padding: 10px 0 10px 0;text-align: center;">
			<a id="scale-demo" href="#!" class="btn-floating btn-small scale-transition green">
				<i id="add" class="material-icons waves-effect waves-light" onclick="location.href='marcarmensaje.php?id=<?php echo $mensaje->getId(); ?>'" title="Marcar como leido ...">check</i>
			</a>
		</div>
		<div class="input-field col s12 m4" style="padding: 10px 0 10px 0;text-align: center;">
			<a id="scale-demo" href="#!" class="btn-floating btn-small scale-transition black">
				<i id="add" class="material-icons waves-effect waves-light" onclick="location.href='nuevomensaje.php?id=<?php echo $mensaje->getId(); ?>'" title="Responder ...">reply</i>
			</a>
		</div>
		<div class="input-field col s12 m4" style="padding: 10px 0 10px 0;text-align: center;">
			<a id="scale-demo" href="#!" class="btn-floating btn-small scale-transition blue">
				<i id="add" class="material-icons waves-effect waves-light" onclick="location.href='mensajes.php'" title="Volver">keyboard_backspace</i>
			</a>
		</div>		
    </main>
<?php
require_once "footer.php";
?>