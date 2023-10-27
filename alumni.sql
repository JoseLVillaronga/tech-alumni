CREATE DATABASE  IF NOT EXISTS `alumni` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `alumni`;
-- MySQL dump 10.13  Distrib 8.0.32, for Win64 (x86_64)
--
-- Host: 192.168.1.13    Database: alumni
-- ------------------------------------------------------
-- Server version	8.0.34

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `aplicacion`
--

DROP TABLE IF EXISTS `aplicacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `aplicacion` (
  `alta_ot` int NOT NULL DEFAULT '0',
  `alta_cc` int NOT NULL DEFAULT '0',
  `alta_art` int NOT NULL DEFAULT '0',
  `alta_usu` int NOT NULL DEFAULT '0',
  `alta_emp` int NOT NULL DEFAULT '0',
  `lista_ot` int NOT NULL DEFAULT '0',
  `lista_cc` int NOT NULL DEFAULT '0',
  `lista_art` int NOT NULL DEFAULT '0',
  `lista_usu` int NOT NULL DEFAULT '0',
  `lista_emp` int NOT NULL DEFAULT '0',
  `repor_ot` int NOT NULL DEFAULT '0',
  `repor_cc` int NOT NULL DEFAULT '0',
  `cli_usuario` varchar(45) NOT NULL,
  `path` varchar(80) DEFAULT NULL,
  KEY `usu_idx` (`cli_usuario`),
  CONSTRAINT `usu` FOREIGN KEY (`cli_usuario`) REFERENCES `clientes` (`cli_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `calificacion`
--

DROP TABLE IF EXISTS `calificacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `calificacion` (
  `cal_id` int NOT NULL AUTO_INCREMENT,
  `cli_usuario` varchar(45) DEFAULT NULL,
  `calv_id` int DEFAULT NULL,
  `dep_id` int DEFAULT NULL,
  `cur_id` int DEFAULT NULL,
  `cal_fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`cal_id`),
  KEY `fk_cal_cli_idx` (`cli_usuario`),
  KEY `cal_fecha` (`cal_fecha`),
  CONSTRAINT `fk_cal_cli` FOREIGN KEY (`cli_usuario`) REFERENCES `clientes` (`cli_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `calificacion_value`
--

DROP TABLE IF EXISTS `calificacion_value`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `calificacion_value` (
  `calv_id` int NOT NULL AUTO_INCREMENT,
  `calv_value` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`calv_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `clientes`
--

DROP TABLE IF EXISTS `clientes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `clientes` (
  `cli_codigo` int NOT NULL AUTO_INCREMENT,
  `cli_nombre` varchar(45) NOT NULL DEFAULT 'Jhon',
  `cli_apellido` varchar(45) NOT NULL DEFAULT 'Doe',
  `cli_razon_social` varchar(45) NOT NULL DEFAULT 'unipersonal',
  `cli_nombre_contacto` varchar(92) DEFAULT NULL,
  `cli_cuit` varchar(15) DEFAULT NULL,
  `cli_direccion` varchar(170) DEFAULT NULL,
  `cli_telefono` varchar(45) DEFAULT NULL,
  `cli_categoria` varchar(5) DEFAULT NULL,
  `cli_email` varchar(80) DEFAULT NULL,
  `cli_web` varchar(80) DEFAULT NULL,
  `cli_usuario` varchar(45) NOT NULL,
  `cli_password` varchar(85) DEFAULT 'ep+MU2EKmijHKgnu7qVxLRu5cbVFwh8vvQ237Cq2aa8=',
  `cli_admin` tinyint NOT NULL DEFAULT '0',
  `cli_habilitado` tinyint NOT NULL DEFAULT '1',
  `cli_cambiar_pass` tinyint DEFAULT '0',
  `cli_grupo` int DEFAULT '0',
  `cli_solar` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`cli_codigo`),
  UNIQUE KEY `cli_usuario_UNIQUE` (`cli_usuario`),
  FULLTEXT KEY `ind_cli_usuario` (`cli_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `contenidos`
--

DROP TABLE IF EXISTS `contenidos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contenidos` (
  `con_id` int NOT NULL AUTO_INCREMENT,
  `cur_id` int DEFAULT NULL,
  `con_objetivos_inicio` varchar(4096) DEFAULT NULL,
  `con_objetivos_final` varchar(4096) DEFAULT NULL,
  `con_contenido` varchar(145) DEFAULT NULL,
  `con_laboratorio` varchar(145) DEFAULT NULL,
  `con_clase_nro` int DEFAULT NULL,
  `cli_usuario` varchar(45) DEFAULT NULL,
  `con_fecha` datetime DEFAULT NULL,
  PRIMARY KEY (`con_id`),
  KEY `fk_con_cli_idx` (`cli_usuario`),
  KEY `fk_con_cur_idx` (`cur_id`),
  KEY `in_con_fecha` (`con_fecha`),
  CONSTRAINT `fk_con_cli` FOREIGN KEY (`cli_usuario`) REFERENCES `clientes` (`cli_usuario`),
  CONSTRAINT `fk_con_cur` FOREIGN KEY (`cur_id`) REFERENCES `cursos` (`cur_id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `curso_status`
--

DROP TABLE IF EXISTS `curso_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `curso_status` (
  `cs_id` int NOT NULL AUTO_INCREMENT,
  `cs_descripcion` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`cs_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cursos`
--

DROP TABLE IF EXISTS `cursos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cursos` (
  `cur_id` int NOT NULL AUTO_INCREMENT,
  `cur_designacion` varchar(245) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `cur_descripcion` varchar(545) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `cur_duracion` int DEFAULT NULL,
  `cs_id` int DEFAULT NULL,
  `cur_docente` varchar(45) DEFAULT NULL,
  `cur_fecha_inicio` date DEFAULT NULL,
  `cur_fecha_final` date DEFAULT NULL,
  PRIMARY KEY (`cur_id`),
  KEY `fs_cur_cs_idx` (`cs_id`),
  CONSTRAINT `fs_cur_cs` FOREIGN KEY (`cs_id`) REFERENCES `curso_status` (`cs_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `departamento`
--

DROP TABLE IF EXISTS `departamento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `departamento` (
  `dep_id` int NOT NULL AUTO_INCREMENT,
  `dep_nombre` varchar(95) DEFAULT NULL,
  PRIMARY KEY (`dep_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `empresas`
--

DROP TABLE IF EXISTS `empresas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `empresas` (
  `emp_id` int NOT NULL AUTO_INCREMENT,
  `emp_razon_social` varchar(147) NOT NULL,
  `emp_cuit` char(13) NOT NULL,
  `emp_direccion` varchar(147) DEFAULT NULL,
  `emp_telefono` varchar(45) DEFAULT NULL,
  `emp_categoria` varchar(5) DEFAULT NULL,
  `emp_email` varchar(80) DEFAULT NULL,
  `emp_web` varchar(80) DEFAULT NULL,
  `emp_contacto_comercial` varchar(120) DEFAULT NULL,
  `emp_tel_cc` varchar(45) DEFAULT NULL,
  `emp_email_cc` varchar(80) DEFAULT NULL,
  `emp_contacto_administrativo` varchar(120) DEFAULT NULL,
  `emp_tel_ca` varchar(45) DEFAULT NULL,
  `emp_email_ca` varchar(80) DEFAULT NULL,
  PRIMARY KEY (`emp_id`,`emp_razon_social`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `encuesta`
--

DROP TABLE IF EXISTS `encuesta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `encuesta` (
  `enc_id` int NOT NULL AUTO_INCREMENT,
  `cur_id` int DEFAULT NULL,
  `cli_usuario` varchar(45) DEFAULT NULL,
  `enc_fecha` datetime DEFAULT NULL,
  PRIMARY KEY (`enc_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `encuesta_lote`
--

DROP TABLE IF EXISTS `encuesta_lote`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `encuesta_lote` (
  `encl_id` int NOT NULL AUTO_INCREMENT,
  `enc_id` int NOT NULL,
  `encp_id` int NOT NULL,
  `encv_id` int NOT NULL,
  `encl_fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `cli_usuario` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`encl_id`)
) ENGINE=InnoDB AUTO_INCREMENT=168 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `encuesta_preguntas`
--

DROP TABLE IF EXISTS `encuesta_preguntas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `encuesta_preguntas` (
  `encp_id` int NOT NULL AUTO_INCREMENT,
  `encp_descripcion` varchar(250) DEFAULT NULL,
  `encp_aspecto` varchar(145) DEFAULT NULL,
  PRIMARY KEY (`encp_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `encuesta_values`
--

DROP TABLE IF EXISTS `encuesta_values`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `encuesta_values` (
  `encv_id` int NOT NULL AUTO_INCREMENT,
  `encv_descripcion` varchar(250) NOT NULL,
  PRIMARY KEY (`encv_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Temporary view structure for view `encuesta_vista`
--

DROP TABLE IF EXISTS `encuesta_vista`;
/*!50001 DROP VIEW IF EXISTS `encuesta_vista`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `encuesta_vista` AS SELECT 
 1 AS `enc_id`,
 1 AS `usuario`,
 1 AS `curso`,
 1 AS `pregunta`,
 1 AS `aspecto`,
 1 AS `valor`,
 1 AS `docente`,
 1 AS `fecha`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `grupos`
--

DROP TABLE IF EXISTS `grupos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `grupos` (
  `cli_grupo` int NOT NULL AUTO_INCREMENT,
  `gru_nombre` varchar(45) NOT NULL,
  PRIMARY KEY (`cli_grupo`),
  UNIQUE KEY `gru_nombre_UNIQUE` (`gru_nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `inscripcion`
--

DROP TABLE IF EXISTS `inscripcion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inscripcion` (
  `ins_id` int NOT NULL AUTO_INCREMENT,
  `cur_id` int DEFAULT NULL,
  `cli_usuario` varchar(45) DEFAULT NULL,
  `ins_fecha` datetime DEFAULT NULL,
  PRIMARY KEY (`ins_id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mensaje`
--

DROP TABLE IF EXISTS `mensaje`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mensaje` (
  `men_id` int NOT NULL AUTO_INCREMENT,
  `men_asunto` varchar(45) DEFAULT NULL,
  `men_cuerpo` varchar(2048) DEFAULT NULL,
  `men_fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ment_id` int DEFAULT NULL,
  `men_sender` varchar(45) DEFAULT NULL,
  `men_receiver` varchar(45) DEFAULT NULL,
  `men_leido` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`men_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mensaje_tipo`
--

DROP TABLE IF EXISTS `mensaje_tipo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mensaje_tipo` (
  `ment_id` int NOT NULL AUTO_INCREMENT,
  `ment_value` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`ment_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `notificacion`
--

DROP TABLE IF EXISTS `notificacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notificacion` (
  `not_id` int NOT NULL AUTO_INCREMENT,
  `not_titulo` varchar(95) DEFAULT NULL,
  `not_cuerpo` varchar(2048) DEFAULT NULL,
  `nott_id` int DEFAULT NULL,
  `cli_usuario` varchar(45) DEFAULT NULL,
  `not_fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `not_recivido` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`not_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `notificacion_tipo`
--

DROP TABLE IF EXISTS `notificacion_tipo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notificacion_tipo` (
  `nott_id` int NOT NULL AUTO_INCREMENT,
  `nott_value` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`nott_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `visitas`
--

DROP TABLE IF EXISTS `visitas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `visitas` (
  `vi_id` int NOT NULL AUTO_INCREMENT,
  `vi_ip` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `vi_pagina` varchar(80) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `vi_nav` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `vi_pais` varchar(45) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `vi_fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `vi_usuario` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`vi_id`)
) ENGINE=InnoDB AUTO_INCREMENT=761 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping events for database 'alumni'
--

--
-- Dumping routines for database 'alumni'
--

--
-- Final view structure for view `encuesta_vista`
--

/*!50001 DROP VIEW IF EXISTS `encuesta_vista`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb3 */;
/*!50001 SET character_set_results     = utf8mb3 */;
/*!50001 SET collation_connection      = utf8mb3_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`jlvillaronga`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `encuesta_vista` AS select `e`.`enc_id` AS `enc_id`,`el`.`cli_usuario` AS `usuario`,concat(`cu`.`cur_designacion`,' - ',`cu`.`cur_fecha_inicio`) AS `curso`,`ep`.`encp_descripcion` AS `pregunta`,`ep`.`encp_aspecto` AS `aspecto`,`ev`.`encv_descripcion` AS `valor`,`cu`.`cur_docente` AS `docente`,`el`.`encl_fecha` AS `fecha` from ((((`encuesta_lote` `el` join `encuesta` `e`) join `encuesta_preguntas` `ep`) join `encuesta_values` `ev`) join `cursos` `cu`) where ((`e`.`enc_id` = `el`.`enc_id`) and (`el`.`encp_id` = `ep`.`encp_id`) and (`el`.`encv_id` = `ev`.`encv_id`) and (`e`.`cur_id` = `cu`.`cur_id`)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-10-27 14:20:55
