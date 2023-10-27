<?php
require_once 'config.php';
$titulo = "Cierre de sesión";
//$volver=$_SESSION['redirect'];
session_destroy();
header("location: index.php");
exit;