<?php
/**
 * @author : José Luis Villaronga
 * @copyright : 2015
 */
class Ticket
{
	/**
	 * Propiedades
	 */
	private $id;
	private $nombre;
	private $fechaInicio;
	private $fechaFinal;
	private $fechaAlerta;
	private $estado;
	private $notas;
	private $recursos;
	private $dependencia;
	private $categoria;
	public $usuario;
	private $usuarioAsignado;
	private $duenoOriginal;
	private $infor=null;
	private $centroCosto=null;
	private $rechazoCompra=0;
	public $sugerido;
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
	public function __construct($c=null){
		if(empty($c)){
			$this->usuario=new Usuario();
			return $this;
		}else{
			$query="SELECT * FROM tickets WHERE tck_id = $c";
			foreach(Db::listar($query) as $fila){
				$this->id=$fila['tck_id'];
				$this->nombre=$fila['tck_nombre'];
				$this->fechaInicio=$fila['tck_fecha_inicio'];
				$this->fechaFinal=$fila['tck_fecha_final'];
				$this->fechaAlerta=$fila['tck_fecha_alerta'];
				$this->estado=$fila['tck_cerrado'];
				$this->notas=$fila['tck_notas'];
				$this->recursos=$fila['tck_recursos'];
				$this->infor=$fila['tck_informe'];
				$this->dependencia=$fila['tck_dependencia'];
				$this->categoria=$fila['tck_categoria'];
				$this->usuario=new Usuario($fila['cli_usuario']);
				$this->usuarioAsignado=$fila['tck_asignado'];
				$this->duenoOriginal=$fila['tck_dueno'];
				$this->centroCosto=$fila['cc_id'];
				$this->rechazoCompra=$fila['tck_compra_rechazo'];
				$this->sugerido=new TicketSugerencia($this->id);
			}
		}
	}
	/**
	 * Getters ...
	 */
	public function getId(){
		return $this->id;
	}
	public function getNombre(){
		return $this->nombre;
	}
	public function getFechaInicio(){
		return $this->fechaInicio;
	}
	public function getFechaFinal(){
		return $this->fechaFinal;
	}
	public function getFechaAlerta(){
		return $this->fechaAlerta;
	}
	public function getEstado(){
		return $this->estado;
	}
	public function getNotas(){
		return $this->notas;
	}
	public function getRecursos(){
		return $this->recursos;
	}
	public function getDependencia(){
		return $this->dependencia;
	}
	public function getCategoria(){
		return $this->categoria;
	}
	public function getInfor(){
		return $this->infor;
	}
	public function getUsuario(){
		return $this->usuario->getUsuario();
	}
	public function getUsuarioAsignado(){
		return $this->usuarioAsignado;
	}
	public function getDuenoOriginal(){
		return $this->duenoOriginal;
	}
	public function getCentroCosto(){
		return $this->centroCosto;
	}
	public function getRechazoCompra(){
		return $this->rechazoCompra;
	}
	/**
	 * Setters ...
	 */
	public function setId($nId){
		$this->id=$nId;
	}
	public function setNombre($nN){
		if(empty($nN)){
			$this->errores['nombre']="Se espera un valor ...";
			$this->errores['gen'] = "harError";
		}else{
			$this->nombre=$nN;
		}
	}
	public function setFechaInicio($nFI){
		$this->fechaInicio=$nFI;
	}
	public function setFechaFinal($nFF){
		$this->fechaFinal=$nFF;
	}
	public function setFechaAlerta($nFA){
		$this->fechaAlerta=$nFA;
	}
	public function setEstado($nE){
		$this->estado=$nE;
	}
	public function setNotas($nNo){
		if(empty($nNo)){
			$this->errores['notas']="Se espera que 'Notas' tenga contenido ...";
			$this->errores['gen'] = "harError";
		}else{
			$this->notas=$nNo;
		}
	}
	public function setRecursos($nR){
		$this->recursos=$nR;
	}
	public function setInfor($nInfor){
		$this->infor=$nInfor;
	}
	public function setDependencia($nD){
		$check=new Ticket($nD);
		if($check->getDependencia()==$this->id){
			$this->errores['dependencia']="Hay dependencia ciclica ...";
			$this->errores['gen'] = "harError";
		}else{
			$this->dependencia=$nD;
		}
	}
	public function setCategoria($nC){
		$this->categoria=$nC;
	}
	public function setUsuario($nU){
		$this->usuario=new Usuario($nU);
	}
	public function setUsuarioAsignado($nUA){
		$this->usuarioAsignado=$nUA;
	}
	public function setDuenoOriginal($nDO){
		$this->duenoOriginal=$nDO;
	}
	public function setCentroCosto($nCC){
		$this->centroCosto=$nCC;
	}
	public function setRechazoCompra($nRC){
		$this->rechazoCompra=$nRC;
	}
	/**
	 * Actualiza la tabla "tickets" con las propiedades actuales del objeto ...
	 */
	public function actualizaDb(){
		$con=Conexion::conectar();
		$usu=$this->usuario->getUsuario();
		$usu2=$this->getUsuarioAsignado();
		$query = "UPDATE `tickets`
					SET
					`tck_nombre` = :nombre,
					`tck_fecha_inicio` = :fechaInicio,";
		if(!is_null($this->fechaFinal)){
			$query.=   "`tck_fecha_final` = :fechaFinal,";
		}
		if(!is_null($this->fechaAlerta)){
			$query.=   "`tck_fecha_alerta` = :fechaAlerta,";
		}else{
			$query.=   "`tck_fecha_alerta` = null,";
		}
		$query.=   "`tck_cerrado` = :estado,
					`tck_notas` = :notas,
					`tck_recursos` = :recursos,
					`tck_informe` = :informe,";
		if(!is_null($this->dependencia)){
			$query.=   "`tck_dependencia` = :dependencia,";
		}else{
			$query.=   "`tck_dependencia` = null,";
		}
		$query.=   "`cli_usuario` = :usuario,
					`tck_asignado` = :usuarioAsignado,
					`tck_categoria` = :categoria,
					`cc_id` = :centroCosto,
					`tck_compra_rechazo` = :rechazoCompra
					WHERE `tck_id` = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
		$stmt->bindParam(':nombre', $this->nombre, PDO::PARAM_STR);
		$stmt->bindParam(':fechaInicio', $this->fechaInicio, PDO::PARAM_STR);
		if(!is_null($this->fechaFinal)){
			$stmt->bindParam(':fechaFinal', $this->fechaFinal, PDO::PARAM_STR);
		}
		if(!is_null($this->fechaAlerta)){
			$stmt->bindParam(':fechaAlerta', $this->fechaAlerta, PDO::PARAM_STR);
		}
		$stmt->bindParam(':estado', $this->estado, PDO::PARAM_INT);
		$stmt->bindParam(':notas', $this->notas, PDO::PARAM_STR);
		$stmt->bindParam(':recursos', $this->recursos, PDO::PARAM_STR);
		$stmt->bindParam(':informe', $this->infor, PDO::PARAM_STR);
		if(!empty($this->dependencia)){
			$stmt->bindParam(':dependencia', $this->dependencia, PDO::PARAM_INT);
		}
		$stmt->bindParam(':usuario', $usu, PDO::PARAM_STR);
		$stmt->bindParam(':usuarioAsignado', $usu2, PDO::PARAM_STR);
		$stmt->bindParam(':categoria', $this->categoria, PDO::PARAM_STR);
		$stmt->bindParam(':centroCosto', $this->centroCosto, PDO::PARAM_INT);
		$stmt->bindParam(':rechazoCompra', $this->rechazoCompra, PDO::PARAM_INT);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
		//$_SESSION['errorSQL']=$stmt->errorInfo();
	}
	/**
	 * Inserta nuevo registro a la tabla "tickets" usando las propiedades del Objeto ...
	 */
	public function agregaADb(){
		$con=Conexion::conectar();
		$fini=date("Y-m-d H:i:s");
		$usu=$this->usuario->getUsuario();
		$usu2=$this->getUsuarioAsignado();
		$query="INSERT INTO `tickets`
				(`tck_id`,
				`tck_nombre`,
				`tck_fecha_inicio`,
				`tck_fecha_final`,
				`tck_fecha_alerta`,
				`tck_cerrado`,
				`tck_notas`,
				`tck_recursos`,
				`tck_informe`,
				`tck_dependencia`,
				`cli_usuario`,
				`tck_asignado`,
				`tck_dueno`,
				`tck_categoria`,
				`cc_id`,
				`tck_compra_rechazo`)
				VALUES
				(null,
				:nobre,
				:fechaInicio,
				null,
				null,
				0,
				:notas,
				:recursos,
				:informe,
				null,
				:usuario,
				:usuarioAsignado,
				:duenoOriginal,
				:categoria,
				:centroCosto,
				:rechazoCompra)";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':nobre', $this->nombre, PDO::PARAM_STR);
		$stmt->bindParam(':fechaInicio', $fini, PDO::PARAM_STR);
		$stmt->bindParam(':notas', $this->notas, PDO::PARAM_STR);
		$stmt->bindParam(':recursos', $this->recursos, PDO::PARAM_STR);
		$stmt->bindParam(':informe', $this->infor, PDO::PARAM_STR);
		$stmt->bindParam(':usuario', $usu, PDO::PARAM_STR);
		$stmt->bindParam(':usuarioAsignado', $usu2, PDO::PARAM_STR);
		$stmt->bindParam(':duenoOriginal', $this->duenoOriginal, PDO::PARAM_STR);
		$stmt->bindParam(':categoria', $this->categoria, PDO::PARAM_STR);
		$stmt->bindParam(':centroCosto', $this->centroCosto, PDO::PARAM_INT);
		$stmt->bindParam(':rechazoCompra', $this->rechazoCompra, PDO::PARAM_INT);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
		$this->uFilaIns=$con->lastInsertId();
		$this->setId($this->uFilaIns);
	}
	/**
	 * Borra registro de la tabla "tickets" filtrado por ID ...
	 */
	public function borraPorId($ID){
		if(!isset($ID)){
			return $this;
		}
		$con=Conexion::conectar();
		$query = "DELETE FROM tickets WHERE tck_id = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $ID, PDO::PARAM_STR);
		$stmt -> execute();
		$this->uFilaIns=$con->lastInsertId();
	}
}
