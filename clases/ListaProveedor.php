<?php
/**
 * @Author : José Luis Villaronga
 * @copyright : 2015
 */
class ListaProveedor
{
	/**
	 * Propiedades
	 */
	private $id;
	private $compId;
	private $provId;
	public $lista=array();
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
		}elseif(!is_numeric($c)){
			return $this;
		}else{
			$this->id=$c;
			$this->compId=$c;
			$query="SELECT * FROM repu_comp_pro WHERE comp_id = $c";
			foreach(Db::listar($query) as $fila){
				$this->lista[]=new Proveedor($fila['pr_id']);
			}
		}
	}
	/**
	 * Getters ...
	 */
	public function getId(){
		return $this->id;
	}
	public function getCompId(){
		return $this->compId;
	}
	public function getProvId(){
		return $this->provId;
	}
	/**
	 * Setters ...
	 */
	public function setId($nId){
		$this->id=$nId;
		$query="SELECT * FROM repu_comp_pro WHERE comp_id = $nId";
		//$i=0;
		foreach(Db::listar($query) as $fila){
			//$i=$i+1;
			$this->lista[]=new Proveedor($fila['pr_id']);
		}
	}
	public function setCompId($nCI){
		$this->compId=$nCI;
	}
	public function setProvId($nPI){
		$this->provId=$nPI;
	}
	/**
	 * Actualiza la tabla "repu_comp_pro" con las propiedades actuales del objeto ...
	 */
	public function actualizaDb(){
		$con=Conexion::conectar();
		$query="UPDATE repu_comp_pro
				SET
				cp_id = :id,
				comp_id = :compId,
				pr_id = :provId
				WHERE cp_id = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
		$stmt->bindParam(':compId', $this->compId, PDO::PARAM_INT);
		$stmt->bindParam(':provId', $this->provId, PDO::PARAM_INT);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
	}
	/**
	 * Inserta nuevo registro a la tabla "repu_comp_pro" usando las propiedades del Objeto ...
	 */
	public function agregaADb(){
		$con=Conexion::conectar();
		$query="INSERT INTO repu_comp_pro
				(cp_id,
				comp_id,
				pr_id)
				VALUES
				(null,
				:compId,
				:provId)";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':compId', $this->compId, PDO::PARAM_INT);
		$stmt->bindParam(':provId', $this->provId, PDO::PARAM_INT);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
		$this->uFilaIns=$con->lastInsertId();
		$this->setId($this->uFilaIns);
	}
	/**
	 * Borra registro de la tabla "repu_comp_pro" filtrado por ID ...
	 */
	public function borraPorId($ID){
		if(!isset($ID)){
			return $this;
		}
		$con=Conexion::conectar();
		$query = "DELETE FROM repu_comp_pro WHERE cp_id = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $ID, PDO::PARAM_INT);
		$stmt -> execute();
		$this->uFilaIns=$con->lastInsertId();
	}
	public function borraDeLista($rId,$pId){
		if(!isset($rId) OR !isset($pId)){
			return $this;
		}
		$con=Conexion::conectar();
		$query = "DELETE FROM repu_comp_pro WHERE comp_id = :id AND pr_id = :pId";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $rId, PDO::PARAM_INT);
		$stmt->bindParam(':pId', $pId, PDO::PARAM_INT);
		$stmt -> execute();
		$this->uFilaIns=$con->lastInsertId();
	}
	public function borraListaCompleta($rId){
		if(!isset($rId)){
			return $this;
		}
		$con=Conexion::conectar();
		$query = "DELETE FROM repu_comp_pro WHERE comp_id = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $rId, PDO::PARAM_INT);
		$stmt -> execute();
		$this->uFilaIns=$con->lastInsertId();
	}
	public function actualizaLista($array){
		foreach($this->lista as $key => $value){
			$check[]=$value->id;
		}
		if($array==$check){
			return $this;
		}
		if(empty($array)){
			$this->borraListaCompleta($this->compId);
			return $this;
		}
		$this->borraListaCompleta($this->compId);
		foreach($array as $key3 => $value3){
			$this->provId=$value3;
			$this->agregaADb();
		}
	}
}
