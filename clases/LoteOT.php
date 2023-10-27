<?php
/**
 * @Author : José Luis Villaronga
 * @copyright : 2014
 */
class LoteOT
{
	/**
	 * Propiedades
	 */
	private $id;
	private $articulo;
	private $ot;
	private $mac;
	private $serie;
	private $fecha;
	private $usuario;
	public $lote=array();
	public $art;
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
		if(empty($g)){
			$this->usuario=$_SESSION['usuario'];
			$query="SELECT MAX(lot_id) FROM lote_ot WHERE cli_usuario = '$this->usuario'";
			foreach (Db::listar($query) as $fila){
				$g = $fila['MAX(lot_id)'];
			}
			$this->id=$g;
			$query = "SELECT * FROM lote_ot WHERE lot_id = $g";
			$res1=Db::listar($query);
			$this->art=new Articulo($res1[0]['art_id']);
			$this->fecha=$res1[0]['lot_fecha'];
			foreach ($res1 as $fila){
				$this->articulo=$fila['art_id'];
				$this->lote[]=array('lot_id'=>$fila['lot_id'],
				'art_id'=>$fila['art_id'],
				'rep_id'=>$fila['rep_id'],
				'lcc_mac'=>$fila['lcc_mac'],
				'lcc_serie'=>$fila['lcc_serie'],
				'lot_fecha'=>$fila['lot_fecha'],
				'cli_usuario'=>$fila['cli_usuario']);
			}
			return $this;
		}else{
			$this->usuario=$res1[0]['cli_usuario'];
			$this->id=$g;
			$query = "SELECT * FROM lote_ot WHERE lot_id = $g";
			$res1=Db::listar($query);
			$this->art=new Articulo($res1[0]['art_id']);
			$this->fecha=$res1[0]['lot_fecha'];
			foreach ($res1 as $fila){
				$this->articulo=$fila['art_id'];
				$this->lote[]=array('lot_id'=>$fila['lot_id'],
				'art_id'=>$fila['art_id'],
				'rep_id'=>$fila['rep_id'],
				'lcc_mac'=>$fila['lcc_mac'],
				'lcc_serie'=>$fila['lcc_serie'],
				'lot_fecha'=>$fila['lot_fecha'],
				'cli_usuario'=>$fila['cli_usuario']);
			}			
		}
		return $this;
	}
	/**
	 * Getters ..
	 */
	public function getId(){
	 	return $this->id;
	}
	public function getArticulo(){
		return $this->articulo;
	}
	public function getOT(){
		return $this->ot;
	}
	public function getMac(){
		return $this->mac;
	}
	public function getSerie(){
		return $this->serie;
	}
	public function getFecha(){
		return $this->fecha;
	}
	public function getUsuario(){
		return $this->lote[0]['cli_usuario'];
	}
	/**
	 * Setters ...
	 */
	public function setId($id){
		if(!empty($id)){
			$this->id=$id;
		}else{
			$query="SELECT MAX(lot_id) FROM lote_ot WHERE cli_usuario = '$this->usuario'";
			foreach (Db::listar($query) as $fila){
				$id=$fila['MAX(lot_id)'];
			}
			$this->id=$id;
		}
		return $this;
	}
	public function setArticulo($nArt){
		$this->articulo=$nArt;
	}
	public function setOT($nOT){
		$o=new Reparacion($this->id);
		$check=new Reparacion($nOT);
		if($o->articulo->getId() == $check->articulo->getId()){
			$this->ot=$nOT;
			$this->mac=$check->getMac();
			$this->serie=$check->getSerie();
			unset($o);
			unset($check);
		}
	}
	public function setFecha($fLote){
		if(empty($fLote)){
			$this->fecha=date('Y-m-d H:i:s');
		}else{
			$this->fecha=$fLote;
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
	public function setUsuario($nUsu){
		if(empty($nUsu)){
			$this->usuario=$_SESSION['usuario'];
		}else{
			$this->usuario=$nUsu;
		}
	}
	/**
	 * Actualiza la tabla "lote_ot" con las propiedades actuales del objeto ...
	 */
	/*public function actualizaDb(){
		$con=Conexion::conectar();
		$query = "UPDATE `grupos`
					SET
					`cli_grupo` = :id,
					`gru_nombre` = :nombre
					WHERE `cli_grupo` = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
		$stmt->bindParam(':nombre', $this->nombre, PDO::PARAM_STR);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
	}*/
	/**
	 * Inserta nuevo registro a la tabla "lote_ot" usando las propiedades del Objeto ...
	 */
	public function agregaADb(){
		$con=Conexion::conectar();
		$query="INSERT INTO `lote_ot`
				(`lot_id`,
				`art_id`,
				`rep_id`,
				`lcc_mac`,
				`lcc_serie`,
				`lot_fecha`,
				`cli_usuario`)
				VALUES
				(:id,
				:articulo,
				:ot,
				:mac,
				:serie,
				:fecha,
				:usuario)";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
		$stmt->bindParam(':articulo', $this->articulo, PDO::PARAM_INT);
		$stmt->bindParam(':ot', $this->ot, PDO::PARAM_INT);
		$stmt->bindParam(':mac', $this->mac, PDO::PARAM_STR);
		$stmt->bindParam(':serie', $this->serie, PDO::PARAM_STR);
		$stmt->bindParam(':fecha', $this->fecha, PDO::PARAM_STR);
		$stmt->bindParam(':usuario', $this->usuario, PDO::PARAM_STR);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
		$_SESSION['loteOTeSQL']=$stmt->errorInfo();
		//$this->uFilaIns=$con->lastInsertId();
		//$this->setId($this->uFilaIns);
	}
	public function cuentaItems(){
		$id=$this->getId();
		$query="SELECT SUM(rep_cantidad) as total FROM lote_ot,reparacion WHERE lote_ot.rep_id=reparacion.rep_id AND lot_id=$id GROUP BY lot_id";
		$count=Db::listar($query);
		return $count[0]['total'];
	}
	/**
	 * Borra registro de la tabla "lote_ot" filtrado por ID ...
	 */
	public function borraPorId($ID){
		if(!isset($ID)){
			return $this;
		}
		$con=Conexion::conectar();
		$query = "DELETE FROM lote_ot WHERE lot_id = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $ID, PDO::PARAM_STR);
		$stmt -> execute();
		//$this->uFilaIns=$con->lastInsertId();
	}
}
