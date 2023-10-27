<?php
/**
 * @Author : José Luis Villaronga
 * @copyright : 2014
 */
class LoteRemitoSalida
{
	/**
	 * Propiedades
	 */
	private $id;
	private $idRemito;
	private $serie;
	private $nroItem;
	private $nroCaja;
	private $fecha;
	private $usuario;
	private $lstkId;
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
			
			$query="SELECT * FROM remito_salida_lote WHERE rs_id = $c ORDER BY rsl_id DESC LIMIT 1";
			$rs=Db::listar($query);
			if(count($rs)=="0"){
				$this->nroCaja="1";
				$this->nroItem="0";
				$this->idRemito=$c;
			}else{
				$this->id=$rs[0]['rsl_id'];
				$this->idRemito=$rs[0]['rs_id'];
				$this->serie=$rs[0]['rsl_serie'];
				$this->nroItem=$rs[0]['rsl_nro_item'];
				$this->nroCaja=$rs[0]['rsl_nro_caja'];
				$this->fecha=$rs[0]['rsl_fecha'];
				$this->usuario=$rs[0]['cli_usuario'];
				$this->lstkId=$rs[0]['lstk_id'];
				$queryL="SELECT * FROM remito_salida_lote WHERE rs_id = $c";
				foreach(Db::listar($queryL) as $filaL){
					$this->lote[]=array("rsl_id"=>$filaL['rsl_id'],
										"rs_id"=>$filaL['rs_id'],
										"rsl_serie"=>$filaL['rsl_serie'],
										"rsl_nro_item"=>$filaL['rsl_nro_item'],
										"rsl_nro_caja"=>$filaL['rsl_nro_caja'],
										"rsl_fecha"=>$filaL['rsl_fecha'],
										"cli_usuario"=>$filaL['cli_usuario'],
										"lstk_id"=>$filaL['lstk_id']);
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
	public function getIdRemito(){
		return $this->idRemito;
	}
	public function getSerie(){
		return $this->serie;
	}
	public function getNroItem(){
		return $this->nroItem;
	}
	public function getNroCaja(){
		return $this->nroCaja;
	}
	public function getFecha(){
		return $this->fecha;
	}
	public function getUsuario(){
		return $this->usuario;
	}
	/**
	 * Setters ...
	 */
	public function setId($nId){
		$this->id=$nId;
	}
	public function setIdRemito($nI){
		$this->idRemito=$nI;
	}
	public function setSerie($nS,$CM=false){
		if(empty($nS)){
			$this->errores['gen'] = "harError";
			$this->errores['serie']="No se espera valor nulo ...";
			return $this;
		}else{
			if($CM){
				if(ctype_xdigit($nS) AND strlen($nS)=="12"){
					$query="SELECT rsl_serie FROM remito_salida_lote AS l,remito_salida AS r WHERE l.rs_id =".$this->idRemito." AND l.rs_id = r.rs_id AND rs_entregado = 0";
					$check=array();
					foreach(Db::listar($query) as $fila){
						$check[]=$fila['rsl_serie'];
					}
					if(!in_array($nS, $check)){
						$this->serie=$nS;
						$queryRef="SELECT * FROM rem_salida_ref_vista WHERE lstk_mac = '".$nS."' ORDER BY lstk_id DESC LIMIT 1";
						$rls=(array)Db::listar($queryRef);
						if(count($rls)!="0"){
							$this->lstkId=$rls[0]['lstk_id'];
						}
						unset($check);
						return $this;
					}else{
						$this->errores['gen'] = "harError";
						$this->errores['serie']="Item no válido ...";
						unset($check);
						return $this;
					}
				}else{
					$this->errores['gen'] = "harError";
					$this->errores['serie']="Se espera MAC ...";
					return $this;
				}
			}else{
				$check=array();
				$query="SELECT rsl_serie FROM remito_salida_lote AS l,remito_salida AS r WHERE l.rs_id =".$this->idRemito." AND l.rs_id = r.rs_id AND rs_entregado = 0";
				foreach(Db::listar($query) as $fila){
					$check[]=$fila['rsl_serie'];
				}
				if(!in_array($nS, $check)){
					$this->serie=$nS;
					$queryRef="SELECT * FROM rem_salida_ref_vista WHERE lstk_serie = '".$nS."' ORDER BY lstk_id DESC LIMIT 1";
					$rls=Db::listar($queryRef);
					if(count($rls)!="0"){
						$this->lstkId=$rls[0]['lstk_id'];
					}
					unset($check);
					return $this;
				}else{
					$this->errores['gen'] = "harError";
					$this->errores['serie']="Item no válido ...";
					unset($check);
					return $this;
				}
			}
		}
	}
	public function setNroItem($nI){
		$this->nroItem=$nI;
	}
	public function setNroCaja($nC){
		$this->nroCaja=$nC;
	}
	public function setFecha($nF){
		if(empty($nF)){
			$this->fecha=date('Y-m-d H:i:s');
		}else{
			$this->fecha=$nF;
		}
	}
	public function setUsuario($nU){
		if(empty($nU)){
			$this->usuario=$_SESSION['usuario'];
		}else{
			$this->usuario=$nU;
		}
	}
	public function setPropiedadesPorId($id){
		foreach($this->lote as $fila){
			if($fila['rsl_id']==$id){
				$this->id=$fila['rsl_id'];
				$this->idRemito=$fila['rs_id'];
				$this->serie=$fila['rsl_serie'];
				$this->nroItem=$fila['rsl_nro_item'];
				$this->nroCaja=$fila['rsl_nro_caja'];
				$this->fecha=$fila['rsl_fecha'];
				$this->usuario=$fila['cli_usuario'];
				$this->lstkId=$fila['lstk_id'];
			}
		}
	}
	public function setLote($id){
		$queryL="SELECT * FROM remito_salida_lote WHERE rs_id = $id";
		foreach(Db::listar($queryL) as $filaL){
			$this->lote[]=array("rsl_id"=>$filaL['rsl_id'],
								"rs_id"=>$filaL['rs_id'],
								"rsl_serie"=>$filaL['rsl_serie'],
								"rsl_nro_item"=>$filaL['rsl_nro_item'],
								"rsl_nro_caja"=>$filaL['rsl_nro_caja'],
								"rsl_fecha"=>$filaL['rsl_fecha'],
								"cli_usuario"=>$filaL['cli_usuario'],
								"lstk_id"=>$filaL['lstk_id']);
		}
	}
	/**
	 * Actualiza la tabla "remito_salida_lote" con las propiedades actuales del objeto ...
	 */
	public function actualizaDb(){
		$con=Conexion::conectar();
		$query = "UPDATE `remito_salida_lote`
					SET
					`rsl_id` = :id,
					`rs_id` = :idRemito,
					`rsl_serie` = :serie,
					`rsl_nro_item` = :nroItem,
					`rsl_nro_caja` = :nroCaja,
					`rsl_fecha` = :fecha,
					`cli_usuario` = :usuario,
					`lstk_id` = :lstkId
					WHERE `rsl_id` = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
		$stmt->bindParam(':idRemito', $this->idRemito, PDO::PARAM_INT);
		$stmt->bindParam(':serie', $this->serie, PDO::PARAM_STR);
		$stmt->bindParam(':nroItem', $this->nroItem, PDO::PARAM_INT);
		$stmt->bindParam(':nroCaja', $this->nroCaja, PDO::PARAM_INT);
		$stmt->bindParam(':fecha', $this->fecha, PDO::PARAM_STR);
		$stmt->bindParam(':usuario', $this->usuario, PDO::PARAM_STR);
		$stmt->bindParam(':lstkId', $this->lstkId, PDO::PARAM_INT);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
	}
	/**
	 * Actualiza la tabla "rsl_serie" en "remito_salida_lote" con las propiedades actuales del objeto ...
	 */
	public function actualizaSerie(){
		$con=Conexion::conectar();
		$query = "UPDATE `remito_salida_lote`
					SET
					`rsl_serie` = :serie
					WHERE `rsl_id` = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
		$stmt->bindParam(':serie', $this->serie, PDO::PARAM_STR);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
	}
	/**
	 * Inserta nuevo registro a la tabla "remito_salida_lote" usando las propiedades del Objeto ...
	 */
	public function agregaADb(){
		$con=Conexion::conectar();
		$query="INSERT INTO `remito_salida_lote`
				(`rsl_id`,
				`rs_id`,
				`rsl_serie`,
				`rsl_nro_item`,
				`rsl_nro_caja`,
				`rsl_fecha`,
				`cli_usuario`,
				`lstk_id`)
				VALUES
				(null,
				:idRemito,
				:serie,
				:nroItem,
				:nroCaja,
				:fecha,
				:usuario,
				:lstkId)";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':idRemito', $this->idRemito, PDO::PARAM_INT);
		$stmt->bindParam(':serie', $this->serie, PDO::PARAM_STR);
		$stmt->bindParam(':nroItem', $this->nroItem, PDO::PARAM_INT);
		$stmt->bindParam(':nroCaja', $this->nroCaja, PDO::PARAM_INT);
		$stmt->bindParam(':fecha', $this->fecha, PDO::PARAM_STR);
		$stmt->bindParam(':usuario', $this->usuario, PDO::PARAM_STR);
		$stmt->bindParam(':lstkId', $this->lstkId, PDO::PARAM_INT);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
		$this->uFilaIns=$con->lastInsertId();
		$this->setId($this->uFilaIns);
	}
	/**
	 * Borra registro de la tabla "remito_salida_lote" filtrado por ID ...
	 */
	public function borraPorId($ID){
		if(!isset($ID)){
			return $this;
		}
		$con=Conexion::conectar();
		$query = "DELETE FROM remito_salida_lote WHERE rsl_id = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $ID, PDO::PARAM_STR);
		$stmt -> execute();
		$this->uFilaIns=$con->lastInsertId();
	}
	/**
	 * Borra todos los registros de la tabla "remito_salida_lote" filtrado por ID ...
	 */
	public function borraPorIdRemito($ID){
		if(empty($this->idRemito)){
			return $this;
		}
		$con=Conexion::conectar();
		$query = "DELETE FROM remito_salida_lote WHERE rs_id = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $ID, PDO::PARAM_STR);
		$stmt -> execute();
		//$this->uFilaIns=$con->lastInsertId();
	}
}
