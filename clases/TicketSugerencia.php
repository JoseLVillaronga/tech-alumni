<?php
/**
 * @Author : José Luis Villaronga
 * @copyright : 2019
 */
class TicketSugerencia
{
	/**
	 * Propiedades
	 */
	private $id;
	private $tckId;
	private $precio;
	private $proveedorId;
	private $fecha;
	private $notas;
	private $productiva;
	public $lote;
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
		$query="SELECT * FROM compras_sugerencias WHERE tck_id = ".$g;
		$res=Db::listar($query);
		if(count($res)>0){
			$this->id=$res[0]['scomp_id'];
			$this->tckId=$res[0]['tck_id'];
			$this->precio=$res[0]['scomp_precio'];
			$this->proveedorId=$res[0]['pr_id'];
			$this->fecha=$res[0]['scomp_fecha'];
			$this->notas=$res[0]['scomp_notas'];
			$this->productiva=$res[0]['scomp_productivo'];
			$this->lote=new ComprasSegLote2($res[0]['scomp_id']);
		}else{
			if(!empty($g)){
				$this->tckId=$g;
				$this->productiva=0;
				$this->agregaADb();
				$this->lote=new ComprasSegLote2($this->id);
			}
		}
	}
	/**
	 * Getters ..
	 */
	public function getId(){
	 	return $this->id;
	}
	public function getTckId(){
		return $this->tckId;
	}
	public function getPrecio(){
		return $this->precio;
	}
	public function getProveedorId(){
		return $this->proveedorId;
	}
	public function getFecha(){
		return $this->fecha;
	}
	public function getNotas(){
		return $this->notas;
	}
	public function getProductiva(){
		return $this->productiva;
	}
	/**
	 * Setters ...
	 */
	public function setId($id){
		$this->id=$id;
	}
	public function setTckId($nTI){
		$this->tckId=$nTI;
	}
	public function setPrecio($nP){
		$this->precio=$nP;
	}
	public function setProveedorId($nPI){
		$this->proveedorId=$nPI;
	}
	public function setFecha($nF){
		$this->fecha=$nF;
	}
	public function setNotas($nN){
		$this->notas=$nN;
	}
	public function setProductiva($nP){
		$this->productiva=$nP;
	}
	/**
	 * Actualiza la tabla "compras_sugerencias" con las propiedades actuales del objeto ...
	 */
	public function actualizaDb(){
		$con=Conexion::conectar();
		$query="UPDATE `compras_sugerencias`
				SET
				`tck_id` = :tckId,";
		if(!empty($this->precio)){
			$query.=" `scomp_precio` = :precio,";
		}
		if(!empty($this->proveedorId)){
			$query.=" `pr_id` = :proveedor,";
		}
		if(!empty($this->fecha)){
			$query.=" `scomp_fecha` = :fecha,";
		}
		$query.=" `scomp_notas` = :notas,
				`scomp_productivo` = :productiva
				WHERE `scomp_id` = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
		$stmt->bindParam(':tckId', $this->tckId, PDO::PARAM_INT);
		if(!empty($this->precio)){
			$stmt->bindParam(':precio', $this->precio, PDO::PARAM_INT);
		}
		if(!empty($this->proveedorId)){
			$stmt->bindParam(':proveedor', $this->proveedorId, PDO::PARAM_INT);
		}
		if(!empty($this->fecha)){
			$stmt->bindParam(':fecha', $this->fecha, PDO::PARAM_STR);
		}
		$stmt->bindParam(':notas', $this->notas, PDO::PARAM_STR);
		$stmt->bindParam(':productiva', $this->productiva, PDO::PARAM_INT);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
	}
	/**
	 * Inserta nuevo registro a la tabla "compras_sugerencias" usando las propiedades del Objeto ...
	 */
	public function agregaADb(){
		$con=Conexion::conectar();
		$query="INSERT INTO `compras_sugerencias`
				(`tck_id`,
				`scomp_precio`,
				`pr_id`,
				`scomp_fecha`,
				`scomp_notas`,
				`scomp_productivo`)
				VALUES
				(:tckId,
				:precio,
				:proveedor,
				:fecha,
				:notas,
				:productiva)";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':tckId', $this->tckId, PDO::PARAM_INT);
		$stmt->bindParam(':precio', $this->precio, PDO::PARAM_STR);
		$stmt->bindParam(':proveedor', $this->proveedorId, PDO::PARAM_INT);
		$stmt->bindParam(':fecha', $this->fecha, PDO::PARAM_STR);
		$stmt->bindParam(':notas', $this->notas, PDO::PARAM_STR);
		$stmt->bindParam(':productiva', $this->productiva, PDO::PARAM_INT);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
		$this->uFilaIns=$con->lastInsertId();
		$this->setId($this->uFilaIns);
	}
	/**
	 * Borra registro de la tabla "compras_sugerencias" filtrado por ID ...
	 */
	public function borraPorId($ID){
		if(!isset($ID)){
			return $this;
		}
		$con=Conexion::conectar();
		$query = "DELETE FROM compras_sugerencias WHERE scomp_id = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $ID, PDO::PARAM_STR);
		$stmt -> execute();
	}
}
