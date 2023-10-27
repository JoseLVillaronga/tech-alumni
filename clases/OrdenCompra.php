<?php
/**
 * @Author : José Luis Villaronga
 * @copyright : 2015
 */
class OrdenCompra
{
	/**
	 * Propiedades
	 */
	private $id;
	public $empresa;
	private $nroOrdenCompra;
	public $loteItem;
	private $rutaDocumento;
	private $fecha;
	private $fechaCli;
	private $importe;
	private $importeRestante;
	private $tipoCV=1;
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
			$this->empresa=new Empresa(null);
			$this->loteItem=new LoteItemOC(null);
			$this->usuario=new Usuario(null);
			return $this;
		}else{
			$query="SELECT * FROM orden_compra WHERE oc_id = $c";
			foreach(Db::listar($query) as $fila){
				$this->id=$fila['oc_id'];
				$this->empresa=new Empresa($fila['emp_id']);
				$this->nroOrdenCompra=$fila['oc_nro_oc'];
				$this->loteItem=new LoteItemOC($fila['oc_id']);
				$this->rutaDocumento=$fila['oc_adjunto'];
				$this->fecha=$fila['oc_fecha'];
				$this->fechaCli=$fila['oc_fecha_cli'];
				$this->importe=$fila['oc_importe'];
				$this->importeRestante=$fila['oc_importe_restante'];
				$this->tipoCV=$fila['oc_cv'];
				$this->usuario=new Usuario($fila['cli_usuario']);
			}
		}
	}
	/**
	 * Getters ...
	 */
	public function getId(){
		return $this->id;
	}
	public function getNroOrdenCompra(){
		return $this->nroOrdenCompra;
	}
	public function getRutaDocumento(){
		return $this->rutaDocumento;
	}
	public function getFecha(){
		return $this->fecha;
	}
	public function getFechaCli(){
		return $this->fechaCli;
	}
	public function getImporte(){
		return $this->importe;
	}
	public function getImporteRestante(){
		return $this->importeRestante;
	}
	public function getTipoCV(){
		return $this->tipoCV;
	}
	/**
	 * Setters ...
	 */
	public function setId($nId){
		$this->id=$nId;
	}
	public function setNroOrdenCompra($nNOC){
		if(empty($nNOC)){
			$this->errores['nroOrdenCompra']="Ingresar Nro. Orden de Compra ...";
			$this->errores['gen'] = "harError";
		}else{
			$this->nroOrdenCompra=$nNOC;
		}
	}
	public function setRutaDocumento($nRD){
		$this->rutaDocumento=$nRD;
	}
	public function setFecha($nF){
		if(empty($nF)){
			$this->fecha=date('Y-m-d H:i:s');
		}else{
			$this->fecha=$nF;
		}
	}
	public function setFechaCli($nF){
		if(empty($nF)){
			$this->fechaCli=date('Y-m-d');
		}else{
			$this->fechaCli=$nF;
		}
	}
	public function setEmpresa($nE){
		if($nE=="0"){
			$this->errores['empresa']="Seleccionar cliente ...";
			$this->errores['gen'] = "harError";
		}else{
			$this->empresa->__construct($nE);
		}
	}
	public function setImporte($nI){
		if(empty($nI) AND $this->tipoCV=="0"){
			$this->errores['importe']="Cargar Importe ...";
			$this->errores['gen'] = "harError";
		}else{
			$this->importe=$nI;
		}
	}
	public function setImporteRestante($nIR){
		$this->importeRestante=$nIR;
	}
	public function setTipoCV($nT){
		$this->tipoCV=$nT;
	}
	/**
	 * Actualiza la tabla "orden_compra" con las propiedades actuales del objeto ...
	 */
	public function actualizaDb(){
		$con=Conexion::conectar();
		$cli=$this->empresa->getId();
		$usu=$this->usuario->getUsuario();
		$query = "UPDATE `orden_compra`
					SET
					`emp_id` = :cliente,
					`oc_nro_oc` = :nroOrdenCompra,
					`oc_adjunto` = :rutaDocumento,
					`oc_fecha` = :fecha,
					`oc_fecha_cli` = :fechaCli,
					`oc_importe` = :importe,
					`oc_importe_restante` = :importeRestante,
					`oc_cv` = :tipoCV,
					`cli_usuario` = :usuario
					WHERE `oc_id` = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
		$stmt->bindParam(':cliente', $cli, PDO::PARAM_INT);
		$stmt->bindParam(':nroOrdenCompra', $this->nroOrdenCompra, PDO::PARAM_STR);
		$stmt->bindParam(':rutaDocumento', $this->rutaDocumento, PDO::PARAM_STR);
		$stmt->bindParam(':fecha', $this->fecha, PDO::PARAM_STR);
		$stmt->bindParam(':importe', $this->importe, PDO::PARAM_STR);
		$stmt->bindParam(':importeRestante', $this->importeRestante, PDO::PARAM_STR);
		$stmt->bindParam(':tipoCV', $this->tipoCV, PDO::PARAM_STR);
		$stmt->bindParam(':fechaCli', $this->fechaCli, PDO::PARAM_STR);
		$stmt->bindParam(':usuario', $usu, PDO::PARAM_STR);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
	}
	/**
	 * Inserta nuevo registro a la tabla "orden_compra" usando las propiedades del Objeto ...
	 */
	public function agregaADb(){
		$con=Conexion::conectar();
		$cli=$this->empresa->getId();
		$usu=$this->usuario->getUsuario();
		$query="INSERT INTO `orden_compra`
				(`oc_id`,
				`emp_id`,
				`oc_nro_oc`,
				`oc_adjunto`,
				`oc_fecha`,
				`oc_fecha_cli`,
				`oc_importe`,
				`oc_importe_restante`,
				`oc_cv`,
				`cli_usuario`)
				VALUES
				(null,
				:cliente,
				:nroOrdenCompra,
				:rutaDocumento,
				:fecha,
				:fechaCli,
				:importe,
				:importeRestante,
				:tipoCV,
				:usuario)";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':cliente', $cli, PDO::PARAM_INT);
		$stmt->bindParam(':nroOrdenCompra', $this->nroOrdenCompra, PDO::PARAM_STR);
		$stmt->bindParam(':rutaDocumento', $this->rutaDocumento, PDO::PARAM_STR);
		$stmt->bindParam(':fecha', $this->fecha, PDO::PARAM_STR);
		$stmt->bindParam(':fechaCli', $this->fechaCli, PDO::PARAM_STR);
		$stmt->bindParam(':importe', $this->importe, PDO::PARAM_STR);
		$stmt->bindParam(':importeRestante', $this->importeRestante, PDO::PARAM_STR);
		$stmt->bindParam(':tipoCV', $this->tipoCV, PDO::PARAM_STR);
		$stmt->bindParam(':usuario', $usu, PDO::PARAM_STR);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
		$this->uFilaIns=$con->lastInsertId();
		$this->setId($this->uFilaIns);
	}
	/**
	 * Borra registro de la tabla "orden_compra" filtrado por ID ...
	 */
	public function borraPorId($ID){
		if(!isset($ID)){
			return $this;
		}
		$con=Conexion::conectar();
		$query = "DELETE FROM orden_compra WHERE oc_id = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $ID, PDO::PARAM_STR);
		$stmt -> execute();
		$this->uFilaIns=$con->lastInsertId();
	}
}
