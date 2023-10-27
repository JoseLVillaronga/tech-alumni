<?php
header("Content-Type: text/html; charset=utf-8");
ini_set('max_execution_time', 3600);
ini_set('max_input_time', -1);
ini_set('memory_limit', '1024M');
ini_set("session.cookie_lifetime","43200");
ini_set("session.gc_maxlifetime","43200");
date_default_timezone_set('America/Argentina/Buenos_Aires');
session_start();
error_reporting(E_ALL);
require_once 'PHPExcel/Classes/PHPExcel.php';
require_once 'Net/SSH2.php';
require_once 'Math/BigInteger.php';
require_once 'Crypt/Hash.php';
require_once 'Crypt/Rijndael.php';
require_once 'Crypt/RC4.php';
require_once 'TCPDF/examples/tcpdf_include.php';
require_once 'TCPDF/include/tcpdf_colors.php';
require_once 'TCPDF/include/tcpdf_filters.php';
require_once 'TCPDF/include/tcpdf_font_data.php';
require_once 'TCPDF/include/tcpdf_fonts.php';
require_once 'TCPDF/include/tcpdf_images.php';
require_once 'TCPDF/include/tcpdf_static.php';
require_once 'vendor/autoload.php';
function miAutoCargador($nombreClase){
	require_once ("clases/" . $nombreClase . ".php");
}
spl_autoload_register('miAutoCargador');
require_once "include/geoiploc.php";
if(is_null($_SESSION['paisC'])){
  $ip = $_SERVER['REMOTE_ADDR'];
  $_SESSION['paisC'] = getCountryFromIP($ip,"code");
}
$track=array("/alumni/clientes.php",
			"/alumni/empresas.php",
			"/alumni/cursos.php",
			"/alumni/cliente-e.php",
			"/alumni/cliente-a.php",
			"/alumni/cliente-d.php",
			"/alumni/empresa-e.php",
			"/alumni/empresa-d.php",
			"/alumni/empresa-a.php",
			"/alumni/inscripciones.php",
			"/alumni/calificar.php",
			"/alumni/inscrivir.php",
			"/alumni/desinscrivir.php",
			"/alumni/curso-e.php",
			"/alumni/curso-a.php",
			"/alumni/curso-d.php",
			"/alumni/contenido-e.php",
			"/alumni/visitas.php",
			"/alumni/index.php");
$vi=new Visitas($track);
function movile(){
	if(strstr($_SERVER['HTTP_USER_AGENT'],"Android")){
		return true;
	}elseif(strstr($_SERVER['HTTP_USER_AGENT'],"iphone") OR strstr($_SERVER['HTTP_USER_AGENT'],"iPhone")){
		return true;
	}elseif(strstr($_SERVER['HTTP_USER_AGENT'],"ipod")){
		return true;
	}elseif(strstr($_SERVER['HTTP_USER_AGENT'],"ipad")){
		return true;
	}elseif(strstr($_SERVER['HTTP_USER_AGENT'],"XBLWP7")){
		return true;
	}else{
		return false;
	}
}
function ping($host, $port, $timeout) 
{ 
  $tB = microtime(true); 
  $fP = fSockOpen($host, $port, $errno, $errstr, $timeout); 
  if (!$fP) { return "down"; } 
  $tA = microtime(true); 
  return round((($tA - $tB) * 1000), 0)." ms"; 
}