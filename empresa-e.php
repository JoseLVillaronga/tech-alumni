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
	$obj=new Empresa($id);
	$razonSocial=$_POST['razonSocial'];
	$cuit=$_POST['cuit'];
	$direccion=$_POST['direccion'];
	$telefono=$_POST['telefono'];
	$email=$_POST['email'];
	$url=$_POST['url'];
	$contacto=$_POST['contacto'];
	$contactoTel=$_POST['contactoTel'];
	$contactoEmail=$_POST['contactoEmail'];
	$obj->setRazonSocial($razonSocial);
	$obj->setCuit($cuit);
	$obj->setDireccion($direccion);
	$obj->setTelefono($telefono);
	$obj->setEmail($email);
	$obj->setWeb($url);
	$obj->setContactoComercial($contacto);
	$obj->setContactoComercialTel($contactoTel);
	$obj->setContactoComercialEmail($contactoEmail);
	$obj->actualizaDb();
	if($obj->errorSql['0']=="00000"){
		header("location: empresas.php");
		exit;
	}
}elseif($_SERVER['REQUEST_METHOD']=="GET"){
	$id=$_GET['id'];
	$obj=new Empresa($id);
	$razonSocial=$obj->getRazonSocial();
	$cuit=$obj->getCuit();
	$direccion=$obj->getDireccion();
	$telefono=$obj->getTelefono();
	$email=$obj->getEmail();
	$url=$obj->getWeb();
	$contacto=$obj->getContactoComercial();
	$contactoTel=$obj->getContactoComercialTel();
	$contactoEmail=$obj->getContactoComercialEmail();
}
require_once "header.php";
?>
    <main class="container row" id="content" style="background-color: rgba(255,255,255,.4);">
        <h4 style="text-align: center;">Clientes - Edición</h4>
        <br>
        <h6 style="padding: 10px;">Bien venid@ <?php echo $usu->getNombre()." ".$usu->getApellido().", tenes ".$usu->getMensajesNoLeidos()." mensajes sin leer ..."; ?></h6>
        <form method="post" id="formulario">
		<div class="input-field col s12 m6">
			<input type="text" id="razonSocial" name="razonSocial" value="<?php echo $razonSocial; ?>">
			<label for="razonSocial">Razon Social ...</label>
			<input type="hidden" name="id" value="<?php echo $id; ?>">
		</div>
		<div class="input-field col s12 m6">
			<input type="text" id="cuit" name="cuit" value="<?php echo $cuit; ?>">
			<label for="cuit">C.U.I.T. ...</label>
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
			<input type="text" id="url" name="url" value="<?php echo $url; ?>">
			<label for="url">URL ...</label>
		</div>
		<div class="input-field col s12 m6">
			<input type="text" id="contacto" name="contacto" value="<?php echo $contacto; ?>">
			<label for="contacto">Contacto ...</label>
		</div>
		<div class="input-field col s12 m6">
			<input type="text" id="contactoTel" name="contactoTel" value="<?php echo $contactoTel; ?>">
			<label for="contactoTel">Teléfono contacto ...</label>
		</div>
		<div class="input-field col s12 m6">
			<input type="text" id="contactoEmail" name="contactoEmail" value="<?php echo $contactoEmail; ?>">
			<label for="contactoEmail">E-Mail contacto ...</label>
		</div>
		<div class="input-field col s12 m6">
	          <a id="scale-demo" href="#!" class="btn-floating btn-large scale-transition green">
				 <i id="add" class="material-icons waves-effect waves-light" onclick="document.forms['formulario'].submit();" title="Grabar">send</i>
			  </a>
        <?php
        //echo $obj->errorSql['0']=="00000";
        //echo "<pre>";
        //var_dump($obj);
		//echo "</pre>";
        ?>
		</div>
        </form>

    </main>
<?php
require_once "footer.php";
?>