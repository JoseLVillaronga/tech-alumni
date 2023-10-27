<?php
/**
 * @Author : José Luis Villaronga
 * @copyright : 2016
 */
class SgcPivAcTck
{
	/**
	 * Propiedades
	 */
	private $id;
	public $ticket;
	public $accionCorrectiva;
	public $verificacionEficacia;
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
		}elseif(is_numeric($g)){
			$query="SELECT * FROM sgc_piv_ac_tck WHERE pac_id = $g";
			$res=Db::listar($query);
			if(count($res)=="0"){
				return $this;
			}else{
				$this->id=$g;
				$this->ticket=new Ticket($res[0]['tck_id']);
				$this->accionCorrectiva=new SgcAccionCorrectiva($res[0]['ac_id']);
				$this->verificacionEficacia=new SgcVerificacionEficacia($res[0]['ve_id']);
			}
		}elseif(is_object($g) AND get_class($g)=="Ticket" AND in_array($g->getCategoria(), array("Reclamos de Clientes","Hallazgos de Auditoria","Hallazgos de Satisfacción Cliente","Objetivos de Calidad","Otros Hallazgos","Pedido de Soporte"))){
			$id=$g->getId();
			$query="SELECT * FROM sgc_piv_ac_tck WHERE tck_id = $id";
			$res=Db::listar($query);
			if(count($res)=="0"){
				$this->ticket=$g;
				return $this;
			}else{
				$this->id=$res[0]['pac_id'];
				$this->ticket=$g;
				$this->accionCorrectiva=new SgcAccionCorrectiva($res[0]['ac_id']);
				$this->verificacionEficacia=new SgcVerificacionEficacia($res[0]['ve_id']);
			}
		}else{
			return $this;
		}
	}
	/**
	 * Getters ..
	 */
	public function getId(){
	 	return $this->id;
	}
	public function getTicketId(){
		if(is_object($this->ticket)){
			return $this->ticket->getId();
		}else{
			return null;
		}
	}
	public function getAccionCorrectivaId(){
		if(is_object($this->accionCorrectiva)){
			return $this->accionCorrectiva->getId();
		}else{
			return null;
		}
	}
	public function getVerificacioEficaciaId(){
		if(is_object($this->verificacionEficacia)){
			return $this->verificacionEficacia->getId();
		}else{
			return null;
		}
	}
	/**
	 * Setters ...
	 */
	public function setId($id){
		$this->id=$id;
	}
	/**
	 * Actualiza la tabla "sgc_piv_ac_tck" con las propiedades actuales del objeto ...
	 */
	public function actualizaDb(){
		$con=Conexion::conectar();
		$ac=$this->getAccionCorrectivaId();
		$ve=$this->getVerificacioEficaciaId();
		$tck=$this->getTicketId();
		$query = "UPDATE `sgc_piv_ac_tck`
					SET
					`pac_id` = :id,
					`ac_id` = :accionCorrectiva,
					`ve_id` = :verificacionEficacia,
					`tck_id` = :ticket
					WHERE `pac_id` = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
		$stmt->bindParam(':accionCorrectiva', $ac, PDO::PARAM_INT);
		$stmt->bindParam(':verificacionEficacia', $ve, PDO::PARAM_INT);
		$stmt->bindParam(':ticket', $tck, PDO::PARAM_INT);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
	}
	/**
	 * Inserta nuevo registro a la tabla "sgc_piv_ac_tck" usando las propiedades del Objeto ...
	 */
	public function agregaADb(){
		$con=Conexion::conectar();
		$ac=$this->getAccionCorrectivaId();
		$ve=$this->getVerificacioEficaciaId();
		$tck=$this->getTicketId();
		$query="INSERT INTO `sgc_piv_ac_tck`
				(`pac_id`,
				`ac_id`,
				`ve_id`,
				`tck_id`)
				VALUES
				(null,
				:accionCorrectiva,
				:verificacionEficacia,
				:ticket)";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':accionCorrectiva', $ac, PDO::PARAM_INT);
		$stmt->bindParam(':verificacionEficacia', $ve, PDO::PARAM_INT);
		$stmt->bindParam(':ticket', $tck, PDO::PARAM_INT);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
		$this->uFilaIns=$con->lastInsertId();
		$this->setId($this->uFilaIns);
	}
	/**
	 * Borra registro de la tabla "sgc_piv_ac_tck" filtrado por ID ...
	 */
	public function borraPorId($ID){
		if(!isset($ID)){
			return $this;
		}
		$con=Conexion::conectar();
		$query = "DELETE FROM sgc_piv_ac_tck WHERE pac_id = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $ID, PDO::PARAM_STR);
		$stmt -> execute();
		$this->uFilaIns=$con->lastInsertId();
	}
}
