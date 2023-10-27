<?php
/**
 * @Author : José Luis Villaronga
 * @copyright : 2019
 */
class CursoStatus
{
	/**
	 * Propiedades
	 */
	private $id;
	private $descripcion;
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
	public function __construct($g=null){
		if(empty($g)){
			return $this;
		}
		$query="SELECT * FROM curso_status WHERE cs_id = ".$g;
		$res=Db::listar($query);
		foreach ($res as $fila){
			$this->id=$fila['cs_id'];
			$this->descripcion=$fila['cs_descripcion'];
		}
		return $this;
	}
	/**
	 * Getters ..
	 */
	public function getId(){
	 	return $this->id;
	}
	public function getDescripcion(){
		return $this->descripcion;
	}
	/**
	 * Setters ...
	 */
	public function setId($id){
		$this->id=$id;
	}
	public function setDescripcion($descripcion){
		if(empty($descripcion)){
			$this->errores['nombre']="Hay que ingresar un valor ...";
			$this->errores['gen'] = "harError";
		}else{
			$this->descripcion=$descripcion;
		}
	}
	/**
	 * Actualiza la tabla "curso_status" con las propiedades actuales del objeto ...
	 */
	public function actualizaDb(){
		$con=Conexion::conectar();
		$query = "UPDATE `curso_status`
					SET
					`cs_id` = :id,
					`cs_descripcion` = :descripcion
					WHERE `cs_id` = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
		$stmt->bindParam(':descripcion', $this->descripcion, PDO::PARAM_STR);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
	}
	/**
	 * Inserta nuevo registro a la tabla "curso_status" usando las propiedades del Objeto ...
	 */
	public function agregaADb(){
		$con=Conexion::conectar();
		$query="INSERT INTO `curso_status`
				(`cs_id`,
				`cs_descripcion`)
				VALUES
				(null,
				:descripcion)";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':descripcion', $this->descripcion, PDO::PARAM_STR);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
		$this->uFilaIns=$con->lastInsertId();
		$this->setId($this->uFilaIns);
	}
	/**
	 * Borra registro de la tabla "curso_status" filtrado por ID ...
	 */
	public function borraPorId($ID){
		if(!isset($ID)){
			return $this;
		}
		$con=Conexion::conectar();
		$query = "DELETE FROM curso_status WHERE cs_id = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $ID, PDO::PARAM_STR);
		$stmt -> execute();
		$this->uFilaIns=$con->lastInsertId();
	}
}