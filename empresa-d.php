<?php
require_once 'config.php';
$auth=new Usuario($_SESSION['usuario']);
$id=$_GET['id'];
$obj=new Empresa($id);
$res=Db::listar("SELECT * FROM clientes AS c,empresas AS e WHERE c.cli_razon_social = e.emp_razon_social AND emp_id = ".$id);
if($obj->getRazonSocial()!=$auth->empresa->getRazonSocial() AND $obj->getRazonSocial()!="Cliente final ..." AND count($res)>0){
	$obj->borraPorId($id);
}
header("location: empresas.php");
exit;
