<?php
require_once 'config.php';
$id=$_GET['id'];
$curso=new Curso($id);
$obj=new Inscripcion($curso,$_SESSION['usuario']);
if($curso->docente->getUsuario()!=$_SESSION['usuario'] AND $obj->inscrivible==TRUE){
	$obj->agregaADb();
	header("location: miscursos.php");
	exit;
}else{
	header("location: index.php");
	exit;
}
//echo "<pre>";
//print_r($obj);
//echo "</pre>";