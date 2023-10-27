<?php
require_once 'config.php';
$id=$_GET['id'];
$obj=new Curso($id);
if($obj->status->getDescripcion()=="Inactivo"){
	$obj->borraPorId($id);
}
header("location: cursos.php");
exit;