<?php
/**
 * @Author : José Luis Villaronga
 * @copyright : 2019
 */
class Curso
{
	/**
	 * Propiedades
	 */
	private $id;
	private $designacion;
	private $descripcion;
	private $duracion;
	public $status;
	public $docente;
	private $fechaInicio;
	private $fechaFinal;
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
			$this->status=new CursoStatus(null);
			$this->docente=new Usuario(null);
			return $this;
		}
		$query="SELECT * FROM cursos WHERE cur_id = ".$g;
		$res=Db::listar($query);
		foreach($res as $fila){
			$this->id=$fila['cur_id'];
			$this->designacion=$fila['cur_designacion'];
			$this->descripcion=$fila['cur_descripcion'];
			$this->duracion=$fila['cur_duracion'];
			$this->status=new CursoStatus($fila['cs_id']);
			$this->docente=new Usuario($fila['cur_docente']);
			$this->fechaInicio=$fila['cur_fecha_inicio'];
			$this->fechaFinal=$fila['cur_fecha_final'];
		}
	}
	/**
	 * Getters ..
	 */
	public function getId(){
	 	return $this->id;
	}
	public function getDesignacion(){
		return $this->designacion;
	}
	public function getDescripcion(){
		return $this->descripcion;
	}
	public function getDuracion(){
		return $this->duracion;
	}
	public function getFechaInicio(){
		return $this->fechaInicio;
	}
	public function getFechaFinal(){
		return $this->fechaFinal;
	}
	/**
	 * Setters ...
	 */
	public function setId($id){
		$this->id=$id;
	}
	public function setDesignacion($nD){
		if(!empty($nD)){
			$this->designacion=$nD;
		}else{
			$this->errores['designacion'] = "Hay que ingresar Designación ...";
			$this->errores['gen'] = "harError";
		}
	}
	public function setDescripcion($nDe){
		if(!empty($nDe)){
			$this->descripcion=$nDe;
		}else{
			$this->errores['descripcion'] = "Hay que ingresar Descripción ...";
			$this->errores['gen'] = "harError";
		}
	}
	public function setDuracion($nDu){
		if(empty($nDu)){
			$this->errores['duracion'] = "Hay que ingresar Duración ...";
			$this->errores['gen'] = "harError";
		}elseif(is_numeric($nDu)){
			$this->duracion=$nDu;
		}else{
			$this->errores['duracion'] = "El dato Duración debe ser numérico entero ...";
			$this->errores['gen'] = "harError";
		}
	}
	public function setFechaInicio($fechaInicio=null){
		if(is_null($fechaInicio)){
			$this->fechaInicio=date("Y-m-d");
		}else{
			$this->fechaInicio=$fechaInicio;
		}
	}
	public function setFechaFinal($fechaFinal=null){
		if(is_null($fechaFinal)){
			$this->fechaFinal=date("Y-m-d");
		}else{
			$this->fechaFinal=$fechaFinal;
		}
	}
	/**
	 * Actualiza la tabla "cursos" con las propiedades actuales del objeto ...
	 */
	public function actualizaDb(){
		$status=$this->status->getId();
		$docente=$this->docente->getUsuario();
		$con=Conexion::conectar();
		$query="UPDATE `cursos`
				SET
				`cur_id` = :id,
				`cur_designacion` = :designacion,
				`cur_descripcion` = :descripcion,
				`cur_duracion` = :duracion,
				`cs_id` = :status,
				`cur_docente` = :docente,
				`cur_fecha_inicio` = :fechaInicio,
				`cur_fecha_final` = :fechaFinal
				WHERE `cur_id` = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
		$stmt->bindParam(':designacion', $this->designacion, PDO::PARAM_STR);
		$stmt->bindParam(':descripcion', $this->descripcion, PDO::PARAM_STR);
		$stmt->bindParam(':duracion', $this->duracion, PDO::PARAM_INT);
		$stmt->bindParam(':status', $status, PDO::PARAM_INT);
		$stmt->bindParam(':fechaInicio', $this->fechaInicio, PDO::PARAM_STR);
		$stmt->bindParam(':fechaFinal', $this->fechaFinal, PDO::PARAM_STR);
		$stmt->bindParam(':docente', $docente, PDO::PARAM_STR);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
	}
	/**
	 * Inserta nuevo registro a la tabla "cursos" usando las propiedades del Objeto ...
	 */
	public function agregaADb(){
		$status=$this->status->getId();
		$docente=$this->docente->getUsuario();
		$con=Conexion::conectar();
		$query="INSERT INTO `cursos`
				(`cur_designacion`,
				`cur_descripcion`,
				`cur_duracion`,
				`cs_id`,
				`cur_docente`,
				`cur_fecha_inicio`,
				`cur_fecha_final`)
				VALUES
				(:designacion,
				:descripcion,
				:duracion,
				:status,
				:docente,
				:fechaInicio,
				:fechaFinal)";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':designacion', $this->designacion, PDO::PARAM_STR);
		$stmt->bindParam(':descripcion', $this->descripcion, PDO::PARAM_STR);
		$stmt->bindParam(':duracion', $this->duracion, PDO::PARAM_INT);
		$stmt->bindParam(':status', $status, PDO::PARAM_INT);
		$stmt->bindParam(':fechaInicio', $this->fechaInicio, PDO::PARAM_STR);
		$stmt->bindParam(':fechaFinal', $this->fechaFinal, PDO::PARAM_STR);
		$stmt->bindParam(':docente', $docente, PDO::PARAM_STR);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
		$this->uFilaIns=$con->lastInsertId();
		$this->setId($this->uFilaIns);
	}
	/**
	 * Borra registro de la tabla "cursos" filtrado por ID ...
	 */
	public function borraPorId($ID){
		if(!isset($ID)){
			return $this;
		}
		$con=Conexion::conectar();
		$query = "DELETE FROM cursos WHERE cur_id = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $ID, PDO::PARAM_STR);
		$stmt -> execute();
		$this->uFilaIns=$con->lastInsertId();
	}
}