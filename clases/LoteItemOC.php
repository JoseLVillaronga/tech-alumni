<?php
/**
 * @Author : José Luis Villaronga
 * @copyright : 2015
 */
class LoteItemOC
{
	/**
	 * Propiedades
	 */
	private $id;
	private $idOrdenCompra;
	private $nroItem;
	private $detalle;
	private $cantidad;
	private $cantidadRestante;
	private $precioUnitario;
	private $fecha;
	public $usuario;
	public $lote;
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
			$this->usuario=new Usuario(null);
		}else{
			$this->setLote($c);
			$this->usuario=new Usuario($_SESSION['usuario']);
			$this->idOrdenCompra=$c;
		}
	}
	/**
	 * Getters ...
	 */
	public function getId(){
		return $this->id;
	}
	public function getIdOrdenCompra(){
		return $this->idOrdenCompra;
	}
	public function getNroItem(){
		return $this->nroItem;
	}
	public function getDetalle(){
		return $this->detalle;
	}
	public function getCantidad(){
		return $this->cantidad;
	}
	public function getCantidadRestante(){
		return $this->cantidadRestante;
	}
	public function getPrecioUnitario(){
		return $this->precioUnitario;
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
	public function setPropiedadesPorId($nI){
		foreach($this->lote as $fila){
			if($fila['loc_id']==$nI){
				$this->id=$fila['loc_id'];
				$this->idOrdenCompra=$fila['oc_id'];
				$this->nroItem=$fila['loc_nro_item'];
			}
		}
	}
	public function setIdOrdenCompra($nIOC){
		$this->idOrdenCompra=$nIOC;
	}
	public function setNroItem($nNI){
		if(empty($nNI)){
			$this->errores['gen'] = "harError";
			$this->errores['nroItem'] = "Valor requerido ...";
		}else{
			$this->nroItem=$nNI;
		}
	}
	public function setDetalle($nD){
		$this->detalle=$nD;
	}
	public function setCantidad($nC){
		if(empty($nC)){
			$this->errores['gen'] = "harError";
			$this->errores['cantidad'] = "Valor requerido ...";
		}elseif($nC < "0"){
			$this->errores['gen'] = "harError";
			$this->errores['cantidad'] = "Valor incorrecto ...";
		}else{
			$this->cantidad=$nC;
		}
	}
	public function setCantidadRestante($nCR){
		if($nCR < "0"){
			$this->errores['gen'] = "harError";
			$this->errores['cantidadRestante'] = "Valor incorrecto ...";
		}else{
			$this->cantidadRestante=$nCR;
		}
	}
	public function restaCantidadRestante($nCR){
		$this->setCantidadRestante($this->cantidadRestante - $nCR);
	}
	public function sumaCantidadRestante($nCR){
		$this->setCantidadRestante($this->cantidadRestante + $nCR);
	}
	public function setPrecioUnitario($nPU){
		$this->precioUnitario=$nPU;
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
			$this->usuario=new Usuario($_SESSION['usuario']);
		}else{
			$this->usuario=new Usuario($nU);
		}
	}
	public function setLote($nL){
		if(empty($nL)){
			$query="SELECT * FROM lote_orden_compra WHERE oc_id = $this->idOrdenCompra";
			foreach(Db::listar($query) as $fila){
				$this->lote[]=array("loc_id"=>$fila['loc_id'],
									"oc_id"=>$fila['oc_id'],
									"loc_nro_item"=>$fila['loc_nro_item'],
									"loc_detalle_item"=>$fila['loc_detalle_item'],
									"loc_cantidad"=>$fila['loc_cantidad'],
									"loc_cantidad_restante"=>$fila['loc_cantidad_restante'],
									"loc_precio_unitario"=>$fila['loc_precio_unitario'],
									"loc_fecha"=>$fila['loc_fecha'],
									"cli_usuario"=>$fila['cli_usuario']);
			}
		}else{
			$query="SELECT * FROM lote_orden_compra WHERE oc_id = $nL";
			foreach(Db::listar($query) as $fila){
				$this->lote[]=array("loc_id"=>$fila['loc_id'],
									"oc_id"=>$fila['oc_id'],
									"loc_nro_item"=>$fila['loc_nro_item'],
									"loc_detalle_item"=>$fila['loc_detalle_item'],
									"loc_cantidad"=>$fila['loc_cantidad'],
									"loc_cantidad_restante"=>$fila['loc_cantidad_restante'],
									"loc_precio_unitario"=>$fila['loc_precio_unitario'],
									"loc_fecha"=>$fila['loc_fecha'],
									"cli_usuario"=>$fila['cli_usuario']);
			}
		}
	}
	public function setPropPorId($id){
		foreach($this->lote as $fila){
			if($fila['loc_id']==$id){
				$this->id=$id;
				$this->idOrdenCompra=$fila['oc_id'];
				$this->nroItem=$fila['loc_nro_item'];
				$this->detalle=$fila['loc_detalle_item'];
				$this->cantidad=$fila['loc_cantidad'];
				$this->cantidadRestante=$fila['loc_cantidad_restante'];
				$this->precioUnitario=$fila['loc_precio_unitario'];
				$this->fecha=$fila['loc_fecha'];
				$this->usuario=new Usuario($fila['cli_usuario']);
			}
		}
	}
	/**
	 * Actualiza la tabla "lote_orden_compra" con las propiedades actuales del objeto ...
	 */
	public function actualizaDb(){
		$con=Conexion::conectar();
		$usu=$_SESSION['usuario'];
		$query = "UPDATE `lote_orden_compra`
					SET
					`loc_id` = :id,
					`oc_id` = :idOrdenCompra,
					`loc_nro_item` = :nroItem,
					`loc_detalle_item` = :detalle,
					`loc_cantidad` = :cantidad,
					`loc_cantidad_restante` = :cantidadRestante,
					`loc_precio_unitario` = :precioUnitario,
					`loc_fecha` = :fecha,
					`cli_usuario` = :usuario
					WHERE `loc_id` = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
		$stmt->bindParam(':idOrdenCompra', $this->idOrdenCompra, PDO::PARAM_INT);
		$stmt->bindParam(':nroItem', $this->nroItem, PDO::PARAM_STR);
		$stmt->bindParam(':detalle', $this->detalle, PDO::PARAM_STR);
		$stmt->bindParam(':cantidad', $this->cantidad, PDO::PARAM_INT);
		$stmt->bindParam(':cantidadRestante', $this->cantidadRestante, PDO::PARAM_INT);
		$stmt->bindParam(':precioUnitario', $this->precioUnitario, PDO::PARAM_INT);
		$stmt->bindParam(':fecha', $this->fecha, PDO::PARAM_STR);
		$stmt->bindParam(':usuario', $usu, PDO::PARAM_STR);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
	}
	/**
	 * Inserta nuevo registro a la tabla "lote_orden_compra" usando las propiedades del Objeto ...
	 */
	public function agregaADb(){
		$con=Conexion::conectar();
		$usu=$_SESSION['usuario'];
		$query="INSERT INTO `lote_orden_compra`
				(`loc_id`,
				`oc_id`,
				`loc_nro_item`,
				`loc_detalle_item`,
				`loc_cantidad`,
				`loc_cantidad_restante`,
				`loc_precio_unitario`,
				`loc_fecha`,
				`cli_usuario`)
				VALUES
				(null,
				:idOrdenCompra,
				:nroItem,
				:detalle,
				:cantidad,
				:cantidadRestante,
				:precioUnitario,
				:fecha,
				:usuario)";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':idOrdenCompra', $this->idOrdenCompra, PDO::PARAM_INT);
		$stmt->bindParam(':nroItem', $this->nroItem, PDO::PARAM_STR);
		$stmt->bindParam(':detalle', $this->detalle, PDO::PARAM_STR);
		$stmt->bindParam(':cantidad', $this->cantidad, PDO::PARAM_INT);
		$stmt->bindParam(':cantidadRestante', $this->cantidad, PDO::PARAM_INT);
		$stmt->bindParam(':precioUnitario', $this->precioUnitario, PDO::PARAM_INT);
		$stmt->bindParam(':fecha', $this->fecha, PDO::PARAM_STR);
		$stmt->bindParam(':usuario', $usu, PDO::PARAM_STR);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
		$this->uFilaIns=$con->lastInsertId();
		$this->setId($this->uFilaIns);
	}
	/**
	 * Borra registro de la tabla "lote_orden_compra" filtrado por ID ...
	 */
	public function borraPorId($ID){
		if(!isset($ID)){
			return $this;
		}
		$con=Conexion::conectar();
		$query = "DELETE FROM lote_orden_compra WHERE loc_id = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $ID, PDO::PARAM_STR);
		$stmt -> execute();
		//$this->uFilaIns=$con->lastInsertId();
	}
}
