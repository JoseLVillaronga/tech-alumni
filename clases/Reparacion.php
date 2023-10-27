<?php
/**
 * @author : José Luis Villaronga
 * @copyright : 2014
 */
class Reparacion
{
	/**
	 * Propiedades
	 */
	private $id;
	public $articulo;
	private $cantidad;
	private $serie;
	private $mac;
	private $partN;
	public $empresa;
	private $repuesto=array();
	private $observaciones;
	private $infor=null;
	private $sinGarantia;
	public $falla;
	public $tarea;
	private $fechaInicio;
	private $fechaFinal;
	private $fechaVence;
	private $terminado;
	private $scrap=0;
	private $loteStockId;
	public $usuario;
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
			$this->articulo=new Articulo(null);
			$this->empresa=new Empresa(null);
			$this->falla=new Falla(null);
			$this->usuario=new Usuario(null);
			return $this;
		}
		$query = "SELECT * FROM reparacion WHERE rep_id = $c";
		foreach (Db::listar($query) as $fila){
			$this->id=$fila['rep_id'];
			$this->articulo=new Articulo($fila['art_id']);
			$this->cantidad=$fila['rep_cantidad'];
			$this->serie=$fila['rep_serie'];
			$this->mac=$fila['rep_mac'];
			$this->partN=$fila['rep_part_nro'];
			$this->empresa=new Empresa($fila['emp_id']);
			$this->repuesto["0"]=$fila['rep_repuesto1'];
			$this->repuesto["1"]=$fila['rep_repuesto2'];
			$this->repuesto["2"]=$fila['rep_repuesto3'];
			$this->repuesto["3"]=$fila['rep_repuesto4'];
			$this->repuesto["4"]=$fila['rep_repuesto5'];
			$this->observaciones=$fila['rep_observaciones'];
			$this->falla=new Falla($fila['falla_id']);
			$this->tarea=new Tarea($fila['tar_id']);
			$this->fechaInicio=$fila['rep_fecha_inicio'];
			$this->fechaFinal=$fila['rep_fecha_final'];
			$this->fechaVence=$fila['rep_fecha_vence'];
			$this->terminado=$fila['rep_terminado'];
			$this->sinGarantia=$fila['rep_sin_garantia'];
			$this->scrap=$fila['rep_scrap'];
			$this->usuario=new Usuario($fila['cli_usuario']);
			$this->infor=$fila['rep_informe'];
			$this->loteStockId=$fila['lstk_id'];
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
	public function getSerie(){
		return $this->serie;
	}
	public function getMac(){
		return $this->mac;
	}
	public function getPartN(){
		return $this->partN;
	}
	public function getRepu1(){
		return $this->repuesto['0'];
	}
	public function getRepu2(){
		return $this->repuesto['1'];
	}
	public function getRepu3(){
		return $this->repuesto['2'];
	}
	public function getRepu4(){
		return $this->repuesto['3'];
	}
	public function getRepu5(){
		return $this->repuesto['4'];
	}
	public function getObservaciones(){
		return $this->observaciones;
	}
	public function getInfor(){
		return $this->infor;
	}
	public function getFechaInicio(){
		return $this->fechaInicio;
	}
	public function getFechaFinal(){
		return $this->fechaFinal;
	}
	public function getFechaVence(){
		return $this->fechaVence;
	}
	public function getTerminado(){
		return $this->terminado;
	}
	public function getFallaCisco(){
		$p=$this->falla->getId();
		$query = "SELECT * FROM fallas_cisco WHERE falla_id = $p";
		$res=Db::listar($query);
		foreach ($res as $fila){
			if(strpos($this->articulo->modelo->getNombre(),"(EMTA)")){
				if(is_null($fila['falla_id'])){
					return "SV12";
				}else{
					return $fila['fci_emta'];
				}
			}elseif($this->articulo->modelo->getNombre()=="DPC2325"){
				if(is_null($fila['falla_id'])){
					return "P1";
				}else{
					return $fila['fci_2325'];
				}
			}else{
				if(is_null($fila['falla_id'])){
					return "P1";
				}else{
					return $fila['fci_nombre'];
				}
			}
		}
		return $fc;
	}
	public function getSinGarantia(){
		return $this->sinGarantia;
	}
	public function getScrap(){
		return $this->scrap;
	}
	public function getLoteStockId(){
		return $this->loteStockId;
	}
		/**
	 * Setters ...
	 */
	public function setId($nId){
		$this->id=$nId;
	}
	public function setArticulo($nArticulo){
		if(empty($nArticulo)){
			$this->errores['articulo']="Hay que ingresar articulo ...";
			$this->errores['gen'] = "harError";
		}else{
			$this->articulo=new Articulo($nArticulo);
			;
		}
	}
	public function setCantidad($nCantidad){
		if(empty($nCantidad)){
			$this->errores['cantidad']="Hay que ingresar cantidad ...";
			$this->errores['gen'] = "harError";
		}elseif(!is_numeric($nCantidad)){
			$this->errores['cantidad']="Ingrese un valor numérico entero ...";
			$this->errores['gen'] = "harError";
		}elseif($nCantidad < "1"){
			$this->errores['cantidad']="Ingrese un valor positivo ...";
			$this->errores['gen'] = "harError";
		}else{
			$this->cantidad=$nCantidad;
		}
	}
	public function setSerie($nSerie){
		if($this->cantidad != "1"){
			return $this;
		}else{
			$check1=array();
			$check2=array();
			if(empty($nSerie)){
				$query="SELECT art_id FROM articulos_vista 
				WHERE articulo LIKE '%Cablemodem * Cisco%'";
				
				foreach(Db::listar($query) as $fila){
					$check1[]=$fila['art_id'];
				}
				$art=$this->articulo->getId();
				if(in_array($art, $check1)){
					$this->errores['serie']="Ingrese número de serie ...";
					$this->errores['gen'] = "harError";
					$check2=$check1;
					unset($check1);
					return $this;
				}else{
					$this->serie=null;
				}
			}else{
				if(in_array($art, $check2)){
					if(ctype_xdigit($nSerie) AND strlen($nSerie)=="12"){
						$this->errores['serie']="No se espera MAC ...";
						$this->errores['gen'] = "harError";
						return $this;
					}else{
						$this->serie=$nSerie;
					}
				}else{
					$this->serie=$nSerie;
				}
			}
		}
	}
	public function setMac($nMac){
		if($this->cantidad != "1"){
			return $this;
		}elseif (empty($nMac)){
			$this->mac=$nMac;
		} elseif (strlen($nMac) < 12) {
			$this->errores['mac'] = "... La MAC del CableModem debe tener al menos 12 caracteres ...";
			$this->errores['gen'] = "harError";
		} elseif (strlen($nMac) > 12) {
			$this->errores['mac'] = "... La MAC del CableModem no debe tener mas de 12 caracteres ...";
			$this->errores['gen'] = "harError";
		} elseif (!ctype_xdigit($nMac)) {
			$this->errores['mac'] = "La MAC debe ser hexadesimal";
			$this->errores['gen'] = "harError";
		}else{
			$this->mac=$nMac;
		}
	}
	public function setPartN($nPN){
		$this->partN=$nPN;
	}
	public function setEmpresa($nEmpresa){
		if($nEmpresa == "0"){
			$this->errores['empresa'] = "Seleccione Cliente ...";
			$this->errores['gen'] = "harError";
			return $this;
		}
		$this->empresa=new Empresa($nEmpresa);
	}
	public function setRepu1($nRepu1){
		$this->repuesto['0']=$nRepu1;
	}
	public function setRepu2($nRepu2){
		$this->repuesto['1']=$nRepu2;
	}
	public function setRepu3($nRepu3){
		$this->repuesto['2']=$nRepu3;
	}
	public function setRepu4($nRepu4){
		$this->repuesto['3']=$nRepu4;
	}
	public function setRepu5($nRepu5){
		$this->repuesto['4']=$nRepu5;
	}
	public function setObservaciones($nObservacion){
		$this->observaciones=$nObservacion;
	}
	public function setInfor($nInfor){
		$this->infor=$nInfor;
	}
	public function setFalla($nFalla){
		if(!isset($nFalla)){
			$this->errores['falla']="Hay que ingresar falla ...";
			$this->errores['gen'] = "harError";
		}elseif($nFalla == "0"){
			$this->errores['falla']="Hay que ingresar falla ...";
			$this->errores['gen'] = "harError";
		}else{
			$this->falla=new Falla($nFalla);
		}
	}
	public function setTarea($nTarea){
		if(!isset($nTarea) OR $nTarea=="0"){
			$this->errores['tarea']="Hay que ingresar tarea ...";
			$this->errores['gen'] = "harError";
		}else{
			$this->tarea=new Tarea($nTarea);
		}
	}
	public function setFechaInicio($nFI){
		$this->fechaInicio=$nFI;
	}
	public function setFechaFinal($nFF){
		$this->fechaFinal=$nFF;
	}
	public function setFechaVence($nFV){
		$this->fechaVence=$nFV;
	}
	public function setTerminado($nTerm){
		if(!isset($nTerm)){
			$this->terminado="0";
		}elseif($nTerm > 0){
			$this->terminado=$nTerm;
			$this->fechaFinal=date("Y-m-d H:i:s");
		}else{
			return $this;
		}
	}
	public function setTerminadoTodos(){
		$con=Conexion::conectar();
		$usu=$_SESSION['usuario'];
		$fechaFinal=date('Y-m-d H:i:s');
		$term="1";
		$query = "UPDATE `reparacion`
					SET
					`rep_fecha_final` = :fechaFinal,
					`rep_terminado` = :terminado
					WHERE `cli_usuario` = :usuario
					AND `rep_terminado` = 0";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':fechaFinal', $fechaFinal, PDO::PARAM_STR);
		$stmt->bindParam(':terminado', $term, PDO::PARAM_INT);
		$stmt->bindParam(':usuario', $usu, PDO::PARAM_STR);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
	}
	public function setScrapTodas(){
		$con=Conexion::conectar();
		$usu=$_SESSION['usuario'];
		foreach(Db::listar("SELECT rep_serie FROM reparacion WHERE rep_terminado = 0 AND cli_usuario = '$usu'") as $fila){
			$obj=Busca::buscaItemLoteStock($fila['rep_serie']);
			if(is_object($obj)){
				$obj->loteStock->setStockStateId(12);
				$obj->loteStock->actualizaDb();
			}
		}
		$fechaFinal=date('Y-m-d H:i:s');
		$term="1";
		$query = "UPDATE `reparacion`
					SET
					`rep_fecha_final` = :fechaFinal,
					`rep_terminado` = :terminado,
					`rep_scrap` = 1
					WHERE `cli_usuario` = :usuario
					AND `rep_terminado` = 0";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':fechaFinal', $fechaFinal, PDO::PARAM_STR);
		$stmt->bindParam(':terminado', $term, PDO::PARAM_INT);
		$stmt->bindParam(':usuario', $usu, PDO::PARAM_STR);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
	}
	public function setReasignar($usuario){
		$con=Conexion::conectar();
		$usu=$_SESSION['usuario'];
		$term="0";
		$query = "UPDATE `reparacion`
					SET
					`cli_usuario` = :reasignado
					WHERE `cli_usuario` = :usuario
					AND `rep_terminado` = 0";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':usuario', $usu, PDO::PARAM_STR);
		$stmt->bindParam(':reasignado', $usuario, PDO::PARAM_STR);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
	}
	public function setUsuario($nUsu){
		$this->usuario=new Usuario($nUsu);
	}
	public function setSinGarantia($sG){
		$this->sinGarantia=$sG;
	}
	public function setScrap($nS){
		$this->scrap=$nS;
	}
	public function setLoteStockId($nLSTK){
		$this->loteStockId=$nLSTK;
	}
	public function setDOCSIS(){
		$art=$this->articulo->getId();
		if(!empty($this->mac) AND !empty($this->id)){
			//$cuenta=Db::listar("SELECT * FROM cdr.articulos_cm_vista WHERE art_id = ".$art);
			$docsis=traeDataPorMacSPACM($this->mac);
			if(!empty($docsis['TX'])){
				$con=Conexion::conectar();
				$query="UPDATE `reparacion`
						SET
						`Uptime` = :Uptime,
						`SysName` = :SysName,
						`SysDesc` = :SysDesc,
						`Firmware` = :Firmware,
						`TX` = :TX,
						`RX` = :RX,
						`MER` = :MER,
						`Frec_DS` = :Frec_DS,
						`Frec_US` = :Frec_US
						WHERE `rep_id` = :id";
				$stmt = $con -> prepare($query);
				$stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
				$stmt->bindParam(':Uptime', $docsis['Uptime'], PDO::PARAM_STR);
				$stmt->bindParam(':SysName', $docsis['SysName'], PDO::PARAM_STR);
				$stmt->bindParam(':SysDesc', $docsis['SysDesc'], PDO::PARAM_STR);
				$stmt->bindParam(':Firmware', $docsis['Firmware'], PDO::PARAM_STR);
				$stmt->bindParam(':TX', $docsis['TX'], PDO::PARAM_STR);
				$stmt->bindParam(':RX', $docsis['RX'], PDO::PARAM_STR);
				$stmt->bindParam(':MER', $docsis['MER'], PDO::PARAM_STR);
				$stmt->bindParam(':Frec_DS', $docsis['Frec DS'], PDO::PARAM_STR);
				$stmt->bindParam(':Frec_US', $docsis['Frec US'], PDO::PARAM_STR);
				$stmt -> execute();
				$this->errorSql = $stmt->errorInfo();
			}
		}
	}
	/**
	 * Actualiza la tabla "reparacion" con las propiedades actuales del objeto ...
	 */
	public function actualizaDb(){
		$con=Conexion::conectar();
		$query = "UPDATE `reparacion`
					SET
					`rep_id` = :id,
					`art_id` = :articulo,
					`rep_cantidad` = :cantidad,
					`rep_serie` = :serie,
					`rep_mac` = :mac,
					`rep_part_nro` = :partN,
					`emp_id` = :empresa,
					`rep_repuesto1` = :rep1,
					`rep_repuesto2` = :rep2,
					`rep_repuesto3` = :rep3,
					`rep_repuesto4` = :rep4,
					`rep_repuesto5` = :rep5,
					`rep_observaciones` = :observaciones,
					`falla_id` = :falla,
					`tar_id` = :tarea,
					`rep_fecha_inicio` = :fechaInicio,
					`rep_fecha_final` = :fechaFinal,
					`rep_fecha_vence` = :fechaVence,
					`rep_terminado` = :terminado,
					`cli_usuario` = :usuario,
					`rep_informe` = :infor,
					`rep_sin_garantia` = :sinGarantia,
					`rep_scrap` = :scrap,
					`lstk_id` = :loteStockId
					WHERE `rep_id` = :id";
		$stmt = $con -> prepare($query);
		$art=$this->articulo->getId();
		$emp=$this->empresa->getId();
		$falla=$this->falla->getId();
		$tarea=$this->tarea->getId();
		$usu=$this->usuario->getUsuario();
		$stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
		$stmt->bindParam(':articulo', $art, PDO::PARAM_STR);
		$stmt->bindParam(':cantidad', $this->cantidad, PDO::PARAM_STR);
		$stmt->bindParam(':serie', $this->serie, PDO::PARAM_STR);
		$stmt->bindParam(':mac', $this->mac, PDO::PARAM_STR);
		$stmt->bindParam(':partN', $this->partN, PDO::PARAM_STR);
		$stmt->bindParam(':empresa', $emp, PDO::PARAM_STR);
		$stmt->bindParam(':rep1', $this->repuesto['0'], PDO::PARAM_STR);
		$stmt->bindParam(':rep2', $this->repuesto['1'], PDO::PARAM_STR);
		$stmt->bindParam(':rep3', $this->repuesto['2'], PDO::PARAM_STR);
		$stmt->bindParam(':rep4', $this->repuesto['3'], PDO::PARAM_STR);
		$stmt->bindParam(':rep5', $this->repuesto['4'], PDO::PARAM_STR);
		$stmt->bindParam(':observaciones', $this->observaciones, PDO::PARAM_STR);
		$stmt->bindParam(':falla', $falla, PDO::PARAM_STR);
		$stmt->bindParam(':tarea', $tarea, PDO::PARAM_STR);
		$stmt->bindParam(':fechaInicio', $this->fechaInicio, PDO::PARAM_STR);
		$stmt->bindParam(':fechaFinal', $this->fechaFinal, PDO::PARAM_STR);
		$stmt->bindParam(':fechaVence', $this->fechaVence, PDO::PARAM_STR);
		$stmt->bindParam(':terminado', $this->terminado, PDO::PARAM_STR);
		$stmt->bindParam(':usuario', $usu, PDO::PARAM_STR);
		$stmt->bindParam(':infor', $this->infor, PDO::PARAM_STR);
		$stmt->bindParam(':sinGarantia', $this->sinGarantia, PDO::PARAM_INT);
		$stmt->bindParam(':scrap', $this->scrap, PDO::PARAM_INT);
		$stmt->bindParam(':loteStockId', $this->loteStockId, PDO::PARAM_INT);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
	}
	/**
	 * Inserta nuevo registro a la tabla "reparacion" usando las propiedades del Objeto ...
	 */
	public function agregaADb(){
		$con=Conexion::conectar();
		$query="INSERT INTO `reparacion`
				(`rep_id`,
				`art_id`,
				`rep_cantidad`,
				`rep_serie`,
				`rep_mac`,
				`rep_part_nro`,
				`emp_id`,
				`rep_repuesto1`,
				`rep_repuesto2`,
				`rep_repuesto3`,
				`rep_repuesto4`,
				`rep_repuesto5`,
				`rep_observaciones`,
				`falla_id`,
				`tar_id`,
				`rep_fecha_inicio`,
				`rep_fecha_final`,
				`rep_fecha_vence`,
				`rep_terminado`,
				`cli_usuario`,
				`rep_informe`,
				`rep_sin_garantia`,
				`rep_scrap`,
				`lstk_id`)
				VALUES
				(null,
				:articulo,
				:cantidad,
				:serie,
				:mac,
				:partN,
				:empresa,
				:rep1,
				:rep2,
				:rep3,
				:rep4,
				:rep5,
				:observaciones,
				:falla,
				:tarea,
				:fechaInicio,
				:fechaFinal,
				:fechaVence,
				:terminado,
				:usuario,
				:infor,
				:sinGarantia,
				:scrap,
				:loteStockId)";
		$stmt = $con -> prepare($query);
		$art=$this->articulo->getId();
		$emp=$this->empresa->getId();
		$falla=$this->falla->getId();
		$tarea=$this->tarea->getId();
		$usu=$this->usuario->getUsuario();
		$fini=date("Y-m-d H:i:s");
		$inforQ=$this->getInfor();
		$stmt->bindParam(':articulo', $art, PDO::PARAM_STR);
		$stmt->bindParam(':cantidad', $this->cantidad, PDO::PARAM_STR);
		$stmt->bindParam(':serie', $this->serie, PDO::PARAM_STR);
		$stmt->bindParam(':mac', $this->mac, PDO::PARAM_STR);
		$stmt->bindParam(':partN', $this->partN, PDO::PARAM_STR);
		$stmt->bindParam(':empresa', $emp, PDO::PARAM_STR);
		$stmt->bindParam(':rep1', $this->repuesto['0'], PDO::PARAM_STR);
		$stmt->bindParam(':rep2', $this->repuesto['1'], PDO::PARAM_STR);
		$stmt->bindParam(':rep3', $this->repuesto['2'], PDO::PARAM_STR);
		$stmt->bindParam(':rep4', $this->repuesto['3'], PDO::PARAM_STR);
		$stmt->bindParam(':rep5', $this->repuesto['4'], PDO::PARAM_STR);
		$stmt->bindParam(':observaciones', $this->observaciones, PDO::PARAM_STR);
		$stmt->bindParam(':falla', $falla, PDO::PARAM_STR);
		$stmt->bindParam(':tarea', $tarea, PDO::PARAM_STR);
		$stmt->bindParam(':fechaInicio', $this->fechaInicio, PDO::PARAM_STR);
		$stmt->bindParam(':fechaFinal', $this->fechaFinal, PDO::PARAM_STR);
		$stmt->bindParam(':fechaVence', $this->fechaVence, PDO::PARAM_STR);
		$stmt->bindParam(':terminado', $this->terminado, PDO::PARAM_STR);
		$stmt->bindParam(':usuario', $usu, PDO::PARAM_STR);
		$stmt->bindParam(':infor', $this->infor, PDO::PARAM_STR);
		$stmt->bindParam(':sinGarantia', $this->sinGarantia, PDO::PARAM_INT);
		$stmt->bindParam(':scrap', $this->scrap, PDO::PARAM_INT);
		$stmt->bindParam(':loteStockId', $this->loteStockId, PDO::PARAM_INT);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
		$this->uFilaIns=$con->lastInsertId();
		$this->setId($this->uFilaIns);
	}
	/**
	 * Borra registro de la tabla "reparacion" filtrado por ID ...
	 */
	public function borraPorId($ID){
		if(!isset($ID)){
			return $this;
		}
		$con=Conexion::conectar();
		$query = "DELETE FROM reparacion WHERE rep_id = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $ID, PDO::PARAM_STR);
		$stmt -> execute();
		$this->uFilaIns=$con->lastInsertId();
	}
}
