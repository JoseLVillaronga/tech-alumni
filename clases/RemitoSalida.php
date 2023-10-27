<?php
/**
 * @Author : José Luis Villaronga
 * @copyright : 2014
 */
class RemitoSalida
{
	/**
	 * Propiedades
	 */
	private $id;
	public $empresa;
	private $nroRemitoSalida;
	private $codigoCliente;
	private $cantidadPorCaja;
	private $cm=false;
	private $fecha;
	private $entregado=false;
	public $usuario;
	public $ordenCompraId;
	private $tipoOrdenCompra;
	private $importeUnitarioOC;
	public $loteOrdenCompraId;
	private $observaciones;
	public $loteRemitoSalida;
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
			$this->empresa=new Empresa(null);
			$this->usuario=new Usuario(null);
			$this->cm=(bool)$cm;
			$this->entregado=(bool)$this->entregado;
			$this->loteRemitoSalida=new LoteRemitoSalida();
		}else{
			$query="SELECT * FROM remito_salida WHERE rs_id = $c";
			$rs=Db::listar($query);
			$this->id=$rs[0]['rs_id'];
			$this->empresa=new Empresa($rs[0]['emp_id']);
			$this->nroRemitoSalida=$rs[0]['rs_nro_rem_sal'];
			$this->codigoCliente=$rs[0]['rs_codigo_cliente'];
			$this->cantidadPorCaja=$rs[0]['rs_cantidad_caja'];
			$this->cm=(bool)$rs[0]['rs_cm'];
			$this->fecha=$rs[0]['rs_fecha'];
			$this->entregado=(bool)$rs[0]['rs_entregado'];
			$this->ordenCompraId=$rs[0]['oc_id'];
			$this->tipoOrdenCompra=$rs[0]['oc_cv'];
			$this->importeUnitarioOC=$rs[0]['rs_importe_unitario'];
			$this->loteOrdenCompraId=$rs[0]['loc_id'];
			$this->observaciones=$rs[0]['rs_observaciones'];
			$this->usuario=new Usuario($rs[0]['cli_usuario']);
			$this->loteRemitoSalida=new LoteRemitoSalida($this->id);
		}
	}
	/**
	 * Getters ...
	 */
	public function getId(){
		return $this->id;
	}
	public function getEmpresaId(){
		return $this->empresa->getId();
	}
	public function getNroRemitoSalida(){
		return $this->nroRemitoSalida;
	}
	public function getCodigoCliente(){
		return $this->codigoCliente;
	}
	public function getCantidadPorCaja(){
		return $this->cantidadPorCaja;
	}
	public function getCM(){
		return $this->cm;
	}
	public function getFecha(){
		return $this->fecha;
	}
	public function getEntregado(){
		return $this->entregado;
	}
	public function getUsuario(){
		return $this->usuario->getUsuario();
	}
	public function getObservaciones(){
		return $this->observaciones;
	}
	public function getOrdenCompraId(){
		return $this->ordenCompraId;
	}
	public function getTipoOrdenCompra(){
		return $this->tipoOrdenCompra;
	}
	public function getImporteUnitarioOC(){
		return $this->importeUnitarioOC;
	}
	public function getLoteOrdenCompraId(){
		return $this->loteOrdenCompraId;
	}
	/**
	 * Setters ...
	 */
	public function setId($nId){
		$this->id=$nId;
	}
	public function setEmpresa($nE){
		if($nE=="0" OR empty($nE)){
			$this->errores['gen'] = "harError";
			$this->errores['empId']="Hay que elegir cliente ...";
			return $this;
		}else{
			$this->empresa=new Empresa($nE);
		}
		
	}
	public function setNroRemitoSalida($nR){
		if(empty($nR)){
			$this->errores['gen'] = "harError";
			$this->errores['nroRemitoSalida']="No se espera valor nulo ...";
			return $this;
		}else{
			$this->nroRemitoSalida=$nR;
		}
	}
	public function setCodigoCliente($nC){
		$this->codigoCliente=$nC;
	}
	public function setCantidadPorCaja($nCPC){
		if(empty($nCPC)){
			$this->errores['gen'] = "harError";
			$this->errores['cantidadPorCaja']="No se espera valor nulo ...";
			return $this;
		}else{
			if(is_numeric($nCPC)){
				$this->cantidadPorCaja=$nCPC;
			}else{
				$this->errores['gen'] = "harError";
				$this->errores['cantidadPorCaja']="Se espera valor numérico ...";
				return $this;
			}
		}
	}
	public function setCM($nCM){
		if(empty($nCM)){$nCM=false;}
		$this->cm=(bool)$nCM;
	}
	public function setFecha($nF){
		if(empty($nF)){
			$this->fecha=date('Y-m-d H:i:s');
		}else{
			$this->fecha=$nF;
		}
	}
	public function setEntregado($nE){
		if(empty($nE)){$nE=false;}
		$this->entregado=(bool)$nE;
	}
	public function setUsuario($nU){
		$this->usuario=new Usuario($nU);
	}
	public function setObservaciones($nO){
		$this->observaciones=$nO;
	}
	public function setOrdenCompraId($nOC){
		$this->ordenCompraId=$nOC;
	}
	public function setTipoOrdenCompra($nTOC){
		$this->tipoOrdenCompra=$nTOC;
	}
	public function setImporteUnitarioOC($nIUOC){
		$this->importeUnitarioOC=$nIUOC;
	}
	public function setLoteOrdenCompraId($nLOC){
		$this->loteOrdenCompraId=$nLOC;
	}
	/**
	 * Actualiza la tabla "remito_salida" con las propiedades actuales del objeto ...
	 */
	public function actualizaDb(){
		$con=Conexion::conectar();
		$emp=$this->getEmpresaId();
		$usu=$this->getUsuario();
		if($this->cm){$cm="1";}else{$cm="0";}
		if($this->entregado){$entre="1";}else{$entre="0";}
		$query = "UPDATE `remito_salida`
					SET
					`rs_id` = :id,
					`emp_id` = :empId,
					`rs_nro_rem_sal` = :nroRemitoSalida,
					`rs_codigo_cliente` = :codigoCliente,
					`rs_cantidad_caja` = :cantidadCaja,
					`rs_cm` = :CM,
					`rs_entregado` = :entregado,
					`oc_id` = :ordenCompraId,
					`oc_cv` = :tipoOrdenCompra,
					`rs_importe_unitario` = :importeUnitarioOC,
					`loc_id` = :loteOrdenCompraId,
					`rs_observaciones` = :observaciones,
					`cli_usuario` = :usuario
					WHERE `rs_id` = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
		$stmt->bindParam(':empId', $emp, PDO::PARAM_INT);
		$stmt->bindParam(':nroRemitoSalida', $this->nroRemitoSalida, PDO::PARAM_STR);
		$stmt->bindParam(':codigoCliente', $this->codigoCliente, PDO::PARAM_STR);
		$stmt->bindParam(':cantidadCaja', $this->cantidadPorCaja, PDO::PARAM_INT);
		$stmt->bindParam(':CM', $cm, PDO::PARAM_INT);
		$stmt->bindParam(':entregado', $entre, PDO::PARAM_STR);
		$stmt->bindParam(':ordenCompraId', $this->ordenCompraId, PDO::PARAM_INT);
		$stmt->bindParam(':tipoOrdenCompra', $this->tipoOrdenCompra, PDO::PARAM_INT);
		$stmt->bindParam(':importeUnitarioOC', $this->importeUnitarioOC, PDO::PARAM_STR);
		$stmt->bindParam(':loteOrdenCompraId', $this->loteOrdenCompraId, PDO::PARAM_INT);
		$stmt->bindParam(':observaciones', $this->observaciones, PDO::PARAM_STR);
		$stmt->bindParam(':usuario', $usu, PDO::PARAM_STR);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
	}
	/**
	 * Inserta nuevo registro a la tabla "remito_salida" usando las propiedades del Objeto ...
	 */
	public function agregaADb(){
		$con=Conexion::conectar();
		$emp=$this->getEmpresaId();
		$usu=$this->getUsuario();
		if($this->cm){$cm="1";}else{$cm="0";}
		if($this->entregado){$entre="1";}else{$entre="0";}
		$query="INSERT INTO `remito_salida`
				(`rs_id`,
				`emp_id`,
				`rs_nro_rem_sal`,
				`rs_codigo_cliente`,
				`rs_cantidad_caja`,
				`rs_cm`,
				`rs_fecha`,
				`rs_entregado`,
				`oc_id`,
				`oc_cv`,
				`rs_importe_unitario`,
				`loc_id`,
				`rs_observaciones`,
				`cli_usuario`)
				VALUES
				(null,
				:empId,
				:nroRemitoSalida,
				:codigoCliente,
				:cantidadCaja,
				:CM,
				:fecha,
				:entregado,
				:ordenCompraId,
				:tipoOrdenCompra,
				:importeUnitarioOC,
				:loteOrdenCompraId,
				:observaciones,
				:usuario)";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':empId', $emp, PDO::PARAM_INT);
		$stmt->bindParam(':nroRemitoSalida', $this->nroRemitoSalida, PDO::PARAM_STR);
		$stmt->bindParam(':codigoCliente', $this->codigoCliente, PDO::PARAM_STR);
		$stmt->bindParam(':cantidadCaja', $this->cantidadPorCaja, PDO::PARAM_INT);
		$stmt->bindParam(':CM', $cm, PDO::PARAM_INT);
		$stmt->bindParam(':fecha', $this->fecha, PDO::PARAM_STR);
		$stmt->bindParam(':entregado', $entre, PDO::PARAM_STR);
		$stmt->bindParam(':ordenCompraId', $this->ordenCompraId, PDO::PARAM_INT);
		$stmt->bindParam(':tipoOrdenCompra', $this->tipoOrdenCompra, PDO::PARAM_INT);
		$stmt->bindParam(':importeUnitarioOC', $this->importeUnitarioOC, PDO::PARAM_STR);
		$stmt->bindParam(':loteOrdenCompraId', $this->loteOrdenCompraId, PDO::PARAM_INT);
		$stmt->bindParam(':observaciones', $this->observaciones, PDO::PARAM_STR);
		$stmt->bindParam(':usuario', $usu, PDO::PARAM_STR);
		$stmt->execute();
		$this->errorSql = $stmt->errorInfo();
		$this->uFilaIns=$con->lastInsertId();
		$this->setId($this->uFilaIns);
	}
	/**
	 * Borra registro de la tabla "remito_salida" filtrado por ID ...
	 */
	public function borraPorId($ID){
		if(!isset($ID)){
			return $this;
		}
		$con=Conexion::conectar();
		$query = "DELETE FROM remito_salida WHERE rs_id = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $ID, PDO::PARAM_STR);
		$stmt -> execute();
		$this->uFilaIns=$con->lastInsertId();
	}
	private function exporta1(){
		$query="SELECT * FROM remito_salida_lote_not_null_view WHERE rs_id = ".$this->id;
		$query2="SELECT * FROM remito_salida_lote_null_view WHERE rs_id = ".$this->id;
		$res=Db::listar($query);
		$res2=Db::listar($query2);
		$res3=array_merge($res,$res2);
		foreach ($res3 as $fil) {
			$rsl[]=$fil['rsl_id'];
		}
		array_multisort($rsl, SORT_ASC, $res3);
		return $res3;
	}
	private function exporta2(){
	  	if($this->cm=="1"){
	  		$query="SELECT remito_salida_lote.lstk_id,rsl_id,remito_salida_lote.rs_id,rs_nro_rem_sal,rsl_serie,rsl_nro_item,rsl_nro_caja,rsl_fecha,remito_salida_lote.cli_usuario,MAX(stk_id) AS stk_id,stk_nro_rem_cli
					FROM remito_salida,remito_salida_lote,(SELECT * FROM rem_salida_ref_vista ORDER BY stk_id DESC) AS rem_salida_ref_vista
					WHERE rsl_serie = lstk_mac
					AND remito_salida_lote.rs_id = remito_salida.rs_id
					AND remito_salida_lote.rs_id = ".$this->id." 
					GROUP BY rsl_serie
					ORDER BY rsl_nro_caja,stk_nro_rem_cli,stk_id DESC";
	  	}else{
	  		$query="SELECT remito_salida_lote.lstk_id,rsl_id,remito_salida_lote.rs_id,rs_nro_rem_sal,rsl_serie,rsl_nro_item,rsl_nro_caja,rsl_fecha,remito_salida_lote.cli_usuario,MAX(stk_id) AS stk_id,stk_nro_rem_cli
					FROM remito_salida,remito_salida_lote,(SELECT * FROM rem_salida_ref_vista ORDER BY stk_id DESC) AS rem_salida_ref_vista
					WHERE rsl_serie = lstk_serie
					AND remito_salida_lote.rs_id = remito_salida.rs_id
					AND remito_salida_lote.rs_id = ".$this->id." 
					GROUP BY rsl_serie
					ORDER BY rsl_nro_caja,stk_nro_rem_cli,stk_id DESC";
	  	}
		$res=Db::listar($query);
		foreach ($res as $fil) {
			$rsl[]=$fil['rsl_id'];
		}
		array_multisort($rsl, SORT_ASC, $res);
		return $res;
	}
	public function exporta(){
		$f=date("Y-m-d",strtotime($this->fecha));
		if($f > "2017-11-21"){
			$r=$this->exporta1();
			return $r;
		}else{
			$r=$this->exporta2();
			return $r;
		}
	}
	public function exportaMasivo($idss){
		$query="SELECT * FROM remito_salida_lote_not_null_view WHERE rs_id IN (".$idss.")";
		$query2="SELECT * FROM remito_salida_lote_null_view WHERE rs_id IN (".$idss.")";
		$res=Db::listar($query);
		$res2=Db::listar($query2);
		$res3=array_merge($res,$res2);
		foreach ($res3 as $fil) {
			$rsl[]=$fil['rsl_id'];
		}
		array_multisort($rsl, SORT_ASC, $res3);
		return $res3;
	}
}
