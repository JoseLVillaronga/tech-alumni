<?php
require_once "config.php";
if(!empty($_SESSION['usuario'])){
    $usu=new Usuario($_SESSION['usuario']);
}else{
	header("location: login.php");
	exit;
}
if($_SERVER['REQUEST_METHOD']=="POST"){
	$data=$_POST['data'];
	$decoded = str_rot13($data);
	$resultado=json_decode($decoded,TRUE);
	$resultado=(array)$resultado;
	$actualPass=Encripta::decrypt($usu->getPass());
	if(count($resultado) > 1 AND strlen($resultado['Nueva']) > 7 AND strlen($resultado['Confirmacion']) > 7 AND $actualPass == $resultado['Contrasena']){
		$nuevaContrass=Encripta::encrypt($resultado['Confirmacion']);
		$query="UPDATE `auth`.`clientes`
				SET
				`cli_password` = '".$nuevaContrass."'
				WHERE `cli_codigo` = ".$usu->getId();
		$con=Conexion::conectar();
		$stmt = $con->prepare($query);
		$stmt->execute();
		$errorSql = $stmt->errorInfo();
		if($errorSql['0'] == "00000"){
			$scriptFinal="document.getElementById('errorValidacion').textContent = 'Se cambio la contraseña correctamente ...';\n";
		}else{
			$scriptFinal="document.getElementById('errorValidacion').textContent = 'No se pudo cambiar la contraseña ...';\n";
		}
	}else{
		$scriptFinal="document.getElementById('errorValidacion').textContent = 'No se pudo cambiar la contraseña * ".$actualPass." ".$resultado['Contrasena']." ...';\n";
	}
	//Datos de usuario
	$id=$_POST['id'];
	$obj=new Usuario($id);
	$nombre=$_POST['nombre'];
	$apellido=$_POST['apellido'];
	$dni=$_POST['dni'];
	$direccion=$_POST['direccion'];
	$telefono=$_POST['telefono'];
	$email=$_POST['email'];
	$usuario=$_POST['usuario'];
	$grupo=$_POST['grupo'];
	$empresa=$_POST['empresa'];
	//$pass=$_POST['pass'];
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
	//$obj->setPass($pass);
	$obj->actualizaDb();
	if($obj->errorSql['0']=="00000"){
		header("location: index.php");
		exit;
	}
}elseif($_SERVER['REQUEST_METHOD']=="GET"){
	$usuario=$_SESSION['usuario'];	
	$obj=new Usuario($usuario);
	$id=$obj->getId();
	$nombre=$obj->getNombre();
	$apellido=$obj->getApellido();
	$dni=$obj->getCuit();
	$direccion=$obj->getDireccion();
	$telefono=$obj->getTelefono();
	$email=$obj->getEmail();
	$grupo=$obj->grupo->getId();
	$empresa=$obj->empresa->getRazonSocial();
	$pass=Encripta::decrypt($obj->getPass());
}
require_once "header.php";
?>
    <main class="container" id="content" style="background-color: rgba(255,255,255,.4);">
        <h4 style="text-align: center;">Mis Datos</h4>
        <h6 style="padding: 10px;">Bien venid@ <?php echo $usu->getNombre()." ".$usu->getApellido().", tenes ".$usu->getMensajesNoLeidos()." mensajes sin leer ..."; ?></h6>
        <form method="post" id="formulario">
        <div class="row">
			<div class="input-field col s12 m6">
				<input type="text" id="nombre" name="nombre" value="<?php echo $nombre; ?>">
				<label for="nombre">Nombre ...</label>
				<input type="hidden" name="id" value="<?php echo $id; ?>">
				<input type="hidden" name="usuario" value="<?php echo $usuario; ?>">
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
			<div class="input-field col s12 m6" style="display: none;">
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
			<div class="input-field col s12 m12">
				<div class="input-field col s12 m6" style="text-align: center;">
			          <a id="scale-demo" href="#!" class="btn-floating btn-large scale-transition green">
						 <i id="add" class="material-icons waves-effect waves-light" onclick="document.forms['formulario'].submit();" title="Grabar">send</i>
					  </a>
				</div>
				<div class="input-field col s12 m6" style="text-align: center;">
				      <a id="scale-demo" href="#!" class="btn-floating btn-large scale-transition indigo">
						 <i id="add" class="material-icons waves-effect waves-light" onclick="location.href='index.php'" title="Inicio">arrow_back</i>
					  </a>
				<?php //echo "Hola !!!<br><pre>";print_r($obj->errores);echo "</pre>"; ?>
				</div>
			</div>
		</div>
		
		<!--Cambio contraseña -->
		
        <section style="width: 98%;background-color: rgba(0,0,0,.1);padding: 1em;margin: auto;border-top: solid 1px #9fa8da;">
        	<h5>Cambiar contraseña</h5>
        	
        	<input type="hidden" name="data" id="data" />
        	<input type="hidden" id="usuario" value="<?php echo $usu->getUsuario(); ?>" >
        	<div class="row">
				<div class="input-field col s12 l4">
					<input type="password" id="password">
					<label for="password">Contraseña actual ...</label>
				</div>
				<div class="input-field col s12 l4">
					<input type="password" id="nueva">
					<label for="nueva">Nueva contraseña ...</label>
				</div>
				<div class="input-field col s12 l4">
					<input type="password" id="confirmacion">
					<label for="confirmacion">Confirmar contraseña ...</label>
				</div>
        	</div>
        	<div style="text-align: center;">
			  <a class="btn-floating btn-large waves-effect waves-light red" title="Canbiar contraseña" onclick="encriptarMensaje2();"><i class="material-icons">send</i></a>
			  <a class="btn-floating btn-large waves-effect waves-light indigo" title="Mostrar contraseñas ..." onclick="muestra();"><i class="material-icons">remove_red_eye</i></a>
			</div>
			<div style="height: 20px;"></div>
			<div id="errorValidacion" style="text-align: center;font-size: 1.6em;color: red;"></div>
        	<div style="height: 20px;"></div>
        	<cite style="background-color: rgba(0,0,0,.1);">
        		Las contraseñas deben tener al menos 8 caracteres ...
        	</cite>
        	
        </section>
        <br>
        </form>
        <?php
        //echo $empresa;
        ?>
    </main>
<?php
require_once "footer.php";
?>