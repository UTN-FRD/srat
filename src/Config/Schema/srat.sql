-- MySQL dump 10.13  Distrib 5.5.38, for debian-linux-gnu (x86_64)
-- ------------------------------------------------------
-- Server version	5.5.38-0ubuntu0.14.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

DROP DATABASE IF EXISTS `srat`;
CREATE DATABASE `srat`;
USE `srat`;

--
-- Table structure for table `asignaturas`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `asignaturas` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `carrera_id` tinyint(3) unsigned NOT NULL,
  `materia_id` smallint(5) unsigned NOT NULL,
  `area_id` tinyint(3) unsigned NOT NULL,
  `nivel_id` tinyint(3) unsigned NOT NULL,
  `tipo_id` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UK_ASIGNATURA` (`carrera_id`,`materia_id`),
  KEY `IK_AREA` (`area_id`),
  KEY `IK_MATERIA` (`materia_id`),
  KEY `IK_NIVEL` (`nivel_id`),
  KEY `IK_TIPO` (`tipo_id`),
  CONSTRAINT `FK_ASIGNATURAS_AREA` FOREIGN KEY (`area_id`) REFERENCES `asignaturas_areas` (`id`),
  CONSTRAINT `FK_ASIGNATURAS_CARRERA` FOREIGN KEY (`carrera_id`) REFERENCES `asignaturas_carreras` (`id`),
  CONSTRAINT `FK_ASIGNATURAS_MATERIA` FOREIGN KEY (`materia_id`) REFERENCES `asignaturas_materias` (`id`),
  CONSTRAINT `FK_ASIGNATURAS_NIVEL` FOREIGN KEY (`nivel_id`) REFERENCES `asignaturas_niveles` (`id`),
  CONSTRAINT `FK_ASIGNATURAS_TIPO` FOREIGN KEY (`tipo_id`) REFERENCES `asignaturas_tipos` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `asignaturas`
--

LOCK TABLES `asignaturas` WRITE;
/*!40000 ALTER TABLE `asignaturas` DISABLE KEYS */;
/*!40000 ALTER TABLE `asignaturas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `asignaturas_areas`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `asignaturas_areas` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `obs` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UK_NOMBRE` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `asignaturas_areas`
--

LOCK TABLES `asignaturas_areas` WRITE;
/*!40000 ALTER TABLE `asignaturas_areas` DISABLE KEYS */;
/*!40000 ALTER TABLE `asignaturas_areas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `asignaturas_carreras`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `asignaturas_carreras` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `obs` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UK_NOMBRE` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `asignaturas_carreras`
--

LOCK TABLES `asignaturas_carreras` WRITE;
/*!40000 ALTER TABLE `asignaturas_carreras` DISABLE KEYS */;
/*!40000 ALTER TABLE `asignaturas_carreras` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `asignaturas_materias`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `asignaturas_materias` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `obs` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UK_NOMBRE` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `asignaturas_materias`
--

LOCK TABLES `asignaturas_materias` WRITE;
/*!40000 ALTER TABLE `asignaturas_materias` DISABLE KEYS */;
/*!40000 ALTER TABLE `asignaturas_materias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `asignaturas_niveles`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `asignaturas_niveles` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `obs` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UK_NOMBRE` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `asignaturas_niveles`
--

LOCK TABLES `asignaturas_niveles` WRITE;
/*!40000 ALTER TABLE `asignaturas_niveles` DISABLE KEYS */;
INSERT INTO `asignaturas_niveles` VALUES (1,'Primero',NULL),(2,'Segundo',NULL),(3,'Tercero',NULL),(4,'Cuarto',NULL),(5,'Quinto',NULL),(6,'General',NULL);
/*!40000 ALTER TABLE `asignaturas_niveles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `asignaturas_tipos`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `asignaturas_tipos` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `obs` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UK_NOMBRE` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `asignaturas_tipos`
--

LOCK TABLES `asignaturas_tipos` WRITE;
/*!40000 ALTER TABLE `asignaturas_tipos` DISABLE KEYS */;
INSERT INTO `asignaturas_tipos` VALUES (1,'Curricular',NULL),(2,'Integradora',NULL),(3,'Electiva',NULL);
/*!40000 ALTER TABLE `asignaturas_tipos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cargos`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cargos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `asignatura_id` int(10) unsigned NOT NULL,
  `usuario_id` int(10) unsigned NOT NULL,
  `tipo_id` tinyint(3) unsigned NOT NULL,
  `grado_id` tinyint(3) unsigned NOT NULL,
  `dedicacion_id` tinyint(3) unsigned NOT NULL,
  `dedicacion` decimal(2,1) unsigned NOT NULL,
  `resolucion` smallint(5) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UK_CARGO` (`asignatura_id`,`usuario_id`),
  KEY `IK_ASIGNATURA` (`asignatura_id`),
  KEY `IK_USUARIO` (`usuario_id`),
  KEY `IK_TIPO` (`tipo_id`),
  KEY `IK_GRADO` (`grado_id`),
  KEY `IK_DEDICACION` (`dedicacion_id`),
  CONSTRAINT `FK_CARGOS_ASIGNATURA` FOREIGN KEY (`asignatura_id`) REFERENCES `asignaturas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_CARGOS_DEDICACION` FOREIGN KEY (`dedicacion_id`) REFERENCES `cargos_dedicaciones` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_CARGOS_GRADO` FOREIGN KEY (`grado_id`) REFERENCES `cargos_grados` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_CARGOS_TIPO` FOREIGN KEY (`tipo_id`) REFERENCES `cargos_tipos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_CARGOS_USUARIO` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cargos`
--

LOCK TABLES `cargos` WRITE;
/*!40000 ALTER TABLE `cargos` DISABLE KEYS */;
/*!40000 ALTER TABLE `cargos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cargos_dedicaciones`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cargos_dedicaciones` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `obs` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UK_NOMBRE` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cargos_dedicaciones`
--

LOCK TABLES `cargos_dedicaciones` WRITE;
/*!40000 ALTER TABLE `cargos_dedicaciones` DISABLE KEYS */;
INSERT INTO `cargos_dedicaciones` VALUES (1,'Simple',NULL),(2,'Semiexclusiva',NULL),(3,'Exclusiva',NULL);
/*!40000 ALTER TABLE `cargos_dedicaciones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cargos_grados`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cargos_grados` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `obs` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UK_NOMBRE` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cargos_grados`
--

LOCK TABLES `cargos_grados` WRITE;
/*!40000 ALTER TABLE `cargos_grados` DISABLE KEYS */;
INSERT INTO `cargos_grados` VALUES (1,'Profesor Titular',NULL),(2,'Profesor Asociado',NULL),(3,'Profesor Adjunto',NULL),(4,'Jefe de Trabajos Prácticos',NULL),(5,'Ayudante Primera',NULL),(6,'Ayudante Segunda',NULL);
/*!40000 ALTER TABLE `cargos_grados` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cargos_tipos`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cargos_tipos` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `obs` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UK_NOMBRE` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cargos_tipos`
--

LOCK TABLES `cargos_tipos` WRITE;
/*!40000 ALTER TABLE `cargos_tipos` DISABLE KEYS */;
INSERT INTO `cargos_tipos` VALUES (1,'Ordinario',NULL),(2,'Interino',NULL);
/*!40000 ALTER TABLE `cargos_tipos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `horarios`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `horarios` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `asignatura_id` int(10) unsigned NOT NULL,
  `dia` tinyint(3) unsigned NOT NULL,
  `entrada` time NOT NULL,
  `salida` time NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UK_HORARIO` (`asignatura_id`,`dia`),
  CONSTRAINT `FK_HORARIOS_ASIGNATURA` FOREIGN KEY (`asignatura_id`) REFERENCES `asignaturas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `horarios`
--

LOCK TABLES `horarios` WRITE;
/*!40000 ALTER TABLE `horarios` DISABLE KEYS */;
/*!40000 ALTER TABLE `horarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `registros`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `registros` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tipo` tinyint(2) unsigned NOT NULL,
  `cargo_id` int(10) unsigned NOT NULL,
  `fecha` date NOT NULL,
  `entrada` time DEFAULT NULL,
  `salida` time DEFAULT NULL,
  `obs` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IK_CARGO` (`cargo_id`),
  KEY `IK_TIPO` (`tipo`),
  UNIQUE KEY `UK_REGISTRO` (`tipo`,`cargo_id`,`fecha`),
  CONSTRAINT `FK_REGISTROS_CARGO` FOREIGN KEY (`cargo_id`) REFERENCES `cargos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `registros`
--

LOCK TABLES `registros` WRITE;
/*!40000 ALTER TABLE `registros` DISABLE KEYS */;
/*!40000 ALTER TABLE `registros` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuarios` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rol_id` tinyint(3) unsigned DEFAULT NULL,
  `legajo` mediumint(8) unsigned NOT NULL,
  `password` char(60) NOT NULL,
  `reset` tinyint(1) unsigned DEFAULT NULL,
  `estado` tinyint(1) unsigned NOT NULL,
  `apellido` varchar(25) NOT NULL,
  `nombre` varchar(40) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UK_LEGAJO` (`legajo`),
  KEY `IK_ROL` (`rol_id`),
  CONSTRAINT `FK_USUARIOS_ROL` FOREIGN KEY (`rol_id`) REFERENCES `usuarios_roles` (`id`) ON DELETE SET NULL ON UPDATE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (1,1,1,'$2a$10$JTFmlyWPAXBXVh.NW0azOuU1WvwL/W0q2vRQum7vM645Ote/Cy8Oq',NULL,1,'-','Administrador');
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios_roles`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuarios_roles` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `obs` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UK_NOMBRE` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios_roles`
--

LOCK TABLES `usuarios_roles` WRITE;
/*!40000 ALTER TABLE `usuarios_roles` DISABLE KEYS */;
INSERT INTO `usuarios_roles` VALUES (1,'Administrador',NULL),(2,'Docente',NULL);
/*!40000 ALTER TABLE `usuarios_roles` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
