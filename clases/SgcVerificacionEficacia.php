<?php
/**
 * @Author : José Luis Villaronga
 * @copyright : 2016
 */
class SgcVerificacionEficacia
{
	/**
	 * Propiedades
	 */
	private $id;
	public $responsable;
	private $objetivo;
	public $resultado;
	private $plazo;
	private $fecha;
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
			$query="SELECT * FROM sgc_verificacion_eficacia WHERE ve_id = $g";
			$res=Db::listar($query);
			if(count($res)=="0"){
				return $this;
			}else{
				foreach($res as $fila){
					$this->id=$g;
					$this->responsable=new Usuario($fila['ve_responsable']);
					$this->objetivo=$fila['ve_objetivo'];
					$this->resultado=new SgcACResultados($fila['acr_id']);
					$this->plazo=$fila['ve_plazo'];
					$this->fecha=$fila['ve_fecha'];
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
	public function getObjetivo(){
		return $this->objetivo;
	}
	public function getPlazo(){
		return $this->plazo;
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
	public function setObjetivo($nO){
		if(empty($nO)){
			$this->errores['objetivo']="Hay que ingresar Objetivo ...";
			$this->errores['gen'] = "harError";
		}elseif(strlen($nO) > "250"){
			$this->errores['objetivo']="Tiene mas de 250 caracteres ...";
			$this->errores['gen'] = "harError";
		}else{
			$this->objetivo=$nO;
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
	public function setResultado($nR){
		$this->resultado=new SgcACResultados($nR);
	}
	public function setPlazo($nP){
		$this->plazo=$nP;
	}
	public function setFecha($nF){
		$this->fecha=$nF;
	}
	/**
	 * Actualiza la tabla "sgc_verificacion_eficacia" con las propiedades actuales del objeto ...
	 */
	public function actualizaDb(){
		$con=Conexion::conectar();
		$respon=$this->responsable->getUsuario();
		$usu=$this->usuario->getUsuario();
		$resultado=$this->resultado->getId();
		$query = "UPDATE `sgc_verificacion_eficacia`
					SET
					`ve_id` = :id,
					`ve_responsable` = :responsable,
					`ve_objetivo` = :objetivo,
					`acr_id` = :resultado,
					`ve_plazo` = :plazo,
					`ve_fecha` = :fecha,
					`cli_usuario` = :usuario
					WHERE `ve_id` = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
		$stmt->bindParam(':responsable', $respon, PDO::PARAM_STR);
		$stmt->bindParam(':objetivo', $this->objetivo, PDO::PARAM_STR);
		$stmt->bindParam(':resultado', $resultado, PDO::PARAM_INT);
		$stmt->bindParam(':plazo', $this->plazo, PDO::PARAM_STR);
		$stmt->bindParam(':fecha', $this->fecha, PDO::PARAM_STR);
		$stmt->bindParam(':usuario', $usu, PDO::PARAM_STR);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
	}
	/**
	 * Inserta nuevo registro a la tabla "sgc_verificacion_eficacia" usando las propiedades del Objeto ...
	 */
	public function agregaADb(){
		$con=Conexion::conectar();
		$respon=$this->responsable->getUsuario();
		$usu=$this->usuario->getUsuario();
		$resultado=$this->resultado->getId();
		$query="INSERT INTO `sgc_verificacion_eficacia`
				(`ve_id`,
				`ve_responsable`,
				`ve_objetivo`,
				`acr_id`,
				`ve_plazo`,
				`ve_fecha`,
				`cli_usuario`)
				VALUES
				(null,
				:responsable,
				:objetivo,
				:resultado,
				:plazo,
				:fecha,
				:usuario)";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':responsable', $respon, PDO::PARAM_STR);
		$stmt->bindParam(':objetivo', $this->objetivo, PDO::PARAM_STR);
		$stmt->bindParam(':resultado', $resultado, PDO::PARAM_INT);
		$stmt->bindParam(':plazo', $this->plazo, PDO::PARAM_STR);
		$stmt->bindParam(':fecha', $this->fecha, PDO::PARAM_STR);
		$stmt->bindParam(':usuario', $usu, PDO::PARAM_STR);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
		$this->uFilaIns=$con->lastInsertId();
		$this->setId($this->uFilaIns);
	}
	/**
	 * Borra registro de la tabla "sgc_verificacion_eficacia" filtrado por ID ...
	 */
	public function borraPorId($ID){
		if(!isset($ID)){
			return $this;
		}
		$con=Conexion::conectar();
		$query = "DELETE FROM sgc_verificacion_eficacia WHERE ve_id = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $ID, PDO::PARAM_STR);
		$stmt -> execute();
		$this->uFilaIns=$con->lastInsertId();
	}
}
