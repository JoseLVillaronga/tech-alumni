<?php
/**
 * @Author : José Luis Villaronga
 * @copyright : 2014
 */
class Articulo
{
	/**
	 * Propiedades
	 */
	private $id;
	private $nombre;
	public $categoria;
	private $descripcion;
	private $foto;
	public $modelo;
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
			$this->modelo=new Modelo(null);
			return $this;
		}
		$query = "SELECT * FROM articulos WHERE art_id = $c";
		$res=Db::listar($query);
		if(count($res)=="0"){
			$c="256";
			$query = "SELECT * FROM articulos WHERE art_id = $c";
			foreach (Db::listar($query) as $fila){
				$this->id=$fila['art_id'];
				$this->nombre=$fila['art_nombre'];
				$this->categoria=new Categoria($fila['cat_id']);
				$this->descripcion=$fila['art_descripcion'];
				$this->modelo=new Modelo($fila['mod_id']);
				$this->foto=$fila['mod_foto'];
			}
		}else{
			foreach (Db::listar($query) as $fila){
				$this->id=$fila['art_id'];
				$this->nombre=$fila['art_nombre'];
				$this->categoria=new Categoria($fila['cat_id']);
				$this->descripcion=$fila['art_descripcion'];
				$this->modelo=new Modelo($fila['mod_id']);
				$this->foto=$fila['mod_foto'];
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
	public function getDescripcion(){
		return $this->descripcion;
	}
	public function getFoto(){
		return $this->foto;
	}
	/**
	 * Setters ...
	 */
	public function setId($nId){
		$this->id=$nId;
	}
	public function setNombre($nNombre){
		if(empty($nNombre)){
			$this->errores['nombre']="Hay que ingresar un articulo ...";
			$this->errores['gen'] = "harError";
		}else{
			$this->nombre=$nNombre;
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
	public function setDescripcion($nDescripcion){
		$this->descripcion=$nDescripcion;
	}
	public function setModelo($nModelo){
		if(empty($nModelo)){
			$this->errores['modelo']="Hay que ingresar una modelo ...";
			$this->errores['gen'] = "harError";
		}else{
			$this->modelo=new Modelo($nModelo);
		}
	}
	public function setFoto($nF){
		$this->foto=$nF;
	}
	/**
	 * Actualiza la tabla "articulos" con las propiedades actuales del objeto ...
	 */
	public function actualizaDb(){
		$con=Conexion::conectar();
		$query = "UPDATE `articulos`
					SET
					`art_id` = :id,
					`art_nombre` = :nombre,
					`art_descripcion` = :descripcion,
					`cat_id` = :categoria,
					`marca_id` = :marca,
					`mod_id` = :modelo,
					`mod_foto` = :foto
					WHERE `art_id` = :id";
		$stmt = $con -> prepare($query);
		$c=$this->categoria->getId();
		$mar=$this->modelo->marca->getId();
		$mod=$this->modelo->getId();
		$stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
		$stmt->bindParam(':nombre', $this->nombre, PDO::PARAM_STR);
		$stmt->bindParam(':descripcion', $this->descripcion, PDO::PARAM_STR);
		$stmt->bindParam(':categoria', $c, PDO::PARAM_STR);
		$stmt->bindParam(':marca', $mar, PDO::PARAM_STR);
		$stmt->bindParam(':modelo', $mod, PDO::PARAM_STR);
		$stmt->bindParam(':foto', $this->foto, PDO::PARAM_STR);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
	}
	/**
	 * Inserta nuevo registro a la tabla "articulos" usando las propiedades del Objeto ...
	 */
	public function agregaADb(){
		$con=Conexion::conectar();
		$query="INSERT INTO `articulos`
				(`art_id`,
				`art_nombre`,
				`art_descripcion`,
				`cat_id`,
				`marca_id`,
				`mod_id`,
				`mod_foto`)
				VALUES
				(null,
				:nombre,
				:descripcion,
				:categoria,
				:marca,
				:modelo,
				:foto)";
		$stmt = $con -> prepare($query);
		$c=$this->categoria->getId();
		if($this->modelo->marca->getId() != 0){
			$mar=$this->modelo->marca->getId();
		}
		if($mod=$this->modelo->getId() != 0){
			$mod=$this->modelo->getId();
		}
		$stmt->bindParam(':nombre', $this->nombre, PDO::PARAM_STR);
		$stmt->bindParam(':descripcion', $this->descripcion, PDO::PARAM_STR);
		$stmt->bindParam(':categoria', $c, PDO::PARAM_STR);
		$stmt->bindParam(':marca', $mar, PDO::PARAM_STR);
		$stmt->bindParam(':modelo', $mod, PDO::PARAM_STR);
		$stmt->bindParam(':foto', $this->foto, PDO::PARAM_STR);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
		$this->uFilaIns=$con->lastInsertId();
		$this->setId($this->uFilaIns);
	}
	/**
	 * Borra registro de la tabla "articulos" filtrado por ID ...
	 */
	public function borraPorId($ID){
		if(!isset($ID)){
			return $this;
		}
		$con=Conexion::conectar();
		$query = "DELETE FROM articulos WHERE art_id = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $ID, PDO::PARAM_STR);
		$stmt -> execute();
		$this->uFilaIns=$con->lastInsertId();
	}
}
