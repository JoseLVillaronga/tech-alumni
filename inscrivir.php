<?php
require_once 'config.php';
if(!empty($_SESSION['usuario'])){
    $usu=new Usuario($_SESSION['usuario']);
}else{
	header("location: login.php");
	exit;
}
if($_SERVER['REQUEST_METHOD']=="POST"){
	$id=$_POST['id'];
	$curso=new Curso($id);
	$usuario=$_POST['usuario'];
	$obj=new Inscripcion($curso,$usuario);
	if($curso->docente->getUsuario()!=$usuario AND $obj->inscrivible==TRUE){
		$obj->agregaADb();
		header("location: cursos.php");
		exit;
		//$mensaje="agregado";
	}else{
		header("location: cursos.php");
		exit;
		//$mensaje="no-agregado";
	}
}else{
	$id=$_GET['curso'];
	$curso=new Curso($id);
	$usuario;
}
require_once "header.php";
?>
    <main class="container row" id="content" style="background-color: rgba(255,255,255,.4);">
        <h4 style="text-align: center;">Inscribír en - <?php echo $curso->getDesignacion(); ?></h4>
        <br>
        <h6 style="padding: 10px;">Bien venid@ <?php echo $usu->getNombre()." ".$usu->getApellido().", tenes ".$usu->getMensajesNoLeidos()." mensajes sin leer ..."; ?></h6>
		<form method="post" id="formulario">
		<div class="input-field col s12 m6">
			<input type="hidden" name="id" value="<?php echo $id; ?>">
			<select id="usuario" name="usuario">
				<option value="">Seleccionar ...</option>
				<?php
				foreach (Db::listar("SELECT * FROM clientes WHERE cli_habilitado = 1 ORDER BY cli_nombre,cli_apellido") as $filaDo){ ?>
				<option value="<?php echo $filaDo['cli_usuario']; ?>" <?php if($filaDo['cli_usuario']==$usuario){echo "SELECTED";} ?>><?php echo $filaDo['cli_nombre']." ".$filaDo['cli_apellido']; ?></option>
				<?php } ?>
			</select>
			<label for="usuario">Usuario ...</label>
		</div>
		<div class="input-field col s12 m6" style="text-align: center;">
		         <a id="scale-demo" href="#!" class="btn-floating btn-large scale-transition green">
				 <i id="add" class="material-icons waves-effect waves-light" onclick="document.forms['formulario'].submit();" title="Grabar">send</i>
			  </a>
		</div>
		</form>
		<br>
    </main>
<?php
//echo $mensaje."<br>";
//echo "<pre>";
//print_r($obj);
//echo "</pre>";
require_once "footer.php";
?>