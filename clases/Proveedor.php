<?php
/**
 * @Author : José Luis Villaronga
 * @copyright : 2015
 */
class Proveedor
{
	/**
	 * Propiedades
	 */
	public $id;
	private $nombre;
	private $rubro;
	private $tipo;
	private $direccion;
	private $telefono;
	private $fax;
	private $email;
	private $contacto;
	private $responsable;
	private $documento;
	private $telefonoContacto;
	private $emailContacto;
	private $observaciones;
	private $habilitado=1;
	private $provisorio=1;
	private $fecha;
	public $usuario;
	private $infor=null;
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
		if(empty($c) OR is_null($c)){
			$this->usuario=new Usuario($_SESSION['usuario']);
			return $this;
		}else{
			$query="SELECT * FROM repu_proveedores WHERE pr_id = $c";
			foreach(Db::listar($query) as $fila){
				$this->id=$fila['pr_id'];
				$this->nombre=$fila['pr_nombre'];
				$this->rubro=$fila['pr_rubro'];
				$this->tipo=$fila['pr_tipo'];
				$this->direccion=$fila['pr_direccion'];
				$this->telefono=$fila['pr_tel'];
				$this->fax=$fila['pr_fax'];
				$this->email=$fila['pr_email'];
				$this->contacto=$fila['pr_contacto'];
				$this->responsable=$fila['pr_responsable'];
				$this->documento=$fila['pr_infor'];
				$this->telefonoContacto=$fila['pr_tel_contacto'];
				$this->emailContacto=$fila['pr_email_contacto'];
				$this->observaciones=$fila['pr_observaciones'];
				$this->fecha=$fila['pr_fecha'];
				$this->usuario=new Usuario($fila['cli_usuario']);
				$this->habilitado=$fila['pr_habilitado'];
				$this->provisorio=$fila['pr_provisorio'];
				$this->infor=$fila['pr_informe'];
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
	public function getRubro(){
		return $this->rubro;
	}
	public function getTipo(){
		return $this->tipo;
	}
	public function getDireccion(){
		return $this->direccion;
	}
	public function getTelefono(){
		return $this->telefono;
	}
	public function getFax(){
		return $this->fax;
	}
	public function getEMail(){
		return $this->email;
	}
	public function getContacto(){
		return $this->contacto;
	}
	public function getResponsable(){
		return $this->responsable;
	}
	public function getDocumento(){
		return $this->documento;
	}
	public function getTelefonoContacto(){
		return $this->telefonoContacto;
	}
	public function getEmailContacto(){
		return $this->emailContacto;
	}
	public function getObservaciones(){
		return $this->observaciones;
	}
	public function getFecha(){
		return $this->fecha;
	}
	public function getHabilitado(){
		return $this->habilitado;
	}
	public function getProvisorio(){
		return $this->provisorio;
	}
	public function getInfor(){
		return $this->infor;
	}
	/**
	 * Setters ...
	 */
	public function setId($nId){
		$this->id=$nId;
	}
	public function setNombre($nN){
		if(empty($nN)){
			$this->errores['nombre']="Hay que ingresar nombre ...";
			$this->errores['gen'] = "harError";
		}else{
			$this->nombre=$nN;
		}
	}
	public function setRubro($nR){
		if(empty($nR)){
			$this->rubro=NULL;
			$this->errores['rubro']="Hay que ingresar rubro ...";
			$this->errores['gen'] = "harError";
		}else{
			$this->rubro=$nR;
		}
	}
	public function setTipo($nT){
		if($nT==1){
			$this->tipo=$nT;
		}elseif($nT==2){
			$this->errores['tipo']="Hay que ingresar tipo ...";
			$this->errores['gen'] = "harError";
		}else{
			$this->tipo=0;
		}
	}
	public function setDireccion($nD){
		if(empty($nD)){
			$this->direccion=NULL;
		}else{
			$this->direccion=$nD;
		}
	}
	public function setTelefono($nT){
		if(empty($nT)){
			$this->telefono=NULL;
		}else{
			$this->telefono=$nT;
		}
	}
	public function setFax($nF){
		if(empty($nF)){
			$this->fax=NULL;
		}else{
			$this->fax=$nF;
		}
	}
	public function setEMail($nEM){
		if(empty($nEM)){
			$this->email=NULL;
		}else{
			$this->email=$nEM;
		}
	}
	public function setContacto($nC){
		if(empty($nC)){
			$this->contacto=NULL;
		}else{
			$this->contacto=$nC;
		}
	}
	public function setResponsable($nR){
		if(empty($nR)){
			$this->errores['responsable']="Hay que ingresar responsable ...";
			$this->errores['gen'] = "harError";
		}else{
			$this->responsable=$nR;
		}
	}
	public function setDocumento($nD){
		$this->documento=$nD;
	}
	public function setTelefonoContacto($nTC){
		$this->telefonoContacto=$nTC;
	}
	public function setEmailContacto($nEC){
		$this->emailContacto=$nEC;
	}
	public function setObservaciones($nO){
		if(empty($nO)){
			$this->observaciones=NULL;
		}else{
			$this->observaciones=$nO;
		}
	}
	public function setFecha($nF){
		if(is_null($nF)){
			$this->fecha=date("Y-m-d H:i:s");
		}else{
			$this->fecha=$nF;
		}
	}
	public function setHabilitado(){
		$this->habilitado=1;
	}
	public function setDesHabilitado(){
		$this->habilitado=0;
	}
	public function setProvisorio(){
		$this->provisorio=1;
	}
	public function setPermanente(){
		$this->provisorio=0;
	}
	public function setInfor($nInfor){
		$this->infor=$nInfor;
	}
	/**
	 * Actualiza la tabla "repu_proveedores" con las propiedades actuales del objeto ...
	 */
	public function actualizaDb(){
		$con=Conexion::conectar();
		$query="UPDATE repu_proveedores
				SET
				pr_id = :id,
				pr_nombre = :nombre,
				pr_rubro = :rubro,
				pr_tipo = :tipo,
				pr_direccion = :direccion,
				pr_tel = :telefono,
				pr_fax = :fax,
				pr_email = :email,
				pr_contacto = :contacto,
				pr_responsable = :responsable,
				pr_infor = :documento,
				pr_tel_contacto = :telefonoContacto,
				pr_email_contacto = :emailContacto,
				pr_observaciones = :observaciones,
				pr_fecha = :fecha,
				cli_usuario = :usuario,
				pr_habilitado = :habilitado,
				pr_provisorio = :provisorio,
				pr_informe = :infor
				WHERE pr_id = :id";
		$stmt = $con -> prepare($query);
		$usu = $this->usuario->getUsuario();
		$stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
		$stmt->bindParam(':nombre', $this->nombre, PDO::PARAM_STR);
		$stmt->bindParam(':rubro', $this->rubro, PDO::PARAM_STR);
		$stmt->bindParam(':direccion', $this->direccion, PDO::PARAM_STR);
		$stmt->bindParam(':telefono', $this->telefono, PDO::PARAM_STR);
		$stmt->bindParam(':fax', $this->fax, PDO::PARAM_STR);
		$stmt->bindParam(':email', $this->email, PDO::PARAM_STR);
		$stmt->bindParam(':contacto', $this->contacto, PDO::PARAM_STR);
		$stmt->bindParam(':observaciones', $this->observaciones, PDO::PARAM_STR);
		$stmt->bindParam(':fecha', $this->fecha, PDO::PARAM_STR);
		$stmt->bindParam(':usuario', $usu, PDO::PARAM_STR);
		$stmt->bindParam(':provisorio', $this->provisorio, PDO::PARAM_INT);
		$stmt->bindParam(':habilitado', $this->habilitado, PDO::PARAM_INT);
		$stmt->bindParam(':tipo', $this->tipo, PDO::PARAM_INT);
		$stmt->bindParam(':responsable', $this->responsable, PDO::PARAM_STR);
		$stmt->bindParam(':documento', $this->documento, PDO::PARAM_STR);
		$stmt->bindParam(':telefonoContacto', $this->telefonoContacto, PDO::PARAM_STR);
		$stmt->bindParam(':emailContacto', $this->emailContacto, PDO::PARAM_STR);
		$stmt->bindParam(':infor', $this->infor, PDO::PARAM_STR);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
	}
	/**
	 * Inserta nuevo registro a la tabla "repu_proveedores" usando las propiedades del Objeto ...
	 */
	public function agregaADb(){
		$con=Conexion::conectar();
		$query="INSERT INTO repu_proveedores
				(pr_id,
				pr_nombre,
				pr_rubro,
				pr_tipo,
				pr_direccion,
				pr_tel,
				pr_fax,
				pr_email,
				pr_contacto,
				pr_responsable,
				pr_infor,
				pr_tel_contacto,
				pr_email_contacto,
				pr_observaciones,
				pr_fecha,
				pr_habilitado,
				pr_provisorio,
				cli_usuario,
				pr_informe)
				VALUES
				(null,
				:nombre,
				:rubro,
				:tipo,
				:direccion,
				:telefono,
				:fax,
				:email,
				:contacto,
				:responsable,
				:documento,
				:telefonoContacto,
				:emailContacto,
				:observaciones,
				:fecha,
				:habilitado,
				:provisorio,
				:usuario,
				:infor)";
		$stmt = $con -> prepare($query);
		$usu = $this->usuario->getUsuario();
		$stmt->bindParam(':nombre', $this->nombre, PDO::PARAM_STR);
		$stmt->bindParam(':rubro', $this->rubro, PDO::PARAM_STR);
		$stmt->bindParam(':direccion', $this->direccion, PDO::PARAM_STR);
		$stmt->bindParam(':telefono', $this->telefono, PDO::PARAM_STR);
		$stmt->bindParam(':fax', $this->fax, PDO::PARAM_STR);
		$stmt->bindParam(':email', $this->email, PDO::PARAM_STR);
		$stmt->bindParam(':contacto', $this->contacto, PDO::PARAM_STR);
		$stmt->bindParam(':observaciones', $this->observaciones, PDO::PARAM_STR);
		$stmt->bindParam(':fecha', $this->fecha, PDO::PARAM_STR);
		$stmt->bindParam(':provisorio', $this->provisorio, PDO::PARAM_INT);
		$stmt->bindParam(':habilitado', $this->habilitado, PDO::PARAM_INT);
		$stmt->bindParam(':usuario', $usu, PDO::PARAM_STR);
		$stmt->bindParam(':tipo', $this->tipo, PDO::PARAM_INT);
		$stmt->bindParam(':responsable', $this->responsable, PDO::PARAM_STR);
		$stmt->bindParam(':documento', $this->documento, PDO::PARAM_STR);
		$stmt->bindParam(':telefonoContacto', $this->telefonoContacto, PDO::PARAM_STR);
		$stmt->bindParam(':emailContacto', $this->emailContacto, PDO::PARAM_STR);
		$stmt->bindParam(':infor', $this->infor, PDO::PARAM_STR);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
		$this->uFilaIns=$con->lastInsertId();
		$this->setId($this->uFilaIns);
	}
	/**
	 * Borra registro de la tabla "repu_proveedores" filtrado por ID ...
	 */
	public function borraPorId($ID){
		if(!isset($ID)){
			return $this;
		}
		$con=Conexion::conectar();
		$query = "DELETE FROM repu_proveedores WHERE pr_id = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $ID, PDO::PARAM_STR);
		$stmt -> execute();
		$this->uFilaIns=$con->lastInsertId();
	}
}