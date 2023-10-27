<?php
/**
 * @Author : José Luis Villaronga
 * @copyright : 2014
 */
class StockState
{
	/**
	 * Propiedades
	 */
	private $id;
	private $nombre=null;
	private $color=null;
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
		}
		$query = "SELECT * FROM stock_state WHERE ss_id = $c";
		foreach (Db::listar($query) as $fila){
			$this->id=$fila['ss_id'];
			$this->nombre=$fila['ss_descripcion'];
			$this->color=$fila['ss_color'];
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
	public function getColor(){
		if(!empty($this->color)){
			return $this->color;
		}else{
			return null;
		}
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
	public function setColor($nC){
		$this->color=$nC;
	}
	/**
	 * Actualiza la tabla "stock_state" con las propiedades actuales del objeto ...
	 */
	public function actualizaDb(){
		$con=Conexion::conectar();
		$query = "UPDATE `stock_state`
					SET
					`ss_id` = :id,
					`ss_descripcion` = :nombre,
					`ss_color` = :color
					WHERE `ss_id` = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
		$stmt->bindParam(':nombre', $this->nombre, PDO::PARAM_STR);
		$stmt->bindParam(':color', $this->color, PDO::PARAM_STR);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
	}
	/**
	 * Inserta nuevo registro a la tabla "stock_state" usando las propiedades del Objeto ...
	 */
	public function agregaADb(){
		$con=Conexion::conectar();
		$query="INSERT INTO `stock_state`
				(`ss_id`,
				`ss_descripcion`,
				`ss_color`)
				VALUES
				(null,
				:nombre,
				:color)";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':nombre', $this->nombre, PDO::PARAM_STR);
		$stmt->bindParam(':color', $this->color, PDO::PARAM_STR);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
		$this->uFilaIns=$con->lastInsertId();
		$this->setId($this->uFilaIns);
	}
	/**
	 * Borra registro de la tabla "stock_state" filtrado por ID ...
	 */
	public function borraPorId($ID){
		if(!isset($ID)){
			return $this;
		}
		$con=Conexion::conectar();
		$query = "DELETE FROM stock_state WHERE ss_id = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $ID, PDO::PARAM_STR);
		$stmt -> execute();
		$this->uFilaIns=$con->lastInsertId();
	}
}