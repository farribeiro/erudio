-- MySQL dump 10.13  Distrib 5.7.20, for Linux (x86_64)
--
-- Host: 127.0.0.1    Database: erudio
-- ------------------------------------------------------
-- Server version	5.7.20-0ubuntu0.16.04.1

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

--
-- Table structure for table `edu_acesso_atribuicao`
--

DROP TABLE IF EXISTS `edu_acesso_atribuicao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edu_acesso_atribuicao` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `apelido` varchar(45) DEFAULT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_modificacao` timestamp NULL DEFAULT NULL,
  `data_exclusao` timestamp NULL DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `usuario_id` int(11) NOT NULL,
  `grupo_id` int(11) DEFAULT NULL,
  `instituicao_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `usuario_permissao_fk01_idx` (`usuario_id`),
  KEY `usuario_permissao_fk03` (`grupo_id`),
  KEY `usuario_permissao_fk04` (`instituicao_id`),
  CONSTRAINT `usuario_permissao_fk01` FOREIGN KEY (`usuario_id`) REFERENCES `edu_acesso_usuario` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `usuario_permissao_fk03` FOREIGN KEY (`grupo_id`) REFERENCES `edu_acesso_grupo` (`id`),
  CONSTRAINT `usuario_permissao_fk04` FOREIGN KEY (`instituicao_id`) REFERENCES `edu_instituicao` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edu_acesso_grupo`
--

DROP TABLE IF EXISTS `edu_acesso_grupo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edu_acesso_grupo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(45) DEFAULT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_modificacao` timestamp NULL DEFAULT NULL,
  `data_exclusao` timestamp NULL DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edu_acesso_permissao`
--

DROP TABLE IF EXISTS `edu_acesso_permissao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edu_acesso_permissao` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `nome_exibicao` varchar(45) NOT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edu_acesso_permissao_grupo`
--

DROP TABLE IF EXISTS `edu_acesso_permissao_grupo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edu_acesso_permissao_grupo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` enum('L','E') NOT NULL DEFAULT 'L',
  `grupo_id` int(11) NOT NULL DEFAULT '0',
  `permissao_id` int(11) NOT NULL DEFAULT '0',
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `edu_permissao_grupo_fk01` (`grupo_id`),
  KEY `edu_permissao_grupo_fk_02` (`permissao_id`),
  CONSTRAINT `edu_permissao_grupo_fk01` FOREIGN KEY (`grupo_id`) REFERENCES `edu_acesso_grupo` (`id`),
  CONSTRAINT `edu_permissao_grupo_fk_02` FOREIGN KEY (`permissao_id`) REFERENCES `edu_acesso_permissao` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=141 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edu_acesso_usuario`
--

DROP TABLE IF EXISTS `edu_acesso_usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edu_acesso_usuario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome_exibicao` varchar(255) NOT NULL,
  `nome_usuario` varchar(150) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `senha` varchar(100) NOT NULL,
  `senha_expirada` tinyint(1) NOT NULL DEFAULT '0',
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_modificacao` timestamp NULL DEFAULT NULL,
  `data_exclusao` timestamp NULL DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edu_alocacao`
--

DROP TABLE IF EXISTS `edu_alocacao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edu_alocacao` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `carga_horaria` int(11) NOT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_modificacao` timestamp NULL DEFAULT NULL,
  `data_exclusao` timestamp NULL DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `vinculo_id` int(11) NOT NULL,
  `instituicao_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `alocacao_fk01_idx` (`vinculo_id`),
  KEY `alocacao_fk02_idx` (`instituicao_id`),
  CONSTRAINT `alocacao_fk01` FOREIGN KEY (`vinculo_id`) REFERENCES `edu_vinculo` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `alocacao_fk02` FOREIGN KEY (`instituicao_id`) REFERENCES `edu_instituicao` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edu_aula`
--

DROP TABLE IF EXISTS `edu_aula`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edu_aula` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_modificacao` timestamp NULL DEFAULT NULL,
  `data_exclusao` timestamp NULL DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `turma_id` int(11) NOT NULL,
  `dia_id` int(11) NOT NULL,
  `quadro_horario_aula_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `aula_fk01_idx` (`turma_id`),
  KEY `aula_fk02_idx` (`dia_id`),
  KEY `aula_fk03_idx` (`quadro_horario_aula_id`),
  CONSTRAINT `aula_fk01` FOREIGN KEY (`turma_id`) REFERENCES `edu_turma` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `aula_fk02` FOREIGN KEY (`dia_id`) REFERENCES `edu_calendario_dia` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `aula_fk03` FOREIGN KEY (`quadro_horario_aula_id`) REFERENCES `edu_quadro_horario_aula` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edu_aula_anotacao`
--

DROP TABLE IF EXISTS `edu_aula_anotacao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edu_aula_anotacao` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `observacao` varchar(255) DEFAULT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_modificacao` timestamp NULL DEFAULT NULL,
  `data_exclusao` timestamp NULL DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `aula_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `aula_id` (`aula_id`),
  CONSTRAINT `edu_aula_anotacao_ibfk_1` FOREIGN KEY (`aula_id`) REFERENCES `edu_aula` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edu_aula_frequencia`
--

DROP TABLE IF EXISTS `edu_aula_frequencia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edu_aula_frequencia` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` enum('FALTA','FALTA_JUSTIFICADA','PRESENCA','DISPENSA') NOT NULL,
  `justificativa` varchar(255) DEFAULT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_modificacao` timestamp NULL DEFAULT NULL,
  `data_exclusao` timestamp NULL DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `aula_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `frequencia_fk02_idx` (`aula_id`),
  CONSTRAINT `frequencia_fk02` FOREIGN KEY (`aula_id`) REFERENCES `edu_aula` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edu_aula_frequencia_matricula_disciplina`
--

DROP TABLE IF EXISTS `edu_aula_frequencia_matricula_disciplina`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edu_aula_frequencia_matricula_disciplina` (
  `frequencia_id` int(11) NOT NULL,
  `matricula_disciplina_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edu_aula_turma_disciplina`
--

DROP TABLE IF EXISTS `edu_aula_turma_disciplina`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edu_aula_turma_disciplina` (
  `aula_id` int(11) NOT NULL,
  `turma_disciplina_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edu_avaliacao`
--

DROP TABLE IF EXISTS `edu_avaliacao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edu_avaliacao` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) DEFAULT NULL,
  `media` tinyint(4) NOT NULL,
  `tipo_avaliacao` enum('AVALIACAO_QUANTITATIVA','AVALIACAO_QUALITATIVA') NOT NULL DEFAULT 'AVALIACAO_QUANTITATIVA',
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_modificacao` timestamp NULL DEFAULT NULL,
  `data_exclusao` timestamp NULL DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `turma_disciplina_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `avaliacao_fk02_idx` (`turma_disciplina_id`),
  CONSTRAINT `avaliacao_fk02` FOREIGN KEY (`turma_disciplina_id`) REFERENCES `edu_turma_disciplina` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edu_avaliacao_conceito`
--

DROP TABLE IF EXISTS `edu_avaliacao_conceito`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edu_avaliacao_conceito` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `sigla` varchar(3) NOT NULL,
  `valor_min` decimal(4,2) NOT NULL,
  `valor_max` decimal(4,2) NOT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_modificacao` timestamp NULL DEFAULT NULL,
  `data_exclusao` timestamp NULL DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edu_avaliacao_habilidade`
--

DROP TABLE IF EXISTS `edu_avaliacao_habilidade`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edu_avaliacao_habilidade` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) DEFAULT NULL,
  `media` smallint(6) NOT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_modificacao` timestamp NULL DEFAULT NULL,
  `data_exclusao` timestamp NULL DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `disciplina_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `avaliacao_habilidade_fk01_idx` (`disciplina_id`),
  CONSTRAINT `avaliacao_habilidade_fk01` FOREIGN KEY (`disciplina_id`) REFERENCES `edu_disciplina` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=730 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edu_avaliacao_qualitativa`
--

DROP TABLE IF EXISTS `edu_avaliacao_qualitativa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edu_avaliacao_qualitativa` (
  `id` int(11) NOT NULL,
  `regime_fechamento` tinyint(1) NOT NULL DEFAULT '0',
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_modificacao` timestamp NULL DEFAULT NULL,
  `data_exclusao` timestamp NULL DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `tipo` enum('DIAGNOSTICO','PROCESSUAL','FINAL') NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `avaliacao_qualitativa_fk01` FOREIGN KEY (`id`) REFERENCES `edu_avaliacao` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edu_avaliacao_qualitativa_habilidade`
--

DROP TABLE IF EXISTS `edu_avaliacao_qualitativa_habilidade`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edu_avaliacao_qualitativa_habilidade` (
  `habilidade_id` int(11) NOT NULL,
  `avaliacao_qualitativa_id` int(11) NOT NULL,
  KEY `avaliacao_qualitativa_habilidade_fk01_idx` (`habilidade_id`),
  KEY `avaliacao_qualitativa_habilidade_fk02_idx` (`avaliacao_qualitativa_id`),
  CONSTRAINT `avaliacao_qualitativa_habilidade_fk01` FOREIGN KEY (`habilidade_id`) REFERENCES `edu_avaliacao_habilidade` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `avaliacao_qualitativa_habilidade_fk02` FOREIGN KEY (`avaliacao_qualitativa_id`) REFERENCES `edu_avaliacao_qualitativa` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edu_avaliacao_quantitativa`
--

DROP TABLE IF EXISTS `edu_avaliacao_quantitativa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edu_avaliacao_quantitativa` (
  `id` int(11) NOT NULL,
  `peso` smallint(2) DEFAULT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_modificacao` timestamp NULL DEFAULT NULL,
  `data_exclusao` timestamp NULL DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `tipo_id` int(11) NOT NULL,
  `data_entrega` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `avaliacao_quantitativa_fk01` FOREIGN KEY (`id`) REFERENCES `edu_avaliacao` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edu_avaliacao_tipo`
--

DROP TABLE IF EXISTS `edu_avaliacao_tipo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edu_avaliacao_tipo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(45) DEFAULT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_modificacao` timestamp NULL DEFAULT NULL,
  `data_exclusao` timestamp NULL DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edu_beneficio_social`
--

DROP TABLE IF EXISTS `edu_beneficio_social`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edu_beneficio_social` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edu_calendario`
--

DROP TABLE IF EXISTS `edu_calendario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edu_calendario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) DEFAULT NULL,
  `data_inicio` date NOT NULL,
  `data_termino` date NOT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_modificacao` timestamp NULL DEFAULT NULL,
  `data_exclusao` timestamp NULL DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `calendario_base_id` int(11) DEFAULT NULL,
  `instituicao_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `calendario_fk02_idx` (`instituicao_id`),
  CONSTRAINT `calendario_fk02` FOREIGN KEY (`instituicao_id`) REFERENCES `edu_instituicao` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edu_calendario_dia`
--

DROP TABLE IF EXISTS `edu_calendario_dia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edu_calendario_dia` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data_dia` date NOT NULL,
  `letivo` tinyint(1) DEFAULT NULL,
  `efetivo` tinyint(1) DEFAULT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_modificacao` timestamp NULL DEFAULT NULL,
  `data_exclusao` timestamp NULL DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `calendario_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `dia_calendario_fk01_idx` (`calendario_id`),
  CONSTRAINT `calendario_dia_fk01` FOREIGN KEY (`calendario_id`) REFERENCES `edu_calendario` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edu_calendario_evento`
--

DROP TABLE IF EXISTS `edu_calendario_evento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edu_calendario_evento` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `descricao` varchar(255) DEFAULT NULL,
  `tipo` enum('ATIVIDADE_ESCOLAR','ATIVIDADE_ADMINISTRATIVA','INTERESSE_PUBLICO','FERIADO','RECESSO') NOT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_modificacao` timestamp NULL DEFAULT NULL,
  `data_exclusao` timestamp NULL DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `fixo` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edu_calendario_evento_dia`
--

DROP TABLE IF EXISTS `edu_calendario_evento_dia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edu_calendario_evento_dia` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `inicio` time DEFAULT NULL,
  `termino` time DEFAULT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_modificacao` timestamp NULL DEFAULT NULL,
  `data_exclusao` timestamp NULL DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `dia_id` int(11) NOT NULL,
  `evento_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `dia_evento_fk01_idx` (`evento_id`),
  KEY `calendario_evento_dia_fk02_idx` (`dia_id`),
  CONSTRAINT `calendario_evento_dia_fk01` FOREIGN KEY (`evento_id`) REFERENCES `edu_calendario_evento` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `calendario_evento_dia_fk02` FOREIGN KEY (`dia_id`) REFERENCES `edu_calendario_dia` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edu_calendario_periodo`
--

DROP TABLE IF EXISTS `edu_calendario_periodo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edu_calendario_periodo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `media` int(11) NOT NULL DEFAULT '0',
  `data_inicio` date NOT NULL,
  `data_termino` date NOT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_modificacao` timestamp NULL DEFAULT NULL,
  `data_exclusao` timestamp NULL DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `calendario_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `calendario_periodo_fk01` (`calendario_id`),
  CONSTRAINT `calendario_periodo_fk01` FOREIGN KEY (`calendario_id`) REFERENCES `edu_calendario` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edu_cargo`
--

DROP TABLE IF EXISTS `edu_cargo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edu_cargo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `professor` tinyint(1) NOT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_modificacao` timestamp NULL DEFAULT NULL,
  `data_exclusao` timestamp NULL DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `acesso_grupo_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=75 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edu_curso`
--

DROP TABLE IF EXISTS `edu_curso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edu_curso` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(45) NOT NULL,
  `alfabetizatorio` tinyint(1) NOT NULL DEFAULT '0',
  `especializado` tinyint(1) NOT NULL DEFAULT '0',
  `limite_defasagem` tinyint(3) DEFAULT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_modificacao` timestamp NULL DEFAULT NULL,
  `data_exclusao` timestamp NULL DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `modalidade_ensino_id` int(11) DEFAULT NULL,
  `instituicao_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `curso_fk01_idx` (`modalidade_ensino_id`),
  CONSTRAINT `curso_fk01` FOREIGN KEY (`modalidade_ensino_id`) REFERENCES `edu_modalidade_ensino` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edu_disciplina`
--

DROP TABLE IF EXISTS `edu_disciplina`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edu_disciplina` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `nome_exibicao` varchar(100) DEFAULT NULL,
  `sigla` varchar(45) DEFAULT NULL,
  `carga_horaria` smallint(6) DEFAULT NULL,
  `opcional` tinyint(1) NOT NULL DEFAULT '0',
  `ofertado` tinyint(1) NOT NULL DEFAULT '1',
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_modificacao` timestamp NULL DEFAULT NULL,
  `data_exclusao` timestamp NULL DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `curso_id` int(11) NOT NULL,
  `etapa_id` int(11) NOT NULL,
  `disciplina_agrupamento_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `disciplina_fk01_idx` (`curso_id`),
  KEY `disciplina_fk02_idx` (`etapa_id`),
  KEY `disciplina_fk03` (`disciplina_agrupamento_id`),
  CONSTRAINT `disciplina_fk01` FOREIGN KEY (`curso_id`) REFERENCES `edu_curso` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `disciplina_fk02` FOREIGN KEY (`etapa_id`) REFERENCES `edu_etapa` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `disciplina_fk03` FOREIGN KEY (`disciplina_agrupamento_id`) REFERENCES `edu_disciplina_agrupamento` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=728 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edu_disciplina_agrupamento`
--

DROP TABLE IF EXISTS `edu_disciplina_agrupamento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edu_disciplina_agrupamento` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_modificacao` timestamp NULL DEFAULT NULL,
  `data_exclusao` timestamp NULL DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `etapa_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `disciplina_agrupamento_fk01` (`etapa_id`),
  CONSTRAINT `disciplina_agrupamento_fk01` FOREIGN KEY (`etapa_id`) REFERENCES `edu_etapa` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edu_enturmacao`
--

DROP TABLE IF EXISTS `edu_enturmacao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edu_enturmacao` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `encerrado` tinyint(1) NOT NULL DEFAULT '0',
  `concluido` tinyint(1) NOT NULL DEFAULT '0',
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_modificacao` timestamp NULL DEFAULT NULL,
  `data_exclusao` timestamp NULL DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `matricula_id` int(11) NOT NULL,
  `turma_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `enturmacao_fk01_idx` (`matricula_id`),
  KEY `enturmacao_fk02_idx` (`turma_id`),
  CONSTRAINT `enturmacao_fk01` FOREIGN KEY (`matricula_id`) REFERENCES `edu_matricula` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `enturmacao_fk02` FOREIGN KEY (`turma_id`) REFERENCES `edu_turma` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edu_etapa`
--

DROP TABLE IF EXISTS `edu_etapa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edu_etapa` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `nome_exibicao` varchar(100) DEFAULT NULL,
  `ordem` smallint(6) DEFAULT NULL,
  `limite_alunos` int(11) DEFAULT NULL,
  `frequencia_unificada` tinyint(1) NOT NULL DEFAULT '0',
  `idade_recomendada` tinyint(3) DEFAULT NULL,
  `integral` tinyint(1) NOT NULL DEFAULT '1',
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_modificacao` timestamp NULL DEFAULT NULL,
  `data_exclusao` timestamp NULL DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `curso_id` int(11) NOT NULL,
  `modulo_id` int(11) DEFAULT NULL,
  `quadro_horario_modelo_id` int(11) NOT NULL,
  `sistema_avaliacao_id` int(11) NOT NULL,
  `observacao_aprovacao` text,
  PRIMARY KEY (`id`),
  KEY `etapa_fk0_idx` (`curso_id`),
  KEY `etapa_fk1_idx` (`modulo_id`),
  KEY `etapa_fk04_idx` (`quadro_horario_modelo_id`),
  KEY `etapa_fk05_idx` (`sistema_avaliacao_id`),
  CONSTRAINT `etapa_fk01` FOREIGN KEY (`curso_id`) REFERENCES `edu_curso` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `etapa_fk02` FOREIGN KEY (`modulo_id`) REFERENCES `edu_modulo` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `etapa_fk04` FOREIGN KEY (`quadro_horario_modelo_id`) REFERENCES `edu_quadro_horario_modelo` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `etapa_fk05` FOREIGN KEY (`sistema_avaliacao_id`) REFERENCES `edu_sistema_avaliacao` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edu_instituicao`
--

DROP TABLE IF EXISTS `edu_instituicao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edu_instituicao` (
  `id` int(11) NOT NULL,
  `sigla` varchar(10) DEFAULT NULL,
  `instituicao_pai_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `instituicao_fk02` (`instituicao_pai_id`),
  CONSTRAINT `instituicao_fk01` FOREIGN KEY (`id`) REFERENCES `sme_pessoa` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `instituicao_fk02` FOREIGN KEY (`instituicao_pai_id`) REFERENCES `edu_instituicao` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edu_matricula`
--

DROP TABLE IF EXISTS `edu_matricula`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edu_matricula` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(45) DEFAULT NULL,
  `status` enum('CURSANDO','APROVADO','REPROVADO','TRANCADO','ABANDONO','FALECIDO','CANCELADO','MUDANCA_DE_CURSO') NOT NULL DEFAULT 'CURSANDO',
  `data_encerramento` timestamp NULL DEFAULT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_modificacao` timestamp NULL DEFAULT NULL,
  `data_exclusao` timestamp NULL DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `pessoa_fisica_aluno_id` int(11) NOT NULL,
  `curso_id` int(11) NOT NULL,
  `unidade_ensino_id` int(11) NOT NULL,
  `etapa_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo_UNIQUE` (`codigo`),
  KEY `matricula_fk2_idx` (`pessoa_fisica_aluno_id`),
  KEY `matricula_fk2_idx1` (`curso_id`),
  KEY `matricula_fk4_idx` (`unidade_ensino_id`),
  KEY `matricula_fk5_idx` (`etapa_id`),
  CONSTRAINT `matricula_fk2` FOREIGN KEY (`pessoa_fisica_aluno_id`) REFERENCES `sme_pessoa_fisica` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `matricula_fk3` FOREIGN KEY (`curso_id`) REFERENCES `edu_curso` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `matricula_fk4` FOREIGN KEY (`unidade_ensino_id`) REFERENCES `edu_unidade_ensino` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `matricula_fk5` FOREIGN KEY (`etapa_id`) REFERENCES `edu_etapa` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edu_matricula_disciplina`
--

DROP TABLE IF EXISTS `edu_matricula_disciplina`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edu_matricula_disciplina` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` enum('CURSANDO','APROVADO','REPROVADO','DISPENSADO','INCOMPLETO','EM_EXAME') NOT NULL,
  `frequencia_total` decimal(5,2) DEFAULT NULL,
  `media_final` varchar(45) DEFAULT NULL,
  `ano` year(4) NOT NULL,
  `insercao_manual` tinyint(1) NOT NULL DEFAULT '0',
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_encerramento` timestamp NULL DEFAULT NULL,
  `data_modificacao` timestamp NULL DEFAULT NULL,
  `data_exclusao` timestamp NULL DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `matricula_id` int(11) NOT NULL,
  `enturmacao_id` int(11) DEFAULT NULL,
  `disciplina_id` int(11) NOT NULL,
  `turma_disciplina_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `matricula_disciplina_fk01_idx` (`matricula_id`),
  KEY `matricula_disciplina_fk02_idx` (`enturmacao_id`),
  KEY `matricula_disciplina_fk03_idx` (`disciplina_id`),
  KEY `matricula_disciplina_fk04_idx` (`turma_disciplina_id`),
  CONSTRAINT `matricula_disciplina_fk01` FOREIGN KEY (`matricula_id`) REFERENCES `edu_matricula` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `matricula_disciplina_fk02` FOREIGN KEY (`enturmacao_id`) REFERENCES `edu_enturmacao` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `matricula_disciplina_fk03` FOREIGN KEY (`disciplina_id`) REFERENCES `edu_disciplina` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `matricula_disciplina_fk04` FOREIGN KEY (`turma_disciplina_id`) REFERENCES `edu_turma_disciplina` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edu_matricula_etapa`
--

DROP TABLE IF EXISTS `edu_matricula_etapa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edu_matricula_etapa` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `unidade_ensino` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `ano` year(4) NOT NULL,
  `status` enum('APROVADO','REPROVADO') COLLATE latin1_general_ci NOT NULL DEFAULT 'APROVADO',
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_modificacao` timestamp NULL DEFAULT NULL,
  `data_exclusao` timestamp NULL DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `matricula_id` int(11) NOT NULL,
  `etapa_id` int(11) NOT NULL,
  `enturmacao_id` int(11) DEFAULT NULL,
  `cidade_id` int(11) DEFAULT NULL,
  `insercao_manual` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `matricula_etapa_fk01_idx` (`matricula_id`),
  KEY `matricula_etapa_fk02_idx` (`etapa_id`),
  KEY `matricula_etapa_fk03_idx` (`enturmacao_id`),
  KEY `matricula_etapa_fk04_idx` (`cidade_id`),
  CONSTRAINT `matricula_etapa_fk01` FOREIGN KEY (`matricula_id`) REFERENCES `edu_matricula` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `matricula_etapa_fk02` FOREIGN KEY (`etapa_id`) REFERENCES `edu_etapa` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `matricula_etapa_fk03` FOREIGN KEY (`enturmacao_id`) REFERENCES `edu_enturmacao` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `matricula_etapa_fk04` FOREIGN KEY (`cidade_id`) REFERENCES `sme_cidade` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edu_matricula_historico_observacao`
--

DROP TABLE IF EXISTS `edu_matricula_historico_observacao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edu_matricula_historico_observacao` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `texto` text COLLATE latin1_general_ci NOT NULL,
  `matricula_id` int(11) NOT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_modificacao` timestamp NULL DEFAULT NULL,
  `data_exclusao` timestamp NULL DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `fk_edu_matricula_historico_observacao_matricula_idx` (`matricula_id`),
  CONSTRAINT `fk_edu_matricula_historico_observacao_matricula` FOREIGN KEY (`matricula_id`) REFERENCES `edu_matricula` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edu_media`
--

DROP TABLE IF EXISTS `edu_media`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edu_media` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(45) DEFAULT NULL,
  `numero` smallint(6) NOT NULL,
  `valor` decimal(4,2) DEFAULT NULL,
  `peso` int(3) NOT NULL DEFAULT '1',
  `faltas` tinyint(3) NOT NULL DEFAULT '0',
  `frequencia` decimal(5,2) NOT NULL DEFAULT '100.00',
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_modificacao` timestamp NULL DEFAULT NULL,
  `data_exclusao` timestamp NULL DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `matricula_disciplina_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `media_fk01_idx` (`matricula_disciplina_id`),
  CONSTRAINT `media_fk01` FOREIGN KEY (`matricula_disciplina_id`) REFERENCES `edu_matricula_disciplina` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edu_modalidade_ensino`
--

DROP TABLE IF EXISTS `edu_modalidade_ensino`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edu_modalidade_ensino` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edu_modulo`
--

DROP TABLE IF EXISTS `edu_modulo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edu_modulo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(45) NOT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_modificacao` timestamp NULL DEFAULT NULL,
  `data_exclusao` timestamp NULL DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `curso_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `curso_nivel_fk01_idx` (`curso_id`),
  CONSTRAINT `modulo_fk01` FOREIGN KEY (`curso_id`) REFERENCES `edu_curso` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edu_movimentacao`
--

DROP TABLE IF EXISTS `edu_movimentacao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edu_movimentacao` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `justificativa` varchar(255) DEFAULT NULL,
  `tipo_movimentacao` enum('TRANSFERENCIA','MOVIMENTACAO_TURMA','DESLIGAMENTO','RECLASSIFICACAO','RETORNO') NOT NULL DEFAULT 'TRANSFERENCIA',
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_modificacao` timestamp NULL DEFAULT NULL,
  `data_exclusao` timestamp NULL DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `matricula_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `movimentacao_fk01_idx` (`matricula_id`),
  CONSTRAINT `movimentacao_fk01` FOREIGN KEY (`matricula_id`) REFERENCES `edu_matricula` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edu_movimentacao_desligamento`
--

DROP TABLE IF EXISTS `edu_movimentacao_desligamento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edu_movimentacao_desligamento` (
  `id` int(11) NOT NULL,
  `destino` varchar(255) DEFAULT NULL,
  `motivo` enum('ABANDONO','FALECIMENTO','TRANSFERENCIA_EXTERNA','CANCELAMENTO','MUDANCA_DE_CURSO') NOT NULL DEFAULT 'TRANSFERENCIA_EXTERNA',
  PRIMARY KEY (`id`),
  CONSTRAINT `desligamento_fk01` FOREIGN KEY (`id`) REFERENCES `edu_movimentacao` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edu_movimentacao_reclassificacao`
--

DROP TABLE IF EXISTS `edu_movimentacao_reclassificacao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edu_movimentacao_reclassificacao` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `enturmacao_origem_id` int(11) NOT NULL DEFAULT '0',
  `enturmacao_destino_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `reclassificacao_fk02` (`enturmacao_origem_id`),
  KEY `reclassificacao_fk03` (`enturmacao_destino_id`),
  CONSTRAINT `reclassificacao_fk01` FOREIGN KEY (`id`) REFERENCES `edu_movimentacao` (`id`),
  CONSTRAINT `reclassificacao_fk02` FOREIGN KEY (`enturmacao_origem_id`) REFERENCES `edu_enturmacao` (`id`),
  CONSTRAINT `reclassificacao_fk03` FOREIGN KEY (`enturmacao_destino_id`) REFERENCES `edu_enturmacao` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edu_movimentacao_reclassificacao_nota`
--

DROP TABLE IF EXISTS `edu_movimentacao_reclassificacao_nota`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edu_movimentacao_reclassificacao_nota` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `valor` decimal(4,2) DEFAULT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_modificacao` timestamp NULL DEFAULT NULL,
  `data_exclusao` timestamp NULL DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `disciplina_id` int(11) NOT NULL DEFAULT '0',
  `reclassificacao_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `reclassificacao_nota_fk01` (`disciplina_id`),
  KEY `reclassificacao_nota_fk02` (`reclassificacao_id`),
  CONSTRAINT `reclassificacao_nota_fk01` FOREIGN KEY (`disciplina_id`) REFERENCES `edu_disciplina` (`id`),
  CONSTRAINT `reclassificacao_nota_fk02` FOREIGN KEY (`reclassificacao_id`) REFERENCES `edu_movimentacao_reclassificacao` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edu_movimentacao_retorno`
--

DROP TABLE IF EXISTS `edu_movimentacao_retorno`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edu_movimentacao_retorno` (
  `id` int(11) NOT NULL,
  `origem` varchar(150) COLLATE latin1_general_ci DEFAULT NULL,
  `etapa_id` int(11) NOT NULL,
  `unidade_ensino_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `retorno_fk02_idx` (`etapa_id`),
  KEY `retorno_fk03_idx` (`unidade_ensino_id`),
  CONSTRAINT `retorno_fk01` FOREIGN KEY (`id`) REFERENCES `edu_movimentacao` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `retorno_fk02` FOREIGN KEY (`etapa_id`) REFERENCES `edu_etapa` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `retorno_fk03` FOREIGN KEY (`unidade_ensino_id`) REFERENCES `edu_unidade_ensino` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edu_movimentacao_transferencia`
--

DROP TABLE IF EXISTS `edu_movimentacao_transferencia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edu_movimentacao_transferencia` (
  `id` int(11) NOT NULL,
  `resposta` varchar(255) DEFAULT NULL,
  `status` enum('PENDENTE','ACEITO','RECUSADO') NOT NULL,
  `data_encerramento` timestamp NULL DEFAULT NULL,
  `data_agendamento` date DEFAULT NULL,
  `unidade_ensino_origem_id` int(11) NOT NULL,
  `unidade_ensino_destino_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `transferencia_fk02_idx` (`unidade_ensino_origem_id`),
  KEY `transferencia_fk03_idx` (`unidade_ensino_destino_id`),
  CONSTRAINT `transferencia_fk01` FOREIGN KEY (`id`) REFERENCES `edu_movimentacao` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `transferencia_fk02` FOREIGN KEY (`unidade_ensino_origem_id`) REFERENCES `edu_unidade_ensino` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `transferencia_fk03` FOREIGN KEY (`unidade_ensino_destino_id`) REFERENCES `edu_unidade_ensino` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edu_movimentacao_turma`
--

DROP TABLE IF EXISTS `edu_movimentacao_turma`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edu_movimentacao_turma` (
  `id` int(11) NOT NULL,
  `enturmacao_origem_id` int(11) NOT NULL,
  `enturmacao_destino_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `movimentacao_turma_fk02_idx` (`enturmacao_origem_id`),
  KEY `movimentacao_turma_fk03_idx` (`enturmacao_destino_id`),
  CONSTRAINT `movimentacao_turma_fk01` FOREIGN KEY (`id`) REFERENCES `edu_movimentacao` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `movimentacao_turma_fk02` FOREIGN KEY (`enturmacao_origem_id`) REFERENCES `edu_enturmacao` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `movimentacao_turma_fk03` FOREIGN KEY (`enturmacao_destino_id`) REFERENCES `edu_enturmacao` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edu_nota`
--

DROP TABLE IF EXISTS `edu_nota`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edu_nota` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_modificacao` timestamp NULL DEFAULT NULL,
  `data_exclusao` timestamp NULL DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `tipo_nota` enum('NOTA_QUANTITATIVA','NOTA_QUALITATIVA') NOT NULL,
  `media_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `nota_fk02_idx` (`media_id`),
  CONSTRAINT `nota_fk02` FOREIGN KEY (`media_id`) REFERENCES `edu_media` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edu_nota_qualitativa`
--

DROP TABLE IF EXISTS `edu_nota_qualitativa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edu_nota_qualitativa` (
  `id` int(11) NOT NULL,
  `avaliacao_qualitativa_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_child_idx` (`avaliacao_qualitativa_id`),
  CONSTRAINT `nota_qualitativa` FOREIGN KEY (`id`) REFERENCES `edu_nota` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `nota_qualitativa_fk01` FOREIGN KEY (`avaliacao_qualitativa_id`) REFERENCES `edu_avaliacao_qualitativa` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edu_nota_qualitativa_habilidade`
--

DROP TABLE IF EXISTS `edu_nota_qualitativa_habilidade`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edu_nota_qualitativa_habilidade` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_modificacao` timestamp NULL DEFAULT NULL,
  `data_exclusao` timestamp NULL DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `habilidade_id` int(11) NOT NULL,
  `conceito_id` int(11) NOT NULL,
  `nota_qualitativa_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `nota_qualitativa_habilidade_fk01_idx` (`habilidade_id`),
  KEY `nota_qualitativa_habilidade_fk01_idx1` (`conceito_id`),
  KEY `nota_qualitativa_habilidade_fk03_idx` (`nota_qualitativa_id`),
  CONSTRAINT `nota_qualitativa_habilidade_fk01` FOREIGN KEY (`habilidade_id`) REFERENCES `edu_avaliacao_habilidade` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `nota_qualitativa_habilidade_fk02` FOREIGN KEY (`conceito_id`) REFERENCES `edu_avaliacao_conceito` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `nota_qualitativa_habilidade_fk03` FOREIGN KEY (`nota_qualitativa_id`) REFERENCES `edu_nota_qualitativa` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edu_nota_quantitativa`
--

DROP TABLE IF EXISTS `edu_nota_quantitativa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edu_nota_quantitativa` (
  `id` int(11) NOT NULL,
  `valor` decimal(4,2) NOT NULL,
  `avaliacao_quantitativa_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `nota_quantitativa_fk01` FOREIGN KEY (`id`) REFERENCES `edu_nota` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edu_pessoa_fisica_beneficio_social`
--

DROP TABLE IF EXISTS `edu_pessoa_fisica_beneficio_social`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edu_pessoa_fisica_beneficio_social` (
  `pessoa_fisica_id` int(11) NOT NULL,
  `beneficio_social_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edu_quadro_horario`
--

DROP TABLE IF EXISTS `edu_quadro_horario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edu_quadro_horario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) DEFAULT NULL,
  `turno_inicio` time NOT NULL,
  `turno_termino` time NOT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_modificacao` timestamp NULL DEFAULT NULL,
  `data_exclusao` timestamp NULL DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `modelo_id` int(11) NOT NULL,
  `unidade_ensino_id` int(11) NOT NULL,
  `turno_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `quadro_horario_unidade_fk013_idx` (`modelo_id`),
  KEY `quadro_horario_unidade_fk02_idx` (`unidade_ensino_id`),
  KEY `quadro_horario_unidade_fk03_idx` (`turno_id`),
  CONSTRAINT `quadro_horario_unidade_fk01` FOREIGN KEY (`modelo_id`) REFERENCES `edu_quadro_horario_modelo` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `quadro_horario_unidade_fk02` FOREIGN KEY (`unidade_ensino_id`) REFERENCES `edu_unidade_ensino` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `quadro_horario_unidade_fk03` FOREIGN KEY (`turno_id`) REFERENCES `edu_turno` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edu_quadro_horario_aula`
--

DROP TABLE IF EXISTS `edu_quadro_horario_aula`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edu_quadro_horario_aula` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `inicio` time NOT NULL,
  `termino` time NOT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_modificacao` timestamp NULL DEFAULT NULL,
  `data_exclusao` timestamp NULL DEFAULT NULL,
  `ativo` tinyint(1) DEFAULT '1',
  `quadro_horario_id` int(11) NOT NULL,
  `quadro_horario_dia_semana_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `quadro_horario_aula_fk01_idx` (`quadro_horario_id`),
  KEY `quadro_horario_aula_fk02_idx` (`quadro_horario_dia_semana_id`),
  CONSTRAINT `quadro_horario_aula_fk01` FOREIGN KEY (`quadro_horario_id`) REFERENCES `edu_quadro_horario` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `quadro_horario_aula_fk02` FOREIGN KEY (`quadro_horario_dia_semana_id`) REFERENCES `edu_quadro_horario_dia_semana` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edu_quadro_horario_aula_turma`
--

DROP TABLE IF EXISTS `edu_quadro_horario_aula_turma`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edu_quadro_horario_aula_turma` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_modificacao` timestamp NULL DEFAULT NULL,
  `data_exclusao` timestamp NULL DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `quadro_horario_aula_id` int(11) NOT NULL,
  `turma_disciplina_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `quadro_horario_aula_turma_fk01_idx` (`quadro_horario_aula_id`),
  KEY `quadro_horario_aula_turma_fk02_idx` (`turma_disciplina_id`),
  CONSTRAINT `quadro_horario_aula_turma_fk01` FOREIGN KEY (`quadro_horario_aula_id`) REFERENCES `edu_quadro_horario_aula` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `quadro_horario_aula_turma_fk02` FOREIGN KEY (`turma_disciplina_id`) REFERENCES `edu_turma_disciplina` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edu_quadro_horario_dia_semana`
--

DROP TABLE IF EXISTS `edu_quadro_horario_dia_semana`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edu_quadro_horario_dia_semana` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dia_semana` int(11) NOT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_modificacao` timestamp NULL DEFAULT NULL,
  `data_exclusao` timestamp NULL DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `quadro_horario_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `quadro_horario_dia_semana_fk01_idx` (`quadro_horario_id`),
  CONSTRAINT `quadro_horario_dia_semana_fk01` FOREIGN KEY (`quadro_horario_id`) REFERENCES `edu_quadro_horario` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edu_quadro_horario_modelo`
--

DROP TABLE IF EXISTS `edu_quadro_horario_modelo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edu_quadro_horario_modelo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(45) NOT NULL DEFAULT '',
  `quantidade_aulas` int(11) NOT NULL DEFAULT '0',
  `duracao_aula` int(11) NOT NULL DEFAULT '0',
  `duracao_intervalo` int(11) NOT NULL DEFAULT '0',
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_modificacao` timestamp NULL DEFAULT NULL,
  `data_exclusao` timestamp NULL DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `curso_id` int(11) DEFAULT NULL,
  `posicao_intervalo` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edu_regime`
--

DROP TABLE IF EXISTS `edu_regime`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edu_regime` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(45) NOT NULL,
  `unidade` varchar(50) DEFAULT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_modificacao` timestamp NULL DEFAULT NULL,
  `data_exclusao` timestamp NULL DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edu_sistema_avaliacao`
--

DROP TABLE IF EXISTS `edu_sistema_avaliacao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edu_sistema_avaliacao` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `nome_identificacao` varchar(45) NOT NULL,
  `exame` tinyint(1) NOT NULL,
  `tipo` enum('QUANTITATIVO','QUALITATIVO','SEM_AVALIACAO') NOT NULL,
  `quantidade_medias` int(11) DEFAULT NULL,
  `nota_aprovacao` decimal(4,2) NOT NULL DEFAULT '0.00',
  `nota_aprovacao_exame` decimal(4,2) DEFAULT NULL,
  `peso_exame` int(3) DEFAULT NULL,
  `frequencia_minima` decimal(5,2) NOT NULL DEFAULT '0.75',
  `hora_aula` decimal(4,2) NOT NULL DEFAULT '60.00',
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_modificacao` timestamp NULL DEFAULT NULL,
  `data_exclusao` timestamp NULL DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `regime_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome_identificacao_UNIQUE` (`nome_identificacao`),
  KEY `edu_sistema_avaliacao_idx` (`regime_id`),
  CONSTRAINT `edu_sistema_avaliacao` FOREIGN KEY (`regime_id`) REFERENCES `edu_regime` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edu_tipo_unidade_ensino`
--

DROP TABLE IF EXISTS `edu_tipo_unidade_ensino`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edu_tipo_unidade_ensino` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `sigla` varchar(10) DEFAULT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_modificacao` timestamp NULL DEFAULT NULL,
  `data_exclusao` timestamp NULL DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edu_turma`
--

DROP TABLE IF EXISTS `edu_turma`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edu_turma` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(45) NOT NULL,
  `apelido` varchar(45) DEFAULT NULL,
  `limite_alunos` int(11) NOT NULL,
  `data_encerramento` timestamp NULL DEFAULT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_modificacao` timestamp NULL DEFAULT NULL,
  `data_exclusao` timestamp NULL DEFAULT NULL,
  `status` enum('CRIADO','EM_ANDAMENTO','ENCERRADO') NOT NULL DEFAULT 'CRIADO',
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `unidade_ensino_id` int(11) NOT NULL,
  `etapa_id` int(11) NOT NULL,
  `turno_id` int(11) NOT NULL,
  `pessoa_fisica_regente_id` int(11) DEFAULT NULL,
  `turma_agrupamento_id` int(11) DEFAULT NULL,
  `calendario_id` int(11) NOT NULL,
  `calendario_periodo_id` int(11) DEFAULT NULL,
  `quadro_horario_id` int(11) DEFAULT NULL,
  `disciplina_agrupamento_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `turma_fk0_idx` (`etapa_id`),
  KEY `turma_fk1_idx` (`unidade_ensino_id`),
  KEY `turma_fk2_idx` (`turno_id`),
  KEY `turma_fk3_idx` (`pessoa_fisica_regente_id`),
  KEY `turma_fk4_idx` (`turma_agrupamento_id`),
  KEY `turma_fk5_idx` (`calendario_id`),
  KEY `turma_fk00_idx` (`quadro_horario_id`),
  KEY `turma_fk08_idx` (`calendario_periodo_id`),
  KEY `turma_fk09_idx` (`disciplina_agrupamento_id`),
  CONSTRAINT `turma_fk01` FOREIGN KEY (`etapa_id`) REFERENCES `edu_etapa` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `turma_fk02` FOREIGN KEY (`unidade_ensino_id`) REFERENCES `edu_unidade_ensino` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `turma_fk03` FOREIGN KEY (`turno_id`) REFERENCES `edu_turno` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `turma_fk04` FOREIGN KEY (`pessoa_fisica_regente_id`) REFERENCES `sme_pessoa_fisica` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `turma_fk05` FOREIGN KEY (`turma_agrupamento_id`) REFERENCES `edu_turma_agrupamento` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `turma_fk06` FOREIGN KEY (`calendario_id`) REFERENCES `edu_calendario` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `turma_fk07` FOREIGN KEY (`quadro_horario_id`) REFERENCES `edu_quadro_horario` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `turma_fk08` FOREIGN KEY (`calendario_periodo_id`) REFERENCES `edu_calendario_periodo` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `turma_fk09` FOREIGN KEY (`disciplina_agrupamento_id`) REFERENCES `edu_disciplina_agrupamento` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edu_turma_agrupamento`
--

DROP TABLE IF EXISTS `edu_turma_agrupamento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edu_turma_agrupamento` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_modificacao` timestamp NULL DEFAULT NULL,
  `data_exclusao` timestamp NULL DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `unidade_ensino_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `turma_agrupamento_fk01` (`unidade_ensino_id`),
  CONSTRAINT `turma_agrupamento_fk01` FOREIGN KEY (`unidade_ensino_id`) REFERENCES `edu_unidade_ensino` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edu_turma_disciplina`
--

DROP TABLE IF EXISTS `edu_turma_disciplina`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edu_turma_disciplina` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_modificacao` timestamp NULL DEFAULT NULL,
  `data_exclusao` timestamp NULL DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `turma_id` int(11) NOT NULL,
  `disciplina_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `turma_disciplina_fk01_idx` (`turma_id`),
  KEY `turma_disciplina_fk02_idx` (`disciplina_id`),
  CONSTRAINT `turma_disciplina_fk01` FOREIGN KEY (`turma_id`) REFERENCES `edu_turma` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `turma_disciplina_fk02` FOREIGN KEY (`disciplina_id`) REFERENCES `edu_disciplina` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edu_turma_disciplina_alocacao`
--

DROP TABLE IF EXISTS `edu_turma_disciplina_alocacao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edu_turma_disciplina_alocacao` (
  `turma_disciplina_id` int(11) NOT NULL,
  `alocacao_id` int(11) NOT NULL,
  KEY `disciplina_ofertada_professor_fk01_idx` (`turma_disciplina_id`),
  KEY `disciplina_ofertada_professor_fk02_idx` (`alocacao_id`),
  CONSTRAINT `turma_disciplina_alocacao_fk01` FOREIGN KEY (`turma_disciplina_id`) REFERENCES `edu_turma_disciplina` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `turma_disciplina_alocacao_fk02` FOREIGN KEY (`alocacao_id`) REFERENCES `edu_alocacao` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edu_turma_media`
--

DROP TABLE IF EXISTS `edu_turma_media`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edu_turma_media` (
  `id` int(11) NOT NULL,
  `numero` tinyint(1) NOT NULL,
  `data_abertura` timestamp NULL DEFAULT NULL,
  `data_fechamento` timestamp NULL DEFAULT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_modificacao` timestamp NULL DEFAULT NULL,
  `data_exclusao` timestamp NULL DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `turma_id` int(11) NOT NULL,
  `media_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `turma_media_fk01_idx` (`turma_id`),
  KEY `turma_media_fk02_idx` (`media_id`),
  CONSTRAINT `turma_media_fk01` FOREIGN KEY (`turma_id`) REFERENCES `edu_turma` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `turma_media_fk02` FOREIGN KEY (`media_id`) REFERENCES `edu_media` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edu_turno`
--

DROP TABLE IF EXISTS `edu_turno`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edu_turno` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(45) NOT NULL,
  `inicio` time DEFAULT NULL,
  `termino` time DEFAULT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_modificacao` timestamp NULL DEFAULT NULL,
  `data_exclusao` timestamp NULL DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edu_unidade_ensino`
--

DROP TABLE IF EXISTS `edu_unidade_ensino`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edu_unidade_ensino` (
  `id` int(11) NOT NULL,
  `tipo_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `unidade_ensino_fk02_idx` (`tipo_id`),
  CONSTRAINT `unidade_ensino_fk01` FOREIGN KEY (`id`) REFERENCES `sme_pessoa` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `unidade_ensino_fk02` FOREIGN KEY (`tipo_id`) REFERENCES `edu_tipo_unidade_ensino` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edu_unidade_ensino_curso`
--

DROP TABLE IF EXISTS `edu_unidade_ensino_curso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edu_unidade_ensino_curso` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_modificacao` timestamp NULL DEFAULT NULL,
  `data_exclusao` timestamp NULL DEFAULT NULL,
  `unidade_ensino_id` int(11) NOT NULL,
  `curso_id` int(11) NOT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `unidade_ensino_curso_fk01_idx` (`unidade_ensino_id`),
  KEY `unidade_ensino_curso_fk02_idx` (`curso_id`),
  CONSTRAINT `unidade_ensino_curso_fk01` FOREIGN KEY (`unidade_ensino_id`) REFERENCES `edu_unidade_ensino` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `unidade_ensino_curso_fk02` FOREIGN KEY (`curso_id`) REFERENCES `edu_curso` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edu_uniforme`
--

DROP TABLE IF EXISTS `edu_uniforme`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edu_uniforme` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniforme_numero` varchar(10) COLLATE latin1_general_ci DEFAULT NULL,
  `calcado_numero` varchar(10) COLLATE latin1_general_ci DEFAULT NULL,
  `matricula_id` int(11) NOT NULL DEFAULT '0',
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_modificacao` timestamp NULL DEFAULT NULL,
  `data_exclusao` timestamp NULL DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `matricula_id` (`matricula_id`),
  CONSTRAINT `edu_uniforme_ibfk_1` FOREIGN KEY (`matricula_id`) REFERENCES `edu_matricula` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edu_vaga`
--

DROP TABLE IF EXISTS `edu_vaga`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edu_vaga` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_modificacao` timestamp NULL DEFAULT NULL,
  `data_exclusao` timestamp NULL DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `turma_id` int(11) NOT NULL DEFAULT '0',
  `enturmacao_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `vaga_fk01` (`turma_id`),
  KEY `vaga_fk03` (`enturmacao_id`),
  CONSTRAINT `vaga_fk01` FOREIGN KEY (`turma_id`) REFERENCES `edu_turma` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `vaga_fk03` FOREIGN KEY (`enturmacao_id`) REFERENCES `edu_enturmacao` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edu_vinculo`
--

DROP TABLE IF EXISTS `edu_vinculo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edu_vinculo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(45) NOT NULL,
  `tipo_contrato` enum('EFETIVO','TEMPORARIO','COMISSIONADO') NOT NULL DEFAULT 'EFETIVO',
  `carga_horaria` int(11) NOT NULL,
  `status` enum('ATIVO','AFASTADO','DESLIGADO','CANCELADO') NOT NULL DEFAULT 'ATIVO',
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_modificacao` timestamp NULL DEFAULT NULL,
  `data_exclusao` timestamp NULL DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `pessoa_fisica_funcionario_id` int(11) NOT NULL,
  `instituicao_id` int(11) NOT NULL,
  `cargo_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `vinculo_fk01_idx` (`pessoa_fisica_funcionario_id`),
  KEY `vinculo_fk02_idx` (`instituicao_id`),
  KEY `vincilo_fk03_idx` (`cargo_id`),
  CONSTRAINT `vincilo_fk03` FOREIGN KEY (`cargo_id`) REFERENCES `edu_cargo` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `vinculo_fk01` FOREIGN KEY (`pessoa_fisica_funcionario_id`) REFERENCES `sme_pessoa_fisica` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `vinculo_fk02` FOREIGN KEY (`instituicao_id`) REFERENCES `edu_instituicao` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sme_cidade`
--

DROP TABLE IF EXISTS `sme_cidade`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sme_cidade` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) CHARACTER SET latin1 NOT NULL,
  `sigla` varchar(10) CHARACTER SET latin1 DEFAULT NULL,
  `ibge` int(11) DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `estado_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cidade_fk01_idx` (`estado_id`),
  CONSTRAINT `cidade_fk01` FOREIGN KEY (`estado_id`) REFERENCES `sme_estado` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=5508 DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=50 COMMENT='InnoDB free: 10240 kB; (`estado_id`) REFER `dgp/sme_estado`(';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sme_endereco`
--

DROP TABLE IF EXISTS `sme_endereco`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sme_endereco` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `logradouro` varchar(150) CHARACTER SET latin1 NOT NULL,
  `numero` int(11) DEFAULT NULL,
  `complemento` varchar(50) CHARACTER SET latin1 DEFAULT NULL,
  `referencia` text CHARACTER SET latin1,
  `bairro` varchar(80) CHARACTER SET latin1 DEFAULT NULL,
  `cep` varchar(8) CHARACTER SET latin1 DEFAULT NULL,
  `zona` enum('URBANA','RURAL') DEFAULT NULL,
  `tipo_logradouro` enum('RURAL','URBANO') NOT NULL DEFAULT 'URBANO',
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_modificacao` timestamp NULL DEFAULT NULL,
  `data_exclusao` timestamp NULL DEFAULT NULL,
  `latitude` decimal(9,5) DEFAULT NULL,
  `longitude` decimal(9,5) DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `cidade_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `endereco_fk01_idx` (`cidade_id`),
  CONSTRAINT `endereco_fk01` FOREIGN KEY (`cidade_id`) REFERENCES `sme_cidade` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=122963 DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=196 COMMENT='InnoDB free: 10240 kB; (`cidade_id`) REFER `dgp/sme_cidade`(';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sme_estado`
--

DROP TABLE IF EXISTS `sme_estado`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sme_estado` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) CHARACTER SET latin1 NOT NULL,
  `sigla` varchar(10) CHARACTER SET latin1 DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `pais_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `estado_fk01_idx` (`pais_id`),
  CONSTRAINT `estado_fk01` FOREIGN KEY (`pais_id`) REFERENCES `sme_pais` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=606 COMMENT='InnoDB free: 10240 kB; (`pais_id`) REFER `dgp/sme_pais`(`id`';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sme_estado_civil`
--

DROP TABLE IF EXISTS `sme_estado_civil`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sme_estado_civil` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=2730 COMMENT='InnoDB free: 10240 kB';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sme_necessidade_especial`
--

DROP TABLE IF EXISTS `sme_necessidade_especial`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sme_necessidade_especial` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sme_pais`
--

DROP TABLE IF EXISTS `sme_pais`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sme_pais` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `iso` char(2) DEFAULT NULL,
  `iso3` char(3) DEFAULT NULL,
  `codigo` varchar(45) DEFAULT NULL,
  `nome` varchar(100) CHARACTER SET latin1 NOT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=245 DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=16384 COMMENT='InnoDB free: 10240 kB';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sme_particularidade`
--

DROP TABLE IF EXISTS `sme_particularidade`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sme_particularidade` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `tipo` enum('DEFICIENCIA','TRANSTORNO','SUPERDOTACAO') NOT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sme_pessoa`
--

DROP TABLE IF EXISTS `sme_pessoa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sme_pessoa` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) CHARACTER SET latin1 NOT NULL,
  `apelido` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  `cpf_cnpj` varchar(14) CHARACTER SET latin1 DEFAULT NULL,
  `data_nascimento` date DEFAULT NULL,
  `email` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  `genero` char(1) DEFAULT NULL,
  `inep` varchar(45) DEFAULT NULL,
  `tipo_pessoa` enum('PessoaFisica','PessoaJuridica','Instituicao','UnidadeEnsino') NOT NULL DEFAULT 'PessoaFisica',
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_modificacao` timestamp NULL DEFAULT NULL,
  `data_exclusao` timestamp NULL DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `endereco_id` int(11) DEFAULT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `endereco_id_UNIQUE` (`endereco_id`),
  KEY `usuario_id_UNIQUE` (`usuario_id`),
  KEY `inep_UNIQUE` (`inep`),
  CONSTRAINT `pessoa_fk01` FOREIGN KEY (`endereco_id`) REFERENCES `sme_endereco` (`id`),
  CONSTRAINT `pessoa_fk02` FOREIGN KEY (`usuario_id`) REFERENCES `edu_acesso_usuario` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=101870 DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=114 COMMENT='InnoDB free: 10240 kB; (`endereco_id`) REFER `dgp/sme_endere';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sme_pessoa_fisica`
--

DROP TABLE IF EXISTS `sme_pessoa_fisica`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sme_pessoa_fisica` (
  `id` int(11) NOT NULL,
  `rg_numero` varchar(15) CHARACTER SET latin1 DEFAULT NULL,
  `rg_orgao_expedidor` varchar(10) CHARACTER SET latin1 DEFAULT NULL,
  `rg_data_expedicao` date DEFAULT NULL,
  `pis_pasep` varchar(15) CHARACTER SET latin1 DEFAULT NULL,
  `titulo_eleitor_numero` varchar(15) CHARACTER SET latin1 DEFAULT NULL,
  `titulo_eleitor_secao` varchar(5) CHARACTER SET latin1 DEFAULT NULL,
  `titulo_eleitor_zona` varchar(5) CHARACTER SET latin1 DEFAULT NULL,
  `naturalidade` varchar(100) DEFAULT NULL,
  `nacionalidade` varchar(100) DEFAULT NULL,
  `nacionalidade_tipo` enum('BRASILEIRO','ESTRANGEIRO','ESTRANGEIRO NATURALIZADO') DEFAULT NULL,
  `mae_nome` varchar(255) DEFAULT NULL,
  `pai_nome` varchar(255) DEFAULT NULL,
  `responsavel_nome` varchar(255) DEFAULT NULL,
  `profissao_nome` varchar(255) DEFAULT NULL,
  `profissao_renda` varchar(255) DEFAULT NULL,
  `certidao_nascimento_completa` varchar(32) DEFAULT NULL,
  `certidao_nascimento_data_expedicao` date DEFAULT NULL,
  `carteira_trabalho_numero` varchar(15) DEFAULT NULL,
  `carteira_trabalho_serie` varchar(10) DEFAULT NULL,
  `carteira_trabalho_data_expedicao` date DEFAULT NULL,
  `nis` varchar(45) DEFAULT NULL,
  `cns` varchar(45) DEFAULT NULL,
  `carteira_trabalho_estado_id` int(11) DEFAULT NULL,
  `alfabetizado` tinyint(1) NOT NULL DEFAULT '0',
  `cidade_nascimento_id` int(11) DEFAULT NULL,
  `cidade_titulo_eleitor_id` int(11) DEFAULT NULL,
  `pais_nacionalidade_id` int(11) DEFAULT NULL,
  `estado_civil_id` int(11) DEFAULT NULL,
  `raca_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pessoa_fisica_fk02_idx` (`estado_civil_id`),
  KEY `pessoa_fisica_fk03_idx` (`pais_nacionalidade_id`),
  KEY `pessoa_fisica_fk04_idx` (`cidade_nascimento_id`),
  KEY `pessoa_fisica_fk05_idx` (`carteira_trabalho_estado_id`),
  KEY `pessoa_fisica_fk06_idx` (`raca_id`),
  KEY `pessoa_fisica_fk07_idx` (`cidade_titulo_eleitor_id`),
  CONSTRAINT `pessoa_fisica_fk01` FOREIGN KEY (`id`) REFERENCES `sme_pessoa` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `pessoa_fisica_fk02` FOREIGN KEY (`estado_civil_id`) REFERENCES `sme_estado_civil` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `pessoa_fisica_fk03` FOREIGN KEY (`pais_nacionalidade_id`) REFERENCES `sme_pais` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `pessoa_fisica_fk04` FOREIGN KEY (`cidade_nascimento_id`) REFERENCES `sme_cidade` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `pessoa_fisica_fk05` FOREIGN KEY (`carteira_trabalho_estado_id`) REFERENCES `sme_estado` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `pessoa_fisica_fk06` FOREIGN KEY (`raca_id`) REFERENCES `sme_raca` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `pessoa_fisica_fk07` FOREIGN KEY (`cidade_titulo_eleitor_id`) REFERENCES `sme_cidade` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=133 COMMENT='InnoDB free: 10240 kB; (`estado_civil_id`) REFER `dgp/sme_es';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sme_pessoa_fisica_necessidade_especial`
--

DROP TABLE IF EXISTS `sme_pessoa_fisica_necessidade_especial`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sme_pessoa_fisica_necessidade_especial` (
  `pessoa_fisica_id` int(11) NOT NULL,
  `necessidade_especial_id` int(11) NOT NULL,
  KEY `sme_pessoa_fisica_necessidade_especial_fk0_idx` (`pessoa_fisica_id`),
  KEY `sme_pessoa_fisica_necessidade_especial_fk1_idx` (`necessidade_especial_id`),
  CONSTRAINT `pessoa_fisica_necessidade_especial_fk01` FOREIGN KEY (`pessoa_fisica_id`) REFERENCES `sme_pessoa_fisica` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `pessoa_fisica_necessidade_especial_fk02` FOREIGN KEY (`necessidade_especial_id`) REFERENCES `sme_necessidade_especial` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sme_pessoa_fisica_particularidade`
--

DROP TABLE IF EXISTS `sme_pessoa_fisica_particularidade`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sme_pessoa_fisica_particularidade` (
  `pessoa_fisica_id` int(11) NOT NULL,
  `particularidade_id` int(11) NOT NULL,
  KEY `sme_pessoa_fisica_particularidade_fk0_idx` (`pessoa_fisica_id`),
  KEY `sme_pessoa_fisica_particularidade_fk1_idx` (`particularidade_id`),
  CONSTRAINT `pessoa_fisica_particularidade_fk01` FOREIGN KEY (`pessoa_fisica_id`) REFERENCES `sme_pessoa_fisica` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `pessoa_fisica_particularidade_fk02` FOREIGN KEY (`particularidade_id`) REFERENCES `sme_particularidade` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sme_pessoa_juridica`
--

DROP TABLE IF EXISTS `sme_pessoa_juridica`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sme_pessoa_juridica` (
  `id` int(11) NOT NULL,
  `inscricao_estadual_numero` varchar(12) DEFAULT NULL,
  `inscricao_estadual_uf` char(2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `inscricao_estadual_numero_UNIQUE` (`inscricao_estadual_numero`),
  KEY `pessoa_juridica_fk01_idx` (`id`),
  CONSTRAINT `pessoa_juridica_fk01` FOREIGN KEY (`id`) REFERENCES `sme_pessoa` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=143 COMMENT='InnoDB free: 10240 kB; (`id`) REFER `dgp/sme_pessoa`(`id`); ';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sme_pessoa_telefone`
--

DROP TABLE IF EXISTS `sme_pessoa_telefone`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sme_pessoa_telefone` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `numero` varchar(30) DEFAULT NULL,
  `descricao` varchar(50) DEFAULT NULL,
  `falar_com` varchar(100) DEFAULT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_modificacao` timestamp NULL DEFAULT NULL,
  `data_exclusao` timestamp NULL DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `pessoa_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pessoa_telefone_fk01` (`pessoa_id`),
  CONSTRAINT `pessoa_telefone_fk01` FOREIGN KEY (`pessoa_id`) REFERENCES `sme_pessoa` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=59 COMMENT='InnoDB free: 10240 kB; (`pessoa_id`) REFER `dgp/sme_pessoa`(';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sme_raca`
--

DROP TABLE IF EXISTS `sme_raca`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sme_raca` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome_UNIQUE` (`nome`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-01-02 18:29:00
