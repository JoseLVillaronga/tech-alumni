<?php
/**
 * @Author : José Luis Villaronga
 * @copyright : 2014
 */
class Empresa
{
	/**
	 * Propiedades ...
	 */
	private $id;
	private $razonSocial;
	private $cuit;
	private $direccion;
	private $telefono;
	private $categoria;
	private $email;
	private $web;
	private $contactoComercial;
	private $contactoComercialTel;
	private $contactoComercialEmail;
	private $contactoAdministrativo;
	private $contactoAdministrativoTel;
	private $contactoAdministrativoEmail;
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
	/**
	 * Metodo constructor ...
	 */
	public function __construct($rSocial=null){
		if(empty($rSocial)){
			return $this;
		}elseif(is_numeric($rSocial)){
			foreach (Db::listaEmpresaId($rSocial) as $fila){
				$this->id=$fila['emp_id'];
				$this->razonSocial=$fila['emp_razon_social'];
				$this->cuit=$fila['emp_cuit'];
				$this->direccion=$fila['emp_direccion'];
				$this->telefono=$fila['emp_telefono'];
				$this->categoria=$fila['emp_categoria'];
				$this->email=$fila['emp_email'];
				$this->web=$fila['emp_web'];
				$this->contactoComercial=$fila['emp_contacto_comercial'];
				$this->contactoComercialTel=$fila['emp_tel_cc'];
				$this->contactoComercialEmail=$fila['emp_email_cc'];
				$this->contactoAdministrativo=$fila['emp_contacto_administrativo'];
				$this->contactoAdministrativoTel=$fila['emp_tel_ca'];
				$this->contactoAdministrativoEmail=$fila['emp_email_ca'];
			}
			return $this;
		}
		foreach (Db::listaEmpresaPorNombre($rSocial) as $fila){
			$this->id=$fila['emp_id'];
			$this->razonSocial=$fila['emp_razon_social'];
			$this->cuit=$fila['emp_cuit'];
			$this->direccion=$fila['emp_direccion'];
			$this->telefono=$fila['emp_telefono'];
			$this->categoria=$fila['emp_categoria'];
			$this->email=$fila['emp_email'];
			$this->web=$fila['emp_web'];
			$this->contactoComercial=$fila['emp_contacto_comercial'];
			$this->contactoComercialTel=$fila['emp_tel_cc'];
			$this->contactoComercialEmail=$fila['emp_email_cc'];
			$this->contactoAdministrativo=$fila['emp_contacto_administrativo'];
			$this->contactoAdministrativoTel=$fila['emp_tel_ca'];
			$this->contactoAdministrativoEmail=$fila['emp_email_ca'];
		}
		return $this;
	}
	/**
	 * Getters ..
	 */
	public function getId(){
		return $this->id;
	}
	public function getRazonSocial(){
		return $this->razonSocial;
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
	public function getContactoComercial(){
		return $this->contactoComercial;
	}
	public function getContactoComercialTel(){
		return $this->contactoComercialTel;
	}
	public function getContactoComercialEmail(){
		return $this->contactoComercialEmail;
	}
	public function getContactoAdministrativo(){
		return $this->contactoAdministrativo;
	}
	public function getContactoAdministrativoTel(){
		return $this->contactoAdministrativoTel;
	}
	public function getContactoAdministrativoEmail(){
		return $this->contactoAdministrativoEmail;
	}
	/**
	 * Setters ...
	 */
	public function setId($nId){
		$this->id=$nId;
	}
	public function setRazonSocial($nRazonSocial){
		if(empty($nRazonSocial)){
			$this->razonSocial['rsocial'] = "Debe ingresar Razon Social ...";
			$this->errores['gen'] = "harError";
		}else{
			$this->razonSocial=$nRazonSocial;
		}
	}
	public function setCuit($nCuit){
		if(empty($nCuit)){
			$this->cuit['cuit'] = "Debe ingresar un C.U.I.T. ...";
			$this->errores['gen'] = "harError";
		}else if(strlen($nCuit) < 13){
			$this->cuit['cuit'] = "El C.U.I.T. debe tener al menos 13 caracteres ...";
			$this->errores['gen'] = "harError";
		}else if(strlen($nCuit) > 13){
			$this->cuit['cuit'] = "El C.U.I.T. no debe superar los 13 caracteres ...";
			$this->errores['gen'] = "harError";
		}else{
			$this->cuit=$nCuit;
		}
	}
	public function setDireccion($nDireccion){
		if(empty($nDireccion)){
			$this->errores['direccion'] = "Debe ingresar una dirección ...";
			$this->errores['gen'] = "harError";
		}else{
			$this->direccion=$nDireccion;
		}
	}
	public function setTelefono($nTelefono){
		if(empty($nTelefono)){
			$this->errores['telefono'] = "Debe ingresar un teléfono ...";
			$this->errores['gen'] = "harError";
		}else{
			$this->telefono=$nTelefono;
		}
	}
	public function setCategoria($nCategoria){
		$this->categoria=$nCategoria;
	}
	public function setEmail($nEmail){
		if(empty($nEmail)){
			$this->errores['email'] = "Debe ingresar un E-Mail ...";
			$this->errores['gen'] = "harError";
		}else{
			$this->email=$nEmail;
		}
	}
	public function setWeb($nWeb){
		$this->web=$nWeb;
	}
	public function setContactoComercial($nCC){
		$this->contactoComercial=$nCC;
	}
	public function setContactoComercialTel($nCCT){
		$this->contactoComercialTel=$nCCT;
	}
	public function setContactoComercialEmail($nCCE){
		$this->contactoComercialEmail=$nCCE;
	}
	public function setContactoAdministrativo($nCA){
		$this->contactoAdministrativo=$nCA;
	}
	public function setContactoAdministrativoTel($nCAT){
		$this->contactoAdministrativoTel=$nCAT;
	}
	public function setContactoAdministrativoEmail($nCAE){
		$this->contactoAdministrativoEmail=$nCAE;
	}
	/**
	 * Actualiza la tabla "clientes" con las propiedades del Objeto ...
	 */
	public function actualizaDb(){
		$con=Conexion::conectar();
		$query = "UPDATE `cdr`.`empresas`
					SET
					`emp_razon_social` = :rSocial,
					`emp_cuit` = :cuit,
					`emp_direccion` = :direccion,
					`emp_telefono` = :telefono,
					`emp_categoria` = :categoria,
					`emp_email` = :email,
					`emp_web` = :web,
					`emp_contacto_comercial` = :contactoComercial,
					`emp_tel_cc` = :contactoComercialTel,
					`emp_email_cc` = :contactoComercialEmail,
					`emp_contacto_administrativo` = :contactoAdministrativo,
					`emp_tel_ca` = :contactoAdministrativoTel,
					`emp_email_ca` = :contactoAdministrativoEmail
					WHERE `emp_id` = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':rSocial', $this->razonSocial, PDO::PARAM_STR);
		$stmt->bindParam(':cuit', $this->cuit, PDO::PARAM_STR);
		$stmt->bindParam(':direccion', $this->direccion, PDO::PARAM_STR);
		$stmt->bindParam(':telefono', $this->telefono, PDO::PARAM_STR);
		$stmt->bindParam(':categoria', $this->categoria, PDO::PARAM_STR);
		$stmt->bindParam(':email', $this->email, PDO::PARAM_STR);
		$stmt->bindParam(':web', $this->web, PDO::PARAM_STR);
		$stmt->bindParam(':id', $this->id, PDO::PARAM_STR);
		$stmt->bindParam(':contactoComercial', $this->contactoComercial, PDO::PARAM_STR);
		$stmt->bindParam(':contactoComercialTel', $this->contactoComercialTel, PDO::PARAM_STR);
		$stmt->bindParam(':contactoComercialEmail', $this->contactoComercialEmail, PDO::PARAM_STR);
		$stmt->bindParam(':contactoAdministrativo', $this->contactoAdministrativo, PDO::PARAM_STR);
		$stmt->bindParam(':contactoAdministrativoTel', $this->contactoAdministrativoTel, PDO::PARAM_STR);
		$stmt->bindParam(':contactoAdministrativoEmail', $this->contactoAdministrativoEmail, PDO::PARAM_STR);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
	}
	/**
	 * Inserta nuevo registro a la tabla "empresas" usando las propiedades del Objeto ...
	 */
	public function agregaADb(){
		$con=Conexion::conectar();
		$query = "INSERT INTO `empresas`
					(`emp_id`,
					`emp_razon_social`,
					`emp_cuit`,
					`emp_direccion`,
					`emp_telefono`,
					`emp_categoria`,
					`emp_email`,
					`emp_web`,
					`emp_contacto_comercial`,
					`emp_tel_cc`,
					`emp_email_cc`,
					`emp_contacto_administrativo`,
					`emp_tel_ca`,
					`emp_email_ca`)
					VALUES
					(null,
					:rSocial,
					:cuit,
					:direccion,
					:telefono,
					:categoria,
					:email,
					:web,
					:contactoComercial,
					:contactoComercialTel,
					:contactoComercialEmail,
					:contactoAdministrativo,
					:contactoAdministrativoTel,
					:contactoAdministrativoEmail)";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':rSocial', $this->razonSocial, PDO::PARAM_STR);
		$stmt->bindParam(':cuit', $this->cuit, PDO::PARAM_STR);
		$stmt->bindParam(':direccion', $this->direccion, PDO::PARAM_STR);
		$stmt->bindParam(':telefono', $this->telefono, PDO::PARAM_STR);
		$stmt->bindParam(':categoria', $this->categoria, PDO::PARAM_STR);
		$stmt->bindParam(':email', $this->email, PDO::PARAM_STR);
		$stmt->bindParam(':web', $this->web, PDO::PARAM_STR);
		$stmt->bindParam(':contactoComercial', $this->contactoComercial, PDO::PARAM_STR);
		$stmt->bindParam(':contactoComercialTel', $this->contactoComercialTel, PDO::PARAM_STR);
		$stmt->bindParam(':contactoComercialEmail', $this->contactoComercialEmail, PDO::PARAM_STR);
		$stmt->bindParam(':contactoAdministrativo', $this->contactoAdministrativo, PDO::PARAM_STR);
		$stmt->bindParam(':contactoAdministrativoTel', $this->contactoAdministrativoTel, PDO::PARAM_STR);
		$stmt->bindParam(':contactoAdministrativoEmail', $this->contactoAdministrativoEmail, PDO::PARAM_STR);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
		$this->uFilaIns=$con->lastInsertId();
		$this->setId($this->uFilaIns);
	}
	/**
	 * Borra registro de la tabla "empresas" filtrado por ID ...
	 */
	public function borraPorId($ID){
		if(!isset($ID)){
			return $this;
		}
		$con=Conexion::conectar();
		$query = "DELETE FROM empresas WHERE emp_id = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $ID, PDO::PARAM_STR);
		$stmt -> execute();
		$this->uFilaIns=$con->lastInsertId();
	}
}