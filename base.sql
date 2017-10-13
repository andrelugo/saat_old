-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tempo de Geração: Out 13, 2017 as 07:33 AM
-- Versão do Servidor: 5.1.54
-- Versão do PHP: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Banco de Dados: `penhatv05`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `base`
--

CREATE TABLE IF NOT EXISTS `base` (
  `administrador` varchar(50) NOT NULL DEFAULT '',
  `senha` varchar(20) NOT NULL DEFAULT '',
  `cod_unidade_filial` varchar(10) NOT NULL DEFAULT '',
  `descricao_filial` varchar(50) NOT NULL DEFAULT '',
  `end_filial` varchar(100) NOT NULL DEFAULT '',
  `cliente_exclusivo` varchar(10) NOT NULL DEFAULT '',
  `Observações` text NOT NULL,
  `simples` double(10,4) DEFAULT NULL,
  `icms` double(10,4) DEFAULT NULL,
  `lucro` double(10,4) DEFAULT NULL,
  `perda` double(10,4) DEFAULT NULL,
  `cpmf` double(10,4) DEFAULT NULL,
  `dif_icms` double(10,4) DEFAULT NULL,
  `pasta_backup` varchar(40) NOT NULL DEFAULT '',
  `l1` varchar(100) DEFAULT NULL,
  `l2` varchar(100) DEFAULT NULL,
  `l3` varchar(100) DEFAULT NULL,
  `l4` varchar(100) DEFAULT NULL,
  `l5` varchar(100) DEFAULT NULL,
  `max_item_nota` int(11) NOT NULL DEFAULT '0',
  `ultimo_backup` datetime DEFAULT NULL,
  UNIQUE KEY `cliente_exclusivo` (`cliente_exclusivo`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Tabela para configuração Básica do SAAT (integração e a';

--
-- Extraindo dados da tabela `base`
--

INSERT INTO `base` (`administrador`, `senha`, `cod_unidade_filial`, `descricao_filial`, `end_filial`, `cliente_exclusivo`, `Observações`, `simples`, `icms`, `lucro`, `perda`, `cpmf`, `dif_icms`, `pasta_backup`, `l1`, `l2`, `l3`, `l4`, `l5`, `max_item_nota`, `ultimo_backup`) VALUES
('Fernando', '121314', '2', 'CBD', 'Av. Marg. Dir Tiete', '2', 'Unidade 3 é Jundiaí - Servidor Casa Bahia\r\nCliente 1 é a Casa Bahia', 11.7000, 3.1008, 35.0000, 5.0000, 0.3800, 6.0000, 'f:/cbd/', '', ' "Não são as perdas nem as quedas que fazem nossa vida fracassar,', ' senão a falta de coragem de se levantar e seguir adiante"', '', 'V.M.Samael Aun Weor', 1000, '2012-06-18 17:25:31');
