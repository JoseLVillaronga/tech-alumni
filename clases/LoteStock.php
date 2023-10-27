<?php
/**
 * @Author : José Luis Villaronga
 * @copyright : 2014
 */
class LoteStock
{
	/**
	 * Propiedades
	 */
	private $id;
	private $id2=null;
	private $stockId;
	private $artId;
	private $fecha;
	private $serie;
	private $mac=null;
	private $part=null;
	private $cantidad;
	private $stockStateId="0";
	private $garantiaCisco;
	private $lemonCount;
	private $garantiaTeccam;
	private $chipId;
	private $chipId2;
	private $tarjeta;
	private $usuario;
	private $cajaNro;
	public $historico;
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
	public function __construct($c=null,$cache=null){
		$this->stockId=$c;
		if(empty($cache)){
			$query="SELECT * FROM lote_stock WHERE stk_id = $c";
			if($_SESSION['empresa']=="36"){
				foreach (Db::listar($query) as $fila){
					$this->lote[]=array(
						'lstk_id' => $fila['lstk_id'],
						'lstk_id2' => $fila['lstk_id2'],
						'stk_id' => $fila['stk_id'],
						'art_id' => $fila['art_id'],
						'lstk_fecha' => $fila['lstk_fecha'],
						'lstk_serie' => Funcion::textoStandard($fila['lstk_serie']),
						'lstk_mac' => $fila['lstk_mac'],
						'lstk_part' => $fila['lstk_part'],
						'lstk_cantidad' => $fila['lstk_cantidad'],
						'ss_id' => $fila['ss_id'],
						'lstk_gar_cisco' => $fila['lstk_gar_cisco'],
						'lstk_lemon_count' => $fila['lstk_lemon_count'],
						'lstk_gar_teccam' => $fila['lstk_gar_teccam'],
						'lstk_chipid' => $fila['lstk_chipid'],
						'lstk_chipid2' => $fila['lstk_chipid2'],
						'lstk_tarjeta' => $fila['lstk_tarjeta'],
						'cli_usuario' => $fila['cli_usuario'],
						'stkc_caja_nro' => $fila['stkc_caja_nro'],
					);
				}
			}else{
				foreach (Db::listar($query) as $fila){
					$this->lote[]=array(
						'lstk_id' => $fila['lstk_id'],
						'lstk_id2' => $fila['lstk_id2'],
						'stk_id' => $fila['stk_id'],
						'art_id' => $fila['art_id'],
						'lstk_fecha' => $fila['lstk_fecha'],
						'lstk_serie' => $fila['lstk_serie'],
						'lstk_mac' => $fila['lstk_mac'],
						'lstk_part' => $fila['lstk_part'],
						'lstk_cantidad' => $fila['lstk_cantidad'],
						'ss_id' => $fila['ss_id'],
						'lstk_gar_cisco' => $fila['lstk_gar_cisco'],
						'lstk_lemon_count' => $fila['lstk_lemon_count'],
						'lstk_gar_teccam' => $fila['lstk_gar_teccam'],
						'cli_usuario' => $fila['cli_usuario'],
						'stkc_caja_nro' => $fila['stkc_caja_nro'],
					);
				}
			}
		}else{
			//$query="SELECT * FROM lote_stock WHERE stk_id = $c";
			if($_SESSION['empresa']=="36"){
				foreach ($cache['loteStock'] as $fila){
					if($fila['stk_id']==$c){
						$this->lote[]=array(
							'lstk_id' => $fila['lstk_id'],
							'lstk_id2' => $fila['lstk_id2'],
							'stk_id' => $fila['stk_id'],
							'art_id' => $fila['art_id'],
							'lstk_fecha' => $fila['lstk_fecha'],
							'lstk_serie' => $fila['lstk_serie'],
							'lstk_mac' => $fila['lstk_mac'],
							'lstk_part' => $fila['lstk_part'],
							'lstk_cantidad' => $fila['lstk_cantidad'],
							'ss_id' => $fila['ss_id'],
							'lstk_gar_cisco' => $fila['lstk_gar_cisco'],
							'lstk_lemon_count' => $fila['lstk_lemon_count'],
							'lstk_gar_teccam' => $fila['lstk_gar_teccam'],
							'lstk_chipid' => $fila['lstk_chipid'],
							'lstk_chipid2' => $fila['lstk_chipid2'],
							'lstk_tarjeta' => $fila['lstk_tarjeta'],
							'cli_usuario' => $fila['cli_usuario'],
							'stkc_caja_nro' => $fila['stkc_caja_nro'],
						);
					}else{
						continue;
					}
				}
			}else{
				foreach ($cache['loteStock'] as $fila){
					if($fila['stk_id']==$c){
						$this->lote[]=array(
							'lstk_id' => $fila['lstk_id'],
							'lstk_id2' => $fila['lstk_id2'],
							'stk_id' => $fila['stk_id'],
							'art_id' => $fila['art_id'],
							'lstk_fecha' => $fila['lstk_fecha'],
							'lstk_serie' => $fila['lstk_serie'],
							'lstk_mac' => $fila['lstk_mac'],
							'lstk_part' => $fila['lstk_part'],
							'lstk_cantidad' => $fila['lstk_cantidad'],
							'ss_id' => $fila['ss_id'],
							'lstk_gar_cisco' => $fila['lstk_gar_cisco'],
							'lstk_lemon_count' => $fila['lstk_lemon_count'],
							'lstk_gar_teccam' => $fila['lstk_gar_teccam'],
							'cli_usuario' => $fila['cli_usuario'],
							'stkc_caja_nro' => $fila['stkc_caja_nro'],
						);
					}else{
						continue;
					}
				}
			}
		}
	}
	/**
	 * Getters ...
	 */
	public function getId(){
		return $this->id;
	}
	public function getId2(){
		return $this->id2;
	}
	public function getStockId(){
		return $this->stockId;
	}
	public function getArtId(){
		return $this->artId;
	}
	public function getFecha(){
		return $this->fecha;
	}
	public function getSerie(){
		return Funcion::textoStandard($this->serie);
	}
	public function getMac(){
		return $this->mac;
	}
	public function getPartNro(){
		return $this->part;
	}
	public function getCantidad(){
		return $this->cantidad;
	}
	public function getStockStateId(){
		return $this->stockStateId;
	}
	public function getGarantiaCisco(){
		return $this->garantiaCisco;
	}
	public function getLemonCount(){
		return $this->lemonCount;
	}
	public function getGarantiaTeccam(){
		return $this->garantiaTeccam;
	}
	public function getChipId(){
		return $this->chipId;
	}
	public function getChipId2(){
		return $this->chipId2;
	}
	public function getTarjeta(){
		return $this->tarjeta;
	}
	public function getUsuario(){
		return $this->usuario;
	}
	public function getCajaNro(){
		return $this->cajaNro;
	}
	public function getSerieReferencia(){
		if(!empty($this->id2)){
			$query="SELECT lstk_serie FROM lote_stock WHERE lstk_id = '".$this->id2."'";
			$res=Db::listar($query);
			return $res[0]['lstk_serie'];
		}else{
			return;
		}
	}
	public function getSerieRelacionados(){
		$query="SELECT lstk_serie FROM lote_stock WHERE lstk_id2 = '".$this->id."'";
		$res=Db::listar($query);
		if(count($res)!="0"){
			foreach ($res as $fila) {
				$arr[]=$fila['lstk_serie'];
			}
			return implode(",", $arr);
		}else{
			return;
		}
	}
	/**
	 * Setters ...
	 */
	public function setId($nI){
		$this->id=$nI;
	}
	public function setId2($nI){
		$this->id2=$nI;
	}
	public function setStockId($nSI){
		$this->stockId=$nSI;
	}
	public function setLote($nL){
		if(empty($nL)){
			return $this;
		}else{
			$query="SELECT * FROM lote_stock WHERE stk_id = $nL";
			$res=Db::listar($query);
			foreach ($res as $fila){
				$this->lote=array(
					'lstk_id' => $fila['lstk_id'],
					'lstk_id2' => $fila['lstk_id2'],
					'stk_id' => $fila['stk_id'],
					'art_id' => $fila['art_id'],
					'lstk_fecha' => $fila['lstk_fecha'],
					'lstk_serie' => $fila['lstk_serie'],
					'lstk_part' => $fila['lstk_part'],
					'lstk_cantidad' => $fila['lstk_cantidad'],
					'ss_id' => $fila['ss_id'],
					'lstk_gar_cisco' => $fila['lstk_gar_cisco'],
					'lstk_lemon_count' => $fila['lstk_lemon_count'],
					'lstk_gar_teccam' => $fila['lstk_gar_teccam'],
					'cli_usuario' => $fila['cli_usuario'],
				);
			}
		}
	}
	public function setArtId($nAI){
		$query="SELECT art_id FROM articulos";
		foreach(Db::listar($query) as $fila){
			$check[]=$fila['art_id'];
		}
		if(!in_array($nAI, $check)){
			$this->errores['gen']="harError";
			$this->errores['articulo']="Artículo no válido ...";
		}else{
			$this->artId=$nAI;
		}
	}
	public function setFecha($nF){
		if(empty($nF)){
			$this->fecha=date('Y-m-d H:i:s');
		}else{
			$this->fecha=$nF;
		}
	}
	public function setSerie($nS){
		$ns=Funcion::textoStandard($nS);
		$query="SELECT art_id FROM articulos_vista 
		WHERE articulo LIKE '%Cablemodem * Cisco%'";
		$check1=array();
		foreach(Db::listar($query) as $fila){
			$check1[]=$fila['art_id'];
		}
		if(empty($nS)){
			$this->errores['gen'] = "harError";
			$this->errores['serie']="Valor nulo no admitido ...";
			unset($check1);
			return $this;
		}else{
			if(in_array($this->artId, $check1)){
				if(ctype_xdigit($nS) AND strlen($nS)=="12"){
					$this->errores['gen'] = "harError";
					$this->errores['serie']="No se espera MAC ...";
					return $this;
				}else{
					$queryS="CALL checkserie1('".$nS."')";
					//$_SESSION['queryS']=$queryS;
					$check=Db::listar($queryS);
					if(count($check)==0){
						$this->serie=$nS;
						$_SESSION['check']=count($check);
						unset($check);
						return $this;
					}else{
						$this->errores['gen'] = "harError";
						$this->errores['serie']="Item no válido ...";
						$_SESSION['check']=count($check);
						unset($check);
						return $this;
					}
				}
			}else{
				$queryS="CALL checkserie1('".$nS."')";
				//$_SESSION['queryS']=$queryS;
				$check=(array)Db::listar($queryS);
				if(count($check)==0){
					$this->serie=$nS;
					$_SESSION['check']=count($check);
					unset($check);
					return $this;
				}else{
					$this->errores['gen'] = "harError";
					$this->errores['serie']="Item no válido ...";
					$_SESSION['check']=count($check);
					unset($check);
					return $this;
				}
			}
		}
	}
	public function setMac($nM){
		if(empty($nM)){
			$this->errores['gen'] = "harError";
			$this->errores['mac']="No se espera valor nulo ...";
			return $this;
		}else{
			if(ctype_xdigit($nM) AND strlen($nM)=="12"){
				$this->mac=$nM;
			}else{
				$this->errores['gen'] = "harError";
				$this->errores['mac']="Se espera MAC ...";
				return $this;
			}
		}
	}
	public function setPartNro($nP){
		$this->part=$nP;
	}
	public function setCantidad($nC){
		if(!is_numeric($nC)){
			$this->errores['gen'] = "harError";
			$this->errores['cantidad'] = "Se espera valor numérico ...";
		}elseif($nC < "0"){
			$this->errores['gen'] = "harError";
			$this->errores['cantidad'] = "Se espera valor positivo ...";
		}else{
			$this->cantidad=$nC;
		}
	}
	public function setStockStateId($nSSI){
		if($this->stockStateId=="4" OR $this->stockStateId=="5"){
			return;
		}
		$this->stockStateId=$nSSI;
	}
	public function setGarantiaCisco($nGC){
		$this->garantiaCisco=$nGC;
	}
	public function setLemonCount($nLC){
		if(empty($nLC)){
			$this->lemonCount=null;
		}else{
			$this->lemonCount=$nLC;
		}
	}
	public function setGarantiaTeccam($nGT){
		$this->garantiaTeccam=$nGT;
	}
	public function setChipId($nCI){
		$this->chipId=$nCI;
		$con=Conexion::conectar();
		$query="UPDATE lote_stock SET lstk_chipid = :chipId WHERE lstk_id = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
		$stmt->bindParam(':chipId', $nCI, PDO::PARAM_STR);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
	}
	public function setChipId2($nCI2){
		$this->chipId2=$nCI2;
		$con=Conexion::conectar();
		$query="UPDATE lote_stock SET lstk_chipid2 = :chipId2 WHERE lstk_id = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
		$stmt->bindParam(':chipId2', $nCI2, PDO::PARAM_STR);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
	}
	public function setTarjeta($nT){
		$this->tarjeta=$nT;
		$con=Conexion::conectar();
		$query="UPDATE lote_stock SET lstk_tarjeta = :tarjeta WHERE lstk_id = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
		$stmt->bindParam(':tarjeta', $nT, PDO::PARAM_STR);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
	}
	public function setUsuario($nU){
		$query="SELECT cli_usuario FROM clientes";
		foreach (Db::listar($query) as $fila){
			$check[]=$fila['cli_usuario'];
		}
		if(in_array($nU, $check)){
			$this->usuario=$nU;
		}else{
			$this->errores['gen'] = "harError";
			$this->errores['usuario'] = "Usuario invalido ...";
		}
	}
	public function setCajaNro($nCN){
		$this->cajaNro=$nCN;
	}
	/**
	 * Inserta en la tabla lote_stock con las propiedades del objeto ...
	 */
	public function agregaADb(){
		$con=Conexion::conectar();
		$query="INSERT INTO `lote_stock`
				(`lstk_id`,
				`lstk_id2`,
				`stk_id`,
				`art_id`,
				`lstk_fecha`,
				`lstk_serie`,
				`lstk_mac`,
				`lstk_part`,
				`lstk_cantidad`,
				`ss_id`,
				`lstk_gar_cisco`,
				`lstk_lemon_count`,
				`lstk_gar_teccam`,
				`cli_usuario`,
				`stkc_caja_nro`)
				VALUES
				(null,
				:id2,
				:stockId,
				:artId,
				:fecha,
				:serie,
				:mac,
				null,
				:cantidad,
				1,
				0,
				null,
				0,
				:usuario,
				:cajaNro)";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id2', $this->id2, PDO::PARAM_INT);
		$stmt->bindParam(':stockId', $this->stockId, PDO::PARAM_INT);
		$stmt->bindParam(':artId', $this->artId, PDO::PARAM_INT);
		$stmt->bindParam(':fecha', $this->fecha, PDO::PARAM_STR);
		$stmt->bindParam(':serie', $this->serie, PDO::PARAM_STR);
		$stmt->bindParam(':mac', $this->mac, PDO::PARAM_STR);
		$stmt->bindParam(':cantidad', $this->cantidad, PDO::PARAM_INT);
		$stmt->bindParam(':cajaNro', $this->cajaNro, PDO::PARAM_INT);
		//$stmt->bindParam(':stockStateId', "0", PDO::PARAM_INT);
		//$stmt->bindParam(':garantiaCisco', null, PDO::PARAM_INT);
		//$stmt->bindParam(':lemonCount', null, PDO::PARAM_INT);
		//$stmt->bindParam(':garantiaTeccam', null, PDO::PARAM_INT);
		$stmt->bindParam(':usuario', $this->usuario, PDO::PARAM_STR);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
		$_SESSION['errorSQL']=$stmt->errorInfo();
		$this->uFilaIns=$con->lastInsertId();
		$this->setId($this->uFilaIns);
	}
	/**
	 * Actualiza la tabla "lote_stock" con las propiedades del Objeto ...
	 */
	public function actualizaDb(){
		$con=Conexion::conectar();
		$query="UPDATE lote_stock
				SET
				lstk_id2=:id2,
				stk_id=:stockId,
				art_id=:artId,
				lstk_fecha=:fecha,
				lstk_serie=:serie,
				lstk_mac=:mac,
				lstk_part=:part,
				lstk_cantidad=:cantidad,
				ss_id=:stockStateId,
				lstk_gar_cisco=:garantiaCisco,
				lstk_lemon_count=:lemonCount,
				lstk_gar_teccam=:garantiaTeccam,
				cli_usuario=:usuario,
				stkc_caja_nro=:cajaNro
				WHERE lstk_id = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id2', $this->id2, PDO::PARAM_INT);
		$stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
		$stmt->bindParam(':stockId', $this->stockId, PDO::PARAM_INT);
		$stmt->bindParam(':artId', $this->artId, PDO::PARAM_INT);
		$stmt->bindParam(':fecha', $this->fecha, PDO::PARAM_STR);
		$stmt->bindParam(':serie', $this->serie, PDO::PARAM_STR);
		$stmt->bindParam(':mac', $this->mac, PDO::PARAM_STR);
		$stmt->bindParam(':part', $this->part, PDO::PARAM_STR);
		$stmt->bindParam(':cantidad', $this->cantidad, PDO::PARAM_INT);
		$stmt->bindParam(':stockStateId', $this->stockStateId, PDO::PARAM_INT);
		$stmt->bindParam(':garantiaCisco', $this->garantiaCisco, PDO::PARAM_INT);
		$stmt->bindParam(':lemonCount', $this->lemonCount, PDO::PARAM_INT);
		$stmt->bindParam(':garantiaTeccam', $this->garantiaTeccam, PDO::PARAM_INT);
		$stmt->bindParam(':usuario', $this->usuario, PDO::PARAM_STR);
		$stmt->bindParam(':cajaNro', $this->cajaNro, PDO::PARAM_INT);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
		if($this->errorSql[0]=="00000"){
			$this->historico=new HistoryState($this->id);
			$this->historico->agregaADb();
		}
	}
	/**
	 * Actualiza la tabla "lote_stock" con las propiedades agregadas en la base UY del Objeto ...
	 */
	public function actualizaDbUy(){
		$con=Conexion::conectar();
		$query="UPDATE lote_stock
				SET";
		if(!empty($this->chipId)){
			$query.=" lstk_chipid=:chipId";
		}
		if(!empty($this->chipId2) AND !empty($this->chipId)){
			$query.=", lstk_chipid2=:chipId2";
		}
		if(!empty($this->chipId2) AND empty($this->chipId)){
			$query.=" lstk_chipid2=:chipId2";
		}
		if(!empty($this->tarjeta) AND !empty($this->chipId2)){
			$query.=", lstk_tarjeta=:tarjeta";
		}
		if(!empty($this->tarjeta) AND empty($this->chipId2) AND empty($this->chipId)){
			$query.=" lstk_tarjeta=:tarjeta";
		}
		$query.=" WHERE lstk_id=:id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
		if(!empty($this->chipId)){
			$stmt->bindParam(':chipId', $this->chipId, PDO::PARAM_STR);
		}
		if(!empty($this->chipId2)){
			$stmt->bindParam(':chipId2', $this->chipId2, PDO::PARAM_STR);
		}
		if(!empty($this->tarjeta)){
			$stmt->bindParam(':tarjeta', $this->tarjeta, PDO::PARAM_STR);
		}
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
		$_SESSION['errorSQL']=$stmt->errorInfo();
	}
	/**
	 * Borra registro de la tabla "lote_stock" filtrado por ID ...
	 */
	public function borraPorId($ID){
		if(!isset($ID)){
			return $this;
		}
		$con=Conexion::conectar();
		$query = "DELETE FROM lote_stock WHERE lstk_id = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $ID, PDO::PARAM_STR);
		$stmt -> execute();
		$this->uFilaIns=$con->lastInsertId();
	}
	public function borraPorLote(){
		$ID=$this->stockId;
		$con=Conexion::conectar();
		$query = "DELETE FROM lote_stock WHERE stk_id = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $ID, PDO::PARAM_STR);
		$stmt -> execute();
		$this->uFilaIns=$con->lastInsertId();
	}
	public function cuentaItems(){
		$id=$this->getStockId();
		$query="SELECT COUNT(stk_id) AS total FROM lote_stock WHERE stk_id = $id AND ss_id NOT IN (5,4)";
		$count=(array)Db::listar($query);
		return $count[0]['total'];
	}
	public function traePropiedadesPorNSerie($nS){
		//$query="SELECT * FROM lote_stock WHERE stk_id = $nI AND lstk_serie = '$nS'";
		$ns=Funcion::textoStandard($nS);
		if($_SESSION['empresa']=="36"){
			foreach($this->lote as $fila){
				if($fila['lstk_serie']==$nS){
					$this->id=$fila['lstk_id'];
					$this->id2=$fila['lstk_id2'];
					$this->stockId=$fila['stk_id'];
					$this->artId=$fila['art_id'];
					$this->fecha=$fila['lstk_fecha'];
					$this->serie=$fila['lstk_serie'];
					$this->mac=$fila['lstk_mac'];
					$this->part=$fila['lstk_part'];
					$this->cantidad=$fila['lstk_cantidad'];
					$this->stockStateId=$fila['ss_id'];
					$this->garantiaCisco=$fila['lstk_gar_cisco'];
					$this->lemonCount=$fila['lstk_lemon_count'];
					$this->garantiaTeccam=$fila['lstk_gar_teccam'];
					$this->chipId=$fila['lstk_chipid'];
					$this->chipId2=$fila['lstk_chipid2'];
					$this->tarjeta=$fila['lstk_tarjeta'];
					$this->usuario=$fila['cli_usuario'];
					$this->cajaNro = $fila['stkc_caja_nro'];
					$this->historico=new HistoryState($this->id);
				}
			}
		}else{
			foreach($this->lote as $fila){
				if($fila['lstk_serie']==$nS){
					$this->id=$fila['lstk_id'];
					$this->id2=$fila['lstk_id2'];
					$this->stockId=$fila['stk_id'];
					$this->artId=$fila['art_id'];
					$this->fecha=$fila['lstk_fecha'];
					$this->serie=$fila['lstk_serie'];
					$this->mac=$fila['lstk_mac'];
					$this->part=$fila['lstk_part'];
					$this->cantidad=$fila['lstk_cantidad'];
					$this->stockStateId=$fila['ss_id'];
					$this->garantiaCisco=$fila['lstk_gar_cisco'];
					$this->lemonCount=$fila['lstk_lemon_count'];
					$this->garantiaTeccam=$fila['lstk_gar_teccam'];
					$this->usuario=$fila['cli_usuario'];
					$this->cajaNro = $fila['stkc_caja_nro'];
					$this->historico=new HistoryState($this->id);
				}
			}
		}
	}
	public function traePropiedadesPorId($nS=null,$cache=null){
		//$query="SELECT * FROM lote_stock WHERE stk_id = $nI AND lstk_serie = '$nS'";
		if(empty($cache)){
			$query="SELECT * FROM lote_stock WHERE lstk_id = $nS";
			if($_SESSION['empresa']=="36"){
				foreach (Db::listar($query) as $fila){
						$this->id = $fila['lstk_id'];
						$this->id2=$fila['lstk_id2'];
						$this->stockId = $fila['stk_id'];
						$this->artId = $fila['art_id'];
						$this->fecha = $fila['lstk_fecha'];
						$this->serie = $fila['lstk_serie'];
						$this->mac = $fila['lstk_mac'];
						$this->part = $fila['lstk_part'];
						$this->cantidad = $fila['lstk_cantidad'];
						$this->stockStateId = $fila['ss_id'];
						$this->garantiaCisco = $fila['lstk_gar_cisco'];
						$this->lemonCount = $fila['lstk_lemon_count'];
						$this->garantiaTeccam = $fila['lstk_gar_teccam'];
						$this->chipId = $fila['lstk_chipid'];
						$this->chipId2 = $fila['lstk_chipid2'];
						$this->tarjeta = $fila['lstk_tarjeta'];
						$this->usuario = $fila['cli_usuario'];
						$this->cajaNro = $fila['stkc_caja_nro'];
						$this->historico=new HistoryState($this->id);
				}
			}else{
				foreach (Db::listar($query) as $fila){
					$this->id = $fila['lstk_id'];
					$this->id2=$fila['lstk_id2'];
					$this->stockId = $fila['stk_id'];
					$this->artId = $fila['art_id'];
					$this->fecha = $fila['lstk_fecha'];
					$this->serie = $fila['lstk_serie'];
					$this->mac = $fila['lstk_mac'];
					$this->part = $fila['lstk_part'];
					$this->cantidad = $fila['lstk_cantidad'];
					$this->stockStateId = $fila['ss_id'];
					$this->garantiaCisco = $fila['lstk_gar_cisco'];
					$this->lemonCount = $fila['lstk_lemon_count'];
					$this->garantiaTeccam = $fila['lstk_gar_teccam'];
					$this->usuario = $fila['cli_usuario'];
					$this->cajaNro = $fila['stkc_caja_nro'];
					$this->historico=new HistoryState($this->id);
				}
			}
		}else{
			//$query="SELECT * FROM lote_stock WHERE stk_id = $c";
			if($_SESSION['empresa']=="36"){
				foreach ($cache['loteStock'] as $fila){
					if($fila['lstk_id']==$nS){
						$this->id = $fila['lstk_id'];
						$this->id2=$fila['lstk_id2'];
						$this->stockId = $fila['stk_id'];
						$this->artId = $fila['art_id'];
						$this->fecha = $fila['lstk_fecha'];
						$this->serie = $fila['lstk_serie'];
						$this->mac = $fila['lstk_mac'];
						$this->part = $fila['lstk_part'];
						$this->cantidad = $fila['lstk_cantidad'];
						$this->stockStateId = $fila['ss_id'];
						$this->garantiaCisco = $fila['lstk_gar_cisco'];
						$this->lemonCount = $fila['lstk_lemon_count'];
						$this->garantiaTeccam = $fila['lstk_gar_teccam'];
						$this->chipId = $fila['lstk_chipid'];
						$this->chipId2 = $fila['lstk_chipid2'];
						$this->tarjeta = $fila['lstk_tarjeta'];
						$this->usuario = $fila['cli_usuario'];
						$this->cajaNro = $fila['stkc_caja_nro'];
						//$this->historico=new HistoryState($this->id);
						break;
					}
				}
			}else{
				foreach ($cache['loteStock'] as $fila){
					if($fila['lstk_id']==$nS){
						$this->id = $fila['lstk_id'];
						$this->id2=$fila['lstk_id2'];
						$this->stockId = $fila['stk_id'];
						$this->artId = $fila['art_id'];
						$this->fecha = $fila['lstk_fecha'];
						$this->serie = $fila['lstk_serie'];
						$this->mac = $fila['lstk_mac'];
						$this->part = $fila['lstk_part'];
						$this->cantidad = $fila['lstk_cantidad'];
						$this->stockStateId = $fila['ss_id'];
						$this->garantiaCisco = $fila['lstk_gar_cisco'];
						$this->lemonCount = $fila['lstk_lemon_count'];
						$this->garantiaTeccam = $fila['lstk_gar_teccam'];
						$this->usuario = $fila['cli_usuario'];
						$this->cajaNro = $fila['stkc_caja_nro'];
						//$this->historico=new HistoryState($this->id);
						break;
					}
				}
			}
		}
	}
}