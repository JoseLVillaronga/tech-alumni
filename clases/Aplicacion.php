<?php
/**
 * @Author : José Luis Villaronga
 * @copyright : 2014
 */
class Aplicacion
{
	private $altaOT;
	private $altaCC;
	private $altaArticulo;
	private $altaUsuario;
	private $altaEmpresa;
	private $listaOT;
	private $listaCC;
	private $listaArticulo;
	private $listaUsuario;
	private $listaEmpresa;
	private $reporteOT;
	private $reporteCC;
	public $path=array();
	/**
	 * Metodo Constructor
	 */
	public function __construct($U=null){
		if(!empty($U)){
			$query="SELECT * FROM aplicacion WHERE cli_usuario = '$U'";
			foreach (Db::listar($query) as $fila){
				$this->altaOT=$fila['alta_ot'];
				$this->altaCC=$fila['alta_cc'];
				$this->altaArticulo=$fila['alta_art'];
				$this->altaUsuario=$fila['alta_usu'];
				$this->altaEmpresa=$fila['alta_emp'];
				$this->listaOT=$fila['lista_ot'];
				$this->listaCC=$fila['lista_cc'];
				$this->listaArticulo=$fila['lista_art'];
				$this->listaUsu=$fila['lista_usu'];
				$this->listaEmpresa=$fila['lista_usu'];
				$this->reporteOT=$fila['repor_ot'];
				$this->reporteCC=$fila['repor_cc'];
				$this->path[]=array(
					"path"=>$fila['path'],
					"usuario"=>$fila['cli_usuario']
				);
			}
		}else{
			return $this;
		}
	}
	/**
	 * Getters ...
	 */
	public function getAltaOT(){
		return $this->altaOT;
	}
	public function getAltaCC(){
		return $this->altaCC;
	}
	public function getAltaArticulo(){
		return $this->altaArticulo;
	}
	public function getAltaUsuario(){
		return $this->altaUsuario;
	}
	public function getAltaEmpresa(){
		return $this->altaEmpresa;
	}
	public function getListaOT(){
		return $this->listaOT;
	}
	public function getListaCC(){
		return $this->listaCC;
	}
	public function getListaArticulo(){
		return $this->listaArticulo;
	}
	public function getListaUsuario(){
		return $this->listaUsuario;
	}
	public function getListaEmpresa(){
		return $this->listaEmpresa;
	}
	public function getReporteOT(){
		return $this->reporteOT;
	}public function getReporteCC(){
		return $this->reporteCC;
	}
}
