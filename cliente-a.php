<?php
require_once "config.php";
if(!empty($_SESSION['usuario'])){
    $usu=new Usuario($_SESSION['usuario']);
}else{
	header("location: login.php");
	exit;
}
if($_SERVER['REQUEST_METHOD']=="POST"){
	$obj=new Usuario(null);
	$nombre=$_POST['nombre'];
	$apellido=$_POST['apellido'];
	$dni=$_POST['dni'];
	$direccion=$_POST['direccion'];
	$telefono=$_POST['telefono'];
	$email=$_POST['email'];
	$usuario=$_POST['usuario'];
	$grupo=$_POST['grupo'];
	$empresa=$_POST['empresa'];
	$obj->setNombre($nombre);
	$obj->setApellido($apellido);
	$obj->setCuit($dni);
	$obj->setDireccion($direccion);
	$obj->setTelefono($telefono);
	$obj->setEmail($email);
	$obj->setUsuario($usuario);
	$obj->setGrupo($grupo);
	$obj->setRSocial($empresa);
	$obj->setCambPass(0);
	$obj->setAdmin(0);
	$obj->setHabilitado(1);
	$obj->setPass("teccam");
	$obj->agregaADb();
	if($obj->errorSql['0']=="00000"){
		header("location: clientes.php");
		exit;
	}
}elseif($_SERVER['REQUEST_METHOD']=="GET"){
	$nombre="";
	$apellido="";
	$dni="";
	$direccion="";
	$telefono="";
	$email="";
	$usuario="";
	$grupo="";
	$empresa="";
}
require_once "header.php";
?>
    <main class="container row" id="content" style="background-color: rgba(255,255,255,.4);">
        <h4 style="text-align: center;">Usuarios - Alta</h4>
        <br>
        <h6 style="padding: 10px;">Bien venid@ <?php echo $usu->getNombre()." ".$usu->getApellido().", tenes ".$usu->getMensajesNoLeidos()." mensajes sin leer ..."; ?></h6>
        <form method="post" id="formulario">
		<div class="input-field col s12 m6">
			<input type="text" id="nombre" name="nombre" value="<?php echo $nombre; ?>">
			<label for="nombre">Nombre ...</label>
		</div>
		<div class="input-field col s12 m6">
			<input type="text" id="apellido" name="apellido" value="<?php echo $apellido; ?>">
			<label for="apellido">Apellido ...</label>
		</div>
		<div class="input-field col s12 m6">
			<input type="text" id="dni" name="dni" value="<?php echo $dni; ?>">
			<label for="dni">D.N.I. ...</label>
		</div>
		<div class="input-field col s12 m6">
			<input type="text" id="direccion" name="direccion" value="<?php echo $direccion; ?>">
			<label for="direccion">Dirección ...</label>
		</div>
		<div class="input-field col s12 m6">
			<input type="text" id="telefono" name="telefono" value="<?php echo $telefono; ?>">
			<label for="telefono">Teléfono ...</label>
		</div>
		<div class="input-field col s12 m6">
			<input type="text" id="email" name="email" value="<?php echo $email; ?>">
			<label for="email">E-Mail ...</label>
		</div>
		<div class="input-field col s12 m6">
			<input type="text" id="usuario" name="usuario" value="<?php echo $usuario; ?>">
			<label for="usuario">Usuario ...</label>
		</div>
		<div class="input-field col s12 m6">
			<select id="grupo" name="grupo">
				<?php
				foreach(Db::listar("SELECT * FROM grupos") as $filaGrupo){ ?>
				<option value="<?php echo $filaGrupo['cli_grupo']; ?>" <?php if($grupo==$filaGrupo['cli_grupo']){echo "SELECTED";} ?>><?php echo $filaGrupo['gru_nombre']; ?></option>
				<?php } ?>
			</select>
			<label for="grupo">Grupo ...</label>
		</div>
		<div class="input-field col s12 m6">
			<select id="empresa" name="empresa">
				<?php
				foreach(Db::listar("SELECT * FROM empresas") as $filaEmp){ ?>
				<option value="<?php echo $filaEmp['emp_razon_social']; ?>" <?php if($empresa==$filaEmp['emp_razon_social']){echo "SELECTED";} ?>><?php echo $filaEmp['emp_razon_social']; ?></option>
				<?php } ?>
			</select>
			<label for="grupo">Empresa ...</label>
		</div>
		<div class="input-field col s12 m6">
	          <a id="scale-demo" href="#!" class="btn-floating btn-large scale-transition green">
				 <i id="add" class="material-icons waves-effect waves-light" onclick="document.forms['formulario'].submit();" title="Grabar">send</i>
			  </a>
		</div>
        </form>
        <?php
        //echo $empresa;
        ?>
    </main>
<?php
require_once "footer.php";
?>