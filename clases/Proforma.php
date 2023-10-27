<?php
/**
 * @Author : José Luis Villaronga
 * @copyright : 2018
 */
class Proforma
{
	/**
	 * Propiedades
	 */
	private $id;
	private $fechaInicio;
	private $nombre;
	private $apellido;
	private $razonSocial;
	private $cuit;
	private $consumidorFinal=1;
	private $email;
	private $areaCode;
	private $telefono;
	private $dni;
	private $calle;
	private $numero;
	private $zipCode;
	private $facturaNumero;
	private $fechaFactura;
	private $remitoNro;
	private $facturado=0;
	private $cobrado=0;
	private $terminado=0;
	private $fechaTerminado;
	private $usuario;
	
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
		$query="SELECT * FROM proforma WHERE p_id = ".$g;
		$res=Db::listar($query);
		if(count($res)==1){
			$this->id=$res[0]['p_id'];
			$this->fechaInicio=$res[0]['p_fecha_inicio'];
			$this->nombre=$res[0]['p_nombre'];
			$this->apellido=$res[0]['p_apellido'];
			$this->razonSocial=$res[0]['p_razon_social'];
			$this->cuit=$res[0]['p_cuit'];
			$this->consumidorFinal=$res[0]['p_consumidor_final'];
			$this->email=$res[0]['p_email'];
			$this->areaCode=$res[0]['p_codigo_area'];
			$this->telefono=$res[0]['p_telefono'];
			$this->dni=$res[0]['p_dni'];
			$this->calle=$res[0]['p_calle'];
			$this->numero=$res[0]['p_numero'];
			$this->zipCode=$res[0]['p_zip_code'];
			$this->facturaNumero=$res[0]['p_factura_nro'];
			$this->fechaFactura=$res[0]['p_fecha_factura'];
			$this->remitoNro=$res[0]['p_remito_nro'];
			$this->facturado=$res[0]['p_facturado'];
			$this->cobrado=$res[0]['p_cobrado'];
			$this->terminado=$res[0]['p_terminado'];
			$this->fechaTerminado=$res[0]['p_fecha_terminado'];
			$this->usuario=$res[0]['cli_usuario'];
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
	public function getNombre(){
		return $this->nombre;
	}
	public function getApellido(){
		return $this->apellido;
	}
	public function getRazonSocial(){
		return $this->razonSocial;
	}
	public function getCuit(){
		return $this->cuit;
	}
	public function getConsumidorFinal(){
		return $this->consumidorFinal;
	}
	public function getEMAIL(){
		return $this->email;
	}
	public function getAreaCode(){
		return $this->areaCode;
	}
	public function getTelefono(){
		return $this->telefono;
	}
	public function getDNI(){
		return $this->dni;
	}
	public function getCalle(){
		return $this->calle;
	}
	public function getNumero(){
		return $this->numero;
	}
	public function getZipCode(){
		return $this->zipCode;
	}
	public function getFacturaNumero(){
		return $this->facturaNumero;
	}
	public function getFechaFactura(){
		return $this->fechaFactura;
	}
	public function getRemitoNumero(){
		return $this->remitoNro;
	}
	public function getFacturado(){
		return $this->facturado;
	}
	public function getCobrado(){
		return $this->cobrado;
	}
	public function getFechaTerminado(){
		return $this->fechaTerminado;
	}
	public function getTerminado(){
		return $this->terminado;
	}
	public function getUsuario(){
		return $this->usuario;
	}
	/**
	 * Setters ...
	 */
	public function setId($id){
		$this->id=$id;
	}
	public function setFechaInicio($nFI){
		$this->fechaInicio=$nFI;
		if(empty($nFI)){
			$this->fechaInicio=date("Y-m-d H:i:s");
		}
	}
	public function setNombre($nN){
		$this->nombre=$nN;
	}
	public function setApellido($nA){
		$this->apellido=$nA;
	}
	public function setRazonSocial($nRS){
		$this->razonSocial=$nRS;
	}
	public function setCuit($nC){
		$this->cuit=$nC;
	}
	public function setConsumidorFinal($nCF){
		$this->consumidorFinal=$nCF;
	}
	public function setEMAIL($nE){
		$this->email=$nE;
	}
	public function setAreaCode($nAC){
		$this->areaCode=$nAC;
	}
	public function setTelefono($nT){
		$this->telefono=$nT;
	}
	public function setDNI($nDNI){
		$this->dni=$nDNI;
	}
	public function setCalle($nC){
		$this->calle=$nC;
	}
	public function setNumero($nN){
		$this->numero=$nN;
	}
	public function setZipCode($nZC){
		$this->zipCode=$nZC;
	}
	public function setFacturaNumero($nFN){
		$this->facturaNumero=$nFN;
	}
	public function setFechaFactura($nFF){
		$this->fechaFactura=$nFF;
	}
	public function setRemitoNumero($nRN){
		$this->remitoNro=$nRN;
	}
	public function setFacturado($nF){
		$this->facturado=$nF;
	}
	public function setCobrado($nC){
		$this->cobrado=$nC;
	}
	public function setFechaTerminado($nFT){
		$this->fechaTerminado=$nFT;
	}
	public function setTerminado($nT){
		$this->terminado=$nT;
	}
	public function setUsuario($nU){
		$this->usuario=$nU;
	}
	/**
	 * Actualiza la tabla "proforma" con las propiedades actuales del objeto ...
	 */
	public function actualizaDb(){
		$con=Conexion::conectar();
		$query="UPDATE `proforma`
				SET
				`p_id` = :id,
				`p_fecha_inicio` = :fechaInicio,
				`p_nombre` = :nombre,
				`p_apellido` = :apellido,
				`p_razon_social` = :razonSocial,
				`p_cuit` = :cuit,
				`p_consumidor_final` = :consumidorFinal,
				`p_email` = :email,
				`p_codigo_area` = :areaCode,
				`p_telefono` = :telefono,
				`p_dni` = :dni,
				`p_calle` = :calle,
				`p_numero` = :numero,
				`p_zip_code` = :zipCode,
				`p_factura_nro` = :facturaNro,
				`p_fecha_factura` = :fechaFactura,
				`p_remito_nro` = :remitoNro,
				`p_facturado` = :facturado,
				`p_cobrado` = :cobrado,
				`p_terminado` = :terminado,
				`p_fecha_terminado` = :fechaTerminado,
				`cli_usuario` = :usuario
				WHERE `p_id` = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
		$stmt->bindParam(':fechaInicio', $this->fechaInicio, PDO::PARAM_STR);
		$stmt->bindParam(':nombre', $this->nombre, PDO::PARAM_STR);
		$stmt->bindParam(':apellido', $this->apellido, PDO::PARAM_STR);
		$stmt->bindParam(':razonSocial', $this->razonSocial, PDO::PARAM_STR);
		$stmt->bindParam(':cuit', $this->cuit, PDO::PARAM_STR);
		$stmt->bindParam(':consumidorFinal', $this->consumidorFinal, PDO::PARAM_INT);
		$stmt->bindParam(':email', $this->email, PDO::PARAM_STR);
		$stmt->bindParam(':areaCode', $this->areaCode, PDO::PARAM_STR);
		$stmt->bindParam(':telefono', $this->telefono, PDO::PARAM_STR);
		$stmt->bindParam(':dni', $this->dni, PDO::PARAM_STR);
		$stmt->bindParam(':calle', $this->calle, PDO::PARAM_STR);
		$stmt->bindParam(':numero', $this->numero, PDO::PARAM_STR);
		$stmt->bindParam(':zipCode', $this->zipCode, PDO::PARAM_STR);
		$stmt->bindParam(':facturaNro', $this->facturaNumero, PDO::PARAM_STR);
		$stmt->bindParam(':fechaFactura', $this->fechaFactura, PDO::PARAM_STR);
		$stmt->bindParam(':remitoNro', $this->remitoNro, PDO::PARAM_STR);
		$stmt->bindParam(':facturado', $this->facturado, PDO::PARAM_STR);
		$stmt->bindParam(':cobrado', $this->cobrado, PDO::PARAM_STR);
		$stmt->bindParam(':terminado', $this->terminado, PDO::PARAM_STR);
		$stmt->bindParam(':fechaTerminado', $this->fechaTerminado, PDO::PARAM_STR);
		$stmt->bindParam(':usuario', $this->usuario, PDO::PARAM_STR);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
	}
	/**
	 * Inserta nuevo proforma a la tabla "proforma" usando las propiedades del Objeto ...
	 */
	public function agregaADb(){
		$con=Conexion::conectar();
		$query="INSERT INTO `proforma`
				(`p_nombre`,
				`p_fecha_inicio`,
				`p_apellido`,
				`p_razon_social`,
				`p_cuit`,
				`p_consumidor_final`,
				`p_email`,
				`p_codigo_area`,
				`p_telefono`,
				`p_dni`,
				`p_calle`,
				`p_numero`,
				`p_zip_code`,
				`p_factura_nro`,
				`p_fecha_factura`,
				`p_remito_nro`,
				`p_facturado`,
				`p_cobrado`,
				`p_terminado`,
				`p_fecha_terminado`,
				`cli_usuario`)
				VALUES
				(:nombre,
				:fechaInicio,
				:apellido,
				:razonSocial,
				:cuit,
				:consumidorFinal,
				:email,
				:areaCode,
				:telefono,
				:dni,
				:calle,
				:numero,
				:zipCode,
				:facturaNumero,
				:fechaFactura,
				:remitoNro,
				:facturado,
				:cobrado,
				:terminado,
				:fechaTerminado,
				:usuario)";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':fechaInicio', $this->fechaInicio, PDO::PARAM_STR);
		$stmt->bindParam(':nombre', $this->nombre, PDO::PARAM_STR);
		$stmt->bindParam(':apellido', $this->apellido, PDO::PARAM_STR);
		$stmt->bindParam(':razonSocial', $this->razonSocial, PDO::PARAM_STR);
		$stmt->bindParam(':cuit', $this->cuit, PDO::PARAM_STR);
		$stmt->bindParam(':consumidorFinal', $this->consumidorFinal, PDO::PARAM_INT);
		$stmt->bindParam(':email', $this->email, PDO::PARAM_STR);
		$stmt->bindParam(':areaCode', $this->areaCode, PDO::PARAM_STR);
		$stmt->bindParam(':telefono', $this->telefono, PDO::PARAM_STR);
		$stmt->bindParam(':dni', $this->dni, PDO::PARAM_STR);
		$stmt->bindParam(':calle', $this->calle, PDO::PARAM_STR);
		$stmt->bindParam(':numero', $this->numero, PDO::PARAM_STR);
		$stmt->bindParam(':zipCode', $this->zipCode, PDO::PARAM_STR);
		$stmt->bindParam(':facturaNumero', $this->facturaNumero, PDO::PARAM_STR);
		$stmt->bindParam(':fechaFactura', $this->fechaFactura, PDO::PARAM_STR);
		$stmt->bindParam(':remitoNro', $this->remitoNro, PDO::PARAM_STR);
		$stmt->bindParam(':facturado', $this->facturado, PDO::PARAM_STR);
		$stmt->bindParam(':cobrado', $this->cobrado, PDO::PARAM_STR);
		$stmt->bindParam(':terminado', $this->terminado, PDO::PARAM_STR);
		$stmt->bindParam(':fechaTerminado', $this->fechaTerminado, PDO::PARAM_STR);
		$stmt->bindParam(':usuario', $this->usuario, PDO::PARAM_STR);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
		$this->uFilaIns=$con->lastInsertId();
		$this->setId($this->uFilaIns);
	}
	/**
	 * Borra registro de la tabla "proforma" filtrado por ID ...
	 */
	public function borraPorId($ID){
		if(!isset($ID)){
			return $this;
		}
		$con=Conexion::conectar();
		$query = "DELETE FROM proforma WHERE p_id = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $ID, PDO::PARAM_STR);
		$stmt -> execute();
		$this->uFilaIns=$con->lastInsertId();
	}
}