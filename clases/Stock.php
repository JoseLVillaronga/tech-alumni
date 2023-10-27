<?php
/**
 * @Author : José Luis Villaronga
 * @copyright : 2014
 */
class Stock
{
	/**
	 * Propiedades
	 */
	private $id;
	public $stockId;
	public $articulo;
	public $empresa;
	private $numeroRemitoCliente;
	private $fechaRemitoCliente;
	private $fecha;
	private $activo;
	private $observaciones;
	private $posicion;
	private $codigo;
	public $tarea;
	public $usuario;
	public $loteStock;
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
		if(empty($c)){
			$this->articulo=new Articulo(null);
			$this->empresa=new Empresa(null);
			$this->usuario=new Usuario(null);
			$this->tarea=new Tarea(null);
			return $this;
		}elseif(is_numeric($c)){
			if(empty($cache)){
				$query="SELECT * FROM stock WHERE stk_id = $c";
				foreach (Db::listar($query) as $fila){
					$this->id=$fila['stk_id'];
					$this->stockId=$fila['stk_id'];
					$this->articulo=new Articulo($fila['art_id']);
					$this->empresa=new Empresa($fila['emp_id']);
					$this->numeroRemitoCliente=$fila['stk_nro_rem_cli'];
					$this->fechaRemitoCliente=$fila['stk_fecha_rem_cli'];
					$this->fecha=$fila['stk_fecha'];
					$this->activo=$fila['stk_activo'];
					$this->observaciones=$fila['stk_observaciones'];
					$this->usuario=new Usuario($fila['cli_usuario']);
					$this->loteStock=new LoteStock($fila['stk_id']);
					$this->tarea=new Tarea($fila['tar_id']);
					$this->posicion=$fila['stk_posicion'];
					$this->codigo=$fila['stk_codigo'];
				}
			}else{
				foreach($cache['stock'] as $fila){
					if($fila['stk_id']==$c){
						$this->id=$fila['stk_id'];
						$this->stockId=$fila['stk_id'];
						$this->articulo=new Articulo($fila['art_id']);
						$this->empresa=new Empresa($fila['emp_id']);
						$this->numeroRemitoCliente=$fila['stk_nro_rem_cli'];
						$this->fechaRemitoCliente=$fila['stk_fecha_rem_cli'];
						$this->fecha=$fila['stk_fecha'];
						$this->activo=$fila['stk_activo'];
						$this->observaciones=$fila['stk_observaciones'];
						$this->usuario=new Usuario($fila['cli_usuario']);
						$this->loteStock=new LoteStock($fila['stk_id'],$cache);
						$this->tarea=new Tarea($fila['tar_id']);
						$this->posicion=$fila['stk_posicion'];
						$this->codigo=$fila['stk_codigo'];
					}else{
						continue;
					}
				}
			}

		}else{
			$this->articulo=new Articulo(null);
			$this->empresa=new Empresa(null);
			$this->usuario=new Usuario(null);
			$this->tarea=new Tarea(null);
			return $this;
		}
	}
	/**
	 * Getters ...
	 */
	public function getId(){
		return $this->id;
	}
	public function getArtId(){
		$aI=$this->articulo->getId();
		return $aI;
	}
	public function getEmpresaId(){
		$eI=$this->empresa->getId();
		return $eI;
	}
	public function getNumeroRemitoCliente(){
		return $this->numeroRemitoCliente;
	}
	public function getFechaRemitoCliente(){
		return $this->fechaRemitoCliente;
	}
	public function getFecha(){
		return $this->fecha;
	}
	public function getActivo(){
		return $this->activo;
	}
	public function getObservaciones(){
		return $this->observaciones;
	}
	public function getUsuario(){
		$u=$this->usuario->getUsuario();
		return $u;
	}
	public function getPosicion(){
		return $this->posicion;
	}
	public function getCodigo(){
		return $this->codigo;
	}
	/**
	 * Setters ...
	 */
	public function setId($nId){
		$this->id=$nId;
		$this->stockId=$nId;
	}
	public function setArticulo($id){
		$query="SELECT art_id FROM articulos";
		foreach (Db::listar($query) as $fila){
			$check[]=$fila['art_id'];
		}
		if(in_array($id, $check)){
			$this->articulo=new Articulo($id);
			unset($check);
		}else{
			$this->errores['articulo']="No es un Articulo válido ...";
			$this->errores['gen'] = "harError";
			unset($check);
		}
	}
	public function setEmpresa($nEmp){
		$query="SELECT emp_id FROM empresas";
		foreach(Db::listar($query) as $fila){
			$check[]=$fila['emp_id'];
		}
		if(in_array($nEmp, $check)){
			$this->empresa=new Empresa($nEmp);
			unset($check);
		}else{
			$this->errores['empresa']="No es un cliente válido";
			$this->errores['gen']="harError";
			unset($check);
		}
	}
	public function setNumeroRemitoCliente($nNRC){
		$this->numeroRemitoCliente=$nNRC;
	}
	public function setFechaRemitoCliente($nFRC){
		if(empty($nFRC)){
			$this->fechaRemitoCliente=null;
		}else{
			$this->fechaRemitoCliente=$nFRC;
		}
	}
	public function setFecha($nF){
		if(empty($nF)){
			$this->fecha=null;
		}else{
			$this->fecha=$nF;
		}
	}
	public function setActivo($nA){
		$this->activo=$nA;
	}
	public function setObservaciones($nO){
		$this->observaciones=$nO;
	}
	public function setPosicion($nP){
		$this->posicion=$nP;
	}
	public function setCodigo($nC){
		$this->codigo=$nC;
	}
	public function setTarea($nT){
		$query="SELECT * FROM tarea";
		foreach(Db::listar($query) as $fila){
			$tarId[]=$fila['tar_id'];
		}
		if(in_array($nT, $tarId)){
			$this->tarea=new Tarea($nT);
		}else{
			$this->errores['tarea']="No es una tarea válida";
			$this->errores['gen']="harError";
		}
	}
	public function setUsuario($nU){
		$this->usuario=new Usuario($nU);
	}
	/**
	 * Actualiza la tabla "stock" con las propiedades actuales del objeto ...
	 */
	public function actualizaDb(){
		$con=Conexion::conectar();
		$art=$this->getArtId();
		$emp=$this->getEmpresaId();
		$usu=$this->getUsuario();
		$tar=$this->tarea->getId();
		$f=$this->getFechaRemitoCliente();
		$nrc=$this->getNumeroRemitoCliente();
		$query = "UPDATE `stock`
					SET
					`stk_id` = :id,
					`art_id` = :artId,
					`emp_id` = :empId,";
		if(!empty($nrc)){
			$query.=" `stk_nro_rem_cli` = :nroRemCli,";
		}else{
			$query.=" `stk_nro_rem_cli` = null,";
		}
		if(!empty($f)){
			$query.=" `stk_fecha_rem_cli` = :fechaRemCli,";
		}else{
			$query.=" `stk_fecha_rem_cli` = null,";
		}
		$query.=	" `stk_activo` = :activo,
					`stk_observaciones` = :observaciones,
					`tar_id` = :tarea,
					`stk_posicion` = :posicion,
					`stk_codigo` = :codigo,
					`cli_usuario` = :usuario
					WHERE `stk_id` = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
		$stmt->bindParam(':artId', $art, PDO::PARAM_INT);
		$stmt->bindParam(':empId', $emp, PDO::PARAM_INT);
		if(!empty($nrc)){
			$stmt->bindParam(':nroRemCli', $this->numeroRemitoCliente, PDO::PARAM_STR);
		}
		if(!empty($f)){
			$stmt->bindParam(':fechaRemCli', $this->fechaRemitoCliente, PDO::PARAM_STR);
		}
		$stmt->bindParam(':activo', $this->activo, PDO::PARAM_INT);
		$stmt->bindParam(':observaciones', $this->observaciones, PDO::PARAM_STR);
		$stmt->bindParam(':tarea', $tar, PDO::PARAM_INT);
		$stmt->bindParam(':posicion', $this->posicion, PDO::PARAM_STR);
		$stmt->bindParam(':codigo', $this->codigo, PDO::PARAM_STR);
		$stmt->bindParam(':usuario', $usu, PDO::PARAM_STR);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
	}
	/**
	 * Inserta nuevo registro a la tabla "stock" usando las propiedades del Objeto ...
	 */
	public function agregaADb(){
		$con=Conexion::conectar();
		$art=$this->getArtId();
		$emp=$this->empresa->getId();
		$usu=$this->getUsuario();
		$tar=$this->tarea->getId();
		$f=$this->getFechaRemitoCliente();
		$nrc=$this->getNumeroRemitoCliente();
		$query="INSERT INTO `stock`
				(`stk_id`,
				`art_id`,
				`emp_id`,";
		if(!empty($nrc)){
			$query.=" `stk_nro_rem_cli`,";
		}else{
			$query.=" `stk_nro_rem_cli`,";
		}
		if(!empty($f)){
			$query.=" `stk_fecha_rem_cli`,";
		}else{
			$query.=" `stk_fecha_rem_cli`,";
		}
		$query.=" `stk_fecha`,
				`stk_activo`,
				`stk_observaciones`,
				`tar_id`,
				`stk_posicion`,
				`stk_codigo`,
				`cli_usuario`)
				VALUES
				(null,
				:artId,
				:empId,";
		if(!empty($nrc)){
			$query.=" :nroRemCli,";
		}else{
			$query.=" null,";
		}
		if(!empty($f)){
			$query.=" :fechaRemCli,";
		}else{
			$query.=" null,";
		}
		$query.=" :fecha,
				0,
				:observaciones,
				:tarea,
				:posicion,
				:codigo,
				:usuario)";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':artId', $art, PDO::PARAM_INT);
		$stmt->bindParam(':empId', $emp, PDO::PARAM_INT);
		if(!empty($nrc)){
			$stmt->bindParam(':nroRemCli', $this->numeroRemitoCliente, PDO::PARAM_STR);
		}
		if(!empty($f)){
			$stmt->bindParam(':fechaRemCli', $this->fechaRemitoCliente, PDO::PARAM_STR);
		}
		$stmt->bindParam(':fecha', $this->fecha, PDO::PARAM_STR);
		$stmt->bindParam(':observaciones', $this->observaciones, PDO::PARAM_STR);
		$stmt->bindParam(':tarea', $tar, PDO::PARAM_INT);
		$stmt->bindParam(':posicion', $this->posicion, PDO::PARAM_STR);
		$stmt->bindParam(':codigo', $this->codigo, PDO::PARAM_STR);
		$stmt->bindParam(':usuario', $usu, PDO::PARAM_STR);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
		$this->uFilaIns=$con->lastInsertId();
		$this->setId($this->uFilaIns);
	}
	/**
	 * Borra registro de la tabla "stock" filtrado por ID ...
	 */
	public function borraPorId($ID){
		if(!isset($ID)){
			return $this;
		}
		$con=Conexion::conectar();
		$query = "DELETE FROM stock WHERE stk_id = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $ID, PDO::PARAM_STR);
		$stmt -> execute();
		$this->uFilaIns=$con->lastInsertId();
	}
}