<?php
/**
 * @Author : José Luis Villaronga
 * @copyright : 2019
 */
class Encuesta
{
	/**
	 * Propiedades
	 */
	private $id=null;
	public $curso=null;
	public $usuario=null;
	private $fecha=null;
	public $loteEncuesta;
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
	public function __construct($curso=null,$usuario=null){
		if(is_object($curso) AND get_class($curso)=="Curso" AND !empty($usuario)){
			$query="SELECT * FROM encuesta WHERE cur_id = ".$curso->getId()." AND cli_usuario = '".$usuario."'";
			$res=Db::listar($query);
			if(count($res)==0){
				$this->curso=new Curso($curso->getId());
				$this->usuario=new Usuario($usuario);
				return $this;
			}else{
				$this->curso=new Curso($curso->getId());
				$this->usuario=new Usuario($usuario);
				$this->id=$res[0]['enc_id'];
				$this->fecha=$res[0]['enc_fecha'];
				$this->loteEncuesta=new EncuestaLote($res[0]['enc_id']);
				return $this;
			}
		}else{
			$this->curso=new Curso(null);
			$this->usuario=new Usuario(null);
			return $this;
		}
	}
	/**
	 * Getters ..
	 */
	public function getId(){
	 	return $this->id;
	}
	public function getFecha(){
		return $this->fecha;
	}
	/**
	 * Setters ...
	 */
	public function setId($id){
		$this->id=$id;
	}
	public function setFecha($fecha=null){
		if(is_null($fecha)){
			$this->fecha=date("Y-m-d H:i:s");
		}else{
			$this->fecha=$fecha;
		}
	}
	/**
	 * Actualiza la tabla "encuesta" con las propiedades actuales del objeto ...
	 */
	public function actualizaDb(){
		$curso=$this->curso->getId();
		$usuario=$this->usuario->getUsuario();
		$con=Conexion::conectar();
		$query="UPDATE `encuesta`
				SET
				`enc_id` = :id,
				`cur_id` = :curso,
				`cli_usuario` = :usuario,
				`enc_fecha` = :fecha
				WHERE `enc_id` = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
		$stmt->bindParam(':curso', $curso, PDO::PARAM_INT);
		$stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
		$stmt->bindParam(':fecha', $this->fecha, PDO::PARAM_INT);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
	}
	/**
	 * Inserta nuevo registro a la tabla "encuesta" usando las propiedades del Objeto ...
	 */
	public function agregaADb(){
		$curso=$this->curso->getId();
		$usuario=$this->usuario->getUsuario();
		$con=Conexion::conectar();
		$query="INSERT INTO `encuesta`
				(`cur_id`,
				`cli_usuario`,
				`enc_fecha`)
				VALUES
				(:curso,
				:usuario,
				:fecha)";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':curso', $curso, PDO::PARAM_INT);
		$stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
		$stmt->bindParam(':fecha', $this->fecha, PDO::PARAM_INT);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
		$this->uFilaIns=$con->lastInsertId();
		$this->setId($this->uFilaIns);
	}
	/**
	 * Borra registro de la tabla "encuesta" filtrado por ID ...
	 */
	public function borraPorId($ID){
		if(!isset($ID)){
			return $this;
		}
		$con=Conexion::conectar();
		$query = "DELETE FROM encuesta WHERE enc_id = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $ID, PDO::PARAM_STR);
		$stmt -> execute();
		$this->uFilaIns=$con->lastInsertId();
	}
}