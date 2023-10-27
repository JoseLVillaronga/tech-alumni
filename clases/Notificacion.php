<?php
/**
 * @Author : José Luis Villaronga
 * @copyright : 2019
 */
class Notificacion
{
	/**
	 * Propiedades
	 */
	private $id;
	private $titulo;
	private $cuerpo;
	public $tipo;
	public $usuario;
	private $fecha;
	private $recivido;
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
			$this->tipo=new NotificacionTipo(null);
			$this->usuario=new Usuario(null);
			return $this;
		}
		$query="SELECT * FROM notificacion WHERE not_id = ".$g;
		$res=Db::listar($query);
		foreach($res as $fila){
			$this->id=$fila['not_id'];
			$this->titulo=$fila['not_titulo'];
			$this->cuerpo=$fila['not_cuerpo'];
			$this->tipo=new NotificacionTipo($fila['nott_id']);
			$this->usuario=new Usuario($fila['cli_usuario']);
			$this->fecha=$fila['not_fecha'];
			$this->recivido=$fila['not_recivido'];
		}
		return $this;
	}
	/**
	 * Getters ..
	 */
	public function getId(){
	 	return $this->id;
	}
	public function getTitulo(){
		return $this->titulo;
	}
	public function getCuerpo(){
		return $this->cuerpo;
	}
	public function getFecha(){
		return $this->fecha;
	}
	public function getRecivido(){
		return $this->recivido;
	}
	/**
	 * Setters ...
	 */
	public function setId($id){
		$this->id=$id;
	}
	public function setTitulo($nT){
		$this->titulo=$nT;
	}
	public function setCuerpo($nC){
		$this->cuerpo=$nC;
	}
	public function setFecha($nF=null){
		if(is_null($nF)){
			$this->fecha=date("Y-m-d H:i:s");
		}else{
			$this->fecha=$nF;
		}
	}
	public function setRecivido($nR){
		$this->recivido=$nR;
	}
	/**
	 * Actualiza la tabla "notificacion" con las propiedades actuales del objeto ...
	 */
	public function actualizaDb(){
		$tipo=$this->tipo->getId();
		$usuario=$this->usuario->getUsuario();
		$con=Conexion::conectar();
		$query="UPDATE `notificacion`
				SET
				`not_id` = :id,
				`not_titulo` = :titulo,
				`not_cuerpo` = :cuerpo,
				`nott_id` = :tipo,
				`cli_usuario` = :usuario,
				`not_fecha` = :fecha,
				`not_recivido` = :recivido
				WHERE `not_id` = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
		$stmt->bindParam(':titulo', $this->titulo, PDO::PARAM_STR);
		$stmt->bindParam(':cuerpo', $this->cuerpo, PDO::PARAM_STR);
		$stmt->bindParam(':tipo', $tipo, PDO::PARAM_INT);
		$stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
		$stmt->bindParam(':fecha', $this->fecha, PDO::PARAM_STR);
		$stmt->bindParam(':recivido', $this->recivido, PDO::PARAM_INT);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
	}
	/**
	 * Inserta nuevo registro a la tabla "notificacion" usando las propiedades del Objeto ...
	 */
	public function agregaADb(){
		$tipo=$this->tipo->getId();
		$usuario=$this->usuario->getUsuario();
		$con=Conexion::conectar();
		$query="INSERT INTO `notificacion`
				(`not_titulo`,
				`not_cuerpo`,
				`nott_id`,
				`cli_usuario`,
				`not_fecha`,
				`not_recivido`)
				VALUES
				(:titulo,
				:cuerpo,
				:tipo,
				:usuario,
				:fecha,
				:recivido)";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':titulo', $this->titulo, PDO::PARAM_STR);
		$stmt->bindParam(':cuerpo', $this->cuerpo, PDO::PARAM_STR);
		$stmt->bindParam(':tipo', $tipo, PDO::PARAM_INT);
		$stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
		$stmt->bindParam(':fecha', $this->fecha, PDO::PARAM_STR);
		$stmt->bindParam(':recivido', $this->recivido, PDO::PARAM_INT);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
		$this->uFilaIns=$con->lastInsertId();
		$this->setId($this->uFilaIns);
	}
	/**
	 * Borra registro de la tabla "notificacion" filtrado por ID ...
	 */
	public function borraPorId($ID){
		if(!isset($ID)){
			return $this;
		}
		$con=Conexion::conectar();
		$query = "DELETE FROM notificacion WHERE not_id = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $ID, PDO::PARAM_STR);
		$stmt -> execute();
		$this->uFilaIns=$con->lastInsertId();
	}
}