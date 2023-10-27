<?php
/**
 * @author : José Luis Villaronga
 * @copyright : 2014
 */
class Tarea
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
	public function __construct($t=null){
		if(empty($t)){
			return $this;
		}
		$res=Db::listaTareaPorId($t);
		foreach ($res as $fila){
			$this->id=$fila['tar_id'];
			$this->nombre=$fila['tar_nombre'];
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
	/**
	 * Setters ...
	 */
	public function setId($id){
		$this->id=$id;
	}
	public function setNombre($nombre){
		if(empty($nombre)){
			$this->errores['nombre']="Hay que ingresar un nombre ...";
			$this->errores['gen'] = "harError";
		}else{
			$this->nombre=$nombre;
		}
	}
	/**
	 * Actualiza la tabla "tarea" con las propiedades actuales del objeto ...
	 */
	public function actualizaDb(){
		$con=Conexion::conectar();
		$query = "UPDATE `tarea`
					SET
					`tar_id` = :id,
					`tar_nombre` = :nombre
					WHERE `tar_id` = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
		$stmt->bindParam(':nombre', $this->nombre, PDO::PARAM_STR);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
	}
	/**
	 * Inserta nuevo registro a la tabla "tarea" usando las propiedades del Objeto ...
	 */
	public function agregaADb(){
		$con=Conexion::conectar();
		$query="INSERT INTO `tarea`
				(`tar_id`,
				`tar_nombre`)
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
	 * Borra registro de la tabla "tarea" filtrado por ID ...
	 */
	public function borraPorId($ID){
		if(!isset($ID)){
			return $this;
		}
		$con=Conexion::conectar();
		$query = "DELETE FROM tarea WHERE tar_id = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $ID, PDO::PARAM_STR);
		$stmt -> execute();
		$this->uFilaIns=$con->lastInsertId();
	}
}
