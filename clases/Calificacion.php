<?php
/**
 * @Author : José Luis Villaronga
 * @copyright : 2019
 */
class Calificacion
{
	/**
	 * Propiedades
	 */
	private $id;
	public $usuario;
	public $value;
	public $departamento;
	public $curso;
	private $fecha;
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
	public function __construct($usuario=null,$curso=null){
		if(empty($usuario) AND empty($curso)){
			$this->usuario=new Usuario(null);
			$this->value=new CalificacionValue(null);
			$this->departamento=new Departamento(null);
			$this->curso=new Curso(null);
			return $this;
		}elseif(empty($curso)){
			$this->usuario=new Usuario($usuario);
			$this->curso=new Curso(null);
			$query="SELECT * FROM calificacion WHERE cli_usuario = '".$usuario."' ORDER BY cal_id DESC LIMIT 1";
			$res=Db::listar($query);
			foreach($res as $fila){
				$this->usuario=new Usuario($fila['cli_usuario']);
				$this->value=new CalificacionValue(null);
				$this->departamento=new Departamento(null);
				$this->curso=new Curso(null);
				$this->id=null;
				$this->fecha=null;
			}
			$this->setLoteCompleto();
			return $this;
		}else{
			$this->usuario=new Usuario($usuario);
			$this->curso=new Curso($curso);
			$query="SELECT * FROM calificacion WHERE cli_usuario = '".$usuario."' AND cur_id = ".$curso." ORDER BY cal_id DESC LIMIT 1";
			$res=Db::listar($query);
			if(count($res)==0){
				$this->value=new CalificacionValue(null);
				$this->departamento=new Departamento(null);
				return $this;
			}else{
				foreach($res as $fila){
					$this->value=new CalificacionValue($fila['calv_id']);
					$this->departamento=new Departamento($fila['dep_id']);
					$this->id=$fila['cal_id'];
					$this->fecha=$fila['cal_fecha'];
				}
				$this->setLote();
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
	public function setLote(){
		$usuario=$this->usuario->getUsuario();
		$curso=$this->curso->getId();
		$query="SELECT * FROM calificacion WHERE cli_usuario = '".$usuario."' AND cur_id = ".$curso." ORDER BY cal_id DESC";
		$res=Db::listar($query);
		foreach($res as $fila){
			$this->lote[]=array(
				"cal_id"=>$fila['cal_id'],
				"cli_usuario"=>$fila['cli_usuario'],
				"calv_id"=>$fila['calv_id'],
				"dep_id"=>$fila['dep_id'],
				"cur_id"=>$fila['cur_id'],
				"cal_fecha"=>$fila['cal_fecha']
			);
		}
	}
	public function setLoteCompleto(){
		$usuario=$this->usuario->getUsuario();
		$query="SELECT * FROM calificacion WHERE cli_usuario = '".$usuario."' ORDER BY cal_id DESC";
		$res=Db::listar($query);
		foreach($res as $fila){
			$this->lote[]=array(
				"cal_id"=>$fila['cal_id'],
				"cli_usuario"=>$fila['cli_usuario'],
				"calv_id"=>$fila['calv_id'],
				"dep_id"=>$fila['dep_id'],
				"cur_id"=>$fila['cur_id'],
				"cal_fecha"=>$fila['cal_fecha']
			);
		}
	}
	/**
	 * Actualiza la tabla "calificacion" con las propiedades actuales del objeto ...
	 */
	public function actualizaDb(){
		$usuario=$this->usuario->getUsuario();
		$valor=$this->value->getId();
		$departamento=$this->departamento->getId();
		$curso=$this->curso->getId();
		$con=Conexion::conectar();
		$query="UPDATE `calificacion`
				SET
				`cal_id` = :id,
				`cli_usuario` = :usuario,
				`calv_id` = :valor,
				`dep_id` = :departamento,
				`cur_id` = :curso,
				`cal_fecha` = :fecha
				WHERE `cal_id` = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
		$stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
		$stmt->bindParam(':valor', $valor, PDO::PARAM_INT);
		$stmt->bindParam(':departamento', $departamento, PDO::PARAM_INT);
		$stmt->bindParam(':curso', $curso, PDO::PARAM_INT);
		$stmt->bindParam(':fecha', $this->fecha, PDO::PARAM_STR);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
	}
	/**
	 * Inserta nuevo registro a la tabla "calificacion" usando las propiedades del Objeto ...
	 */
	public function agregaADb(){
		$usuario=$this->usuario->getUsuario();
		$valor=$this->value->getId();
		$departamento=$this->departamento->getId();
		$curso=$this->curso->getId();
		$con=Conexion::conectar();
		$query="INSERT INTO `calificacion`
				(`cli_usuario`,
				`calv_id`,
				`dep_id`,
				`cur_id`,
				`cal_fecha`)
				VALUES
				(:usuario,
				:valor,
				:departamento,
				:curso,
				:fecha)";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
		$stmt->bindParam(':valor', $valor, PDO::PARAM_INT);
		$stmt->bindParam(':departamento', $departamento, PDO::PARAM_INT);
		$stmt->bindParam(':curso', $curso, PDO::PARAM_INT);
		$stmt->bindParam(':fecha', $this->fecha, PDO::PARAM_STR);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
		$this->uFilaIns=$con->lastInsertId();
		$this->setId($this->uFilaIns);
	}
	/**
	 * Borra registro de la tabla "calificacion" filtrado por ID ...
	 */
	public function borraPorId($ID){
		if(!isset($ID)){
			return $this;
		}
		$con=Conexion::conectar();
		$query = "DELETE FROM calificacion WHERE cal_id = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $ID, PDO::PARAM_STR);
		$stmt -> execute();
		$this->uFilaIns=$con->lastInsertId();
	}
}
