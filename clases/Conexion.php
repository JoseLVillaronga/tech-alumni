<?php
/**
 * @Author : José Luis Villaronga
 * @copyright : 2014
 */
    class Conexion
    {
        //constantes para la conexion
        Static $usuario   = 'jlvillaronga';
        static $clave     = 'teccamsql365';
        //declaramos estática para que se acceda esta propiedad directamente desde el objeto
        static $link;
        
        // definimos el método constructor simplemente para que no se pueda instanciar
        private function __construct(){}
        
     /**
     * @static
     * @return PDO
     * declaramos estática para que se acceda este método directamente desde el objeto
     */
        static function conectar(){
            try {
                self::$link = new PDO("mysql:host=192.168.1.18;port=3306;dbname=alumni;charset=utf8", self::$usuario, self::$clave, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',PDO::MYSQL_ATTR_INIT_COMMAND => 'SET time_zone = \'-03:00\'',PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true));
                /*** nos conectamos ***/
                //echo 'conectado a mysql <br />'; 
                return self::$link;
            }
            catch(PDOException $e)
            {
                echo $e->getMessage();
            }           
        }
    }

//$con = Conexion::conectar();
