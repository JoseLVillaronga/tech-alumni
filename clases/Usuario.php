<?php
/**
 * @Author : José Luis Villaronga
 * @copyright : 2014
 */
class Usuario
{
	/**
	 * Propiedades ...
	 */
	private $id;
	private $nombre;
	private $apellido;
	private $rSocial;
	public $empresa;
	private $contacto;
	private $cuit;
	private $direccion;
	private $telefono;
	private $categoria;
	private $email;
	private $web;
	private $usuario;
	private $pass;
	private $admin;
	private $habilitado;
	private $cambPass;
	private $group;
	public $grupo;
	public $aplicacion;
	/**
	 * Array con error de la ultima transacción (INSERT,UPDATE), se puede imprimir con "print_r($this->errorSql)" ...
	 */
	public $errorSql=array();
	/**
	 * String con el ID de la ultima fila insertada (INSERT) ...
	 */
	public $uFilaIns;
	/**
	 * Array con los errores de los setters ...
	 */
	public $errores=array();
	/***************************
	 * Metodo constructor ...  *
	 ***************************/
	public function __construct($user=null){
		if(empty($user)){
			$this->empresa=new Empresa(null);
			$this->grupo=new Grupo(null);
			$this->aplicacion=new Aplicacion(null);
			return $this;
		}elseif(is_numeric($user)){
			$query = "SELECT *
						FROM clientes
						WHERE cli_codigo=$user";
			$lista=(array)Db::listar($query);
			foreach ($lista as $fila){
				$this->id=$fila['cli_codigo'];
				$this->nombre=$fila['cli_nombre'];
				$this->apellido=$fila['cli_apellido'];
				$this->rSocial=$fila['cli_razon_social'];
				$this->empresa=new Empresa($fila['cli_razon_social']);
				$this->contacto=$fila['cli_nombre_contacto'];
				$this->cuit=$fila['cli_cuit'];
				$this->direccion=$fila['cli_direccion'];
				$this->telefono=$fila['cli_telefono'];
				$this->categoria=$fila['cli_categoria'];
				$this->email=$fila['cli_email'];
				$this->web=$fila['cli_web'];
				$this->usuario=$fila['cli_usuario'];
				$this->pass=$fila['cli_password'];
				$this->admin=$fila['cli_admin'];
				$this->habilitado=$fila['cli_habilitado'];
				$this->cambPass=$fila['cli_cambiar_pass'];
				$this->group=$fila['cli_grupo'];
				$this->grupo=new Grupo($fila['cli_grupo']);
				$this->aplicacion=new Aplicacion($fila['cli_usuario']);
			}
		}else{
			$query="SELECT * 
					FROM clientes
					WHERE cli_usuario='$user'";
			$lista=(array)Db::listar($query);
			foreach ($lista as $fila){
				$this->id=$fila['cli_codigo'];
				$this->nombre=$fila['cli_nombre'];
				$this->apellido=$fila['cli_apellido'];
				$this->rSocial=$fila['cli_razon_social'];
				$this->empresa=new Empresa($fila['cli_razon_social']);
				$this->contacto=$fila['cli_nombre_contacto'];
				$this->cuit=$fila['cli_cuit'];
				$this->direccion=$fila['cli_direccion'];
				$this->telefono=$fila['cli_telefono'];
				$this->categoria=$fila['cli_categoria'];
				$this->email=$fila['cli_email'];
				$this->web=$fila['cli_web'];
				$this->usuario=$fila['cli_usuario'];
				$this->pass=$fila['cli_password'];
				$this->admin=$fila['cli_admin'];
				$this->habilitado=$fila['cli_habilitado'];
				$this->cambPass=$fila['cli_cambiar_pass'];
				$this->group=$fila['cli_grupo'];
				$this->grupo=new Grupo($fila['cli_grupo']);
				$this->aplicacion=new Aplicacion($fila['cli_usuario']);
			}
		}
		return $this;
	}
	/**
	 * Getters ..
	 */
	public function getId(){
		return $this->id;
	}
	public function getNombre(){
		return $this->nombre;
	}
	public function getApellido(){
		return $this->apellido;
	}
	public function getRSocial(){
		return $this->empresa->getRazonSocial();
	}
	public function getContacto(){
		return $this->contacto;
	}
	public function getCuit(){
		return $this->cuit;
	}
	public function getDireccion(){
		return $this->direccion;
	}
	public function getTelefono(){
		return $this->telefono;
	}
	public function getCategoria(){
		return $this->categoria;
	}
	public function getEmail(){
		return $this->email;
	}
	public function getWeb(){
		return $this->web;
	}
	public function getUsuario(){
		return $this->usuario;
	}
	public function getPass(){
		return $this->pass;
	}
	public function getAdmin(){
		return $this->admin;
	}
	public function getHabilitado(){
		return $this->habilitado;
	}
	public function getCambPass(){
		return $this->cambPass;
	}
	public function getGrupoId(){
		return $this->grupo->getId();
	}
	public function getGrupo(){
		return $this->grupo->getNombre();
	}
	public function getMensajesNoLeidos(){
		$query="SELECT * FROM mensaje WHERE men_receiver = '".$this->usuario."' AND men_leido = 0";
		$res=Db::listar($query);
		return count($res);
	}
	/**
	 * Setters ...
	 */
	public function setId($nId){
		$this->id=$nId;
	}
	public function setNombre($nNombre){
		if(empty($nNombre)){
			$this->errores['nombre'] = "Hay que ingresar un Nombre ...";
			$this->errores['gen'] = "harError";
		}else{
			$this->nombre=$nNombre;
		}
	}
	public function setApellido($nApellido){
		if(empty($nApellido)){
			$this->errores['apellido'] = "Hay que ingresar un Apellido ...";
			$this->errores['gen'] = "harError";
		}else{
			$this->apellido=$nApellido;
		}
	}
	public function setRSocial($nRSocial){
		$this->rSocial=$nRSocial;
		$this->empresa=new Empresa($this->rSocial);
	}
	public function setContacto($nContacto){
		$this->contacto=$nContacto;
	}
	public function setCuit($nCuit){
		$this->cuit=$nCuit;
	}
	public function setDireccion($nDireccion){
		$this->direccion=$nDireccion;
	}
	public function setTelefono($nTelefono){
		$this->telefono=$nTelefono;
	}
	public function setCategoria($nCategoria){
		$this->categoria=$nCategoria;
	}
	public function setEmail($nEmail){
		if(empty($nEmail)){
			$this->errores['email'] = "Hay que ingresar un E-Mail ...";
			$this->errores['gen'] = "harError";
		}else{
			$this->email=$nEmail;
		}
	}
	public function setWeb($nWeb){
		$this->web=$nWeb;
	}
	public function setUsuario($nUsuario){
		if(empty($nUsuario)){
			$this->errores['usuario'] = "Hay que ingresar un Usuario ...";
			$this->errores['gen'] = "harError";
		}else{
			$this->usuario=$nUsuario;
		}
	}
	public function setPass($nPass){
		$this->pass=Encripta::encrypt($nPass);
	}
	public function setAdmin($nAdmin){
		$this->admin=$nAdmin;
	}
	public function setHabilitado($nHabilitado){
		$this->habilitado=$nHabilitado;
	}public function setCambPass($nCambPass){
		$this->cambPass=$nCambPass;
	}
	public function setGrupo($nGrupo){
		$this->group=$nGrupo;
		$this->grupo=new Grupo($this->group);
	}
	/**
	 * Actualiza la tabla "clientes" con las propiedades del Objeto ...
	 */
	public function actualizaDb(){
		$con=Conexion::conectar();
		$query = "UPDATE `clientes`
					SET
					`cli_nombre` = :nombre,
					`cli_apellido` = :apellido,
					`cli_razon_social` = :rsocial,
					`cli_nombre_contacto` = :contacto,
					`cli_cuit` = :cuit,
					`cli_direccion` = :direccion,
					`cli_telefono` = :telefono,
					`cli_categoria` = :categoria,
					`cli_email` = :email,
					`cli_web` = :web,
					`cli_usuario` = :usuario,
					`cli_password` = :pass,
					`cli_admin` = :admin,
					`cli_habilitado` = :hab,
					`cli_cambiar_pass` = :cpass,
					`cli_grupo` = :grupo
					WHERE `cli_codigo` = :id";
		$stmt = $con -> prepare($query);
		$rs=$this->empresa->getRazonSocial();
		$gr=$this->grupo->getId();
		$stmt->bindParam(':nombre', $this->nombre, PDO::PARAM_STR);
		$stmt->bindParam(':apellido', $this->apellido, PDO::PARAM_STR);
		$stmt->bindParam(':rsocial', $rs, PDO::PARAM_STR);
		$stmt->bindParam(':contacto', $this->contacto, PDO::PARAM_STR);
		$stmt->bindParam(':cuit', $this->cuit, PDO::PARAM_STR);
		$stmt->bindParam(':direccion', $this->direccion, PDO::PARAM_STR);
		$stmt->bindParam(':telefono', $this->telefono, PDO::PARAM_STR);
		$stmt->bindParam(':categoria', $this->categoria, PDO::PARAM_STR);
		$stmt->bindParam(':email', $this->email, PDO::PARAM_STR);
		$stmt->bindParam(':web', $this->web, PDO::PARAM_STR);
		$stmt->bindParam(':usuario', $this->usuario, PDO::PARAM_STR);
		$stmt->bindParam(':pass', $this->pass, PDO::PARAM_STR);
		$stmt->bindParam(':admin', $this->admin, PDO::PARAM_INT);
		$stmt->bindParam(':hab', $this->habilitado, PDO::PARAM_INT);
		$stmt->bindParam(':cpass', $this->cambPass, PDO::PARAM_INT);
		$stmt->bindParam(':grupo', $gr, PDO::PARAM_INT);
		$stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
	}
	/**
	 * Inserta nuevo registro a la tabla "clientes" usando las propiedades del Objeto ...
	 */
	public function agregaADb(){
		$con=Conexion::conectar();
		$query="INSERT INTO clientes
				(cli_codigo,
				cli_nombre,
				cli_apellido,
				cli_razon_social,
				cli_nombre_contacto,
				cli_cuit,
				cli_direccion,
				cli_telefono,
				cli_categoria,
				cli_email,
				cli_web,
				cli_usuario,
				cli_password,
				cli_admin,
				cli_habilitado,
				cli_cambiar_pass,
				cli_grupo)
				VALUES
				(null,
				:nombre,
				:apellido,
				:rsocial,
				:contacto,
				:cuit,
				:direccion,
				:telefono,
				:categoria,
				:email,
				:web,
				:usuario,
				:pass,
				:admin,
				:hab,
				:cpass,
				:grupo)";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
		$stmt->bindParam(':apellido', $apellido, PDO::PARAM_STR);
		$stmt->bindParam(':rsocial', $rs, PDO::PARAM_STR);
		$stmt->bindParam(':contacto', $contacto, PDO::PARAM_STR);
		$stmt->bindParam(':cuit', $cuit, PDO::PARAM_STR);
		$stmt->bindParam(':direccion', $direccion, PDO::PARAM_STR);
		$stmt->bindParam(':telefono', $telefono, PDO::PARAM_STR);
		$stmt->bindParam(':categoria', $categoria, PDO::PARAM_STR);
		$stmt->bindParam(':email', $email, PDO::PARAM_STR);
		$stmt->bindParam(':web', $wweb, PDO::PARAM_STR);
		$stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
		$stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
		$stmt->bindParam(':admin', $admin, PDO::PARAM_INT);
		$stmt->bindParam(':hab', $habilit, PDO::PARAM_INT);
		$stmt->bindParam(':cpass', $cPas, PDO::PARAM_INT);
		$stmt->bindParam(':grupo', $gr, PDO::PARAM_INT);
		$nombre=$this->getNombre();
		$apellido=$this->getApellido();
		$rs=$this->empresa->getRazonSocial();
		$gr=$this->grupo->getId();
		$contacto=$this->getContacto();
		$cuit=$this->getCuit();
		$direccion=$this->getDireccion();
		$telefono=$this->getTelefono();
		$categoria=$this->getCategoria();
		$email=$this->getEmail();
		$wweb=$this->getWeb();
		$usuario=$this->getUsuario();
		$pass=$this->getPass();
		$admin=$this->getAdmin();
		$habilit=$this->getHabilitado();
		$cPas=$this->getCambPass();
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
		$this->uFilaIns=$con->lastInsertId();
		$this->setId($this->uFilaIns);
	}
	/**
	 * Borra registro de la tabla "clientes" filtrado por ID ...
	 */
	public function borraPorId($ID){
		if(!isset($ID)){
			return $this;
		}
		$con=Conexion::conectar();
		$query = "DELETE FROM clientes WHERE cli_codigo = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $ID, PDO::PARAM_STR);
		$stmt -> execute();
		$this->uFilaIns=$con->lastInsertId();
	}
	public function acl(){
		$path=$_SERVER['SCRIPT_NAME'];
		$check=array();
		foreach($this->aplicacion->path as $fila){
			$check[]=$fila['path'];
		}
		if(in_array($path, $check)){
			return TRUE;
		}else{
			return FALSE;
		}
	}
}
