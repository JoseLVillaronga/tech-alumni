<?php
require_once 'config.php';
$id=$_GET['curso'];
$usuario=$_GET['usuario'];
$curso=new Curso($id);
$obj=new Inscripcion($curso,$usuario);
if($obj->cancelable==1){
	$obj->borraPorId($obj->getId());
	header("location: cursos.php");
	exit;
	//echo "Se borro !!";
}else{
	header("location: cursos.php");
	exit;
	//echo "No se borro !!";
}
