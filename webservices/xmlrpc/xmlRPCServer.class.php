<?php

	class xmlRPCServer{
		
		private	$_server	=	NULL;
		private	$_object	=	NULL;

		public function __construct(){
			
			if(!function_exists("xmlrpc_server_create")){
				
				throw(new Exception("Error, debe cargar el modulo xmlrpc antes de poder utilizar esta clase"));
				
			}
			
			$this->_server	=	xmlrpc_server_create();
			
		}
		
		public function addObject($obj){
			
			if(!is_object($obj)){
				throw(new Exception("Debe pasar un objeto como argumento de addObject"));
			}
			
			$this->_object	=	$obj;
			
		}
		
		public function start(){
			
			if(is_null($this->_object)){

				throw(new Exception("Debe llamar a el metodo addObject antes de poder iniciar el servidor XML RPC"));

			}
			
			$methods	=	$this->listMethods();

			foreach($methods as $method){
				
				xmlrpc_server_register_method($this->_server, $method["xmlMethod"],array($this->_object,$method["method"])); 

			}

			//Funcion anonima (LAMBDA) no dar importancia al error de sintaxis de netbeans!!
			
			$lambda	=	function($_methods){
				return implode(',',$_methods);
			};

			xmlrpc_server_register_method($this->_server,"list",Array($this,"listMethodsForXml"));
			
			$request		=	$GLOBALS["HTTP_RAW_POST_DATA"];

			$response	=	xmlrpc_server_call_method($this->_server, $request,NULL);
			
			if(headers_sent()){
				
				throw(new Exception("Error, encabezados HTTP ya enviados!"));
				
			}
			
			header('Content-Type: text/xml');

			echo $response;

		}

		public function listMethods(){
			
			$methods				=	get_class_methods($this->_object);
			$methodContainer	=	Array();
			
			foreach($methods as $method){
				
				$method		=	strtolower($method);
				
				if(!preg_match("/^_forxmlrpc/",$method)){

					continue;

				}

				$xmlMethod				=	substr($method,strlen("_forxmlrpc"));
				
				$methodContainer[]	=	Array(
															"method"		=>	$method,
															"xmlMethod"	=>	$xmlMethod
				);
				
			}
			
			return $methodContainer;
			
		}
		
		public function listMethodsForXML(){

			$methods				=	$this->listMethods();
			$methodContainer	=	Array();
			
			foreach($methods as $method){
				$methodContainer[]	=	$method["xmlMethod"];
			}

			return $methodContainer;
			
		}

		public function __unset($a){

			xmlrpc_server_destroy($this->_server); 

		}

	}

?>