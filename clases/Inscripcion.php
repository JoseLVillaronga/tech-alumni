<?php
/**
 * @Author : José Luis Villaronga
 * @copyright : 2014
 */
class Inscripcion
{
	/**
	 * Propiedades
	 */
	private $id;
	public $curso;
	private $usuario;
	private $fecha;
	public $inscrivible=0;
	public $cancelable=0;
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
		if(is_object($curso) AND get_class($curso)=="Curso"){
			if(empty($usuario)){$usuario=$_SESSION['usuario'];}
			$this->curso=new Curso($curso->getId());
			$this->usuario=$usuario;
			$this->fecha=date("Y-m-d H:i:s");
			$query="SELECT * FROM inscripcion WHERE cur_id = ".$this->curso->getId()." AND cli_usuario = '".$usuario."'";
			$res=Db::listar($query);
			if($this->curso->status->getDescripcion()!="Terminado" AND $this->curso->getFechaInicio() > date("Y-m-d") AND count($res)=="1"){
				$this->cancelable=1;
				$this->id=$res[0]['ins_id'];
			}elseif($this->curso->status->getDescripcion()!="Terminado" AND $this->curso->getFechaInicio() > date("Y-m-d") AND count($res)!="1"){
				$this->inscrivible=1;
			}
		}
	}
	/**
	 * Getters ..
	 */
	public function getId(){
	 	return $this->id;
	}
	/**
	 * Setters ...
	 */
	public function setId($id){
		$this->id=$id;
	}
	/**
	 * Inserta nuevo registro a la tabla "inscripcion" usando las propiedades del Objeto ...
	 */
	public function agregaADb(){
		if($this->inscrivible==1){
			$curso=$this->curso->getId();
			$con=Conexion::conectar();
			$query="INSERT INTO `inscripcion`
					(`cur_id`,
					`cli_usuario`,
					`ins_fecha`)
					VALUES
					(:curso,
					:usuario,
					:fecha)";
			$stmt = $con -> prepare($query);
			$stmt->bindParam(':curso', $curso, PDO::PARAM_INT);
			$stmt->bindParam(':usuario', $this->usuario, PDO::PARAM_STR);
			$stmt->bindParam(':fecha', $this->fecha, PDO::PARAM_STR);
			$stmt -> execute();
			$this->errorSql = $stmt->errorInfo();
			$this->uFilaIns=$con->lastInsertId();
			$this->setId($this->uFilaIns);
		}
	}
	/**
	 * Borra registro de la tabla "inscripcion" filtrado por ID ...
	 */
	public function borraPorId($ID){
		if(!isset($ID)){
			return $this;
		}
		if($this->cancelable==1){
			$con=Conexion::conectar();
			$query = "DELETE FROM inscripcion WHERE ins_id = :id";
			$stmt = $con -> prepare($query);
			$stmt->bindParam(':id', $ID, PDO::PARAM_STR);
			$stmt -> execute();
			$this->uFilaIns=$con->lastInsertId();
		}
	}
}