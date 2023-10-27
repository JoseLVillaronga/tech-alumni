<?php
/**
 * @Author : José Luis Villaronga
 * @copyright : 2018
 */
class Venta
{
	/**
	 * Propiedades
	 */
	private $id;
	private $codigo;
	public $proforma;
	private $precio;
	private $cantidad=0;
	private $fechaInicio;
	private $nombre;
	private $apellido;
	private $razonSocial;
	private $email;
	private $areaCode;
	private $telefono;
	private $dni;
	private $cuit;
	private $calle;
	private $numero;
	private $zipCode;
	private $reserva;
	private $uuid;
	private $mercadoPagoId;
	private $cobrado=0;
	private $pagoLiberado=0;
	private $facturado=0;
	private $fechaFactura;
	private $facturaNro;
	private $fechaCobrado;
	private $despachado=0;
	private $fechaDespachado;
	private $usuDespacho;
	private $remitoSalidaNro;
	private $ordenCompra;
	private $descOrdenCompra="0";
	private $transporte;
	private $codigoTracking;
	private $terminado=0;
	private $fechaFinal;
	private $usuFinal;
	public $loteVenta;
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
		$query="SELECT * FROM venta WHERE v_uuid = '".$g."'";
		$res=Db::listar($query);
		$this->id=$res[0]['v_id'];
		$this->codigo=$res[0]['stk_codigo'];
		if(!empty($res[0]['p_id'])){
			$this->proforma=new Proforma($res[0]['p_id']);
		}else{
			$this->proforma=new Proforma(null);
		}
		$this->precio=$res[0]['v_precio'];
		$this->cantidad=$res[0]['v_cantidad'];
		$this->fechaInicio=$res[0]['v_fecha_inicio'];
		$this->reserva=$res[0]['v_reserva'];
		$this->uuid=$res[0]['v_uuid'];
		$this->ordenCompra=$res[0]['oc_id'];
		$this->descOrdenCompra=$res[0]['v_oc_desc'];
		$this->mercadoPagoId=$res[0]['v_mp_id'];
		$this->cobrado=$res[0]['v_cobrado'];
		$this->fechaCobrado=$res[0]['v_fecha_cobrado'];
		$this->pagoLiberado=$res[0]['v_pago_liberado'];
		$this->despachado=$res[0]['v_despachado'];
		$this->fechaDespachado=$res[0]['v_fecha_despachado'];
		$this->usuDespacho=$res[0]['v_autoriza_despacho'];
		$this->transporte=$res[0]['v_transporte'];
		$this->codigoTracking=$res[0]['v_codigo_tracking'];
		$this->terminado=$res[0]['v_terminada'];
		$this->fechaFinal=$res[0]['v_fecha_terminado'];
		$this->usuFinal=$res[0]['v_autoriza_final'];
		$this->loteVenta=new LoteVenta(null);
		$this->loteVenta->venta=$this;
		$this->loteVenta->__construct($this->id);
	}
	/**
	 * Getters ..
	 */
	public function getId(){
	 	return $this->id;
	}
	public function getCodigo(){
		return $this->codigo;
	}
	public function getPrecio(){
		return $this->precio;
	}
	public function getCantidad(){
		return $this->cantidad;
	}
	public function getFechaInicio(){
		return $this->fechaInicio;
	}
	public function getNombre(){
		return $this->proforma->getNombre();
	}
	public function getApellido(){
		return $this->proforma->getApellido();
	}
	public function getRazonSocial(){
		return $this->proforma->getRazonSocial();
	}
	public function getEmail(){
		return $this->proforma->getEMAIL();
	}
	public function getAreaCode(){
		return $this->proforma->getAreaCode();
	}
	public function getTelefono(){
		return $this->proforma->getTelefono();
	}
	public function getDNI(){
		return $this->proforma->getDNI();
	}
	public function getCUIT(){
		return $this->proforma->getCuit();
	}
	public function getCalle(){
		return $this->proforma->getCalle();
	}
	public function getNumero(){
		return $this->proforma->getNumero();
	}
	public function getZipCode(){
		return $this->proforma->getZipCode();
	}
	public function getReserva(){
		return $this->reserva;
	}
	public function getUUID(){
		return $this->uuid;
	}
	public function getOrdenCompra(){
		return $this->ordenCompra;
	}
	public function getDescOrdenCompra(){
		return $this->descOrdenCompra;
	}
	public function getConsumidorFinal(){
		return $this->proforma->getConsumidorFinal();
	}
	public function getMercadoPagoId(){
		return $this->mercadoPagoId;
	}
	public function getCobrado(){
		return $this->cobrado;
	}
	public function getPagoLiberado(){
		return $this->pagoLiberado;
	}
	public function getFacturado(){
		return $this->proforma->getFacturado();
	}
	public function getFechaFactura(){
		return $this->proforma->getFechaFactura();
	}
	public function getFacturaNro(){
		return $this->proforma->getFacturaNumero();
	}
	public function getFechaCobrado(){
		return $this->fechaCobrado;
	}
	public function getDespachado(){
		return $this->despachado;
	}
	public function getFechaDespachado(){
		return $this->fechaDespachado;
	}
	public function getUsuDespacho(){
		return $this->usuDespacho;
	}
	public function getRemitoSalidaNro(){
		return $this->proforma->getRemitoNumero();
	}
	public function getTransporte(){
		return $this->transporte;
	}
	public function getCodigoTracking(){
		return $this->codigoTracking;
	}
	public function getTerminado(){
		return $this->terminado;
	}
	public function getFechaFinal(){
		return $this->fechaFinal;
	}
	public function getUsuFinal(){
		return $this->usuFinal;
	}
	/**
	 * Setters ...
	 */
	public function setId($id){
		$this->id=$id;
	}
	public function setCodigo($nC){
		$this->codigo=$nC;
	}
	public function setPrecio($nP){
		$this->precio=$nP;
	}
	public function setCantidad($nC){
		$this->cantidad=$nC;
	}
	public function setFechaInicio($nFI){
		$this->fechaInicio=$nFI;
	}
	public function setNombre($nN){
		$this->proforma->setNombre($nN);
	}
	public function setApellido($nA){
		$this->proforma->setApellido($nA);
	}
	public function setRazonSocial($nRS){
		$this->proforma->setRazonSocial($nRS);
	}
	public function setEmail($nE){
		$this->proforma->setEMAIL($nE);
	}
	public function setAreaCode($nAC){
		$this->proforma->setAreaCode($nAC);
	}
	public function setTelefono($nT){
		$this->proforma->setTelefono($nT);
	}
	public function setDNI($nDNI){
		$this->proforma->setDNI($nDNI);
	}
	public function setCUIT($nC){
		$this->proforma->setCuit($nC);
	}
	public function setCalle($nC){
		$this->proforma->setCalle($nC);
	}
	public function setNumero($nN){
		$this->proforma->setNumero($nN);
	}
	public function setZipCode($nZC){
		$this->proforma->setZipCode($nZC);
	}
	public function setReserva($nR){
		$this->reserva=$nR;
	}
	public function setUUID($nU){
		$this->uuid=$nU;
	}
	public function setOrdenCompra($nOC){
		$this->ordenCompra=$nOC;
	}
	public function setDescOrdenCompra($nDOC){
		$this->descOrdenCompra=$nDOC;
	}
	public function setConsumidorFinal($nCF){
		$this->proforma->setConsumidorFinal($nCF);
	}
	public function setMercadoPagoId($nMPI){
		$this->mercadoPagoId=$nMPI;
	}
	public function setCobrado($nC){
		$this->cobrado=$nC;
	}
	public function setPagoLiberado($nPL){
		$this->pagoLiberado=$nPL;
	}
	public function setFacturado($nF){
		$this->proforma->setFacturado($nF);
	}
	public function setFechaFactura($nFF){
		$this->proforma->setFechaFactura($nFF);
	}
	public function setFacturaNro($nFN){
		$this->proforma->setFacturaNumero($nFN);
	}
	public function setFechaCobrado($nFC){
		$this->fechaCobrado=$nFC;
	}
	public function setDespachado($nD){
		$this->despachado=$nD;
	}
	public function setFechaDespachado($nFD){
		$this->fechaDespachado=$nFD;
	}
	public function setUsuDespacho($nUD){
		$this->usuDespacho=$nUD;
	}
	public function setRemitoASalidaNro($nRSN){
		$this->proforma->setRemitoNumero($nRSN);
	}
	public function setTransporte($nT){
		$this->transporte=$nT;
	}
	public function setCodigoTracking($nCT){
		$this->codigoTracking=$nCT;
	}
	public function setTerminado($nT){
		$this->terminado=$nT;
	}
	public function setFechaFinal($nFF){
		$this->fechaFinal=$nFF;
	}
	public function setUsuFinal($nUF){
		$this->usuFinal=$nUF;
	}
	/**
	 * Actualiza la tabla "venta" con las propiedades actuales del objeto ...
	 */
	public function actualizaDb(){
		$proforma=$this->proforma->getId();
		$con=Conexion::conectar();
		$query="UPDATE `venta`
				SET
				`v_id` = :id,
				`stk_codigo` = :codigo,
				`p_id` = :proforma,
				`v_precio` = :precio,
				`v_cantidad` = :cantidad,
				`v_fecha_inicio` = :fechaInicio,
				`v_reserva` = :reserva,
				`v_uuid` = :uuid,
				`oc_id` = :ordenCompra,
				`v_oc_desc` = :descOrdenCompra,
				`v_mp_id` = :mercadoPagoId,
				`v_cobrado` = :cobrado,
				`v_fecha_cobrado` = :fechaCobrado,
				`v_pago_liberado` = :pagoLiberado,
				`v_despachado` = :despachado,
				`v_fecha_despachado` = :fechaDespachado,
				`v_autoriza_despacho` = :usuDespacho,
				`v_transporte` = :transporte,
				`v_codigo_tracking` = :codigoTracking,
				`v_terminada` = :terminada,
				`v_fecha_terminado` = :fechaTerminada,
				`v_autoriza_final` = :usuFinal
				WHERE `v_id` = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
		$stmt->bindParam(':codigo', $this->codigo, PDO::PARAM_STR);
		$stmt->bindParam(':proforma', $proforma, PDO::PARAM_INT);
		$stmt->bindParam(':precio', $this->precio, PDO::PARAM_STR);
		$stmt->bindParam(':cantidad', $this->cantidad, PDO::PARAM_INT);
		$stmt->bindParam(':fechaInicio', $this->fechaInicio, PDO::PARAM_STR);
		$stmt->bindParam(':reserva', $this->reserva, PDO::PARAM_STR);
		$stmt->bindParam(':uuid', $this->uuid, PDO::PARAM_STR);
		$stmt->bindParam(':ordenCompra', $this->ordenCompra, PDO::PARAM_INT);
		$stmt->bindParam(':descOrdenCompra', $this->descOrdenCompra, PDO::PARAM_INT);
		$stmt->bindParam(':mercadoPagoId', $this->mercadoPagoId, PDO::PARAM_STR);
		$stmt->bindParam(':cobrado', $this->cobrado, PDO::PARAM_INT);
		$stmt->bindParam(':pagoLiberado', $this->pagoLiberado, PDO::PARAM_INT);
		$stmt->bindParam(':fechaCobrado', $this->fechaCobrado, PDO::PARAM_STR);
		$stmt->bindParam(':despachado', $this->despachado, PDO::PARAM_INT);
		$stmt->bindParam(':fechaDespachado', $this->fechaDespachado, PDO::PARAM_STR);
		$stmt->bindParam(':usuDespacho', $this->usuDespacho, PDO::PARAM_STR);
		$stmt->bindParam(':transporte', $this->transporte, PDO::PARAM_STR);
		$stmt->bindParam(':codigoTracking', $this->codigoTracking, PDO::PARAM_STR);
		$stmt->bindParam(':terminada', $this->terminado, PDO::PARAM_INT);
		$stmt->bindParam(':fechaTerminada', $this->fechaFinal, PDO::PARAM_STR);
		$stmt->bindParam(':usuFinal', $this->usuFinal, PDO::PARAM_STR);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
	}
	/**
	 * Inserta nuevo registro a la tabla "venta" usando las propiedades del Objeto ...
	 */
	public function agregaADb(){
		$proforma=$this->proforma->getId();
		if($this->descOrdenCompra==null){$this->descOrdenCompra=0;}
		if($this->pagoLiberado==null){$this->pagoLiberado=0;}
		$con=Conexion::conectar();
		$query="INSERT INTO `venta`
				(`stk_codigo`,
				`p_id`,
				`v_precio`,
				`v_cantidad`,
				`v_fecha_inicio`,
				`v_reserva`,
				`v_uuid`,
				`oc_id`,
				`v_oc_desc`,
				`v_mp_id`,
				`v_cobrado`,
				`v_fecha_cobrado`,
				`v_pago_liberado`,
				`v_despachado`,
				`v_fecha_despachado`,
				`v_autoriza_despacho`,
				`v_transporte`,
				`v_codigo_tracking`,
				`v_terminada`,
				`v_fecha_terminado`,
				`v_autoriza_final`)
				VALUES
				(:codigo,
				:proforma,
				:precio,
				:cantidad,
				:fechaInicio,
				:reserva,
				:uuid,
				:ordenCompra,
				:descOrdenCompra,
				:mercadoPagoId,
				:cobrado,
				:fechaCobrado,
				:pagoLiberado,
				:despachado,
				:fechaDespachado,
				:usuDespacho,
				:transporte,
				:codigoTracking,
				:terminada,
				:fechaTerminada,
				:usuFinal)";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':codigo', $this->codigo, PDO::PARAM_STR);
		$stmt->bindParam(':proforma', $proforma, PDO::PARAM_INT);
		$stmt->bindParam(':precio', $this->precio, PDO::PARAM_STR);
		$stmt->bindParam(':cantidad', $this->cantidad, PDO::PARAM_INT);
		$stmt->bindParam(':fechaInicio', $this->fechaInicio, PDO::PARAM_STR);
		$stmt->bindParam(':reserva', $this->reserva, PDO::PARAM_STR);
		$stmt->bindParam(':uuid', $this->uuid, PDO::PARAM_STR);
		$stmt->bindParam(':ordenCompra', $this->ordenCompra, PDO::PARAM_INT);
		$stmt->bindParam(':descOrdenCompra', $this->descOrdenCompra, PDO::PARAM_INT);
		$stmt->bindParam(':mercadoPagoId', $this->mercadoPagoId, PDO::PARAM_STR);
		$stmt->bindParam(':cobrado', $this->cobrado, PDO::PARAM_INT);
		$stmt->bindParam(':pagoLiberado', $this->pagoLiberado, PDO::PARAM_INT);
		$stmt->bindParam(':fechaCobrado', $this->fechaCobrado, PDO::PARAM_STR);
		$stmt->bindParam(':despachado', $this->despachado, PDO::PARAM_INT);
		$stmt->bindParam(':fechaDespachado', $this->fechaDespachado, PDO::PARAM_STR);
		$stmt->bindParam(':usuDespacho', $this->usuDespacho, PDO::PARAM_STR);
		$stmt->bindParam(':transporte', $this->transporte, PDO::PARAM_STR);
		$stmt->bindParam(':codigoTracking', $this->codigoTracking, PDO::PARAM_STR);
		$stmt->bindParam(':terminada', $this->terminado, PDO::PARAM_INT);
		$stmt->bindParam(':fechaTerminada', $this->fechaFinal, PDO::PARAM_STR);
		$stmt->bindParam(':usuFinal', $this->usuFinal, PDO::PARAM_STR);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
		$this->uFilaIns=$con->lastInsertId();
		$this->setId($this->uFilaIns);
	}
	/**
	 * Borra registro de la tabla "venta" filtrado por ID ...
	 */
	public function borraPorId($ID){
		if(!isset($ID)){
			return $this;
		}
		$con=Conexion::conectar();
		$query = "DELETE FROM venta WHERE v_id = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $ID, PDO::PARAM_STR);
		$stmt -> execute();
		$this->uFilaIns=$con->lastInsertId();
	}
}
