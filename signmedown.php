<?php
require_once 'config.php';
$id=$_GET['id'];
$curso=new Curso($id);
$obj=new Inscripcion($curso,$_SESSION['usuario']);
if($obj->cancelable==TRUE){
	$obj->borraPorId($obj->getId());
	header("location: miscursos.php");
	exit;
}else{
	header("location: miscursos.php");
	exit;
}
//echo "<pre>";
//print_r($obj);
//echo "</pre>";