<?php
/**
 * @Author : José Luis Villaronga
 * @copyright : 2019
 */
class MensajeTipo
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
		$query="SELECT * FROM mensaje_tipo WHERE ment_id = ".$g;
		$res=Db::listar($query);
		foreach ($res as $fila){
			$this->id=$fila['ment_id'];
			$this->value=$fila['ment_value'];
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
	 * Actualiza la tabla "mensaje_tipo" con las propiedades actuales del objeto ...
	 */
	public function actualizaDb(){
		$con=Conexion::conectar();
		$query = "UPDATE `mensaje_tipo`
					SET
					`ment_id` = :id,
					`ment_value` = :value
					WHERE `ment_id` = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
		$stmt->bindParam(':value', $this->value, PDO::PARAM_STR);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
	}
	/**
	 * Inserta nuevo registro a la tabla "mensaje_tipo" usando las propiedades del Objeto ...
	 */
	public function agregaADb(){
		$con=Conexion::conectar();
		$query="INSERT INTO `mensaje_tipo`
				(`ment_id`,
				`ment_value`)
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
	 * Borra registro de la tabla "mensaje_tipo" filtrado por ID ...
	 */
	public function borraPorId($ID){
		if(!isset($ID)){
			return $this;
		}
		$con=Conexion::conectar();
		$query = "DELETE FROM mensaje_tipo WHERE ment_id = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $ID, PDO::PARAM_STR);
		$stmt -> execute();
		$this->uFilaIns=$con->lastInsertId();
	}
}