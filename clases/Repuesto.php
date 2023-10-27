<?php
/**
 * @author : José Luis Villaronga
 * @copyright : 2014
 */
class Repuesto
{
	/**
	 * Propiedades
	 */
	private $id;
	public $categoria;
	private $nombre;
	private $reemplazo;
	private $referencia;
	private $nroParte;
	private $cantidad=NULL;
	private $pReposicion=NULL;
	private $dataSheet;
	private $ubicacion;
	private $observaciones;
	private $fecha;
	public $usuario;
	public $listaProveedores;
	public $history;
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
			$this->categoria=new RepuCategoria(null);
			$this->listaProveedores=new ListaProveedor(null);
			$this->usuario=new Usuario(null);
			$this->history=new HistoryComp(null);
			return $this;
		}
		$query = "SELECT * FROM repu_componentes WHERE comp_id = $c";
		foreach (Db::listar($query) as $fila){
			$this->id=$fila['comp_id'];
			$this->categoria=new RepuCategoria($fila['rcat_nombre']);
			$this->nombre=$fila['comp_denominacion'];
			$this->reemplazo=$fila['comp_reemplazo'];
			$this->referencia=$fila['comp_referencia'];
			$this->nroParte=$fila['comp_nro_parte'];
			$this->cantidad=$fila['comp_existencias'];
			$this->dataSheet=$fila['comp_data_sheet'];
			$this->pReposicion=$fila['comp_reposicion'];
			$this->ubicacion=$fila['comp_ubicacion'];
			$this->fecha=$fila['comp_fecha'];
			$this->usuario=new Usuario($fila['cli_usuario']);
			$this->observaciones=$fila['comp_observaciones'];
			$this->history=new HistoryComp($fila['comp_id']);
		}
		$this->listaProveedores=new ListaProveedor($c);
	}
	/**
	 * Getters ...
	 */
	public function getId(){
		return $this->id;
	}
	public function getNombre(){
		return $this->nombre;
	}
	public function getReemplazo(){
		return $this->reemplazo;
	}
	public function getReferencia(){
		return $this->referencia;
	}
	public function getNroParte(){
		return $this->nroParte;
	}public function getCantidad(){
		return $this->cantidad;
	}
	public function getDataSheet(){
		return $this->dataSheet;
	}
	public function getPuntoReposicion(){
		return $this->pReposicion;
	}
	public function getUbicacion(){
		return $this->ubicacion;
	}
	public function getFecha(){
		return $this->fecha;
	}
	public function getObservaciones(){
		return $this->observaciones;
	}
	/**
	 * Setters ...
	 */
	public function setId($nId){
		$this->id=$nId;
		$this->listaProveedores=new ListaProveedor($nId);
		$this->history=new HistoryComp($nId);
	}
	public function setNombre($nN){
		if(!empty($nN)){
			$this->nombre=$nN;
		}else{
			$this->errores['nombre']="Hay que ingresar denominación ...";
			$this->errores['gen'] = "harError";
		}
	}
	public function setReemplazo($nR){
		if(!empty($nR)){
			$this->reemplazo=$nR;
		}else{
			$this->reemplazo=NULL;
		}
	}
	public function setReferencia($nR){
		if(!empty($nR)){
			$this->referencia=$nR;
		}else{
			$this->referencia=NULL;
		}
	}
	public function setNroParte($nNP){
		if(!empty($nNP)){
			$this->nroParte=$nNP;
		}else{
			$this->nroParte=NULL;
		}
	}
	public function setCantidad($nC){
		if(is_null($nC)){
			$this->errores['cantidad']="Hay que ingresar cantidad ...";
			$this->errores['gen'] = "harError";
		}elseif(!is_numeric($nC)){
			$this->errores['cantidad']="Se espera valor numérico ...";
			$this->errores['gen'] = "harError";
		}elseif($nC < "0"){
			$this->errores['cantidad']="Se espera valor positivo ...";
			$this->errores['gen'] = "harError";
		}else{
			$this->cantidad=$nC;
		}
	}
	public function setDataSheet($nDS){
		if(!empty($nDS)){
			$this->dataSheet=$nDS;
		}else{
			$this->dataSheet=NULL;
		}
	}
	public function setPuntoReposicion($nPR){
		if(is_null($nPR)){
			$this->errores['puntoReposicion']="Hay que ingresar valor ...";
			$this->errores['gen'] = "harError";
		}elseif(!is_numeric($nPR)){
			$this->errores['puntoReposicion']="Se espera valor numérico ...";
			$this->errores['gen'] = "harError";
		}elseif($nPR < 0){
			$this->errores['puntoReposicion']="Se espera valor positivo ...";
			$this->errores['gen'] = "harError";
		}else{
			$this->pReposicion=$nPR;
		}
	}
	public function setUbicacion($nU){
		$this->ubicacion=$nU;
	}
	public function setFecha($nF){
		if(empty($nF)){
			$this->fecha=date('Y-m-d H:i:s');
		}else{
			$this->fecha=$nF;
		}
	}
	public function setObservaciones($nO){
		$this->observaciones=$nO;
	}
	/**
	 * Actualiza la tabla "repu_componentes" con las propiedades actuales del objeto ...
	 */
	public function actualizaDb(){
		$con=Conexion::conectar();
		$query="UPDATE repu_componentes
				SET
				comp_id = :id,
				rcat_nombre = :categoria,
				comp_denominacion = :nombre,
				comp_reemplazo = :reemplazo,
				comp_referencia = :referencia,
				comp_nro_parte = :nroParte,
				comp_existencias = :cantidad,
				comp_reposicion = :puntoReposicion,
				comp_data_sheet = :dataSheet,
				comp_ubicacion = :ubicacion,
				comp_fecha = :fecha,
				cli_usuario = :usuario,
				comp_observaciones = :observaciones
				WHERE comp_id = :id";
		$stmt = $con -> prepare($query);
		$usu=$this->usuario->getUsuario();
		$cat=$this->categoria->getNombre();
		$stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
		$stmt->bindParam(':categoria', $cat, PDO::PARAM_STR);
		$stmt->bindParam(':nombre', $this->nombre, PDO::PARAM_STR);
		$stmt->bindParam(':reemplazo', $this->reemplazo, PDO::PARAM_STR);
		$stmt->bindParam(':referencia', $this->referencia, PDO::PARAM_STR);
		$stmt->bindParam(':nroParte', $this->nroParte, PDO::PARAM_STR);
		$stmt->bindParam(':cantidad', $this->cantidad, PDO::PARAM_INT);
		$stmt->bindParam(':puntoReposicion', $this->pReposicion, PDO::PARAM_INT);
		$stmt->bindParam(':dataSheet', $this->dataSheet, PDO::PARAM_STR);
		$stmt->bindParam(':ubicacion', $this->ubicacion, PDO::PARAM_STR);
		$stmt->bindParam(':fecha', $this->fecha, PDO::PARAM_STR);
		$stmt->bindParam(':usuario', $usu, PDO::PARAM_STR);
		$stmt->bindParam(':observaciones', $this->observaciones, PDO::PARAM_STR);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
	}
	/**
	 * Inserta nuevo registro a la tabla "repu_componentes" usando las propiedades del Objeto ...
	 */
	public function agregaADb(){
		$con=Conexion::conectar();
		$query="INSERT INTO repu_componentes
				(comp_id,
				rcat_nombre,
				comp_denominacion,
				comp_reemplazo,
				comp_referencia,
				comp_nro_parte,
				comp_existencias,
				comp_reposicion,
				comp_data_sheet,
				comp_ubicacion,
				comp_fecha,
				cli_usuario,
				comp_observaciones)
				VALUES
				(null,
				:categoria,
				:nombre,
				:reemplazo,
				:referencia,
				:nroParte,
				:cantidad,
				:puntoReposicion,
				:dataSheet,
				:ubicacion,
				:fecha,
				:usuario,
				null)";
		$stmt = $con -> prepare($query);
		$usu=$this->usuario->getUsuario();
		$cat=$this->categoria->getNombre();
		$stmt->bindParam(':categoria', $cat, PDO::PARAM_STR);
		$stmt->bindParam(':nombre', $this->nombre, PDO::PARAM_STR);
		$stmt->bindParam(':reemplazo', $this->reemplazo, PDO::PARAM_STR);
		$stmt->bindParam(':referencia', $this->referencia, PDO::PARAM_STR);
		$stmt->bindParam(':nroParte', $this->nroParte, PDO::PARAM_STR);
		$stmt->bindParam(':cantidad', $this->cantidad, PDO::PARAM_INT);
		$stmt->bindParam(':puntoReposicion', $this->pReposicion, PDO::PARAM_INT);
		$stmt->bindParam(':dataSheet', $this->dataSheet, PDO::PARAM_STR);
		$stmt->bindParam(':ubicacion', $this->ubicacion, PDO::PARAM_STR);
		$stmt->bindParam(':fecha', $this->fecha, PDO::PARAM_STR);
		$stmt->bindParam(':usuario', $usu, PDO::PARAM_STR);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
		$this->uFilaIns=$con->lastInsertId();
		$this->setId($this->uFilaIns);
	}
	/**
	 * Borra registro de la tabla "repu_componentes" filtrado por ID ...
	 */
	public function borraPorId($ID){
		if(!isset($ID)){
			return $this;
		}
		$con=Conexion::conectar();
		$query = "DELETE FROM repu_componentes WHERE comp_id = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $ID, PDO::PARAM_INT);
		$stmt -> execute();
		$this->uFilaIns=$con->lastInsertId();
	}
}
