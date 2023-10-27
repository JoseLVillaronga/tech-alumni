<?php

	class test{
		
		public function _forXmlRPCHolaMundo($method,$params){
			return "HOLA MUNDO!";
		}
		public function _forXmlRPCChaumundo(){
			return range(1,100);
		}
		public function _forXmlRPCNoticias(){
			$server    = '127.0.0.1';
			$usuario   = 'root';
			$clave     = '';
			$base      = 'noticias_phpescas';
			$port      = '3306';
			$link = new PDO("mysql:host=".$server.";port=".$port.";dbname=".$base.";charset=utf8", $usuario, $clave, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
			$query="SELECT titulo,copete,texto FROM noticias_phpescas.noticias";
			$stmt = $link->prepare($query);
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			$stmt->execute();
			foreach ($stmt as $fila){
				$noti[]=array(
					"Titulo" => $fila['titulo'],
					"Copete" => $fila['copete'],
					"Texto"	 => $fila['texto'],
					);
			}
			return $noti;
		}
	}

?>