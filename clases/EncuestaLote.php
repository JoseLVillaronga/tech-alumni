<?php
/**
 * @Author : José Luis Villaronga
 * @copyright : 2019
 */
class EncuestaLote
{
	/**
	 * Propiedades
	 */
	private $id;
	private $encuestaId;
	private $preguntaId;
	private $preguntaValueId;
	private $fecha;
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
	public function __construct($encuesta=null){
		if(is_null($encuesta)){
			return $this;
		}else{
			$this->encuestaId=$encuesta;
			$query="SELECT * FROM encuesta_lote WHERE enc_id = ".$encuesta;
			$res=Db::listar($query);
			if(count($res)==0){
				return $this;
			}else{
				foreach($res as $fila){
					$this->lote[]=array(
						"encl_id"=>$fila['encl_id'],
						"enc_id"=>$fila['enc_id'],
						"encp_id"=>$fila['encp_id'],
						"encv_id"=>$fila['encv_id'],
						"encl_fecha"=>$fila['encl_fecha'],
						"cli_usuario"=>$fila['cli_usuario']
					);
				}
				return $this;
			}
		}
	}
	/**
	 * Getters ..
	 */
	public function getId(){
	 	return $this->id;
	}
	public function getEncuestaId(){
		return $this->encuestaId;
	}
	public function getPreguntaId(){
		return $this->preguntaId;
	}
	public function getPreguntaValueId(){
		return $this->preguntaValueId;
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
	public function setId($id){
		$this->id=$id;
	}
	public function setEncuestaId($nEI){
		$this->encuestaId=$nEI;
	}
	public function setPreguntaId($nPI){
		$this->preguntaId=$nPI;
	}
	public function setPreguntaValueId($nPVI){
		$this->preguntaValueId=$nPVI;
	}
	public function setFecha($nF){
		$this->fecha=$nF;
	}
	public function setUsuario($nU){
		$this->usuario=$nU;
	}
	/**
	 * Inserta nuevo registro a la tabla "encuesta_lote" usando las propiedades del Objeto ...
	 */
	public function agregaADb(){
		$con=Conexion::conectar();
		$query="INSERT INTO `encuesta_lote`
				(`enc_id`,
				`encp_id`,
				`encv_id`,
				`encl_fecha`,
				`cli_usuario`)
				VALUES
				(:encuestaId,
				:preguntaId,
				:preguntaValueId,
				:fecha,
				:usuario)";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':encuestaId', $this->encuestaId, PDO::PARAM_INT);
		$stmt->bindParam(':preguntaId', $this->preguntaId, PDO::PARAM_INT);
		$stmt->bindParam(':preguntaValueId', $this->preguntaValueId, PDO::PARAM_INT);
		$stmt->bindParam(':fecha', $this->fecha, PDO::PARAM_STR);
		$stmt->bindParam(':usuario', $this->usuario, PDO::PARAM_STR);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
		$this->uFilaIns=$con->lastInsertId();
		$this->setId($this->uFilaIns);
	}
	/**
	 * Borra registro de la tabla "encuesta_lote" filtrado por ID ...
	 */
	public function borraPorId($ID){
		if(!isset($ID)){
			return $this;
		}
		$con=Conexion::conectar();
		$query = "DELETE FROM encuesta_lote WHERE encl_id = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $ID, PDO::PARAM_STR);
		$stmt -> execute();
		$this->uFilaIns=$con->lastInsertId();
	}
}