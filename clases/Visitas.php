<?php
/**
 * @author : José Luis Villaronga
 * @copyright : 2014
 */
class Visitas
{
	private $id=null;
	private $ip;
	private $pagina;
	private $nav;
	private $pais;
	private $fecha;
	private $usuario;
	public $errorSql;
	public $uFilaIns;
	
	/**
	 * Constructor ...
	 */
	public function __construct($track=null){
		$this->ip=$_SERVER['REMOTE_ADDR'];
		$this->pagina=$_SERVER['SCRIPT_NAME'];
		$this->nav=$_SERVER['HTTP_USER_AGENT'];
		$this->pais=$_SESSION['paisC'];
		$this->fecha=date('Y-m-d H:i:s');
		$this->usuario=$_SESSION['usuario'];
		if(!in_array($_SERVER['SCRIPT_NAME'], $track) AND $_SESSION['usuario']!='jlvillaronga' AND !empty($_SESSION['usuario'])){
			$this->agregaADb();
		}
	}
	/**
	 * Setters ...
	 */
	public function setId($nI){
		$this->id=$nI;
	}
	/**
	 * Agrega a SQL ...
	 */
	public function agregaADb(){
		$con=Conexion::conectar();
		$query="INSERT INTO `visitas`
				(`vi_id`,
				`vi_ip`,
				`vi_pagina`,
				`vi_nav`,
				`vi_pais`,
				`vi_fecha`,
				`vi_usuario`)
				VALUES
				(null,
				:ip,
				:pagina,
				:nav,
				:pais,
				:fecha,
				:usuario)";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':ip', $this->ip, PDO::PARAM_STR);
		$stmt->bindParam(':pagina', $this->pagina, PDO::PARAM_STR);
		$stmt->bindParam(':nav', $this->nav, PDO::PARAM_STR);
		$stmt->bindParam(':pais', $this->pais, PDO::PARAM_STR);
		$stmt->bindParam(':fecha', $this->fecha, PDO::PARAM_STR);
		$stmt->bindParam(':usuario', $this->usuario, PDO::PARAM_STR);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
		$this->uFilaIns=$con->lastInsertId();
		$this->setId($this->uFilaIns);
	}
}
