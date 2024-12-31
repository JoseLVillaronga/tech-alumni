<?php
require_once 'config.php';
$titulo = "Cierre de sesión";
//if($_GET['r']=="yes!"){$_SESSION['incorectLogin']=0;}
//$volver=$_SESSION['redirect'];
//header('HTTP/1.1 401 Unauthorized');
//$_SERVER['PHP_AUTH_DIGEST']="";
//$realm = 'Teccam S.R.L.';
//Header("WWW-Authenticate: Basic realm=\"".$realm."\"");
//header('HTTP/1.1 401 Unauthorized');
setcookie('usuario',$data['username'],time()-3600);
setcookie('count',$data['username'],time()-3600);
session_destroy();

header("location: login.php?reset=1");
exit;