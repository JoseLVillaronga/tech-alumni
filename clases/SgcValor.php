<?php
/**
 * @Author : José Luis Villaronga
 * @copyright : 2015
 */
class SgcValor
{
	/**
	 * Propiedades
	 */
	public $id;
	private $nombre;
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
			$query="SELECT * FROM sgc_encuesta_values WHERE encv_id = $g";
			foreach(Db::listar($query) as $fila){
				$this->id=$fila['encv_id'];
				$this->nombre=$fila['encv_descripcion'];
			}
		}
	}
	/**
	 * Getters ..
	 */
	public function getId(){
	 	return $this->id;
	}
	public function getNombre(){
		return $this->nombre;
	}
}