<?php
/**
 * @Author : José Luis Villaronga
 * @copyright : 2019
 */
class CalificacionValue
{
	/**
	 * Propiedades
	 */
	private $id;
	private $value;
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
		$query="SELECT * FROM calificacion_value WHERE calv_id = ".$g;
		$res=Db::listar($query);
		foreach ($res as $fila){
			$this->id=$fila['calv_id'];
			$this->value=$fila['calv_value'];
		}
		return $this;
	}
	/**
	 * Getters ..
	 */
	public function getId(){
	 	return $this->id;
	}
	public function getValue(){
		return $this->value;
	}
	/**
	 * Setters ...
	 */
	public function setId($id){
		$this->id=$id;
	}
	public function setValue($value){
		if(empty($value)){
			$this->errores['nombre']="Hay que ingresar un valor ...";
			$this->errores['gen'] = "harError";
		}else{
			$this->value=$value;
		}
	}
	/**
	 * Actualiza la tabla "calificacion_value" con las propiedades actuales del objeto ...
	 */
	public function actualizaDb(){
		$con=Conexion::conectar();
		$query = "UPDATE `calificacion_value`
					SET
					`calv_id` = :id,
					`calv_value` = :value
					WHERE `calv_id` = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
		$stmt->bindParam(':value', $this->value, PDO::PARAM_STR);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
	}
	/**
	 * Inserta nuevo registro a la tabla "calificacion_value" usando las propiedades del Objeto ...
	 */
	public function agregaADb(){
		$con=Conexion::conectar();
		$query="INSERT INTO `calificacion_value`
				(`calv_id`,
				`calv_value`)
				VALUES
				(null,
				:value)";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':value', $this->value, PDO::PARAM_STR);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
		$this->uFilaIns=$con->lastInsertId();
		$this->setId($this->uFilaIns);
	}
	/**
	 * Borra registro de la tabla "calificacion_value" filtrado por ID ...
	 */
	public function borraPorId($ID){
		if(!isset($ID)){
			return $this;
		}
		$con=Conexion::conectar();
		$query = "DELETE FROM calificacion_value WHERE calv_id = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $ID, PDO::PARAM_STR);
		$stmt -> execute();
		$this->uFilaIns=$con->lastInsertId();
	}
}
