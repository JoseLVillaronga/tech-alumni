<?php
/**
 * @author : José Luis Villaronga
 * @copyright : 2014
 */
class Funcion
{
	static function textoStandard($texto){
		$textoLimpio = preg_replace('([^A-Za-z0-9\-\_])', '', $texto);
		$textoLimpio=str_replace(" ", "", $textoLimpio);
		return strtoupper($textoLimpio);
	}
	static function esMac($mac){
		if(ctype_xdigit($mac) AND strlen($mac)=="12"){
			return TRUE;
		}else{
			return FALSE;
		}
	}
	static function extraeValorNumerico($texto){
		$textoLimpio = preg_replace('([^0-9])', '', $texto);
		return $textoLimpio;
	}
	static function conversorSegundosHoras($tiempo_en_segundos) {
	    $horas = floor($tiempo_en_segundos / 3600);
	    $minutos = floor(($tiempo_en_segundos - ($horas * 3600)) / 60);
	    $segundos = $tiempo_en_segundos - ($horas * 3600) - ($minutos * 60);
	
	    return str_pad($horas, 2, "0", STR_PAD_LEFT).':'.str_pad($minutos, 2, "0", STR_PAD_LEFT).":".str_pad(intval($segundos), 2, "0", STR_PAD_LEFT);
	}
	static function gen_uuid() {
		 $uuid = array(
			  'time_low'  => 0,
			  'time_mid'  => 0,
			  'time_hi'  => 0,
			  'clock_seq_hi' => 0,
			  'clock_seq_low' => 0,
			  'node'   => array()
		 );
	
		 $uuid['time_low'] = mt_rand(0, 0xffff) + (mt_rand(0, 0xffff) << 16);
		 $uuid['time_mid'] = mt_rand(0, 0xffff);
		 $uuid['time_hi'] = (4 << 12) | (mt_rand(0, 0x1000));
		 $uuid['clock_seq_hi'] = (1 << 7) | (mt_rand(0, 128));
		 $uuid['clock_seq_low'] = mt_rand(0, 255);
	
		 for ($i = 0; $i < 6; $i++) {
		  	$uuid['node'][$i] = mt_rand(0, 255);
		 }
	
		 $uuid = sprintf('%08x-%04x-%04x-%02x%02x-%02x%02x%02x%02x%02x%02x',
			  $uuid['time_low'],
			  $uuid['time_mid'],
			  $uuid['time_hi'],
			  $uuid['clock_seq_hi'],
			  $uuid['clock_seq_low'],
			  $uuid['node'][0],
			  $uuid['node'][1],
			  $uuid['node'][2],
			  $uuid['node'][3],
			  $uuid['node'][4],
			  $uuid['node'][5]
		 );
	
	 	return $uuid;
	}
}
