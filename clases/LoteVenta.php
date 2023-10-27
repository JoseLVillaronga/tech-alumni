<?php
/**
 * @Author : José Luis Villaronga
 * @copyright : 2014
 */
class LoteVenta
{
	/**
	 * Propiedades
	 */
	private $id;
	private $ventaId;
	private $serie;
	private $usuario;
	private $fecha;
	public $lote=array();
	public $venta;
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
		$this->ventaId=$c;
		$query="SELECT * FROM lote_venta WHERE v_id = ".$this->ventaId;
		$res=Db::listar($query);
		foreach($res as $fila){
			$this->lote[]=array(
				"lv_id"=>$fila['lv_id'],
				"v_id"=>$fila['v_id'],
				"lstk_serie"=>$fila['lstk_serie'],
				"cli_usuario"=>$fila['cli_usuario'],
				"lv_fecha"=>$fila['lv_fecha']
			);
		}
	}
	/**
	 * Getters ...
	 */
	public function getId(){
		return $this->id;
	}
	public function getVentaId(){
		return $this->ventaId;
	}
	public function getSerie(){
		return $this->serie;
	}
	public function getUsuario(){
		return $this->usuario;
	}
	public function getFecha(){
		return $this->fecha;
	}
	/**
	 * Setters ...
	 */
	public function setId($nI){
		$this->id=$nI;
	}
	public function setVentaId($nVI){
		$this->ventaId=$nVI;
	}
	public function setSerie($nS){
		$nS=Funcion::textoStandard($nS);
		$stk=Busca::buscaItemLoteStock($nS);
		if(is_object($stk)){
			$check=array();
			$query="SELECT lstk_serie FROM lote_venta WHERE v_id = ".$this->ventaId;
			foreach(Db::listar($query) as $fila){
				$check[]=$fila['lstk_serie'];
			}
			if(in_array($nS, $check)){
				$this->errores['gen'] = "harError";
				$this->errores['serie'] = "Valor duplicado ...";
			}else{
				if($stk->getCodigo()==$this->venta->getCodigo()){
					$this->serie=$nS;
					if(!empty($this->ventaId)){
						if(empty($this->usuario)){$this->usuario=$_SESSION['usuario'];}
						if(empty($this->fecha)){$this->fecha=date("Y-m-d H:i:s");}
						$this->agregaADb();
						if($this->venta->getReserva()>0){
							$this->venta->setReserva(($this->venta->getReserva()-1));
							$this->venta->actualizaDb();
							$stk->loteStock->setStockStateId(3);
							$stk->loteStock->actualizaDb();
						}else{
							$stk->loteStock->setStockStateId(3);
							$stk->loteStock->actualizaDb();
						}
					}
				}else{
					$this->errores['gen'] = "harError";
					$this->errores['serie'] = "No es el mismo producto ...";
				}
			}
		}else{
			$this->errores['gen'] = "harError";
			$this->errores['serie'] = "No está en Stock ...";
		}
	}
	public function setUsuario($nU){
		$this->usuario=$nU;
	}
	public function setFecha($nF){
		if(empty($nF)){
			$this->fecha=date("Y-m-d H:i:s");
		}else{
			$this->fecha=$nF;
		}
	}
	public function setLote($nL){
		$query="SELECT * FROM lote_venta WHERE v_id = ".$nL;
		$res=Db::listar($query);
		foreach($res as $fila){
			$this->lote[]=array(
				"lv_id"=>$fila['lv_id'],
				"v_id"=>$fila['v_id'],
				"lstk_serie"=>$fila['lstk_serie'],
				"cli_usuario"=>$fila['cli_usuario'],
				"lv_fecha"=>$fila['lv_fecha']
			);
		}
	}
	/**
	 * Inserta en la tabla lote_venta con las propiedades del objeto ...
	 */
	public function agregaADb(){
		$con=Conexion::conectar();
		$query="INSERT INTO `lote_venta`
				(`v_id`,
				`lstk_serie`,
				`cli_usuario`,
				`lv_fecha`)
				VALUES
				(:ventaId,
				:serie,
				:usuario,
				:fecha)";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':ventaId', $this->ventaId, PDO::PARAM_INT);
		$stmt->bindParam(':serie', $this->serie, PDO::PARAM_STR);
		$stmt->bindParam(':usuario', $this->usuario, PDO::PARAM_STR);
		$stmt->bindParam(':fecha', $this->fecha, PDO::PARAM_STR);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
		$this->uFilaIns=$con->lastInsertId();
		$this->setId($this->uFilaIns);
	}
	/**
	 * Actualiza la tabla "lote_venta" con las propiedades del Objeto ...
	 */
	public function actualizaDb(){
		$con=Conexion::conectar();
		$query = "UPDATE `lote_venta`
					SET
					`lv_id` = :id,
					`v_id` = :ventaId,
					`lstk_serie` = :serie,
					`cli_usuario` = :usuario,
					`lv_fecha` = :fecha
					WHERE `lv_id` = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
		$stmt->bindParam(':ventaId', $this->ventaId, PDO::PARAM_INT);
		$stmt->bindParam(':serie', $this->serie, PDO::PARAM_STR);
		$stmt->bindParam(':usuario', $this->usuario, PDO::PARAM_STR);
		$stmt->bindParam(':fecha', $this->fecha, PDO::PARAM_STR);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
	}
	/**
	 * Borra registro de la tabla "lote_venta" filtrado por ID ...
	 */
	public function borraPorId($ID){
		if(!isset($ID)){
			return $this;
		}
		$con=Conexion::conectar();
		$query = "DELETE FROM lote_venta WHERE lv_id = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $ID, PDO::PARAM_STR);
		$stmt -> execute();
	}
	public function borraPorLote(){
		if(!empty($this->ventaId)){
			$con=Conexion::conectar();
			$query = "DELETE FROM lote_venta WHERE v_id = :id";
			$stmt = $con -> prepare($query);
			$stmt->bindParam(':id', $this->ventaId, PDO::PARAM_STR);
			$stmt -> execute();
		}
	}
	public function cuentaItems(){
		$query="SELECT COUNT(lstk_serie) AS cantidad FROM lote_venta WHERE v_id = ".$this->ventaId;
		$res=Db::listar($query);
		return $res[0]['cantidad'];
	}
	public function traePropiedadesPorNSerie($nS){
		if(!empty($nS)){
			$query="SELECT * FROM lote_venta WHERE lstk_serie = ".$nS." ORDER BY lv_id DESC LIMIT 1";
			$res=Db::listar($query);
			$this->id=$res[0]['lv_id'];
			$this->ventaId=$res[0]['v_id'];
			$this->serie=$res[0]['lstk_serie'];
			$this->usuario=$res[0]['cli_usuario'];
			$this->fecha=$res[0]['lv_fecha'];
		}
		$this->setLote($this->ventaId);
	}
	public function traePropiedadesPorId($nS){
		if(!empty($nS)){
			$query="SELECT * FROM lote_venta WHERE lv_id = ".$nS;
			$res=Db::listar($query);
			$this->id=$res[0]['lv_id'];
			$this->ventaId=$res[0]['v_id'];
			$this->serie=$res[0]['lstk_serie'];
			$this->usuario=$res[0]['cli_usuario'];
			$this->fecha=$res[0]['lv_fecha'];
		}
		$this->setLote($this->ventaId);
	}
}
