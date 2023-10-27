<?php
/**
 * @Author : José Luis Villaronga
 * @copyright : 2014
 */
class Query
{
	/**
	 * "SELECT art_id,categoria.cat_id,concat(cat_nombre,' * ',art_nombre,' * ',marca_nombre,' * ',mod_nombre) AS articulo
	 * FROM articulos,categoria,marca,modelo
	 * WHERE articulos.cat_id=categoria.cat_id
	 * AND articulos.marca_id=marca.marca_id
	 * AND articulos.mod_id=modelo.mod_id
	 * ORDER BY articulo"
	 */
	static $qRepaArt = "SELECT art_id,categoria.cat_id,concat(cat_nombre,' * ',art_nombre,' * ',marca_nombre,' * ',mod_nombre) AS articulo
						FROM articulos,categoria,marca,modelo
						WHERE articulos.cat_id=categoria.cat_id
						AND articulos.marca_id=marca.marca_id
						AND articulos.mod_id=modelo.mod_id
						ORDER BY articulo";
	/**
	 * SELECT art_id,articulo
	 * FROM articulos_vista
	 * ORDER BY articulo
	 */
	static $qRepaArticulo = "SELECT art_id,articulo
							FROM articulos_vista
							ORDER BY articulo";
	/**
	 * SELECT emp_id,emp_razon_social
	 * FROM empresas
	 * ORDER BY emp_razon_social
	 */
	static $qEmpresas = "SELECT emp_id,emp_razon_social
						FROM empresas
						ORDER BY emp_razon_social";
	/**
	 * SELECT falla_id,falla_nombre
	 * FROM fallas
	 * ORDER BY falla_nombre
	 */
	static $qFallas = "SELECT falla_id,falla_nombre 
						FROM fallas
						ORDER BY falla_nombre";
	/**
	 * SELECT cli_grupo,gru_nombre FROM grupos
	 */
	static $qGrupo = "SELECT cli_grupo,gru_nombre FROM grupos";
	/**
	 * Marcas ...
	 */
	static $qMarcas = "SELECT * FROM marca";
	/***********************************************************************************
	 * Método constructor "privado" para evitar instancias de esta clase ...           *
	 ***********************************************************************************/
	private function __construct(){}
}
