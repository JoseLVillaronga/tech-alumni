<?php
/**
 * @Author : José Luis Villaronga
 * @copyright : 2016
 */
class StockCaja
{
	/**
	 * Propiedades
	 */
	private $id=null;
	private $stockId;
	private $cajaNro=0;
	private $qr;
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
	public function __construct($g=null){
		if(is_object($g)){
			$this->stockId=$g->getId();
			$query="SELECT * FROM stock_caja WHERE stk_id = ".$this->stockId;
			foreach (Db::listar($query) as $fila) {
				$this->lote[]=array("stkc_id"=>$fila['stkc_id'],
									"stk_id"=>$fila['stk_id'],
									"stkc_caja_nro"=>$fila['stkc_caja_nro'],
									"stkc_qr"=>$fila['stkc_qr']);
			}
			$this->cajaNro=count($this->lote)+1;
		}
	}
	/**
	 * Getters ..
	 */
	public function getId(){
	 	return $this->id;
	}
	public function getStockId(){
		return $this->stockId;
	}
	public function getCajaNro(){
		return $this->cajaNro;
	}
	public function getQr(){
		return $this->qr;
	}
	/**
	 * Setters ...
	 */
	public function setId($id){
		$this->id=$id;
	}
	public function setStockId($nSI){
		$this->stockId=$nSI;
	}
	public function setCajaNro($nCN){
		$this->cajaNro=$nCN;
	}
	public function setQr($nQ){
		$this->qr=$nQ;
	}
	public function setPropPorNroCaja($nP){
		foreach($this->lote as $fila){
			if($fila['stkc_caja_nro']==$nP){
				$this->id=$fila['stkc_id'];
				$this->stockId=$fila['stk_id'];
				$this->cajaNro=$fila['stkc_caja_nro'];
				$this->qr=$fila['stkc_qr'];
			}
		}
	}
	public function cerrarCaja(){
		$query="SELECT lstk_serie FROM lote_stock WHERE stk_id = ".$this->stockId." AND stkc_caja_nro = ".$this->cajaNro;
		foreach (Db::listar($query) as $fila) {
			$seri[]=$fila['lstk_serie'];
		}
		$serials=implode(",", $seri);
		if(empty($serials)){
			echo "Caja vacia !!!";
			return;
		}
		file_get_contents("http://".$_SERVER['SERVER_NAME']."/phpqrcode/?data=".$serials);
		$nombre=time().".png";
		copy("phpqrcode/temp/test.png", "infor/".$nombre);
		$this->setQr($nombre);
		$this->agregaADb();
	}
	public function actualizaCaja($nC){
		$this->setPropPorNroCaja($nC);
		$query="SELECT lstk_serie FROM lote_stock WHERE stk_id = ".$this->stockId." AND stkc_caja_nro = ".$nC." AND ss_id NOT IN (4,5,12)";
		foreach (Db::listar($query) as $fila) {
			$seri[]=$fila['lstk_serie'];
		}
		$serials=implode(",", $seri);
		if(empty($serials)){
			echo "Caja vacia !!!";
			return;
		}
		file_get_contents("http://".$_SERVER['SERVER_NAME']."/phpqrcode/?data=".$serials);
		$nombre=time().".png";
		copy("phpqrcode/temp/test.png", "infor/".$nombre);
		$this->setQr($nombre);
		$this->actualizaDb();
	}
	/**
	 * Inserta nuevo registro a la tabla "stock_caja" usando las propiedades del Objeto ...
	 */
	public function agregaADb(){
		$con=Conexion::conectar();
		$query="INSERT INTO `stock_caja`
				(`stkc_id`,
				`stk_id`,
				`stkc_caja_nro`,
				`stkc_qr`)
				VALUES
				(null,
				:stockId,
				:cajaNro,
				:qr)";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':stockId', $this->stockId, PDO::PARAM_INT);
		$stmt->bindParam(':cajaNro', $this->cajaNro, PDO::PARAM_INT);
		$stmt->bindParam(':qr', $this->qr, PDO::PARAM_STR);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
		$this->uFilaIns=$con->lastInsertId();
		$this->setId($this->uFilaIns);
	}
	/**
	 * Actualiza la tabla "stock_caja" con las propiedades actuales del objeto ...
	 */
	public function actualizaDb(){
		$con=Conexion::conectar();
		$query = "UPDATE `stock_caja`
					SET
					`stk_id` = :stockId,
					`stkc_caja_nro` = :cajaNro,
					`stkc_qr` = :qr
					WHERE `stkc_id` = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':stockId', $this->stockId, PDO::PARAM_INT);
		$stmt->bindParam(':cajaNro', $this->cajaNro, PDO::PARAM_INT);
		$stmt->bindParam(':qr', $this->qr, PDO::PARAM_STR);
		$stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
	}
}
