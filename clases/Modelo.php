<?php
/**
 * @Author : José Luis Villaronga
 * @copyright : 2014
 */
class Modelo
{
	/**
	 * Propiedades
	 */
	private $id;
	private $nombre;
	public $marca;
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
	 * Metodo constructor ..
	 */
	public function __construct($c=null){
		if(empty($c)){
			$this->marca=new Marca(null);
			return $this;
		}
		$query = "SELECT * FROM modelo WHERE mod_id = $c";
		foreach (Db::listar($query) as $fila){
			$this->id=$fila['mod_id'];
			$this->nombre=$fila['mod_nombre'];
			$this->marca=new Marca($fila['marca_id']);
		}
	}
	/**
	 * Getters ...
	 */
	public function getId(){
		return $this->id;
	}
	public function getNombre(){
		return $this->nombre;
	}
	/**
	 * Setters ...
	 */
	public function setId($nId){
		$this->id=$nId;
	}
	public function setNombre($nNombre){
		if(empty($nNombre)){
			$this->errores['nombre']="Hay que ingresar un nombre ...";
			$this->errores['gen'] = "harError";
		}else{
			$this->nombre=$nNombre;
		}
	}
	public function setMarca($nMarca){
		if(empty($nMarca)){
			$this->errores['marca']="Hay que ingresar marca ...";
			$this->errores['gen'] = "harError";
		}else{
			$this->marca=new Marca($nMarca);
		}
	}
	/**
	 * Actualiza la tabla "modelo" con las propiedades actuales del objeto ...
	 */
	public function actualizaDb(){
		$con=Conexion::conectar();
		$query = "UPDATE `modelo`
					SET
					`mod_id` = :id,
					`marca_id` = :marca,
					`mod_nombre` = :nombre
					WHERE `mod_id` = :id";
		$stmt = $con -> prepare($query);
		$m = $this->marca->getId();
		$stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
		$stmt->bindParam(':nombre', $this->nombre, PDO::PARAM_STR);
		$stmt->bindParam(':marca', $m, PDO::PARAM_STR);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
	}
	/**
	 * Inserta nuevo registro a la tabla "modelo" usando las propiedades del Objeto ...
	 */
	public function agregaADb(){
		$con=Conexion::conectar();
		$query="INSERT INTO `modelo`
				(`mod_id`,
				`marca_id`,
				`mod_nombre`)
				VALUES
				(null,
				:marca,
				:nombre)";
		$stmt = $con -> prepare($query);
		$m = $this->marca->getId();
		$stmt->bindParam(':nombre', $this->nombre, PDO::PARAM_STR);
		$stmt->bindParam(':marca', $m, PDO::PARAM_STR);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
		$this->uFilaIns=$con->lastInsertId();
		$this->setId($this->uFilaIns);
	}
	/**
	 * Borra registro de la tabla "modelo" filtrado por ID ...
	 */
	public function borraPorId($ID){
		if(!isset($ID)){
			return $this;
		}
		$con=Conexion::conectar();
		$query = "DELETE FROM modelo WHERE mod_id = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $ID, PDO::PARAM_STR);
		$stmt -> execute();
		$this->uFilaIns=$con->lastInsertId();
	}
}
