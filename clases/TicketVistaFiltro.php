<?php
class TicketVistaFiltro
{
	public $Titulo;
	public $notas;
	public $recursos;
	public $estado;
	
	public function __construct($Titulo=null,$notas=null,$recursos=null,$estado=null){
		$this->Titulo=$Titulo;
		$this->notas=$notas;
		$this->recursos=$recursos;
		$this->estado=$estado;
	}
}
