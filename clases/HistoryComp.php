<?php
/**
 * @author : José Luis Villaronga
 * @copyright : 2014
 */
class HistoryComp
{
	/**
	 * Propiedades
	 */
	private $id;
	private $repuestoId;
	private $cantidad;
	private $monto;
	private $ingresoRetiro;
	private $fecha;
	private $observaciones;
	public $usuario;
	private $locId;
	public $lote=array();
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
			$usuario=new Usuario($_SESSION['usuario']);
			return $this;
		}else{
			$query="SELECT * FROM repu_history_comp WHERE comp_id = $c";
			foreach(Db::listar($query) as $fila){
				$this->lote[]=array(
					'hcomp_id' => $fila['hcomp_id'],
					'comp_id' => $fila['comp_id'],
					'comp_existencias' => $fila['comp_existencias'],
					'hcomp_monto' => $fila['hcomp_monto'],
					'hcomp_ingreso_retiro' => $fila['hcomp_ingreso_retiro'],
					'hcomp_fecha' => $fila['hcomp_fecha'],
					'comp_observaciones' => $fila['comp_observaciones'],
					'cli_usuario' => $fila['cli_usuario'],
					'loc_id' => $fila['loc_id']
				);
			}
			$usuario=new Usuario($_SESSION['usuario']);
		}
	}
	/**
	 * Getters ...
	 */
	public function getId(){
		return $this->id;
	}
	public function getRepuestoId(){
		return $this->repuestoId;
	}
	public function getCantidad(){
		return $this->cantidad;
	}
	public function getMonto(){
		return $this->monto;
	}
	public function getIngresoRetiro(){
		return $this->ingresoRetiro;
	}
	public function getFecha(){
		return $this->fecha;
	}
	public function getLocId(){
		return $this->locId;
	}
	public function getObsertvaciones(){
		return $this->observaciones;
	}
	/**
	 * Setters ...
	 */
	public function setId($nId){
		$this->id=$nId;
	}
	public function setRepuestoId($nR){
		$this->repuestoId=$nR;
	}
	public function setCantidad($nC){
		$this->cantidad=$nC;
	}
	public function setMonto($nM){
		$this->monto=$nM;
	}
	public function setIngresoRetiro($nIR){
		$this->ingresoRetiro=$nIR;
	}
	public function setFecha($nF){
		$this->fecha=$nF;
	}
	public function setLocId($nLI){
		$this->locId=$nLI;
	}
	public function setObservaciones($nO){
		$this->observaciones=$nO;
	}
	public function setLote(){
		$query="SELECT * FROM repu_history_comp WHERE comp_id = $this->repuestoId";
		foreach(Db::listar($query) as $fila){
			$this->lote[]=array(
				'hcomp_id' => $fila['hcomp_id'],
				'comp_id' => $fila['comp_id'],
				'comp_existencias' => $fila['comp_existencias'],
				'hcomp_monto' => $fila['hcomp_monto'],
				'hcomp_ingreso_retiro' => $fila['hcomp_ingreso_retiro'],
				'hcomp_fecha' => $fila['hcomp_fecha'],
				'comp_observaciones' => $fila['comp_observaciones'],
				'cli_usuario' => $fila['cli_usuario'],
				'loc_id' => $fila['loc_id']
			);
		}
	}
	/**
	 * Inserta nuevo registro a la tabla "repu_componentes" usando las propiedades del Objeto ...
	 */
	public function agregaADb(){
		$con=Conexion::conectar();
		$query="INSERT INTO `repu_history_comp`
				(`hcomp_id`,
				`comp_id`,
				`comp_existencias`,
				`hcomp_monto`,
				`hcomp_ingreso_retiro`,
				`hcomp_fecha`,
				`comp_observaciones`,
				`cli_usuario`,
				`loc_id`)
				VALUES
				(null,
				:repuestoId,
				:cantidad,
				:monto,
				:ingresoRetiro,
				:fecha,
				:observaciones,
				:usuario,
				:locId);";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':repuestoId', $this->repuestoId, PDO::PARAM_INT);
		$stmt->bindParam(':cantidad', $this->cantidad, PDO::PARAM_INT);
		$stmt->bindParam(':monto', $this->monto, PDO::PARAM_INT);
		$stmt->bindParam(':ingresoRetiro', $this->ingresoRetiro, PDO::PARAM_INT);
		$stmt->bindParam(':fecha', $this->fecha, PDO::PARAM_STR);
		$stmt->bindParam(':observaciones', $this->observaciones, PDO::PARAM_STR);
		$stmt->bindParam(':usuario', $_SESSION['usuario'], PDO::PARAM_STR);
		$stmt->bindParam(':locId', $this->locId, PDO::PARAM_INT);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
		$this->uFilaIns=$con->lastInsertId();
		$this->setId($this->uFilaIns);
		$this->setLote();
	}
	/**
	 * Borra registro de la tabla "repu_history_comp" filtrado por ID ...
	 */
	public function borraPorId($ID){
		if(!isset($ID)){
			return $this;
		}
		$con=Conexion::conectar();
		$query = "DELETE FROM repu_history_comp WHERE hcomp_id = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $ID, PDO::PARAM_INT);
		$stmt -> execute();
		$this->uFilaIns=$con->lastInsertId();
	}
}
