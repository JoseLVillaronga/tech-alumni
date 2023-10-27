<?php
/**
 * @Author : José Luis Villaronga
 * @copyright : 2014
 */
class Busca
{
	/**
	 * Método constructor "privado" para evitar instancias de esta clase ...
	 */
	private function __construct(){}
	/**
	 * Busca Item Lote Stock por Nro. Serie en remitos activos ...                                        *
	 */
	static function buscaItemLoteStock($s){
		if(ctype_xdigit($s) AND strlen($s)=="12"){
			$query="SELECT stock.stk_id,lstk_serie FROM stock,lote_stock 
					WHERE stock.stk_id=lote_stock.stk_id
					AND stk_activo = 1
					AND lstk_mac = '$s'
					AND ss_id NOT IN (5)
					ORDER BY lstk_id DESC";
			$query2="SELECT stock.stk_id,lstk_serie FROM stock,lote_stock 
					WHERE stock.stk_id=lote_stock.stk_id
					AND stk_activo = 1
					AND lstk_serie = '$s'
					AND ss_id NOT IN (5)
					ORDER BY lstk_id DESC";
			$res=Db::listar($query);
			if(count($res)=="0"){
				$res=Db::listar($query2);
			}
		}else{
			$query="SELECT stock.stk_id,lstk_serie FROM stock,lote_stock 
					WHERE stock.stk_id=lote_stock.stk_id
					AND stk_activo = 1
					AND lstk_serie = '$s'
					AND ss_id NOT IN (5)
					ORDER BY lstk_id DESC";
			$res=Db::listar($query);			
		}

		
		if(empty($res[0]['stk_id'])){
			return self;
		}else{
			$stock=new Stock($res[0]['stk_id']);
			$stock->loteStock->traePropiedadesPorNSerie($res[0]['lstk_serie']);
			$stock->loteStock->historico=new HistoryState($stock->loteStock->getId());
			return $stock;
		}
	}
	/**
	 * Busca Item Lote Stock por Nro. Serie en remitos inactivos ...                                        *
	 */
	static function buscaItemLoteStockInactivos($s){
		$query="SELECT stock.stk_id,lstk_serie FROM stock,lote_stock 
				WHERE stock.stk_id=lote_stock.stk_id
				AND stk_activo = 0
				AND lstk_mac = '$s'
				AND ss_id NOT IN (4,5)";
		$query2="SELECT stock.stk_id,lstk_serie FROM stock,lote_stock 
				WHERE stock.stk_id=lote_stock.stk_id
				AND stk_activo = 0
				AND lstk_serie = '$s'
				AND ss_id NOT IN (4,5)";
		if(ctype_xdigit($s) AND strlen($s)=="12"){
			$res=Db::listar($query);
			if(count($res)=="0"){
				$res=Db::listar($query2);
			}
		}else{
			$res=Db::listar($query2);
		}

		if(empty($res[0]['stk_id'])){
			return self;
		}else{
			$stock=new Stock($res[0]['stk_id']);
			$stock->loteStock->traePropiedadesPorNSerie($res[0]['lstk_serie']);
			$stock->loteStock->historico=new HistoryState($stock->loteStock->getId());
			return $stock;
		}
	}
	/**
	 * Busca Item Lote Stock por Nro. Serie en remitos dados de baja ...                                        *
	 */
	static function buscaItemLoteStockBaja($s){
		$query="SELECT stock.stk_id,lstk_serie FROM stock,lote_stock 
				WHERE stock.stk_id=lote_stock.stk_id
				AND stk_activo = 2
				AND lstk_serie = '$s'
				ORDER BY lstk_id DESC";
		$query2="SELECT stock.stk_id,lstk_serie FROM stock,lote_stock 
				WHERE stock.stk_id=lote_stock.stk_id
				AND stk_activo = 2
				AND lstk_mac = '$s'
				ORDER BY lstk_id DESC";
		if(ctype_xdigit($s) AND strlen($s)=="12"){
			$res=Db::listar($query);
			if(count($res)=="0"){
				$res=Db::listar($query2);
			}
		}
		if(empty($res[0]['stk_id'])){
			return self;
		}else{
			$stock=new Stock($res[0]['stk_id']);
			$stock->loteStock->traePropiedadesPorNSerie($res[0]['lstk_serie']);
			$stock->loteStock->historico=new HistoryState($stock->loteStock->getId());
			return $stock;
		}
	}
	/**
	 * Busca Item Lote Stock por ID de tabla "lote_stock" ...
	 */
	static function buscaItemLoteStockPorId($s){
		if(empty($s)){return;}
		$queryRem="SELECT stk_id FROM lote_stock WHERE lstk_id = $s";
		$nRem=Db::listar($queryRem);
		if(empty($nRem[0]['stk_id'])){
			return;
		}else{
			$obj=new Stock($nRem[0]['stk_id']);
			if(is_object($obj)){
				if(is_object($obj->loteStock)){
					$obj->loteStock->traePropiedadesPorId($s);
					$obj->loteStock->historico=new HistoryState($obj->loteStock->getId());
					return $obj;
				}else{
					return $obj;
				}
			}
		}
	}
	static function buscaItemLoteStockPorIdCache($s,$loteStock){
		if(empty($s)){return;}
		foreach($loteStock['loteStockCorto'] as $fila){
			if($fila['lstk_id']==$s){
				$nRem[]=$fila['stk_id'];
				break;
			}
		}
		if(empty($nRem[0]['stk_id'])){
			return;
		}else{
			$obj=new Stock($nRem[0]['stk_id'],$loteStock);
			if(is_object($obj)){
				if(is_object($obj->loteStock)){
					$obj->loteStock->traePropiedadesPorId($s);
					$obj->loteStock->historico=new HistoryState($obj->loteStock->getId());
					return $obj;
				}
			}
			return;
		}

	}
	static function buscaItemLoteStockPorMAC($s){
		if(empty($s)){return;}
		$queryRem="SELECT stk_id,lstk_id FROM lote_stock WHERE lstk_mac = '$s' ORDER BY lstk_id DESC LIMIT 1";
		$nRem=Db::listar($queryRem);
		if(empty($nRem[0]['stk_id'])){
			return;
		}else{
			$obj=new Stock($nRem[0]['stk_id']);
			if(is_object($obj)){
				if(is_object($obj->loteStock)){
					$obj->loteStock->traePropiedadesPorId($nRem[0]['lstk_id']);
					$obj->loteStock->historico=new HistoryState($obj->loteStock->getId());
					return $obj;
				}
			}
			return;
		}

	}
	static function buscaItemOrdenCompra($s){
		$query="SELECT * FROM lote_orden_compra
				WHERE loc_id = $s";
		foreach(Db::listar($query) as $fila){
			$oc=new OrdenCompra($fila['oc_id']);
			$oc->loteItem->setPropPorId($s);
			return $oc;
		}
		return;
	}
}
