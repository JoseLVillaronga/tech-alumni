<?php
/**
 * @Author : José Luis Villaronga
 * @copyright : 2014
 */
class Falla
{
	/**
	 * Propiedades
	 */
	private $id;
	private $nombre;
	public $categoria;
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
			$this->categoria=new Categoria(null);
			return $this;
		}
		$query = "SELECT * FROM fallas WHERE falla_id = $c";
		foreach (Db::listar($query) as $fila){
			$this->id=$fila['falla_id'];
			$this->nombre=$fila['falla_nombre'];
			$this->categoria=new Categoria($fila['cat_id']);
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
			$this->errores['nombre']="Hay que ingresar un falla ...";
			$this->errores['gen'] = "harError";
		}else{
			$this->nombre=$nombre;
		}
	}
	public function setCategoria($nCategoria){
		if(empty($nCategoria)){
			$this->errores['categoria']="Hay que ingresar una categoria ...";
			$this->errores['gen'] = "harError";
		}else{
			$this->categoria = new Categoria($nCategoria);
		}
	}
	/**
	 * Actualiza la tabla "fallas" con las propiedades actuales del objeto ...
	 */
	public function actualizaDb(){
		$con=Conexion::conectar();
		$query = "UPDATE `fallas`
					SET
					`falla_id` = :id,
					`cat_id` = :categoria,
					`falla_nombre` = :nombre
					WHERE `falla_id` = :id";
		$stmt = $con -> prepare($query);
		$c=$this->categoria->getId();
		$stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
		$stmt->bindParam(':nombre', $this->nombre, PDO::PARAM_STR);
		$stmt->bindParam(':categoria', $c, PDO::PARAM_STR);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
	}
	/**
	 * Inserta nuevo registro a la tabla "fallas" usando las propiedades del Objeto ...
	 */
	public function agregaADb(){
		$con=Conexion::conectar();
		$query="INSERT INTO `fallas`
				(`falla_id`,
				`cat_id`,
				`falla_nombre`)
				VALUES
				(null,
				:categoria,
				:nombre)";
		$stmt = $con -> prepare($query);
		$c=$this->categoria->getId();
		$stmt->bindParam(':nombre', $this->nombre, PDO::PARAM_STR);
		$stmt->bindParam(':categoria', $c, PDO::PARAM_STR);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
		$this->uFilaIns=$con->lastInsertId();
		$this->setId($this->uFilaIns);
	}
	/**
	 * Borra registro de la tabla "fallas" filtrado por ID ...
	 */
	public function borraPorId($ID){
		if(!isset($ID)){
			return $this;
		}
		$con=Conexion::conectar();
		$query = "DELETE FROM fallas WHERE falla_id = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $ID, PDO::PARAM_STR);
		$stmt -> execute();
		$this->uFilaIns=$con->lastInsertId();
	}
}
