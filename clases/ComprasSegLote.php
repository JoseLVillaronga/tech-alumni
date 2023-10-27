<?php
/**
 * @Author : José Luis Villaronga
 * @copyright : 2016
 */
class ComprasSegLote
{
	/**
	 * Propiedades
	 */
	private $id;
	private $idCS;
	public $repuesto;
	private $cantidad;
	private $descripcion;
	public $lote;
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
			$this->repuesto=new Repuesto();
			$this->idCS=$c;
			return $this;
		}else{
			$query="SELECT * FROM lote_compras_seguimiento WHERE comps_id = $c";
			$res=Db::listar($query);
			if(count($res) > 0){
				foreach($res as $fila){
					$this->lote[]=array(
						"compsl_id"=>$fila['compsl_id'],
						"comps_id"=>$fila['comps_id'],
						"comp_id"=>$fila['comp_id'],
						"compsl_cantidad"=>$fila['compsl_cantidad'],
						"compsl_descripcion"=>$fila['compsl_descripcion']
					);
				}
				$this->idCS=$c;
				$this->repuesto=new Repuesto(null);
			}else{
				$this->idCS=$c;
				$this->repuesto=new Repuesto(null);
				return $this;
			}
		}
	}
	/**
	 * Getters ...
	 */
	public function getId(){
		return $this->id;
	}
	public function getIdCS(){
		return $this->idCS;
	}
	public function getRepuestoId(){
		return $this->repuesto->getId();
	}
	public function getCantidad(){
		return $this->cantidad;
	}
	public function getDescripcion(){
		return $this->descripcion;
	}
	/**
	 * Setters ...
	 */
	public function setId($nId){
		$this->id=$nId;
	}
	public function setIdCS($nICS){
		$this->idCS=$nICS;
	}
	public function setRepuestoId($nRI){
		$this->repuesto=new Repuesto($nRI);
		if($nRI=="0"){
			$this->repuesto->setId($nRI);
		}
	}
	public function setCantidad($nC){
		$this->cantidad=$nC;
	}
	public function setDescripcion($nD){
		$this->descripcion=$nD;
	}
	/**
	 * Actualiza la tabla "lote_compras_seguimiento" con las propiedades actuales del objeto ...
	 */
	public function actualizaDb(){
		$con=Conexion::conectar();
		$repu=$this->getRepuestoId();
		$query="UPDATE `lote_compras_seguimiento`
				SET
				`compsl_id` = :id,
				`comps_id` = :idCS,
				`comp_id` = :repuesto,
				`compsl_cantidad` = :cantidad,
				`compsl_descripcion` = :descripcion
				WHERE `compsl_id` = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
		$stmt->bindParam(':idCS', $this->idCS, PDO::PARAM_INT);
		$stmt->bindParam(':repuesto', $repu, PDO::PARAM_INT);
		$stmt->bindParam(':cantidad', $this->cantidad, PDO::PARAM_INT);
		$stmt->bindParam(':descripcion', $this->descripcion, PDO::PARAM_STR);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
	}
	/**
	 * Inserta nuevo registro a la tabla "lote_compras_seguimiento" usando las propiedades del Objeto ...
	 */
	public function agregaADb(){
		$con=Conexion::conectar();
		$repu=$this->getRepuestoId();
		$query="INSERT INTO `lote_compras_seguimiento`
				(`compsl_id`,
				`comps_id`,
				`comp_id`,
				`compsl_cantidad`,
				`compsl_descripcion`)
				VALUES
				(null,
				:idCS,
				:repuesto,
				:cantidad,
				:descripcion)";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':idCS', $this->idCS, PDO::PARAM_INT);
		$stmt->bindParam(':repuesto', $repu, PDO::PARAM_INT);
		$stmt->bindParam(':cantidad', $this->cantidad, PDO::PARAM_INT);
		$stmt->bindParam(':descripcion', $this->descripcion, PDO::PARAM_STR);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
		$this->uFilaIns=$con->lastInsertId();
		$this->setId($this->uFilaIns);
	}
	/**
	 * Borra registro de la tabla "lote_compras_seguimiento" filtrado por ID ...
	 */
	public function borraPorId($ID){
		if(!isset($ID)){
			return $this;
		}
		$con=Conexion::conectar();
		$query = "DELETE FROM lote_compras_seguimiento WHERE compsl_id = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $ID, PDO::PARAM_STR);
		$stmt -> execute();
		$this->uFilaIns=$con->lastInsertId();
	}
	/**
	 * Setea propiedades buscanco por ID ...
	 */
	public function setPropiedadesPorId($id){
		foreach($this->lote as $fila){
			if($id==$fila['compsl_id']){
				$this->id=$fila['compsl_id'];
				$this->idCS=$fila['comps_id'];
				$this->repuesto=new Repuesto($fila['comp_id']);
				$this->cantidad=$fila['compsl_cantidad'];
			}
		}
	}
}
