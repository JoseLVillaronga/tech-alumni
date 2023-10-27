<?php
/**
 * @Author : José Luis Villaronga
 * @copyright : 2019
 */
class Mensaje
{
	/**
	 * Propiedades
	 */
	private $id;
	private $asunto;
	private $cuerpo;
	private $fecha;
	public $tipo;
	public $sender;
	private $receiver;
	private $leido;
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
			$this->tipo=new MensajeTipo(null);
			$this->sender=new Usuario(null);
			return $this;
		}
		$query="SELECT * FROM mensaje WHERE men_id = ".$g;
		$res=Db::listar($query);
		foreach($res as $fila){
			$this->id=$fila['men_id'];
			$this->asunto=$fila['men_asunto'];
			$this->cuerpo=$fila['men_cuerpo'];
			$this->fecha=$fila['men_fecha'];
			$this->tipo=new MensajeTipo($fila['ment_id']);
			$this->sender=new Usuario($fila['men_sender']);
			$this->receiver=$fila['men_receiver'];
			$this->leido=$fila['men_leido'];
		}
		return $this;
	}
	/**
	 * Getters ..
	 */
	public function getId(){
	 	return $this->id;
	}
	public function getAsunto(){
		return $this->asunto;
	}
	public function getCuerpo(){
		return $this->cuerpo;
	}
	public function getFecha(){
		return $this->fecha;
	}
	public function getReceiver(){
		return $this->receiver;
	}
	public function getLeido(){
		return $this->leido;
	}
	/**
	 * Setters ...
	 */
	public function setId($id){
		$this->id=$id;
	}
	public function setAsunto($nA){
		$this->asunto=$nA;
	}
	public function setCuerpo($nC){
		$this->cuerpo=$nC;
	}
	public function setFecha($nF){
		$this->fecha=$nF;
	}
	public function setLeido($nL){
		$this->leido=$nL;
	}
	public function setReceiver($nR){
		$this->receiver=$nR;
	}
	/**
	 * Actualiza la tabla "mensaje" con las propiedades actuales del objeto ...
	 */
	public function actualizaDb(){
		$tipo=$this->tipo->getId();
		$sender=$this->sender->getUsuario();
		$con=Conexion::conectar();
		$query="UPDATE `mensaje`
				SET
				`men_id` = :id,
				`men_asunto` = :asunto,
				`men_cuerpo` = :cuerpo,
				`men_fecha` = :fecha,
				`ment_id` = :tipo,
				`men_sender` = :sender,
				`men_receiver` = :receiver,
				`men_leido` = :leido
				WHERE `men_id` = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
		$stmt->bindParam(':asunto', $this->asunto, PDO::PARAM_STR);
		$stmt->bindParam(':cuerpo', $this->cuerpo, PDO::PARAM_STR);
		$stmt->bindParam(':fecha', $this->fecha, PDO::PARAM_STR);
		$stmt->bindParam(':tipo', $tipo, PDO::PARAM_INT);
		$stmt->bindParam(':sender', $sender, PDO::PARAM_STR);
		$stmt->bindParam(':receiver', $this->receiver, PDO::PARAM_STR);
		$stmt->bindParam(':leido', $this->leido, PDO::PARAM_STR);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
	}
	/**
	 * Inserta nuevo registro a la tabla "mensaje" usando las propiedades del Objeto ...
	 */
	public function agregaADb(){
		$tipo=$this->tipo->getId();
		$sender=$this->sender->getUsuario();
		$con=Conexion::conectar();
		$query="INSERT INTO `mensaje`
				(`men_asunto`,
				`men_cuerpo`,
				`men_fecha`,
				`ment_id`,
				`men_sender`,
				`men_receiver`,
				`men_leido`)
				VALUES
				(:asunto,
				:cuerpo,
				:fecha,
				:tipo,
				:sender,
				:receiver,
				:leido)";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':asunto', $this->asunto, PDO::PARAM_STR);
		$stmt->bindParam(':cuerpo', $this->cuerpo, PDO::PARAM_STR);
		$stmt->bindParam(':fecha', $this->fecha, PDO::PARAM_STR);
		$stmt->bindParam(':tipo', $tipo, PDO::PARAM_INT);
		$stmt->bindParam(':sender', $sender, PDO::PARAM_STR);
		$stmt->bindParam(':receiver', $this->receiver, PDO::PARAM_STR);
		$stmt->bindParam(':leido', $this->leido, PDO::PARAM_STR);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
		$this->uFilaIns=$con->lastInsertId();
		$this->setId($this->uFilaIns);
	}
	/**
	 * Borra registro de la tabla "mensaje" filtrado por ID ...
	 */
	public function borraPorId($ID){
		if(!isset($ID)){
			return $this;
		}
		$con=Conexion::conectar();
		$query = "DELETE FROM mensaje WHERE men_id = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $ID, PDO::PARAM_STR);
		$stmt -> execute();
		$this->uFilaIns=$con->lastInsertId();
	}
}