<?php
/**
 * @Author : José Luis Villaronga
 * @copyright : 2019
 */
class Contenido
{
	/**
	 * Propiedades
	 */
	private $id;
	public $curso;
	private $objetivosIniciales;
	private $objetivosFinales;
	private $contenido;
	private $laboratorio;
	private $claseNro;
	public $usuario;
	private $fecha;
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
	public function __construct($curso=null,$clase=null,$usuario=null){
		if(is_null($usuario)){
			$usuario=$_SESSION['usuario'];
		}
		if(is_null($clase)){$clase=1;}
		if(is_object($curso) AND get_class($curso)=="Curso"){
			$this->curso=new Curso($curso->getId());
			$this->claseNro=$clase;
			$query="SELECT * FROM contenidos WHERE cur_id = ".$this->curso->getId()." AND con_clase_nro = ".$clase;
			$res=Db::listar($query);
			if(count($res)>0){
				$this->id=$res[0]['con_id'];
				$this->objetivosIniciales=$res[0]['con_objetivos_inicio'];
				$this->objetivosFinales=$res[0]['con_objetivos_final'];
				$this->contenido=$res[0]['con_contenido'];
				$this->laboratorio=$res[0]['con_laboratorio'];
				$this->usuario=new Usuario($res[0]['cli_usuario']);
				$this->fecha=$res[0]['con_fecha'];
				return $this;
			}else{
				$this->usuario=new Usuario($usuario);
				return $this;
			}
		}else{
			$this->curso=new Curso(null);
			$this->usuario=new Usuario($usuario);
			return $this;
		}
	}
	/**
	 * Getters ..
	 */
	public function getId(){
	 	return $this->id;
	}
	public function getObjetivosIniciales(){
		return $this->objetivosIniciales;
	}
	public function getObjetivosFinales(){
		return $this->objetivosFinales;
	}
	public function getContenido(){
		return $this->contenido;
	}
	public function getLaboratorio(){
		return $this->laboratorio;
	}
	public function getClaseNro(){
		return $this->claseNro;
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
	public function setObjetivosIniciales($nOI){
		if(empty($nOI)){
			$this->errores['gen']="harError";
			$this->errores['objetivosIniciales']="Hay que agregar valor ...";
		}else{
			$this->objetivosIniciales=$nOI;
		}
	}
	public function setObjetivosFinales($nOF){
		if(empty($nOF)){
			$this->errores['gen']="harError";
			$this->errores['objetivosFinales']="Hay que agregar valor ...";
		}else{
			$this->objetivosFinales=$nOF;
		}
	}
	public function setContenidos($nC){
		$this->contenido=$nC;
	}
	public function setLaboratorio($nL){
		$this->laboratorio=$nL;
	}
	public function setClaseNro($nCN){
		$this->claseNro=$nCN;
	}
	public function setFecha($nF){
		$this->fecha=$nF;
	}
	/**
	 * Actualiza la tabla "contenidos" con las propiedades actuales del objeto ...
	 */
	public function actualizaDb(){
		$curso=$this->curso->getId();
		$usuario=$_SESSION['usuario'];
		$con=Conexion::conectar();
		$query="UPDATE `contenidos`
				SET
				`con_id` = :id,
				`cur_id` = :curso,
				`con_objetivos_inicio` = :objetivosIniciales,
				`con_objetivos_final` = :objetivosFinales,
				`con_contenido` = :contenido,
				`con_laboratorio` = :laboratorio,
				`con_clase_nro` = :claseNro,
				`cli_usuario` = :usuario,
				`con_fecha` = :fecha
				WHERE `con_id` = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
		$stmt->bindParam(':curso', $curso, PDO::PARAM_INT);
		$stmt->bindParam(':objetivosIniciales', $this->objetivosIniciales, PDO::PARAM_STR);
		$stmt->bindParam(':objetivosFinales', $this->objetivosFinales, PDO::PARAM_STR);
		$stmt->bindParam(':contenido', $this->contenido, PDO::PARAM_STR);
		$stmt->bindParam(':laboratorio', $this->laboratorio, PDO::PARAM_STR);
		$stmt->bindParam(':claseNro', $this->claseNro, PDO::PARAM_INT);
		$stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
		$stmt->bindParam(':fecha', $this->fecha, PDO::PARAM_STR);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
	}
	/**
	 * Inserta nuevo registro a la tabla "contenidos" usando las propiedades del Objeto ...
	 */
	public function agregaADb(){
		$curso=$this->curso->getId();
		$usuario=$_SESSION['usuario'];
		$con=Conexion::conectar();
		$query="INSERT INTO `contenidos`
				(`cur_id`,
				`con_objetivos_inicio`,
				`con_objetivos_final`,
				`con_contenido`,
				`con_laboratorio`,
				`con_clase_nro`,
				`cli_usuario`,
				`con_fecha`)
				VALUES
				(:curso,
				:objetivosIniciales,
				:objetivosFinales,
				:contenido,
				:laboratorio,
				:claseNro,
				:usuario,
				:fecha)";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':curso', $curso, PDO::PARAM_INT);
		$stmt->bindParam(':objetivosIniciales', $this->objetivosIniciales, PDO::PARAM_STR);
		$stmt->bindParam(':objetivosFinales', $this->objetivosFinales, PDO::PARAM_STR);
		$stmt->bindParam(':contenido', $this->contenido, PDO::PARAM_STR);
		$stmt->bindParam(':laboratorio', $this->laboratorio, PDO::PARAM_STR);
		$stmt->bindParam(':claseNro', $this->claseNro, PDO::PARAM_INT);
		$stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
		$stmt->bindParam(':fecha', $this->fecha, PDO::PARAM_STR);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
		$this->uFilaIns=$con->lastInsertId();
		$this->setId($this->uFilaIns);
	}
	/**
	 * Borra registro de la tabla "contenidos" filtrado por ID ...
	 */
	public function borraPorId($ID){
		if(!isset($ID)){
			return $this;
		}
		$con=Conexion::conectar();
		$query = "DELETE FROM contenidos WHERE con_id = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $ID, PDO::PARAM_STR);
		$stmt -> execute();
		$this->uFilaIns=$con->lastInsertId();
	}
}