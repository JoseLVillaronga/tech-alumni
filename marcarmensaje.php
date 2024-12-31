<?php
require_once 'config.php';
$id=$_GET['id'];
$mensaje=new Mensaje($id);
$mensaje->setLeido(1);
$mensaje->actualizaDb();
header("location: mensajes.php");
exit;