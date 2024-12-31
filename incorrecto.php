<?php
require_once 'config.php';
$_SESSION['Conexion']['Host']="127.0.0.1";
$_SESSION['Conexion']['Port']="3306";
$_SESSION['empresa']="Teccam S.R.L.";
$_SESSION['auth']=200;
$_SESSION['Conexion']['Base']="cdr";
/*if(!empty($_SESSION['usuario'])){
    $usu=new Usuario($_SESSION['usuario']);
}else{
	header("location: login.php");
	exit;
}*/
require_once "header.php";
?>
    <main class="container" id="content" style="background-color: rgba(255,255,255,.4);">
        <h4 style="text-align: center;">Sistema de gestión Teccam</h4>
        <br>
        <h6 style="padding: 10px;">Bien venid@ Invitado<?php //echo $usu->getNombre()." ".$usu->getApellido()." ..."; ?></h6>
        <h5 style="color: red;">Credenciales incorrectas, vuelva a intentar dentro de 30 segundos <a href="login.php?reset=1">aquí ...</a></h5><br><br>
        <?php
        if($_GET['v']==1){
        	echo "<pre>";
			print_r($GLOBALS);
			echo "</pre>";
        }
	echo "<pre>";
	print_r($_SESSION);
	echo "</pre>";
        ?>
    </main>
<?php
require_once "footer.php";
?>
