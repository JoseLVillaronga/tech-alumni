<?php
/**
 * @Author : José Luis Villaronga
 * @copyright : 2015
 */
class RepuCategoria
{
	/**
	 * Propiedades
	 */
	private $id;
	private $nombre;
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
			return $this;
		}elseif(is_numeric($c)){
			$query = "SELECT * FROM repu_categoria WHERE rcat_id = $c";
			foreach (Db::listar($query) as $fila){
				$this->id=$fila['rcat_id'];
				$this->nombre=$fila['rcat_nombre'];
			}
		}else{
			$query = "SELECT * FROM repu_categoria WHERE rcat_nombre = '$c'";
			foreach (Db::listar($query) as $fila){
				$this->id=$fila['rcat_id'];
				$this->nombre=$fila['rcat_nombre'];
			}
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
		$query = "SELECT * FROM repu_categoria WHERE rcat_nombre = '$nNombre'";
		$res=Db::listar($query);
		
		if(empty($nNombre)){
			$this->errores['nombre']="Hay que ingresar un nombre ...";
			$this->errores['gen'] = "harError";
		}elseif(count($res) > 0){
			$this->errores['nombre']="Ya existe ...";
			$this->errores['gen'] = "harError";
		}else{
			$this->nombre=$nNombre;
		}
	}
	/**
	 * Actualiza la tabla "repu_categoria" con las propiedades actuales del objeto ...
	 */
	public function actualizaDb(){
		$con=Conexion::conectar();
		$query = "UPDATE `repu_categoria`
					SET
					`rcat_id` = :id,
					`rcat_nombre` = :nombre
					WHERE `rcat_id` = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
		$stmt->bindParam(':nombre', $this->nombre, PDO::PARAM_STR);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
	}
	/**
	 * Inserta nuevo registro a la tabla "repu_categoria" usando las propiedades del Objeto ...
	 */
	public function agregaADb(){
		$con=Conexion::conectar();
		$query="INSERT INTO `repu_categoria`
				(`rcat_id`,
				`rcat_nombre`)
				VALUES
				(null,
				:nombre)";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':nombre', $this->nombre, PDO::PARAM_STR);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
		$this->uFilaIns=$con->lastInsertId();
		$this->setId($this->uFilaIns);
	}
	/**
	 * Borra registro de la tabla "repu_categoria" filtrado por ID ...
	 */
	public function borraPorId($ID){
		if(!isset($ID)){
			return $this;
		}
		$con=Conexion::conectar();
		$query = "DELETE FROM repu_categoria WHERE rcat_id = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $ID, PDO::PARAM_STR);
		$stmt -> execute();
		$this->uFilaIns=$con->lastInsertId();
	}
}
