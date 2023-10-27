<?php
/**
 * @Author : José Luis Villaronga
 * @copyright : 2015
 */
class SgcLoteEncuesta
{
	/**
	 * Propiedades
	 */
	private $id;
	private $encId;
	public $pregunta;
	public $valor;
	private $fecha;
	private $nombre;
	private $apellido;
	private $cargo;
	private $observaciones;
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
		if(empty($g)){
			$this->pregunta=new SgcPregunta(null);
			$this->valor=new SgcValor(null);
			return $this;
		}else{
			$this->encId=$g;
			$this->pregunta=new SgcPregunta(null);
			$this->valor=new SgcValor(null);
			$this->setLote($g);
		}
	}
	/**
	 * Getters ..
	 */
	public function getId(){
	 	return $this->id;
	}
	public function getNombre(){
		return $this->nombre;
	}
	public function getApellido(){
		return $this->apellido;
	}
	public function getCargo(){
		return $this->cargo;
	}
	public function getFecha(){
		return $this->fecha;
	}
	public function getEncId(){
		return $this->encId;
	}
	public function getPreguntaId(){
		return $this->pregunta->getId();
	}
	public function getValorId(){
		return $this->valor->getId();
	}
	public function getObservaciones(){
		return $this->observaciones;
	}
	/**
	 * Setters ...
	 */
	public function setId($id){
		$this->id=$id;
	}
	public function setNombre($nN){
		$this->nombre=$nN;
	}
	public function setApellido($nA){
		$this->apellido=$nA;
	}
	public function setCargo($nC){
		$this->cargo=$nC;
	}
	public function setFecha($nF){
		$this->fecha=$nF;
	}
	public function setEncId($nEI){
		$this->encId=$nEI;
	}
	public function setPregunta($nP){
		$this->pregunta=new SgcPregunta($nP);
	}
	public function setValor($nV){
		$this->valor=new SgcValor($nV);
	}
	public function setObservaciones($nO){
		$this->observaciones=$nO;
	}
	/**
	 * Inserta nuevo registro a la tabla "sgc_encuesta_lote" usando las propiedades del Objeto ...
	 */
	public function agregaADb(){
		$con=Conexion::conectar();
		$preg=$this->getPreguntaId();
		$val=$this->getValorId();
		$query="INSERT INTO sgc_encuesta_lote
				(enc_id,
				 encp_id,
				 encv_id,
				 encl_fecha,
				 encl_nombre,
				 encl_apellido,
				 encl_cargo,
				 encl_observaciones)
				 VALUES
				 (:encId,
				  :pregunta,
				  :valor,
				  :fecha,
				  :nombre,
				  :apellido,
				  :cargo,
				  :observaciones)";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':encId', $this->encId, PDO::PARAM_INT);
		$stmt->bindParam(':fecha', $this->fecha, PDO::PARAM_STR);
		$stmt->bindParam(':pregunta', $this->pregunta->id, PDO::PARAM_INT);
		$stmt->bindParam(':valor', $this->valor->id, PDO::PARAM_INT);
		$stmt->bindParam(':nombre', $this->nombre, PDO::PARAM_STR);
		$stmt->bindParam(':apellido', $this->apellido, PDO::PARAM_STR);
		$stmt->bindParam(':cargo', $this->cargo, PDO::PARAM_STR);
		$stmt->bindParam(':observaciones', $this->observaciones, PDO::PARAM_STR);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
		$this->uFilaIns=$con->lastInsertId();
		$this->setId($this->uFilaIns);
	}
	public function borraPorId($ID){
		if(!isset($ID)){
			return $this;
		}
		$con=Conexion::conectar();
		$query = "DELETE FROM sgc_encuesta_lote WHERE encl_id = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $ID, PDO::PARAM_STR);
		$stmt -> execute();
		$this->uFilaIns=$con->lastInsertId();
	}
	
	public function setPropiedadesPorId($id){
		foreach($this->lote as $fila){
			if($fila['encl_id']==$id){
				$this->id=$fila['encl_id'];
				$this->encId=$fila['enc_id'];
				$this->pregunta=new SgcPregunta($fila['encp_id']);
				$this->valor=new SgcValor($fila['encv_id']);
				$this->fecha=$fila['encl_fecha'];
				$this->nombre=$fila['encl_nombre'];
				$this->apellido=$fila['encl_apellido'];
				$this->cargo=$fila['encl_cargo'];
				$this->observaciones=$fila['encl_observaciones'];
			}
		}
	}
	public function setLote($id){
		if(empty($id)){
			$id=$this->encId;
		}
		$query="SELECT * FROM sgc_encuesta_lote WHERE enc_id = $id";
		foreach(Db::listar($query) as $fila){
			$this->lote[]=array("encl_id"=>$fila['encl_id'],
								"enc_id"=>$fila['enc_id'],
								"encp_id"=>$fila['encp_id'],
								"encv_id"=>$fila['encv_id'],
								"encl_fecha"=>$fila['encl_fecha'],
								"encl_nombre"=>$fila['encl_nombre'],
								"encl_apellido"=>$fila['encl_apellido'],
								"encl_cargo"=>$fila['encl_cargo'],
								"encl_observaciones"=>$fila['encl_observaciones']);
		}
	}
}