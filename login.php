<?php
require_once 'config.php';
if(!empty($_SESSION['usuario'])){
    $usu=new Usuario($_SESSION['usuario']);
}else{
    $usu=new Usuario();
}
if($_SESSION['intento'] > "1"){
	header("location: espera.php");
	exit;
}
if($_SERVER['REQUEST_METHOD']=="POST"){
	$usuario=$_POST['usuario'];
	$password=$_POST['password'];
	$res=Db::listar("SELECT * FROM clientes WHERE cli_usuario = '".$usuario."' AND cli_password = '".Encripta::encrypt($password)."'");
	if(count($res)=="0"){
		$usu->loged=false;
	}else{
		$usu->loged=true;
		$_SESSION['usuario']=$usuario;
	}
	if($usu->loged){
		header("location: index.php");
		exit;
	}
}
require_once 'header.php';
?>
	<main id="content" class="container row">
		<br>
    	<h4>Login</h4>
    	<form method="post" id="formulario">
		<table class="responsive-table">
			<thead>
				<tr>
					<th>Usuario</th>
					<th>Contraseña</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><input placeholder="Usuario" id="usuario" type="text" class="validate" name="usuario" onkeydown="if (event.keyCode == 13) document.getElementById('password').focus();"></td>
					<td><input placeholder="Contraseña" id="password" type="password" class="validate" name="password" onkeydown="if (event.keyCode == 13) document.getElementById('enviar').click();"></td>
				</tr>
			</tbody>
		</table>
			<a id="scale-demo" href="#!" class="btn-floating btn-large scale-transition indigo">
			    <i id="enviar" class="material-icons waves-effect waves-light" onclick="document.forms['formulario'].submit();" title="Enviar">send</i>
			</a>
		</form>
		<?php
		$res2=Db::listar("SELECT * FROM clientes WHERE cli_usuario = '".$usuario."' AND cli_password = '".$password."'");
		echo $_POST['usuario']."<br>".$_POST['password']."<br>";
		//var_dump($_POST['usuario']);
		?>
	</main>
<?php
require_once 'footer.php';
?>