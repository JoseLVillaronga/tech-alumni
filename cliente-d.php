<?php
require_once 'config.php';
$id=$_GET['id'];
$obj=new Usuario($id);
if($_SESSION['usuario']!=$obj->getUsuario()){
	$obj->borraPorId($id);
}
header("location: clientes.php");
exit;