<?php
/**
 * @Author : José Luis Villaronga
 * @copyright : 2018
 */
class DOCSIS
{
	/**
	 * Propiedades
	 */
	private $id;
	private $Uptime;
	private $mac;
	private $serie;
	private $tx;
	private $rx;
	private $mer;
	private $frecUs;
	private $frecDs;
	private $sysName;
	private $sysDescr;
	private $firmware;
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
	public function __construct($Uptime=null,$mac=null,$serie=null,$tx=null,$rx=null,$mer=null,$frecUs=null,$frecDs=null,$sysName=null,$sysDescr=null,$firmware=null){
		$this->Uptime=$Uptime;
		$this->mac=$mac;
		$this->serie=$serie;
		$this->tx=$tx;
		$this->rx=$rx;
		$this->mer=$mer;
		$this->frecDs=$frecDs;
		$this->frecUs=$frecUs;
		$this->sysName=$sysName;
		$this->sysDescr=$sysDescr;
		$this->firmware=$firmware;
	}
	/**
	 * Getters ..
	 */
	public function getId(){
	 	return $this->id;
	}
	public function getUptime(){
		return $this->Uptime;
	}
	public function getMac(){
		return $this->mac;
	}
	public function getSerie(){
		return $this->serie;
	}
	public function getTx(){
		return $this->tx;
	}
	public function getRx(){
		return $this->rx;
	}
	public function getMer(){
		return $this->mer;
	}
	public function getFrecUs(){
		return $this->frecUs;
	}
	public function getFrecDs(){
		return $this->frecDs;
	}
	public function getSysName(){
		return $this->sysName;
	}
	public function getSysDescr(){
		return $this->sysDescr;
	}
	public function getFirmware(){
		return $this->firmware;
	}
	/**
	 * Setters ...
	 */
	public function setId($id){
		$this->id=$id;
	}
	public function setUptime($nU){
		$this->Uptime=$nU;
	}
	public function setMac($nM){
		$this->mac=$nM;
	}
	public function setSerie($nS){
		$this->serie=$nS;
	}
	public function setTx($nT){
		$this->tx=$nT;
	}
	public function setRx($nR){
		$this->rx=$nR;
	}
	public function setMer($nM){
		$this->mer=$nM;
	}
	public function setFrecUs($nFU){
		$this->frecUs=$nFU;
	}
	public function setFrecDs($nFD){
		$this->frecDs=$nFD;
	}
	public function setSysName($nSN){
		$this->sysName=$nSN;
	}
	public function setSysDescr($nSD){
		$this->sysDescr=$nSD;
	}
	public function setFirmware($nF){
		$this->firmware=$nF;
	}
	/**
	 * Inserta nuevo registro a la tabla "docsis" usando las propiedades del Objeto ...
	 */
	public function agregaADb(){
		$con=Conexion::conectar();
		$query="INSERT INTO `cdr`.`docsis`
				(`Uptime`,
				`d_mac`,
				`d_serie`,
				`TX`,
				`RX`,
				`MER`,
				`Frec_DS`,
				`Frec_US`,
				`SysDescr`,
				`SysName`,
				`Firmware`)
				VALUES
				(:Uptime,
				:mac,
				:serie,
				:TX,
				:RX,
				:MER,
				:Frec_DS,
				:Frec_US,
				:SysDescr,
				:SysName,
				:Firmware)";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':Uptime', $this->Uptime, PDO::PARAM_STR);
		$stmt->bindParam(':mac', $this->mac, PDO::PARAM_STR);
		$stmt->bindParam(':serie', $this->serie, PDO::PARAM_STR);
		$stmt->bindParam(':TX', $this->tx, PDO::PARAM_STR);
		$stmt->bindParam(':RX', $this->rx, PDO::PARAM_STR);
		$stmt->bindParam(':MER', $this->mer, PDO::PARAM_STR);
		$stmt->bindParam(':Frec_DS', $this->frecDs, PDO::PARAM_STR);
		$stmt->bindParam(':Frec_US', $this->frecUs, PDO::PARAM_STR);
		$stmt->bindParam(':SysDescr', $this->sysDescr, PDO::PARAM_STR);
		$stmt->bindParam(':SysName', $this->sysName, PDO::PARAM_STR);
		$stmt->bindParam(':Firmware', $this->firmware, PDO::PARAM_STR);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
		$this->uFilaIns=$con->lastInsertId();
		$this->setId($this->uFilaIns);
	}
	/**
	 * Borra registro de la tabla "docsis" filtrado por ID ...
	 */
	public function borraPorId($ID){
		if(!isset($ID)){
			return $this;
		}
		$con=Conexion::conectarCDR();
		$query = "DELETE FROM cdr.docsis WHERE d_id = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $ID, PDO::PARAM_STR);
		$stmt -> execute();
		$this->uFilaIns=$con->lastInsertId();
	}
}