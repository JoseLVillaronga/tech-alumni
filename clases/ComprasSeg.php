<?php
/**
 * @Author : José Luis Villaronga
 * @copyright : 2016
 */
class ComprasSeg
{
	/**
	 * Propiedades
	 */
	private $id;
	public $proveedor;
	public $repuesto;
	private $cantidad;
	private $fecha;
	private $productiva;
	private $autoriza;
	private $idTCK;
	private $montoMax=0;
	private $fechaAutoriza;
	private $autorizaTech;
	private $fechaAutorizaTech;
	private $fechaEstimadaRecibido;
	private $fechaRecibido;
	private $remitoNro;
	private $ordenCompra;
	private $observaciones;
	private $evaluacionPlazoEntrega;
	private $evaluacionCalidadProducto;
	private $evaluacionDocumentacion;
	private $evaluacionObservaciones;
	private $habilitado;
	private $fechaFinal;
	public $mailAutoriza=false;
	public $mailAutoriza2=false;
	public $mailAutoriza3=false;
	public $mail=false;
	public $usuario;
	private $centroCosto=null;
	public $lote;
	private $infor=null;
	
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
			$this->proveedor=new Proveedor(null);
			$this->repuesto=new Repuesto(null);
			$this->usuario=new Usuario(null);
			$this->lote=new ComprasSegLote(null);
			return $this;
		}
		$query = "SELECT * FROM compras_seguimiento WHERE comps_id = $c";
		$res=Db::listar($query);
		if(count($res)=="0"){
			$this->proveedor=new Proveedor(null);
			$this->repuesto=new Repuesto(null);
			$this->usuario=new Usuario(null);
			$this->lote=new ComprasSegLote(null);
			return $this;
		}else{
			foreach($res as $fila){
				$this->id=$fila['comps_id'];
				$this->proveedor=new Proveedor($fila['pr_id']);
				$this->repuesto=new Repuesto($fila['comp_id']);
				$this->cantidad=$fila['comps_cantidad'];
				$this->fecha=$fila['comps_fecha'];
				$this->productiva=$fila['comps_productiva'];
				$this->montoMax=$fila['comps_monto_max'];
				$this->autoriza=$fila['comps_autoriza'];
				$this->fechaAutoriza=$fila['comps_fecha_autorizado'];
				$this->autorizaTech=$fila['comps_autoriza_tech'];
				$this->idTCK=$fila['tck_id'];
				$this->fechaAutorizaTech=$fila['comps_fecha_autorizado_tech'];
				$this->fechaEstimadaRecibido=$fila['comps_fecha_est_recepcion'];
				$this->fechaRecibido=$fila['comps_fecha_recepcion'];
				$this->remitoNro=$fila['comps_remito_nro'];
				$this->ordenCompra=$fila['comps_orden_compra'];
				$this->observaciones=$fila['comps_observaciones'];
				$this->evaluacionPlazoEntrega=$fila['comps_eval_plazo_entrega'];
				$this->evaluacionCalidadProducto=$fila['comps_eval_cal_prod'];
				$this->evaluacionDocumentacion=$fila['comps_eval_documentacion'];
				$this->evaluacionObservaciones=$fila['comps_eval_observaciones'];
				$this->habilitado=$fila['comps_habilitado'];
				$this->fechaFinal=$fila['comps_fecha_final'];
				$this->usuario=new Usuario($fila['cli_usuario']);
				$this->centroCosto=$fila['cc_id'];
				$this->lote=new ComprasSegLote($fila['comps_id']);
				$this->infor=$fila['comps_infor'];
			}
		}
	}
	/**
	 * Getters ...
	 */
	public function getId(){
		return $this->id;
	}
	public function getCantidad(){
		return $this->cantidad;
	}
	public function getFecha(){
		return $this->fecha;
	}
	public function getProductiva(){
		return $this->productiva;
	}
	public function getMontoMax(){
		return $this->montoMax;
	}
	public function getIdTCK(){
		return $this->idTCK;
	}
	public function getAutoriza(){
		return $this->autoriza;
	}
	public function getFechaAutoriza(){
		return $this->fechaAutoriza;
	}
	public function getAutorizaTech(){
		return $this->autorizaTech;
	}
	public function getFechaAutorizaTech(){
		return $this->fechaAutorizaTech;
	}
	public function getFechaEstimadaRecibido(){
		return $this->fechaEstimadaRecibido;
	}
	public function getFechaRecibido(){
		return $this->fechaRecibido;
	}
	public function getRemitoNro(){
		return $this->remitoNro;
	}
	public function getOrdenCompra(){
		return $this->ordenCompra;
	}
	public function getObservaciones(){
		return $this->observaciones;
	}
	public function getEvaluacionPlazoEntrega(){
		return $this->evaluacionPlazoEntrega;
	}
	public function getEvaluacionCalidadProducto(){
		return $this->evaluacionCalidadProducto;
	}
	public function getEvaluacionDocumentacion(){
		return $this->evaluacionDocumentacion;
	}
	public function getEvaluacionObservaciones(){
		return $this->evaluacionObservaciones;
	}
	public function getHabilitado(){
		return $this->habilitado;
	}
	public function getFechaFinal(){
		return $this->fechaFinal;
	}
	public function getCentroCostos(){
		return $this->centroCosto;
	}
	public function getInfor(){
		return $this->infor;
	}
	/**
	 * Setters ...
	 */
	public function setId($nId){
		$this->id=$nId;
	}
	public function setCantidad($nC){
		$this->cantidad=$nC;
	}
	public function setFecha($nF){
		$this->fecha=$nF;
	}
	public function setProductiva($nP){
		$this->productiva=$nP;
	}
	public function setMontoMax($nP){
		$this->montoMax=$nP;
	}
	public function setIdTCK($nITCK){
		$this->idTCK=$nITCK;
	}
	public function setAutoriza($nA){
		$this->autoriza=$nA;
	}
	public function setFechaAutoriza($nFA){
		$this->fechaAutoriza=$nFA;
	}
	public function setAutorizaTech($nAT){
		$this->autorizaTech=$nAT;
	}
	public function setFechaAutorizaTech($nFAT){
		$this->fechaAutorizaTech=$nFAT;
	}
	public function setFechaEstimadaRecibido($nFER){
		$this->fechaEstimadaRecibido=$nFER;
	}
	public function setFechaRecibido($nFR){
		$this->fechaRecibido=$nFR;
	}
	public function setRemitoNro($nRN){
		$this->remitoNro=$nRN;
	}
	public function setOrdenCompra($nOC){
		$this->ordenCompra=$nOC;
	}
	public function setObservaciones($nO){
		$this->observaciones=$nO;
	}
	public function setEvaluacionPlazoEntrega($nEPE){
		$this->evaluacionPlazoEntrega=$nEPE;
	}
	public function setEvaluacionCalidadProducto($nECP){
		$this->evaluacionCalidadProducto=$nECP;
	}
	public function setEvaluacionDocumentacion($nED){
		$this->evaluacionDocumentacion=$nED;
	}
	public function setEvaluacionObservaciones($nEO){
		$this->evaluacionObservaciones=$nEO;
	}
	public function setHabilitado($nH){
		$this->habilitado=$nH;
	}
	public function setFechaFinal($nFF){
		$this->fechaFinal=$nFF;
	}
	public function setCentroCostos($nCC){
		$this->centroCosto=$nCC;
	}
	public function setInfor($nInfor){
		$this->infor=$nInfor;
	}
	/**
	 * Actualiza la tabla "compras_seguimiento" con las propiedades actuales del objeto ...
	 */
	public function actualizaDb(){
		$con=Conexion::conectar();
		$prov=$this->proveedor->getId();
		$rep=$this->repuesto->getId();
		$usu=$this->usuario->getUsuario();
		if(is_null($this->montoMax)){$this->montoMax=0;}
		$query = "UPDATE `compras_seguimiento`
					SET
					`comps_id` = :id,
					`pr_id` = :proveedor,
					`comp_id` = :repuesto,
					`comps_cantidad` = :cantidad,
					`comps_fecha` = :fecha,
					`comps_productiva` = :productiva,
					`tck_id` = :ticket,
					`comps_monto_max` = :montoMax,
					`comps_autoriza` = :autoriza,
					`comps_fecha_autorizado` = :fechaAutorizado,
					`comps_autoriza_tech` = :autorizaTech,
					`comps_fecha_autorizado_tech` = :fechaAutorizadoTech,
					`comps_fecha_est_recepcion` = :fechaEstimadaRecibido,
					`comps_fecha_recepcion` = :fechaRecibido,
					`comps_remito_nro` = :remitoNro,
					`comps_orden_compra` = :ordenCompra,
					`comps_observaciones` = :observaciones,
					`comps_eval_plazo_entrega` = :evaPlazoEntrega,
					`comps_eval_cal_prod` = :evalCalidadProducto,
					`comps_eval_documentacion` = :evalDocumentacion,
					`comps_eval_observaciones` = :evalObservaciones,
					`comps_habilitado` = :habilitado,
					`comps_fecha_final` = :fechaFinal,
					`cli_usuario` = :usuario,
					`cc_id` = :centroCostos,
					`comps_infor` = :infor
					WHERE `comps_id` = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
		$stmt->bindParam(':proveedor', $prov, PDO::PARAM_INT);
		$stmt->bindParam(':repuesto', $rep, PDO::PARAM_INT);
		$stmt->bindParam(':cantidad', $this->cantidad, PDO::PARAM_INT);
		$stmt->bindParam(':fecha', $this->fecha, PDO::PARAM_STR);
		$stmt->bindParam(':productiva', $this->productiva, PDO::PARAM_STR);
		$stmt->bindParam(':montoMax', $this->montoMax, PDO::PARAM_STR);
		$stmt->bindParam(':ticket', $this->idTCK, PDO::PARAM_INT);
		$stmt->bindParam(':autoriza', $this->autoriza, PDO::PARAM_STR);
		$stmt->bindParam(':fechaAutorizado', $this->fechaAutoriza, PDO::PARAM_STR);
		$stmt->bindParam(':autorizaTech', $this->autorizaTech, PDO::PARAM_STR);
		$stmt->bindParam(':fechaAutorizadoTech', $this->fechaAutorizaTech, PDO::PARAM_STR);
		$stmt->bindParam(':fechaEstimadaRecibido', $this->fechaEstimadaRecibido, PDO::PARAM_STR);
		$stmt->bindParam(':fechaRecibido', $this->fechaRecibido, PDO::PARAM_STR);
		$stmt->bindParam(':remitoNro', $this->remitoNro, PDO::PARAM_STR);
		$stmt->bindParam(':ordenCompra', $this->ordenCompra, PDO::PARAM_STR);
		$stmt->bindParam(':observaciones', $this->observaciones, PDO::PARAM_STR);
		$stmt->bindParam(':evaPlazoEntrega', $this->evaluacionPlazoEntrega, PDO::PARAM_INT);
		$stmt->bindParam(':evalCalidadProducto', $this->evaluacionCalidadProducto, PDO::PARAM_INT);
		$stmt->bindParam(':evalDocumentacion', $this->evaluacionDocumentacion, PDO::PARAM_INT);
		$stmt->bindParam(':evalObservaciones', $this->evaluacionObservaciones, PDO::PARAM_STR);
		$stmt->bindParam(':habilitado', $this->habilitado, PDO::PARAM_INT);
		$stmt->bindParam(':fechaFinal', $this->fechaFinal, PDO::PARAM_STR);
		$stmt->bindParam(':usuario', $usu, PDO::PARAM_STR);
		$stmt->bindParam(':centroCostos', $this->centroCosto, PDO::PARAM_INT);
		$stmt->bindParam(':infor', $this->infor, PDO::PARAM_STR);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
	}
	/**
	 * Inserta nuevo registro a la tabla "ccompras_seguimiento" usando las propiedades del Objeto ...
	 */
	public function agregaADb(){
		$con=Conexion::conectar();
		$prov=$this->proveedor->getId();
		$rep=$this->repuesto->getId();
		$usu=$this->usuario->getUsuario();
		$query="INSERT INTO `compras_seguimiento`
				(`comps_id`,
				`pr_id`,
				`comp_id`,
				`comps_cantidad`,
				`comps_fecha`,
				`comps_productiva`,
				`tck_id`,
				`comps_monto_max`,
				`comps_autoriza`,
				`comps_fecha_autorizado`,
				`comps_autoriza_tech`,
				`comps_fecha_autorizado_tech`,
				`comps_fecha_est_recepcion`,
				`comps_fecha_recepcion`,
				`comps_remito_nro`,
				`comps_orden_compra`,
				`comps_observaciones`,
				`comps_eval_plazo_entrega`,
				`comps_eval_cal_prod`,
				`comps_eval_documentacion`,
				`comps_eval_observaciones`,
				`comps_habilitado`,
				`comps_fecha_final`,
				`cli_usuario`,
				`cc_id`,
				`comps_infor`)
				VALUES
				(null,
				:proveedor,
				:repuesto,
				:cantidad,
				:fecha,
				:productiva,
				:ticket,
				:montoMax,
				:autoriza,
				:fechaAutorizado,
				:autorizaTech,
				:fechaAutorizadoTech,
				:fechaEstimadaRecibido,
				:fechaRecibido,
				:remitoNro,
				:ordenCompra,
				:observaciones,
				:evaPlazoEntrega,
				:evalCalidadProducto,
				:evalDocumentacion,
				:evalObservaciones,
				:habilitado,
				:fechaFinal,
				:usuario,
				:centroCostos,
				:infor)";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':proveedor', $prov, PDO::PARAM_INT);
		$stmt->bindParam(':repuesto', $rep, PDO::PARAM_INT);
		$stmt->bindParam(':cantidad', $this->cantidad, PDO::PARAM_INT);
		$stmt->bindParam(':fecha', $this->fecha, PDO::PARAM_STR);
		$stmt->bindParam(':productiva', $this->productiva, PDO::PARAM_STR);
		$stmt->bindParam(':ticket', $this->idTCK, PDO::PARAM_INT);
		$stmt->bindParam(':autoriza', $this->autoriza, PDO::PARAM_STR);
		$stmt->bindParam(':fechaAutorizado', $this->fechaAutoriza, PDO::PARAM_STR);
		$stmt->bindParam(':montoMax', $this->montoMax, PDO::PARAM_STR);
		$stmt->bindParam(':autorizaTech', $this->autorizaTech, PDO::PARAM_STR);
		$stmt->bindParam(':fechaAutorizadoTech', $this->fechaAutorizaTech, PDO::PARAM_STR);
		$stmt->bindParam(':fechaEstimadaRecibido', $this->fechaEstimadaRecibido, PDO::PARAM_STR);
		$stmt->bindParam(':fechaRecibido', $this->fechaRecibido, PDO::PARAM_STR);
		$stmt->bindParam(':remitoNro', $this->remitoNro, PDO::PARAM_STR);
		$stmt->bindParam(':ordenCompra', $this->ordenCompra, PDO::PARAM_STR);
		$stmt->bindParam(':observaciones', $this->observaciones, PDO::PARAM_STR);
		$stmt->bindParam(':evaPlazoEntrega', $this->evaluacionPlazoEntrega, PDO::PARAM_INT);
		$stmt->bindParam(':evalCalidadProducto', $this->evaluacionCalidadProducto, PDO::PARAM_INT);
		$stmt->bindParam(':evalDocumentacion', $this->evaluacionDocumentacion, PDO::PARAM_INT);
		$stmt->bindParam(':evalObservaciones', $this->evaluacionObservaciones, PDO::PARAM_STR);
		$stmt->bindParam(':habilitado', $this->habilitado, PDO::PARAM_INT);
		$stmt->bindParam(':fechaFinal', $this->fechaFinal, PDO::PARAM_STR);
		$stmt->bindParam(':usuario', $usu, PDO::PARAM_STR);
		$stmt->bindParam(':centroCostos', $this->centroCosto, PDO::PARAM_INT);
		$stmt->bindParam(':infor', $this->infor, PDO::PARAM_STR);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
		$this->uFilaIns=$con->lastInsertId();
		$this->setId($this->uFilaIns);
	}
	/**
	 * Borra registro de la tabla "compras_seguimiento" filtrado por ID ...
	 */
	public function borraPorId($ID){
		if(!isset($ID)){
			return $this;
		}
		$con=Conexion::conectar();
		$query = "DELETE FROM compras_seguimiento WHERE comps_id = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $ID, PDO::PARAM_STR);
		$stmt -> execute();
		$this->uFilaIns=$con->lastInsertId();
	}
}
