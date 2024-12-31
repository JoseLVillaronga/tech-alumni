<?php
require_once 'config.php';
//session_start();
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
$_SESSION['Key']=$_ENV['Key'];
$_SESSION['DB_CONNECTION']=$_ENV['DB_CONNECTION'];
$_SESSION['DB_HOST']=$_ENV['DB_HOST'];
$_SESSION['DB_PORT']=$_ENV['DB_PORT'];
$_SESSION['DB_DATABASE']=$_ENV['DB_DATABASE'];
$_SESSION['DB_USERNAME']=$_ENV['DB_USERNAME'];
$_SESSION['DB_PASSWORD']=$_ENV['DB_PASSWORD'];
$realm = 'Teccam S.R.L.';  

if($_GET['reset']=="1" AND empty($_COOKIE['count'])){
	$_SERVER['PHP_AUTH_DIGEST']="";
}

if (empty($_COOKIE['count'])) {
	   setcookie('count',1,time()+30);
}
//$_SERVER['PHP_AUTH_DIGEST']="";
$realm = 'Restricted area';
//user => password
$res1=Db::listar("SELECT * FROM alumni.clientes");  
$users1 = array('admin'=>'pepe','guest'=>'guest');
$users = "{\"".$res1[0]['cli_usuario']."\":\"".Encripta::decrypt($res1[0]['cli_password'])."\"";
foreach ($res1 as $key => $fila1){
	if($key > 0){
		$users.=",\"".$fila1['cli_usuario']."\":\"".Encripta::decrypt($fila1['cli_password'])."\"";
	}
}
$users.="}";
$users=json_decode($users,true);
if (empty($_SERVER['PHP_AUTH_DIGEST'])) {
	$_SERVER['HTTP_ACCEPT_LANGUAGE']="es";
	header('HTTP/1.1 401 Unauthorized');
	header('WWW-Authenticate: Digest realm="' . $realm . '",qop="auth",nonce="' . uniqid() . '",opaque="' . md5($realm) . '"');
	die('<br><br><br><br><br><br><br><br><p style="font-weight: bold;font-size: 2em;text-align: center;font-family: sans-serif;">Vuelva a loguearse <a href="login.php?reset=1">aqui ...</a></p>');
}
// analyze the PHP_AUTH_DIGEST variable
if (!($data = http_digest_parse($_SERVER['PHP_AUTH_DIGEST'])) || !isset($users[$data['username']]))
	die('<p style="font-size: 2em;">Credenciales incorrectas, volver a intentar <a href="logout.php">aquí ...</a></p><script>location.href = "incorrecto.php?reset=1"</script>');
// generate the valid response
$A1 = md5($data['username'] . ':' . $realm . ':' . $users[$data['username']]);
$A2 = md5($_SERVER['REQUEST_METHOD'] . ':' . $data['uri']);
$valid_response = md5($A1 . ':' . $data['nonce'] . ':' . $data['nc'] . ':' . $data['cnonce'] . ':' . $data['qop'] . ':' . $A2);
if ($data['response'] != $valid_response)
	die('<p style="font-size: 2em;">Credenciales incorrectas, volver a intentar <a href="logout.php">aquí ...</a></p><script>location.href = "incorrecto.php"</script>');
// ok, valid username & password
$_SESSION['usuario']=$data['username'];
setcookie('usuario',$data['username'],time()+3600*12);
//$_COOKIE['usuario']=$data['username'];
echo 'You are logged in as: ' . $_SESSION['usuario'];
//session_start();

header("location: index.php");
exit;
// function to parse the http auth header
function http_digest_parse($txt) {
	// protect against missing data
	$needed_parts = array('nonce' => 1, 'nc' => 1, 'cnonce' => 1, 'qop' => 1, 'username' => 1, 'uri' => 1, 'response' => 1);
	$data = array();
	$keys = implode('|', array_keys($needed_parts));
	preg_match_all('@(' . $keys . ')=(?:([\'"])([^\2]+?)\2|([^\s,]+))@', $txt, $matches, PREG_SET_ORDER);
	foreach ($matches as $m) {
		$data[$m[1]] = $m[3] ? $m[3] : $m[4];
		unset($needed_parts[$m[1]]);
	}
	return $needed_parts ? false : $data;
}
?>
