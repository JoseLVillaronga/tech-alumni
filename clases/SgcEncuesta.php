<?php
/**
 * @Author : José Luis Villaronga
 * @copyright : 2015
 */
class SgcEncuesta
{
	/**
	 * Propiedades
	 */
	private $id;
	public $empresa;
	private $fechaInicio;
	private $fechaFinal;
	public $usuario;
	public $loteEncuesta;
	public $valida;
	private $contador=0;
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
			$this->empresa=new Empresa(null);
			$this->usuario=new Usuario(null);
			$this->loteEncuesta=new SgcLoteEncuesta(null);
			return $this;
		}else{
			$query="SELECT * FROM sgc_encuesta WHERE enc_id = $g";
			foreach(Db::listar($query) as $fila){
				$this->id=$fila['enc_id'];
				$this->empresa=new Empresa($fila['emp_id']);
				$this->fechaInicio=$fila['enc_fecha_inicio'];
				$this->fechaFinal=$fila['enc_fecha_cierre'];
				$this->usuario=new Usuario($fila['cli_usuario']);
				$this->contador=$fila['enc_cont'];
			}
			$this->loteEncuesta=new SgcLoteEncuesta($g);
			$this->setValida();
		}
	}
	/**
	 * Getters ..
	 */
	public function getId(){
	 	return $this->id;
	}
	public function getFechaInicio(){
		return $this->fechaInicio;
	}
	public function getFechaFinal(){
		return $this->fechaFinal;
	}
	public function getEmpresaId(){
		return $this->empresa->getId();
	}
	public function getNombreUsuario(){
		return $this->usuario->getUsuario();
	}
	public function getContador(){
		return $this->contador;
	}
	/**
	 * Setters ...
	 */
	public function setId($id){
		$this->id=$id;
	}
	public function setFechaInicio($fI){
		$this->fechaInicio=$fI;
	}
	public function setFechaFinal($nFF){
		$this->fechaFinal=$nFF;
	}
	public function setEmpresa($nE){
		$this->empresa=new Empresa($nE);
	}
	public function setUsuario($nU){
		$this->usuario=new Usuario($nU);
	}
	public function setValida(){
		$fecha=strtotime(date('Y-m-d H:i:s'));
		$fechaI=strtotime($this->fechaInicio." 00:00:01");
		$fechaF=strtotime($this->fechaFinal." 00:00:01");
		if($fecha < $fechaF){
			$this->valida=TRUE;
		}else{
			$this->valida=FALSE;
		}
	}
	public function setContador($nC=null){
		if(is_null($nC)){
			$this->contador=$this->contador+1;
		}else{
			$this->contador=$nC;
		}
	}
	/**
	 * Actualiza la tabla "sgc_encuesta" con las propiedades actuales del objeto ...
	 */
	public function actualizaDb(){
		$con=Conexion::conectar();
		$emp=$this->getEmpresaId();
		$usu=$this->getNombreUsuario();
		$query="UPDATE sgc_encuesta
				SET
				enc_id = :id,
				emp_id = :empresa,
				enc_fecha_inicio = :fechaInicio,
				enc_fecha_cierre = :fechaFinal,
				cli_usuario = :usuario,
				enc_cont = :contador
				WHERE enc_id = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
		$stmt->bindParam(':empresa', $emp, PDO::PARAM_INT);
		$stmt->bindParam(':fechaInicio', $this->fechaInicio, PDO::PARAM_STR);
		$stmt->bindParam(':fechaFinal', $this->fechaFinal, PDO::PARAM_STR);
		$stmt->bindParam(':usuario', $usu, PDO::PARAM_STR);
		$stmt->bindParam(':contador', $this->contador, PDO::PARAM_INT);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
		$this->query=$con->query();
	}
	/**
	 * Inserta nuevo registro a la tabla "sgc_encuesta" usando las propiedades del Objeto ...
	 */
	public function agregaADb(){
		$con=Conexion::conectar();
		$emp=$this->empresa->getId();
		$usu=$this->getNombreUsuario();
		$query="INSERT INTO sgc_encuesta
				(enc_id,
				 emp_id,
				 enc_fecha_inicio,
				 enc_fecha_cierre,
				 cli_usuario,
				 enc_cont)
				 VALUES
				 (null,
				  :empresa,
				  :fechaInicio,
				  :fechaFinal,
				  :usuario,
				  :contador)";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':empresa', $emp, PDO::PARAM_INT);
		$stmt->bindParam(':fechaInicio', $this->fechaInicio, PDO::PARAM_STR);
		$stmt->bindParam(':fechaFinal', $this->fechaFinal, PDO::PARAM_STR);
		$stmt->bindParam(':usuario', $usu, PDO::PARAM_STR);
		$stmt->bindParam(':contador', $this->contador, PDO::PARAM_INT);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
		$this->uFilaIns=$con->lastInsertId();
		$this->setId($this->uFilaIns);
	}
	/**
	 * Borra registro de la tabla "sgc_encuesta" filtrado por ID ...
	 */
	public function borraPorId($ID){
		if(!isset($ID)){
			return $this;
		}
		$con=Conexion::conectar();
		$query = "DELETE FROM sgc_encuesta WHERE enc_id = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $ID, PDO::PARAM_STR);
		$stmt -> execute();
		$this->uFilaIns=$con->lastInsertId();
	}
}
