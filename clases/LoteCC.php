<?php
/**
 * @Author : José Luis Villaronga
 * @copyright : 2014
 */
class LoteCC
{
	/**
	 * Propiedades
	 */
	private $controlCalidad;
	private $fallaCC;
	private $fechaLote;
	private $mac;
	private $serie;
	private $rechazoMuestra;
	private $selMuestra;
	private $reparacion;
	public $lote = array();
	/**
	 * Array con error de la ultima transacción (INSERT,UPDATE), se puede imprimir con "print_r($this->errorSql)" ...
	 */
	public $errorSql=array();
	/**
	 * Array con los errores de los setters ...
	 */
	public $errores=array();
	/**
	 * Metodo constructor ..
	 */
	public function __construct($g=null){
		$query = "SELECT * FROM lote_cc WHERE cca_id = $g";
		if(empty($g)){
			$res=Db::listar($query);
			foreach ($res as $fila){
				$this->controlCalidad=$fila['cca_id'];
				$this->fallaCC=$fila['fcc_id'];
				$this->fechaLote=$fila['lcc_fecha'];
				$this->mac=$fila['lcc_mac'];
				$this->serie=$fila['lcc_serie'];
				$this->rechazoMuestra=$fila['lcc_muestra_rechazo'];
				$this->selMuestra=$fila['lcc_sel_muestra'];
				$this->reparacion=$fila['rep_id'];
			}
			return $this;
		}else{
			$this->controlCalidad=$g;
			$res=Db::listar($query);
			foreach ($res as $fila){
				$this->fallaCC=$fila['fcc_id'];
				$this->lote[]=array("cca_id"=>$fila['cca_id'],
				'fcc_id'=>$fila['fcc_id'],
				'lcc_fecha'=>$fila['lcc_fecha'],
				'lcc_mac'=>$fila['lcc_mac'],
				'lcc_serie'=>$fila['lcc_serie'],
				'lcc_muestra_rechazo'=>$fila['lcc_muestra_rechazo'],
				'lcc_sel_muestra'=>$fila['lcc_sel_muestra'],
				'rep_id'=>$fila['rep_id']);
			}
			return $this;
		}
	}
	/**
	 * Getters ..
	 */
	public function getControlCalidad(){
		return $this->controlCalidad;
	}
	public function getFallaCC(){
		return $this->fallaCC;
	}
	public function getFallaCCN(){
		$f=new FallaCC($this->fallaCC);
		return $f->getNombre();
	}
	public function getFechaLote(){
		return $this->fechaLote;
	}
	public function getMac(){
		return $this->mac;
	}
	public function getSerie(){
		return $this->serie;
	}
	public function getRechazoMuestra(){
		return $this->rechazoMuestra;
	}
	public function getSelMuestra(){
		return $this->selMuestra;
	}
	public function getReparacion(){
		return $this->reparacion;
	}
	public function getLote(){
		return $this->lote;
	}
	/**
	 * Setters ...
	 */
	public function setLoteById($g){
		$this->borraLote($this->controlCalidad);
		$query = "SELECT * FROM lote_cc WHERE cca_id = $g";
		foreach (Db::listar($query) as $fila){
			$this->lote[]=array('cca_id'=>$this->controlCalidad,
			'fcc_id'=>$fila['fcc_id'],
			'art_id'=>$fila['art_id'],
			'lcc_fecha'=>$fila['lcc_fecha'],
			'lcc_mac'=>$fila['lcc_mac'],
			'lcc_serie'=>$fila['lcc_serie'],
			'lcc_muestra_rechazo'=>$fila['lcc_muestra_rechazo'],
			'lcc_sel_muestra'=>$fila['lcc_sel_muestra'],
			'rep_id'=>$fila['rep_id']);
		}
	}
	public function setReparacion($nCC){
		$a=new ControlCalidad($this->controlCalidad);
		$art=$a->articulo->getId();
		$query = "SELECT rep_id FROM reparacion 
				WHERE DATE(rep_fecha_final) > (NOW() - INTERVAL 7 DAY)
				AND art_id = $art";
		foreach (Db::listar($query) as $fila){
			$pos[]=$fila['rep_id'];
		}
		foreach ($this->lote as $fila){
			$check[]=$fila['rep_id'];
		}
		if(!in_array($nCC, $check) AND in_array($nCC, $pos)){
			$this->reparacion=$nCC;
			$obj=new Reparacion($nCC);
			$this->setMac($obj->getMac());
			$this->setSerie($obj->getSerie());
			unset($check);
			unset($pos);
			unset($obj);
		}else{
			$this->errores['reparacion']="Item no válido ...";
			$this->errores['gen'] = "harError";
			unset($check);
			unset($pos);
		}
	}
	public function setCC($nCC){
		$this->controlCalidad=$nCC;
	}
	public function setFallaCC($nFCC){
		$query = "SELECT fcc_id FROM falla_cc";
		foreach (Db::listar($query) as $fila){
			$check[]=$fila['fcc_id'];
		}
		if(in_array($nFCC, $check)){
			$this->fallaCC=$nFCC;
			unset($check);
		}else{
			$this->errores['fallaCC']="Falla no válida ...";
			$this->errores['gen'] = "harError";
			unset($check);
		}
	}
	public function setFechaLote($fLote){
		if(empty($fLote)){
			$this->fechaLote=date('Y-m-d H:i:s');
		}else{
			$this->fechaLote=$fLote;
		}
	}
	public function setMac($nMac){
		if (empty($nMac)){
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
	public function setSerie($nS){
		$this->serie=$nS;
	}
	public function setRechazoMuestra($nRM){
		if(empty($nRM)){
			return $this;
		}elseif($nRM == "0" OR $nRM == "1"){
			$this->rechazoMuestra=$nRM;
		}else{
			$this->errores['rechazoMuestra'] = "Ingresar valor válido ...";
			$this->errores['gen'] = "harError";
		}
	}
	public function setSelMuestra($nSM){
		if(empty($nSM)){
			return $this;
		}elseif($nSM == "0" OR $nSM == "1"){
			$this->selMuestra=$nSM;
		}else{
			$this->errores['selMuestra'] = "Ingresar valor válido ...";
			$this->errores['gen'] = "harError";
		}
	}
	/**
	 * Agruga fila a "$this->lote" con las propiedades del objeto ...
	 */
	public function agregaALote(){
		if(empty($this->reparacion)){
			$this->errores['agregaALote'] = "O.T. vacia ...";
			$this->errores['gen'] = "harError";
		}elseif(!in_array($this->reparacion, $this->lote)){
			$this->lote[]['cca_id']=$this->controlCalidad;
			$this->lote[]['fcc_id']=$this->fallaCC;
			$this->lote[]['lcc_fecha']=$this->fechaLote;
			$this->lote[]['lcc_mac']=$this->mac;
			$this->lote[]['lcc_serie']=$this->serie;
			$this->lote[]['lcc_muestra_rechazo']=$this->rechazoMuestra;
			$this->lote[]['lcc_sel_muestra']=$this->selMuestra;
			$this->lote[]['rep_id']=$this->reparacion;
		}else{
			$this->errores['agregaALote'] = "O.T. repetida ...";
			$this->errores['gen'] = "harError";
		}
	}
	public function grabaLoteComp(){
		foreach ($this->lote as $fila){
			$con=Conexion::conectar();
			$query = "INSERT INTO `lote_cc`
						(`cca_id`,
						`art_id`,
						`fcc_id`,
						`rep_id`,
						`lcc_mac`,
						`lcc_serie`,
						`lcc_fecha`,
						`lcc_sel_muestra`,
						`lcc_muestra_rechazo`)
						VALUES
						(:ccaId,
						:articulo,
						:falla,
						:repId,
						:mac,
						:serie,
						NOW(),
						0,
						0)";
			$stmt = $con -> prepare($query);
			$stmt->bindParam(':ccaId', $fila['cca_id'], PDO::PARAM_INT);
			$stmt->bindParam(':articulo', $fila['art_id'], PDO::PARAM_INT);
			$stmt->bindParam(':falla', $fila['fcc_id'], PDO::PARAM_INT);
			$stmt->bindParam(':repId', $fila['rep_id'], PDO::PARAM_INT);
			$stmt->bindParam(':mac', $fila['lcc_mac'], PDO::PARAM_STR);
			$stmt->bindParam(':serie', $fila['lcc_serie'], PDO::PARAM_STR);
			$stmt -> execute();
			$this->errorSql = $stmt->errorInfo();
			$_SESSION['errorSQL'] = $stmt->errorInfo();
		}
	}
	public function grabaLote(){
		$a=new ControlCalidad($this->getControlCalidad());
		$art=$a->articulo->getId();
		$con=Conexion::conectar();
		$query = "INSERT INTO `lote_cc`
					(`cca_id`,
					`art_id`,
					`fcc_id`,
					`rep_id`,
					`lcc_mac`,
					`lcc_serie`,
					`lcc_fecha`,
					`lcc_sel_muestra`,
					`lcc_muestra_rechazo`)
					VALUES
					(:ccaId,
					:articulo,
					:falla,
					:repId,
					:mac,
					:serie,
					NOW(),
					0,
					0)";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':ccaId', $this->controlCalidad, PDO::PARAM_INT);
		$stmt->bindParam(':articulo', $art, PDO::PARAM_INT);
		$stmt->bindParam(':falla', $this->fallaCC, PDO::PARAM_INT);
		$stmt->bindParam(':repId', $this->reparacion, PDO::PARAM_INT);
		$stmt->bindParam(':mac', $this->mac, PDO::PARAM_STR);
		$stmt->bindParam(':serie', $this->serie, PDO::PARAM_STR);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
		$_SESSION['errorSQL'] = $stmt->errorInfo();
	}
	public function borraLote($ID){
		if(!isset($ID)){
			return $this;
		}
		$con=Conexion::conectar();
		$query = "DELETE FROM lote_cc WHERE cca_id = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $ID, PDO::PARAM_INT);
		$stmt -> execute();
	}
	public function sessionALote(){
		$this->lote=$_SESSION['lote'];
		unset($_SESSION['lote']);
	}
	public function loteASession(){
		$_SESSION['lote']=$this->lote;
	}
	public function agregaLoteOT($id){
		if(empty($id)){
			return $this;
		}else{
			$loteOT = new LoteOT($id);
			foreach ($loteOT->lote as $fila){
				$con=Conexion::conectar();
				$query = "INSERT INTO `lote_cc`
							(`cca_id`,
							`art_id`,
							`fcc_id`,
							`rep_id`,
							`lcc_mac`,
							`lcc_serie`,
							`lcc_fecha`,
							`lcc_sel_muestra`,
							`lcc_muestra_rechazo`)
							VALUES
							(:ccaId,
							:articulo,
							null,
							:repId,
							:mac,
							:serie,
							NOW(),
							0,
							0)";
				$stmt = $con -> prepare($query);
				$stmt->bindParam(':ccaId', $this->controlCalidad, PDO::PARAM_INT);
				$stmt->bindParam(':articulo', $fila['art_id'], PDO::PARAM_INT);
				$stmt->bindParam(':repId', $fila['rep_id'], PDO::PARAM_INT);
				$stmt->bindParam(':mac', $fila['lcc_mac'], PDO::PARAM_STR);
				$stmt->bindParam(':serie', $fila['lcc_serie'], PDO::PARAM_STR);
				$stmt -> execute();
				$this->errorSql = $stmt->errorInfo();
				$_SESSION['errorSQL'] = $stmt->errorInfo();
			}
		}
	}
	public function cuentaItems(){
		$id=$this->getControlCalidad();
		$query="SELECT SUM(rep_cantidad) as total FROM lote_cc,reparacion WHERE lote_cc.rep_id=reparacion.rep_id AND cca_id=$id GROUP BY cca_id";
		$count=Db::listar($query);
		return $count[0]['total'];
	}
	public function cuentaItemsMuestras(){
		$id=$this->getControlCalidad();
		$query="SELECT SUM(rep_cantidad) as total FROM lote_cc,reparacion WHERE lote_cc.rep_id=reparacion.rep_id AND cca_id=$id AND lcc_sel_muestra=1 GROUP BY cca_id";
		$count=Db::listar($query);
		return $count[0]['total'];
	}
	public function cuentaItemsRechazos(){
		$id=$this->getControlCalidad();
		$query="SELECT SUM(rep_cantidad) as total FROM lote_cc,reparacion WHERE lote_cc.rep_id=reparacion.rep_id AND cca_id=$id AND lcc_muestra_rechazo=1 GROUP BY cca_id";
		$count=Db::listar($query);
		return $count[0]['total'];
	}
	public function reabreOTs(){
		foreach($this->lote as $fila){
			$obj=new Reparacion($fila['rep_id']);
			$obj->setFechaFinal(NULL);
			$obj->setTerminado();
			$obj->actualizaDb();
		}
	}
	public function reasignaOTs($nOper){
		foreach($this->lote as $fila){
			$obj=new Reparacion($fila['rep_id']);
			$obj->setUsuario($nOper);
			$obj->actualizaDb();
		}
	}
	public function fallaLote($nF){
		if(!empty($nF)){
			$con=Conexion::conectar();
			$query="UPDATE `lote_cc`
					SET
					`fcc_id` = :falla
					WHERE cca_id = :id";
			$stmt = $con -> prepare($query);
			$stmt->bindParam(':falla', $nF, PDO::PARAM_INT);
			$stmt->bindParam(':id', $this->controlCalidad, PDO::PARAM_INT);
			$stmt -> execute();
		}else{
		$con=Conexion::conectar();
		$query="UPDATE `lote_cc`
				SET
				`fcc_id` = null
				WHERE cca_id = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $this->controlCalidad, PDO::PARAM_INT);
		$stmt -> execute();
		}
	}
	public function setMuestra($nM){
		$query="SELECT lcc_sel_muestra FROM lote_cc WHERE cca_id=$this->controlCalidad AND lcc_serie='$nM'";
		foreach(Db::listar($query) as $fila){
			$a=$fila['lcc_sel_muestra'];
		}
		if($a == "0"){
			$con=Conexion::conectar();
			$query="UPDATE lote_cc
					SET
					lcc_sel_muestra = 1
					WHERE cca_id = :id
					AND lcc_serie = :serie";
			$stmt = $con -> prepare($query);
			$stmt->bindParam(':id', $this->controlCalidad, PDO::PARAM_INT);
			$stmt->bindParam(':serie', $nM, PDO::PARAM_STR);
			$stmt -> execute();
			$_SESSION['sql'] = $stmt->errorInfo();
		}else{
			$con=Conexion::conectar();
			$query="UPDATE lote_cc
					SET
					lcc_sel_muestra = 0
					WHERE cca_id = :id
					AND lcc_serie = :serie";
			$stmt = $con -> prepare($query);
			$stmt->bindParam(':id', $this->controlCalidad, PDO::PARAM_INT);
			$stmt->bindParam(':serie', $nM, PDO::PARAM_STR);
			$stmt -> execute();
			$_SESSION['sql'] = $stmt->errorInfo();
		}
	}
	public function setMuestraRechazo($nM){
		$query="SELECT lcc_muestra_rechazo FROM lote_cc WHERE cca_id=$this->controlCalidad AND lcc_serie='$nM'";
		foreach(Db::listar($query) as $fila){
			$a=$fila['lcc_muestra_rechazo'];
		}
		if($a == "0"){
			$con=Conexion::conectar();
			$query="UPDATE lote_cc
					SET
					lcc_muestra_rechazo = 1
					WHERE cca_id = :id
					AND lcc_serie = :serie";
			$stmt = $con -> prepare($query);
			$stmt->bindParam(':id', $this->controlCalidad, PDO::PARAM_INT);
			$stmt->bindParam(':serie', $nM, PDO::PARAM_STR);
			$stmt -> execute();
		}else{
			$con=Conexion::conectar();
			$query="UPDATE lote_cc
					SET
					lcc_muestra_rechazo = 0
					WHERE cca_id = :id
					AND lcc_serie = :serie";
			$stmt = $con -> prepare($query);
			$stmt->bindParam(':id', $this->controlCalidad, PDO::PARAM_INT);
			$stmt->bindParam(':serie', $nM, PDO::PARAM_STR);
			$stmt -> execute();
		}
	}
}
