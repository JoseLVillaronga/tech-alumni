<?php
/**
 * @Author : José Luis Villaronga
 * @copyright : 2014
 */
class HistoryState
{
	/**
	 * Propiedades
	 */
	private $loteStockId;
	private $stateId;
	private $fechaHistoricoState;
	private $usuario;
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
			return $this;
		}else{
			$query="SELECT * FROM lote_stock WHERE lstk_id = $c";
			$lista2=(array)Db::listar($query);
			foreach ($lista2 as $fila){
				$this->loteStockId=$fila['lstk_id'];
				$this->stateId=$fila['ss_id'];
				$this->fechaHistoricoState=date('Y-m-d H:i:s');
				$this->usuario=$_SESSION['usuario'];
			}
			$sql="SELECT * FROM historico_state WHERE lstk_id = $this->loteStockId";
			$lista=(array)Db::listar($sql);
			foreach($lista as $key){
				$this->lote[]=array(
					'lstk_id'=>$key['lstk_id'],
					'ss_id'=>$key['ss_id'],
					'hss_fecha'=>$key['hss_fecha'],
					'cli_usuario'=>$key['cli_usuario'],
				);
			}
		}
	}
	/**
	 * Getters ...
	 */
	public function getLoteStockId(){
		return $this->loteStockId;
	}
	public function getStateId(){
		return $this->stateId;
	}
	public function getFechaHS(){
		return $this->fechaHistoricoState;
	}
	public function getUsuario(){
		return $this->usuario;
	}
	/**
	 * Setters ...
	 */
	public function setLoteStockId($nLSI){
		$this->loteStockId=$nLSI;
	}
	public function setStateId($nSI){
		$this->stateId=$nSI;
	}
	public function setFechaHS($nFHS){
		$this->fechaHistoricoState=$nFHS;
	}
	public function setUsuario($nU){
		$this->usuario=$nU;
	}
	/**
	 * Inserta en la tabla lote_stock con las propiedades del objeto ...
	 */
	public function agregaADb(){
		$con=Conexion::conectar();
		$query="INSERT INTO `historico_state`
				(`lstk_id`,
				`ss_id`,
				`hss_fecha`,
				`cli_usuario`)
				VALUES
				(:id,
				:stateId,
				:fecha,
				:usuario)";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $this->loteStockId, PDO::PARAM_INT);
		$stmt->bindParam(':stateId', $this->stateId, PDO::PARAM_INT);
		$stmt->bindParam(':fecha', $this->fechaHistoricoState, PDO::PARAM_STR);
		$stmt->bindParam(':usuario', $this->usuario, PDO::PARAM_STR);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
	}
}
