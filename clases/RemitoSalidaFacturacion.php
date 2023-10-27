<?php
/**
 * @Author : José Luis Villaronga
 * @copyright : 2017
 */
class RemitoSalidaFacturacion
{
	/**
	 * Propiedades
	 */
	private $id;
	private $remitoSalidaId;
	private $facturable;
	private $nroFactura;
	private $fechaFactura;
	private $motivo;
	private $fecha;
	private $usuario;
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
		if(empty($g)){return $this;}
		$query="SELECT * FROM remito_salida_factura WHERE rs_id = ".$g;
		$res=Db::listar($query);
		if(count($res)=="0"){
			return $this;
		}else{
			$this->id=$res[0]['rsf_id'];
			$this->remitoSalidaId=$res[0]['rs_id'];
			$this->facturable=$res[0]['rsf_facturable'];
			$this->nroFactura=$res[0]['rsf_nro_factura'];
			$this->fechaFactura=$res[0]['rsf_fecha_factura'];
			$this->motivo=$res[0]['rsf_motivo'];
			$this->fecha=$res[0]['rsf_fecha'];
			$this->usuario=$res[0]['cli_usuario'];
		}
	}
	/**
	 * Getters ..
	 */
	public function getId(){
	 	return $this->id;
	}
	public function getRemitoSalidaId(){
		return $this->remitoSalidaId;
	}
	public function getFacturable(){
		return $this->facturable;
	}
	public function getNroFactura(){
		return $this->nroFactura;
	}
	public function getFechaFactura(){
		return $this->fechaFactura;
	}
	public function getMotivo(){
		return $this->motivo;
	}
	public function getFecha(){
		return $this->fecha;
	}
	public function getUsuario(){
		return $this->usuario;
	}
	/**
	 * Setters ...
	 */
	public function setId($id){
		$this->id=$id;
	}
	public function setRemitoSalidaId($nRSI){
		$this->remitoSalidaId=$nRSI;
	}
	public function setFacturable($nF){
		$this->facturable=$nF;
	}
	public function setNroFactura($nNF){
		$this->nroFactura=$nNF;
	}
	public function setFechaFactura($nFF){
		$this->fechaFactura=$nFF;
	}
	public function setMotivo($nM){
		$this->motivo=$nM;
	}
	public function setFecha($nF){
		$this->fecha=$nF;
	}
	public function setUsuario($nU){
		$this->usuario=$nU;
	}
	/**
	 * Actualiza la tabla "remito_salida_factura" con las propiedades actuales del objeto ...
	 */
	public function actualizaDb(){
		$con=Conexion::conectar();
		$query = "UPDATE `remito_salida_factura`
					SET
					`rsf_id` = :id,
					`rs_id` = :remitoSalidaId,
					`rsf_facturable` = :facturable,
					`rsf_nro_factura` = :nroFactura,
					`rsf_fecha_factura` = :fechaFactura,
					`rsf_motivo` = :motivo,
					`rsf_fecha` = :fecha,
					`cli_usuario` = :usuario
					WHERE `rsf_id` = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
		$stmt->bindParam(':remitoSalidaId', $this->remitoSalidaId, PDO::PARAM_INT);
		$stmt->bindParam(':facturable', $this->facturable, PDO::PARAM_INT);
		$stmt->bindParam(':nroFactura', $this->nroFactura, PDO::PARAM_STR);
		$stmt->bindParam(':fechaFactura', $this->fechaFactura, PDO::PARAM_STR);
		$stmt->bindParam(':motivo', $this->motivo, PDO::PARAM_STR);
		$stmt->bindParam(':fecha', $this->fecha, PDO::PARAM_STR);
		$stmt->bindParam(':usuario', $this->usuario, PDO::PARAM_STR);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
	}
	/**
	 * Inserta nuevo registro a la tabla "remito_salida_factura" usando las propiedades del Objeto ...
	 */
	public function agregaADb(){
		$con=Conexion::conectar();
		$query="INSERT INTO `remito_salida_factura`
				(`rsf_id`,
				`rs_id`,
				`rsf_facturable`,
				`rsf_nro_factura`,
				`rsf_fecha_factura`,
				`rsf_motivo`,
				`rsf_fecha`,
				`cli_usuario`)
				VALUES
				(null,
				:remitoSalidaId,
				:facturable,
				:nroFactura,
				:fechaFactura,
				:motivo,
				:fecha,
				:usuario)";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':remitoSalidaId', $this->remitoSalidaId, PDO::PARAM_INT);
		$stmt->bindParam(':facturable', $this->facturable, PDO::PARAM_INT);
		$stmt->bindParam(':nroFactura', $this->nroFactura, PDO::PARAM_STR);
		$stmt->bindParam(':fechaFactura', $this->fechaFactura, PDO::PARAM_STR);
		$stmt->bindParam(':motivo', $this->motivo, PDO::PARAM_STR);
		$stmt->bindParam(':fecha', $this->fecha, PDO::PARAM_STR);
		$stmt->bindParam(':usuario', $this->usuario, PDO::PARAM_STR);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
		$this->uFilaIns=$con->lastInsertId();
		$this->setId($this->uFilaIns);
	}
	/**
	 * Borra registro de la tabla "remito_salida_factura" filtrado por ID ...
	 */
	public function borraPorId($ID){
		if(!isset($ID)){
			return $this;
		}
		$con=Conexion::conectar();
		$query = "DELETE FROM remito_salida_factura WHERE rsf_id = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $ID, PDO::PARAM_STR);
		$stmt -> execute();
		$this->uFilaIns=$con->lastInsertId();
	}
}
