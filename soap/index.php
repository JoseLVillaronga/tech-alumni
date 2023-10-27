<?php
require_once "../config.php";
$_SESSION['empresa']="2";
if($_SERVER['SERVER_NAME']=="www.teccam.net"){
	$_SESSION['paisC']="AR";
	$_SESSION['Conexion']['Host']="190.188.168.144";
	$_SESSION['Conexion']['Port']="63406";
}else{
	$_SESSION['paisC']="ZZ";
	$_SESSION['Conexion']['Host']="127.0.0.1";
	$_SESSION['Conexion']['Port']="3306";
}
if($_SESSION['empresa'] == "7"){
	$_SESSION['Conexion']['Base']="test";
}elseif($_SESSION['empresa'] == "36"){
	$_SESSION['Conexion']['Base']="uy";
}else{
	$_SESSION['Conexion']['Base']="cdr";
}

	//tested using SoapUI version 4.5.2
	//tested eith php wsdl_client.php
	//tested eith c# visual studio 2010
	//complex data type not supported yet.
	//only support integer string and float data type.
		//just include service class to use it
	
	//implement function body 
//	function testurl($data){	
//		return 'well hello '.$data;
//	}
	//implement function body 
//	function test2($name,$age){
//		if(!isset($age)){
//			return 'fuck ya';
//		}
//		if(is_numeric($age)){
//			return 'hello ' . $name . " I know you are $age years old"; 
//		}else{
//			return 'fuck';
//		}
//	}
	function loguin($auth){
		$login=json_decode($auth);
		$usuario=$login->username;
		$email=$login->email;
		$queryA="SELECT * FROM clientes WHERE cli_usuario = '$usuario' AND cli_email = '$email' AND cli_habilitado = 0";
		$pedo=Db::listar($queryA);
		foreach($pedo as $pepe){
			$caca[]=$pepe['cli_usuario'];
		}

		if(in_array($usuario, $caca)){
			return TRUE;
		}else{
			return FALSE;
		}
	}
	function test($username){
		return "Hola '$username!!!' la conexión es exitosa ...";
	}
	function cmPorMac($mac, $auth){
		if(loguin($auth)){
			if(!empty($mac)){
				$login=json_decode($auth);
				$usuario=$login->username;
				$cli=new Usuario($usuario);
				$emp=$cli->empresa->getId();
				$query="SELECT rep_id 
						FROM reparacion 
						WHERE rep_mac = '$mac'";
				if($cli->empresa->getId()=="2" OR $cli->empresa->getId()=="20"){
					if($emp=="20"){
						$query.=" AND art_id IN(SELECT articulos.art_id FROM articulos, marca WHERE articulos.marca_id = marca.marca_id AND articulos.marca_id IN (1, 24, 23))";
					}
				}else{
					$query.=" AND emp_id = $emp";
				}
				$query.=" ORDER BY rep_id 
						DESC LIMIT 1";
				$res=Db::listar($query);
				if(!empty($res[0]['rep_id'])){
					$obj=new Reparacion($res[0]['rep_id']);
					$cm=array(
						"O.T. ID"=>$obj->getId(),
						"Articulo"=>$obj->articulo->getNombre()." ".$obj->articulo->modelo->marca->getNombre()." ".$obj->articulo->modelo->getNombre(),
						"Nro. Serie"=>$obj->getSerie(),
						"Nro. MAC"=>$obj->getMac(),
						"Part Nro."=>$obj->getPartN(),
						"Dueño"=>$obj->empresa->getRazonSocial(),
						"Observaciones"=>$obj->getObservaciones(),
						"Falla"=>$obj->falla->getNombre(),
						"O.T. Fecha Inicio"=>$obj->getFechaInicio(),
					);
					return json_encode($cm);
				}else{
					return "MAC no encontrada ...";
				}

			}else{
				return "Ingrese MAC ...";
			}
		}else{
			return "No está autenticado ...";
		}

	}
	$e=new SSoap('Teccam S.R.L. Web Service');	//your service name here as argument
	
/*	$e->register(
				'testurl',	//function name of the service
				array(		//input arguments of the function as name=>type 
					'username'=>'string',
				),
				array(		//output arguments of the function as name=>type
					'return'=>'str'
				),
				'this function suppose to be a test'
	);*/
		//define another service
/*	$e->register(
				'test2',
				array(
					'username'=>'stRing',
					'age'=>'float'
				),
				array(
					'return'=>'str'
				),
				'this is another test'
	);*/
	$e->register(
				'test',	//function name of the service
				array(		//input arguments of the function as name=>type 
					'username'=>'string',
				),
				array(		//output arguments of the function as name=>type
					'return'=>'str'
				),
				'función "test($username)" donde "$username" es un nombre de usuario de tipo "string", esto devuelve un "string" con lo siguiente "Hola \'$username!!!\' la conexión es exitosa ..." ... '
	);
	$e->register(
				'cmPorMac',	//function name of the service
				array(		//input arguments of the function as name=>type 
					'mac'=>'string',
					'usuario'=>'string',
					'password'=>'string',
				),
				array(		//output arguments of the function as name=>type
					'return'=>'str'
				),
				'función "cm($mac,$auth)" donde "$mac" es un Nro. de MAC de tipo "string", "$auth es un JSON conteniendo "username" y "email" necesarios para autenticarse, esto devuelve un "string" conteniendo JSON con lista de datos del cablemodem ... '
	);
	
	$e->handle();		//call this method to start service handle
?>