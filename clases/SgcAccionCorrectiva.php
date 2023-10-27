<?php
/**
 * @Author : José Luis Villaronga
 * @copyright : 2016
 */
class SgcAccionCorrectiva
{
	/**
	 * Propiedades
	 */
	private $id;
	private $analisisCausaRaiz;
	private $accion;
	public $responsable;
	private $plazo;
	public $estado;
	private $fecha=null;
	private $fechaTerminado=null;
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
	public function __construct($g=null){
		if(empty($g)){
			return $this;
		}else{
			$query="SELECT * FROM sgc_accion_correctiva WHERE ac_id = $g";
			$res=Db::listar($query);
			if(count($res)=="0"){
				return $this;
			}else{
				foreach($res as $fila){
					$this->id=$g;
					$this->analisisCausaRaiz=$fila['ac_analisis_causa_raiz'];
					$this->accion=$fila['ac_accion'];
					$this->responsable=new Usuario($fila['ac_responsable']);
					$this->plazo=$fila['ac_plazo'];
					$this->estado=new SgcACEstado($fila['ace_id']);
					$this->fecha=$fila['ac_fecha'];
					$this->fechaTerminado=$fila['ac_fecha_terminado'];
					$this->usuario=new Usuario($fila['cli_usuario']);
				}
			}
		}
	}
	/**
	 * Getters ..
	 */
	public function getId(){
	 	return $this->id;
	}
	public function getAnalisisCausaRaiz(){
		return $this->analisisCausaRaiz;
	}
	public function getAccion(){
		return $this->accion;
	}
	public function getResponsable(){
		return $this->responsable->getUsuario();
	}
	public function getPlazo(){
		return $this->plazo;
	}
	public function getFecha(){
		return $this->fecha;
	}
	public function getFechaTerminado(){
		return $this->fechaTerminado;
	}
	public function getUsuario(){
		return $this->usuario->getUsuario();
	}
	/**
	 * Setters ...
	 */
	public function setId($id){
		$this->id=$id;
	}
	public function setAnalisisCausaRaiz($nACR){
		if(empty($nACR)){
			$this->errores['acraiz']="Hay que ingresar Causa Raiz ...";
			$this->errores['gen'] = "harError";
		}elseif(strlen($nACR) > "250"){
			$this->errores['acraiz']="Máximo 250 caracteres ...";
			$this->errores['gen'] = "harError";
		}else{
			$this->analisisCausaRaiz=$nACR;
		}
	}
	public function setAccion($nA){
		if(empty($nA)){
			$this->errores['accion']="Hay que ingresar Acción Correctiva ...";
			$this->errores['gen'] = "harError";
		}elseif(strlen($nA) > "2048"){
			$this->errores['accion']="Máximo 2048 caracteres ...";
			$this->errores['gen'] = "harError";
		}else{
			$this->accion=$nA;
		}
	}
	public function setResponsable($nR){
		if(empty($nR)){
			$this->errores['responsable']="Hay que ingresar Responsable ...";
			$this->errores['gen'] = "harError";
		}elseif($nR=="0"){
			$this->errores['responsable']="Hay que ingresar Responsable ...";
			$this->errores['gen'] = "harError";
		}else{
			$this->responsable=new Usuario($nR);
		}
	}
	public function setPlazo($nP){
		$this->plazo=$nP;
	}
	public function setEstado($nE){
		if(empty($nE)){
			$this->errores['estado']="Hay que ingresar Estado ...";
			$this->errores['gen'] = "harError";
		}elseif($nE=="-1"){
			$this->errores['estado']="Hay que ingresar Estado ...";
			$this->errores['gen'] = "harError";
		}else{
			$this->estado=new SgcACEstado($nE);
		}
	}
	public function setFecha($nF){
		if(empty($nF)){
			$this->errores['fecha']="Hay que ingresar Fecha de Inicio ...";
			$this->errores['gen'] = "harError";
		}else{
			$this->fecha=$nF;
		}
	}
	public function setFechaTerminado($nFT){
		$this->fechaTerminado=$nFT;
	}
	/**
	 * Actualiza la tabla "sgc_accion_correctiva" con las propiedades actuales del objeto ...
	 */
	public function actualizaDb(){
		$con=Conexion::conectar();
		$resp=$this->responsable->getUsuario();
		$estado=$this->estado->getId();
		$usu=$this->usuario->getUsuario();
		$query = "UPDATE `sgc_accion_correctiva`
					SET
					`ac_id` = :id,
					`ac_analisis_causa_raiz` = :analisisCausaRaiz,
					`ac_accion` = :accion,
					`ac_responsable` = :responsable,
					`ac_plazo` = :plazo,
					`ace_id` = :estado,
					`ac_fecha` = :fecha,
					`ac_fecha_terminado` = :fechaTerminado,
					`cli_usuario` = :usuario
					WHERE `ac_id` = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
		$stmt->bindParam(':analisisCausaRaiz', $this->analisisCausaRaiz, PDO::PARAM_STR);
		$stmt->bindParam(':accion', $this->accion, PDO::PARAM_STR);
		$stmt->bindParam(':responsable', $resp, PDO::PARAM_STR);
		$stmt->bindParam(':plazo', $this->plazo, PDO::PARAM_STR);
		$stmt->bindParam(':estado', $estado, PDO::PARAM_INT);
		$stmt->bindParam(':fecha', $this->fecha, PDO::PARAM_STR);
		$stmt->bindParam(':fechaTerminado', $this->fechaTerminado, PDO::PARAM_STR);
		$stmt->bindParam(':usuario', $usu, PDO::PARAM_STR);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
	}
	/**
	 * Inserta nuevo registro a la tabla "sgc_accion_correctiva" usando las propiedades del Objeto ...
	 */
	public function agregaADb(){
		$con=Conexion::conectar();
		$resp=$this->responsable->getUsuario();
		$estado=$this->estado->getId();
		$usu=$this->usuario->getUsuario();
		$query="INSERT INTO `sgc_accion_correctiva`
				(`ac_id`,
				`ac_analisis_causa_raiz`,
				`ac_accion`,
				`ac_responsable`,
				`ac_plazo`,
				`ace_id`,
				`ac_fecha`,
				`ac_fecha_terminado`,
				`cli_usuario`)
				VALUES
				(null,
				:analisisCausaRaiz,
				:accion,
				:responsable,
				:plazo,
				:estado,
				:fecha,
				:fechaTerminado,
				:usuario)";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':analisisCausaRaiz', $this->analisisCausaRaiz, PDO::PARAM_STR);
		$stmt->bindParam(':accion', $this->accion, PDO::PARAM_STR);
		$stmt->bindParam(':responsable', $resp, PDO::PARAM_STR);
		$stmt->bindParam(':plazo', $this->plazo, PDO::PARAM_STR);
		$stmt->bindParam(':estado', $estado, PDO::PARAM_INT);
		$stmt->bindParam(':fecha', $this->fecha, PDO::PARAM_STR);
		$stmt->bindParam(':fechaTerminado', $this->fechaTerminado, PDO::PARAM_STR);
		$stmt->bindParam(':usuario', $usu, PDO::PARAM_STR);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
		$this->uFilaIns=$con->lastInsertId();
		$this->setId($this->uFilaIns);
	}
	/**
	 * Borra registro de la tabla "sgc_accion_correctiva" filtrado por ID ...
	 */
	public function borraPorId($ID){
		if(!isset($ID)){
			return $this;
		}
		$con=Conexion::conectar();
		$query = "DELETE FROM sgc_accion_correctiva WHERE ac_id = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $ID, PDO::PARAM_STR);
		$stmt -> execute();
		$this->uFilaIns=$con->lastInsertId();
	}
}