<?php
/**
 * @Author : José Luis Villaronga
 * @copyright : 2014
 */
class TipoCC
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
	public function __construct($g=null){
		$query = "SELECT * FROM tipo_cc WHERE cca_obi_final = $g";
		if(empty($g)){
			return $this;
		}
		$res=Db::listar($query);
		foreach ($res as $fila){
			$this->id=$fila['cca_obi_final'];
			$this->nombre=$fila['tcc_nombre'];
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
	 * Actualiza la tabla "tipo_cc" con las propiedades actuales del objeto ...
	 */
	public function actualizaDb(){
		$con=Conexion::conectar();
		$query = "UPDATE `tipo_cc`
					SET
					`cca_obi_final` = :id,
					`tcc_nombre` = :nombre
					WHERE `cca_obi_final` = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
		$stmt->bindParam(':nombre', $this->nombre, PDO::PARAM_STR);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
	}
	/**
	 * Inserta nuevo registro a la tabla "tipo_cc" usando las propiedades del Objeto ...
	 */
	public function agregaADb(){
		$con=Conexion::conectar();
		$query="INSERT INTO `tipo_cc`
				(`cca_obi_final`,
				`tcc_nombre`)
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
	 * Borra registro de la tabla "tipo_cc" filtrado por ID ...
	 */
	public function borraPorId($ID){
		if(!isset($ID)){
			return $this;
		}
		$con=Conexion::conectar();
		$query = "DELETE FROM tipo_cc WHERE cca_obi_final = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $ID, PDO::PARAM_STR);
		$stmt -> execute();
		$this->uFilaIns=$con->lastInsertId();
	}
}