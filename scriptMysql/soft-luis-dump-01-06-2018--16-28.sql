-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 01, 2018 at 09:26 PM
-- Server version: 10.1.30-MariaDB
-- PHP Version: 7.0.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `soft-luis`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_cliente`
--

CREATE TABLE `tb_cliente` (
  `cli_id` int(11) NOT NULL,
  `cli_nome` varchar(80) NOT NULL,
  `cli_cpf_cnpj` varchar(18) DEFAULT NULL,
  `cli_rg_ie` varchar(20) DEFAULT NULL,
  `cli_tel_ddd` varchar(2) DEFAULT NULL,
  `cli_tel_numero` varchar(14) DEFAULT NULL,
  `cli_cel_ddd` varchar(2) DEFAULT NULL,
  `cli_cel_numero` varchar(14) DEFAULT NULL,
  `cli_end_cep` varchar(10) DEFAULT NULL,
  `cli_end_tp_lgr` varchar(10) DEFAULT NULL,
  `cli_end_logradouro` varchar(80) DEFAULT NULL,
  `cli_end_numero` varchar(15) DEFAULT NULL,
  `cli_end_bairro` varchar(80) DEFAULT NULL,
  `cli_end_cidade` varchar(80) DEFAULT NULL,
  `cli_end_estado` varchar(2) DEFAULT NULL,
  `cli_observacao` text,
  `cli_ativo` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tb_cliente`
--

INSERT INTO `tb_cliente` (`cli_id`, `cli_nome`, `cli_cpf_cnpj`, `cli_rg_ie`, `cli_tel_ddd`, `cli_tel_numero`, `cli_cel_ddd`, `cli_cel_numero`, `cli_end_cep`, `cli_end_tp_lgr`, `cli_end_logradouro`, `cli_end_numero`, `cli_end_bairro`, `cli_end_cidade`, `cli_end_estado`, `cli_observacao`, `cli_ativo`) VALUES
(1, 'Leandro Parra', '339.118.538-40', '26527089-3', '19', '3468-3244', '19', '98132-4148', '13.477-708', 'Rua', 'Progresso', '317', 'Jdm Boer', 'Americana', 'SP', '', 1),
(2, 'Carla Parra', '349.641.258-58', '987654321', '99', '8888-8888', '77', '6666-6666', '13450-712', 'Rua', 'José Prando', '135', 'Dona Margarida', 'Santa Bárbara D\'Oeste', 'SP', 'Bloco B, Ap11', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tb_cont_pagar`
--

CREATE TABLE `tb_cont_pagar` (
  `ctp_id` int(11) NOT NULL,
  `ctp_dtvencimento` date NOT NULL,
  `ctp_valor` double NOT NULL,
  `ctp_dtpagamento` date DEFAULT NULL,
  `ctp_valor_pago` double DEFAULT NULL,
  `ctp_fornecedor` varchar(80) DEFAULT NULL,
  `ctp_obs` text,
  `ctp_deletado` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tb_cont_pagar`
--

INSERT INTO `tb_cont_pagar` (`ctp_id`, `ctp_dtvencimento`, `ctp_valor`, `ctp_dtpagamento`, `ctp_valor_pago`, `ctp_fornecedor`, `ctp_obs`, `ctp_deletado`) VALUES
(1, '2018-05-17', 87.99, NULL, NULL, 'Jóias Limeira', NULL, 0),
(3, '2018-05-17', 1.7, '2018-05-18', 1.8, '', 'Observação', 1),
(4, '2018-05-09', 0.8, '2018-05-10', 0.9, 'Fornecedor', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tb_cont_receber`
--

CREATE TABLE `tb_cont_receber` (
  `ctr_id` int(11) NOT NULL,
  `ctr_cli_id` int(11) DEFAULT NULL,
  `ctr_ven_id` int(11) DEFAULT NULL,
  `ctr_vda_id` int(11) DEFAULT NULL,
  `ctr_dtvencimento` date NOT NULL,
  `ctr_valor` double NOT NULL,
  `ctr_dtpagamento` date DEFAULT NULL,
  `ctr_valor_pago` double DEFAULT NULL,
  `ctr_obs` text,
  `ctr_deletado` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tb_cont_receber`
--

INSERT INTO `tb_cont_receber` (`ctr_id`, `ctr_cli_id`, `ctr_ven_id`, `ctr_vda_id`, `ctr_dtvencimento`, `ctr_valor`, `ctr_dtpagamento`, `ctr_valor_pago`, `ctr_obs`, `ctr_deletado`) VALUES
(6, 1, NULL, 5, '2018-05-04', 21.98, NULL, NULL, '', 0),
(10, NULL, 3, NULL, '2018-05-05', 19.99, '2018-05-06', 19.99, '', 1),
(11, 1, NULL, NULL, '2018-05-15', 5.4, NULL, NULL, '', 1),
(12, NULL, 3, NULL, '2018-05-17', 1.99, NULL, NULL, NULL, 0),
(15, NULL, 3, 19, '2018-05-31', 74.85, NULL, NULL, '', 1),
(16, NULL, 3, 18, '2018-05-31', 74.35, NULL, NULL, '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tb_estado`
--

CREATE TABLE `tb_estado` (
  `est_id` int(11) NOT NULL,
  `est_sigla` varchar(2) NOT NULL,
  `est_descricao` varchar(40) NOT NULL,
  `est_codigo` varchar(2) NOT NULL COMMENT 'Códido IBGE'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tb_estado`
--

INSERT INTO `tb_estado` (`est_id`, `est_sigla`, `est_descricao`, `est_codigo`) VALUES
(1, 'AC', 'Acre', '12'),
(2, 'AL', 'Alagoas', '27'),
(3, 'AP', 'Amapá', '16'),
(4, 'AM', 'Amazonas', '13'),
(5, 'BA', 'Bahia', '29'),
(6, 'CE', 'Ceará', '23'),
(7, 'DF', 'Distrito Federal', '53'),
(8, 'ES', 'Espírito Santo', '32'),
(9, 'GO', 'Goiás', '52'),
(10, 'MA', 'Maranhão', '21'),
(11, 'MT', 'Mato Grosso', '51'),
(12, 'MS', 'Mato Grosso do Sul', '50'),
(13, 'MG', 'Minas Gerais', '31'),
(14, 'PA', 'Pará', '15'),
(15, 'PB', 'Paraíba', '25'),
(16, 'PR', 'Paraná', '41'),
(17, 'PE', 'Pernambuco', '26'),
(18, 'PI', 'Piauí', '22'),
(19, 'RJ', 'Rio de Janeiro', '33'),
(20, 'RN', 'Rio Grande do Norte', '24'),
(21, 'RS', 'Rio Grande do Sul', '43'),
(22, 'RO', 'Rondônia', '11'),
(23, 'RR', 'Roraima', '14'),
(24, 'SC', 'Santa Catarina', '42'),
(25, 'SP', 'São Paulo', '35'),
(26, 'SE', 'Sergipe', '28'),
(27, 'TO', 'Tocantins', '17');

-- --------------------------------------------------------

--
-- Table structure for table `tb_menu`
--

CREATE TABLE `tb_menu` (
  `men_id` int(11) NOT NULL,
  `men_descricao` varchar(35) NOT NULL,
  `men_controller` varchar(50) NOT NULL,
  `men_action` varchar(50) NOT NULL,
  `men_vars` varchar(100) DEFAULT NULL,
  `men_id_pai` int(11) DEFAULT NULL,
  `men_ativo` tinyint(1) NOT NULL DEFAULT '1',
  `men_icon` varchar(50) DEFAULT NULL,
  `men_order` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tb_menu`
--

INSERT INTO `tb_menu` (`men_id`, `men_descricao`, `men_controller`, `men_action`, `men_vars`, `men_id_pai`, `men_ativo`, `men_icon`, `men_order`) VALUES
(1, 'Início', 'Start', 'index', NULL, NULL, 1, '<i class=\"icon icon-home\"></i>', 0),
(2, 'Produtos', 'Produto', 'index', NULL, NULL, 1, '<i class=\"icon icon-tasks\"></i>', 1),
(3, 'Clientes', 'Cliente', 'index', NULL, NULL, 1, '<i class=\"icon icon-user\"></i>', 1),
(4, 'Vendedores', 'Vendedor', 'index', NULL, NULL, 1, '<i class=\"icon icon-briefcase\"></i>', 1),
(5, 'Venda', '', '', NULL, NULL, 1, '<i class=\"icon icon-tag\"></i>', 1),
(6, 'Nova Venda', 'Venda', 'incluir', NULL, 5, 1, NULL, 1),
(7, 'Ver Incluídas', 'Venda', 'listaIncluidas', NULL, 5, 1, NULL, 2),
(8, 'Ver Finalizadas', 'Venda', 'listaFinalizadas', NULL, 5, 1, NULL, 3),
(9, 'Recebimentos', 'ContaReceber', 'index', NULL, NULL, 1, '<i class=\"icon icon-money\"></i>', 1),
(10, 'Inventário', 'Produto', 'inventario', NULL, NULL, 1, '<i class=\"icon icon-tasks\"></i>', 1),
(11, 'Pagamentos', 'ContaPagar', 'index', NULL, NULL, 1, '<i class=\"icon icon-credit-card\"></i>', 1),
(12, 'Mostruários', 'Venda', 'indexMostruario', NULL, 5, 1, '<i class=\"icon icon-book\"></i>', 4),
(13, 'Relatórios', 'Relatorio', 'index', NULL, NULL, 1, '<i class=\"icon icon-print\"></i>', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tb_produto`
--

CREATE TABLE `tb_produto` (
  `pro_id` int(11) NOT NULL,
  `pro_descricao` varchar(60) NOT NULL,
  `pro_codigo` varchar(12) DEFAULT NULL,
  `pro_ean` varchar(30) DEFAULT NULL,
  `pro_estoque` int(11) NOT NULL,
  `pro_prec_custo` double NOT NULL DEFAULT '0',
  `pro_prec_venda` double NOT NULL DEFAULT '0',
  `pro_observacao` text,
  `pro_ativo` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tb_produto`
--

INSERT INTO `tb_produto` (`pro_id`, `pro_descricao`, `pro_codigo`, `pro_ean`, `pro_estoque`, `pro_prec_custo`, `pro_prec_venda`, `pro_observacao`, `pro_ativo`) VALUES
(10, 'Teste Leandro', 'C0001', 'null', 5, 10.99, 15.99, '', 1),
(11, 'Teste Leandro 132', 'C0002', 'null', 1, 1.5, 2.5, '', 1),
(12, 'Teste Vagner', 'C147258', '789147896', 1, 3.33, 4.44, '', 1),
(13, 'Teste Vagner 2', 'C1472587', '7891478967', 2, 3.33, 4.44, '', 0),
(15, 'Correntinha de Ouro', 'CR0001', 'null', 3, 5.99, 10.99, '', 1);

--
-- Triggers `tb_produto`
--
DELIMITER $$
CREATE TRIGGER `trig_tb_produto_add_estoque` AFTER INSERT ON `tb_produto` FOR EACH ROW INSERT INTO tb_produto_est_log(pel_pro_id, pel_data, pel_qtde_anterior, pel_qtde_nova, pel_observacao) VALUES (NEW.pro_id, NOW(), 0, NEW.pro_estoque, CONCAT('Cadastro Inicial do produto com estoque ', NEW.pro_estoque))
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trig_tb_produto_edit_estoque` AFTER UPDATE ON `tb_produto` FOR EACH ROW IF NEW.pro_estoque <> OLD.pro_estoque AND @TRIGGER_CHECKS <> FALSE THEN
  INSERT INTO tb_produto_est_log(pel_pro_id, pel_data, pel_qtde_anterior, pel_qtde_nova, pel_observacao) VALUES (NEW.pro_id, NOW(), OLD.pro_estoque, NEW.pro_estoque, CONCAT('Alterado cadastro do estoque do produto para ', NEW.pro_estoque));
END IF
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `tb_produto_est_log`
--

CREATE TABLE `tb_produto_est_log` (
  `pel_id` int(11) NOT NULL,
  `pel_pro_id` int(11) NOT NULL,
  `pel_data` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `pel_qtde_anterior` int(11) NOT NULL,
  `pel_qtde_nova` int(11) NOT NULL,
  `pel_observacao` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tabela com o histórico de alteração de estoque';

--
-- Dumping data for table `tb_produto_est_log`
--

INSERT INTO `tb_produto_est_log` (`pel_id`, `pel_pro_id`, `pel_data`, `pel_qtde_anterior`, `pel_qtde_nova`, `pel_observacao`) VALUES
(2, 15, '2018-04-19 19:19:30', 0, 6, 'Cadastro Inicial do produto com estoque 6'),
(10, 10, '2018-04-19 19:20:16', 9, 8, 'Alterado estoque do produto para 8 por causa da finalização da venda ID 2'),
(11, 15, '2018-04-19 19:20:16', 6, 4, 'Alterado estoque do produto para 4 por causa da finalização da venda ID 2'),
(12, 10, '2018-04-19 19:20:50', 8, 9, 'Alterado estoque do produto para 9 por causa do cancelamento da venda ID 2'),
(13, 15, '2018-04-19 19:20:50', 4, 6, 'Alterado estoque do produto para 6 por causa do cancelamento da venda ID 2'),
(14, 10, '2018-05-30 14:58:26', 5, 0, 'Alterado estoque do produto para 0 por causa da finalização da venda ID 19'),
(15, 11, '2018-05-30 14:58:26', 1, 0, 'Alterado estoque do produto para 0 por causa da finalização da venda ID 19'),
(16, 10, '2018-05-30 15:07:52', 0, 5, 'Alterado estoque do produto para 5 por causa do cancelamento da venda ID 19'),
(17, 11, '2018-05-30 15:07:52', 0, 1, 'Alterado estoque do produto para 1 por causa do cancelamento da venda ID 19'),
(18, 12, '2018-05-30 18:22:40', 6, 1, 'Alterado estoque do produto para 1 por causa da finalização da venda ID 18');

-- --------------------------------------------------------

--
-- Table structure for table `tb_usuario`
--

CREATE TABLE `tb_usuario` (
  `usu_id` int(10) NOT NULL,
  `usu_login` varchar(40) NOT NULL,
  `usu_senha` varchar(40) NOT NULL,
  `usu_nome` varchar(50) DEFAULT NULL,
  `usu_sobrenome` varchar(50) DEFAULT NULL,
  `usu_email` varchar(100) DEFAULT NULL,
  `usu_ativo` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tb_usuario`
--

INSERT INTO `tb_usuario` (`usu_id`, `usu_login`, `usu_senha`, `usu_nome`, `usu_sobrenome`, `usu_email`, `usu_ativo`) VALUES
(1, 'admin', '213299609efe85beef603ede5c10a508', 'Admin', NULL, 'nixlovemi@gmail.com', 1),
(2, 'luis', '14d777febb71c53630e9e843bedbd4d8', 'Luis', 'Truculo', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tb_venda`
--

CREATE TABLE `tb_venda` (
  `vda_id` int(11) NOT NULL,
  `vda_data` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `vda_cli_id` int(11) DEFAULT NULL,
  `vda_usu_id` int(11) NOT NULL,
  `vda_ven_id` int(11) DEFAULT NULL COMMENT 'id do vendedor',
  `vda_tot_itens` int(11) NOT NULL DEFAULT '0' COMMENT 'atualizado p/ trigger',
  `vda_vlr_itens` double DEFAULT '0',
  `vda_status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tb_venda`
--

INSERT INTO `tb_venda` (`vda_id`, `vda_data`, `vda_cli_id`, `vda_usu_id`, `vda_ven_id`, `vda_tot_itens`, `vda_vlr_itens`, `vda_status`) VALUES
(2, '2018-05-02 12:16:15', 1, 1, NULL, 3, 32.8, 3),
(5, '2018-04-30 14:03:33', 1, 1, NULL, 4, 43.96, 1),
(18, '2018-05-25 15:27:44', NULL, 1, 3, 5, 74.35, 2),
(19, '2018-05-25 15:29:09', NULL, 1, 3, 6, 74.85, 3);

--
-- Triggers `tb_venda`
--
DELIMITER $$
CREATE TRIGGER `trig_tb_venda_status_itens_AI` AFTER INSERT ON `tb_venda` FOR EACH ROW UPDATE tb_venda_itens SET vdi_status = NEW.vda_status WHERE vdi_vda_id = NEW.vda_id
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trig_tb_venda_status_itens_AU` AFTER UPDATE ON `tb_venda` FOR EACH ROW IF OLD.vda_status <> NEW.vda_status THEN
  UPDATE tb_venda_itens SET vdi_status = NEW.vda_status WHERE vdi_vda_id = NEW.vda_id;
END IF
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `tb_venda_itens`
--

CREATE TABLE `tb_venda_itens` (
  `vdi_id` int(11) NOT NULL,
  `vdi_vda_id` int(11) NOT NULL,
  `vdi_pro_id` int(11) NOT NULL,
  `vdi_qtde` int(11) NOT NULL,
  `vdi_valor` double NOT NULL,
  `vdi_desconto` double NOT NULL DEFAULT '0',
  `vdi_total` double DEFAULT NULL COMMENT 'atualizado p/ trigger',
  `vdi_status` int(11) NOT NULL DEFAULT '1' COMMENT 'atualizado p/ trigger'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tb_venda_itens`
--

INSERT INTO `tb_venda_itens` (`vdi_id`, `vdi_vda_id`, `vdi_pro_id`, `vdi_qtde`, `vdi_valor`, `vdi_desconto`, `vdi_total`, `vdi_status`) VALUES
(13, 2, 10, 1, 11, 0, 11, 3),
(14, 2, 15, 2, 10.9, 0, 21.8, 3),
(15, 5, 15, 4, 10.99, 0, 43.96, 1),
(25, 18, 12, 5, 14.87, 0, 74.35, 2),
(26, 19, 10, 5, 14.87, 2, 72.35, 3),
(27, 19, 11, 1, 2.5, 0, 2.5, 3);

--
-- Triggers `tb_venda_itens`
--
DELIMITER $$
CREATE TRIGGER `trig_tb_venda_itens_add_total` BEFORE UPDATE ON `tb_venda_itens` FOR EACH ROW SET NEW.vdi_total = (NEW.vdi_qtde * NEW.vdi_valor) - NEW.vdi_desconto
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trig_tb_venda_itens_edit_total` BEFORE INSERT ON `tb_venda_itens` FOR EACH ROW SET NEW.vdi_total = (NEW.vdi_qtde * NEW.vdi_valor) - NEW.vdi_desconto
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trig_tb_venda_itens_vda_totais_AD` AFTER DELETE ON `tb_venda_itens` FOR EACH ROW BEGIN
    DECLARE vTotItens INT;
    DECLARE vVlrItens DOUBLE;

    SET vTotItens = (SELECT COALESCE(SUM(`vdi_qtde`), 0) FROM `tb_venda_itens` WHERE `vdi_vda_id` = OLD.vdi_vda_id);

    SET vVlrItens = (SELECT COALESCE(SUM(`vdi_total`), 0) FROM `tb_venda_itens` WHERE `vdi_vda_id` = OLD.vdi_vda_id);

    UPDATE tb_venda SET vda_tot_itens = vTotItens, vda_vlr_itens = vVlrItens WHERE vda_id = OLD.vdi_vda_id;
  END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trig_tb_venda_itens_vda_totais_AI` AFTER INSERT ON `tb_venda_itens` FOR EACH ROW BEGIN
    DECLARE vTotItens INT;
    DECLARE vVlrItens DOUBLE;

    SET vTotItens = (SELECT COALESCE(SUM(`vdi_qtde`), 0) FROM `tb_venda_itens` WHERE `vdi_vda_id` = NEW.vdi_vda_id);

    SET vVlrItens = (SELECT COALESCE(SUM(`vdi_total`), 0) FROM `tb_venda_itens` WHERE `vdi_vda_id` = NEW.vdi_vda_id);

    UPDATE tb_venda SET vda_tot_itens = vTotItens, vda_vlr_itens = vVlrItens WHERE vda_id = NEW.vdi_vda_id;
  END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trig_tb_venda_itens_vda_totais_AU` AFTER UPDATE ON `tb_venda_itens` FOR EACH ROW BEGIN
    /* totais da venda */
    DECLARE vTotItens INT;
    DECLARE vVlrItens DOUBLE;
    DECLARE vEstoque INT;

    SET vTotItens = (SELECT COALESCE(SUM(`vdi_qtde`), 0) FROM `tb_venda_itens` WHERE `vdi_vda_id` = NEW.vdi_vda_id);

    SET vVlrItens = (SELECT COALESCE(SUM(`vdi_total`), 0) FROM `tb_venda_itens` WHERE `vdi_vda_id` = NEW.vdi_vda_id);

    IF OLD.vdi_qtde <> NEW.vdi_qtde OR OLD.vdi_valor <> NEW.vdi_valor OR OLD.vdi_desconto <> NEW.vdi_desconto THEN
      UPDATE tb_venda SET vda_tot_itens = vTotItens, vda_vlr_itens = vVlrItens WHERE vda_id = NEW.vdi_vda_id;
    END IF;
    /* =============== */
    
    /* estoque */
    IF OLD.vdi_status = 1 AND NEW.vdi_status = 2 THEN /*incluido p/ finalizado*/
      SET @TRIGGER_CHECKS = FALSE; /*disabilitar a trigger do produto*/

      SET vEstoque = (SELECT pro_estoque FROM tb_produto WHERE pro_id = NEW.vdi_pro_id);
    
      UPDATE tb_produto SET pro_estoque = pro_estoque - NEW.vdi_qtde WHERE pro_id = NEW.vdi_pro_id;
      
      INSERT INTO tb_produto_est_log(pel_pro_id, pel_data, pel_qtde_anterior, pel_qtde_nova, pel_observacao) VALUES (NEW.vdi_pro_id, NOW(), vEstoque, (vEstoque - NEW.vdi_qtde), CONCAT('Alterado estoque do produto para ', (vEstoque - NEW.vdi_qtde), ' por causa da finalização da venda ID ', NEW.vdi_vda_id));

    ELSEIF OLD.vdi_status = 2 AND NEW.vdi_status = 3 THEN /*finalizado p/ cancelado*/
    
      SET @TRIGGER_CHECKS = FALSE; /*disabilitar a trigger do produto*/

      SET vEstoque = (SELECT pro_estoque FROM tb_produto WHERE pro_id = NEW.vdi_pro_id);
    
      UPDATE tb_produto SET pro_estoque = pro_estoque + NEW.vdi_qtde WHERE pro_id = NEW.vdi_pro_id;
      
      INSERT INTO tb_produto_est_log(pel_pro_id, pel_data, pel_qtde_anterior, pel_qtde_nova, pel_observacao) VALUES (NEW.vdi_pro_id, NOW(), vEstoque, (vEstoque + NEW.vdi_qtde), CONCAT('Alterado estoque do produto para ', (vEstoque + NEW.vdi_qtde), ' por causa do cancelamento da venda ID ', NEW.vdi_vda_id));

    END IF;
    /* ======= */
  END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `tb_venda_mostruario`
--

CREATE TABLE `tb_venda_mostruario` (
  `vdm_id` int(11) NOT NULL,
  `vdm_ven_id` int(11) NOT NULL,
  `vdm_dtentrega` date NOT NULL,
  `vdm_dtacerto` date DEFAULT NULL,
  `vdm_deletado` tinyint(1) NOT NULL DEFAULT '0',
  `vdm_vda_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tb_venda_mostruario`
--

INSERT INTO `tb_venda_mostruario` (`vdm_id`, `vdm_ven_id`, `vdm_dtentrega`, `vdm_dtacerto`, `vdm_deletado`, `vdm_vda_id`) VALUES
(2, 3, '2018-05-14', NULL, 0, 19);

-- --------------------------------------------------------

--
-- Table structure for table `tb_venda_mostruario_itens`
--

CREATE TABLE `tb_venda_mostruario_itens` (
  `vmi_id` int(11) NOT NULL,
  `vmi_vdm_id` int(11) NOT NULL,
  `vmi_pro_id` int(11) NOT NULL,
  `vmi_qtde` int(11) NOT NULL,
  `vmi_valor` double NOT NULL,
  `vmi_desconto` double DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tb_venda_mostruario_itens`
--

INSERT INTO `tb_venda_mostruario_itens` (`vmi_id`, `vmi_vdm_id`, `vmi_pro_id`, `vmi_qtde`, `vmi_valor`, `vmi_desconto`) VALUES
(7, 2, 12, 5, 14.87, 0),
(8, 2, 15, 2, 17, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tb_venda_mostruario_itens_ret`
--

CREATE TABLE `tb_venda_mostruario_itens_ret` (
  `vmir_id` int(11) NOT NULL,
  `vmir_vdm_id` int(11) NOT NULL,
  `vmir_pro_id` int(11) NOT NULL,
  `vmir_qtde` int(11) NOT NULL,
  `vmir_valor` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tb_venda_mostruario_itens_ret`
--

INSERT INTO `tb_venda_mostruario_itens_ret` (`vmir_id`, `vmir_vdm_id`, `vmir_pro_id`, `vmir_qtde`, `vmir_valor`) VALUES
(16, 2, 15, 2, 17);

-- --------------------------------------------------------

--
-- Table structure for table `tb_venda_status`
--

CREATE TABLE `tb_venda_status` (
  `vds_id` int(11) NOT NULL,
  `vds_status` varchar(25) NOT NULL,
  `vds_ativo` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tb_venda_status`
--

INSERT INTO `tb_venda_status` (`vds_id`, `vds_status`, `vds_ativo`) VALUES
(1, 'Incluída', 1),
(2, 'Finalizada', 1),
(3, 'Cancelada', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tb_vendedor`
--

CREATE TABLE `tb_vendedor` (
  `ven_id` int(11) NOT NULL,
  `ven_nome` varchar(80) NOT NULL,
  `ven_cpf_cnpj` varchar(18) DEFAULT NULL,
  `ven_rg_ie` varchar(20) DEFAULT NULL,
  `ven_tel_ddd` varchar(2) DEFAULT NULL,
  `ven_tel_numero` varchar(14) DEFAULT NULL,
  `ven_cel_ddd` varchar(2) DEFAULT NULL,
  `ven_cel_numero` varchar(14) DEFAULT NULL,
  `ven_end_cep` varchar(10) DEFAULT NULL,
  `ven_end_tp_lgr` varchar(10) DEFAULT NULL,
  `ven_end_logradouro` varchar(80) DEFAULT NULL,
  `ven_end_numero` varchar(15) DEFAULT NULL,
  `ven_end_bairro` varchar(80) DEFAULT NULL,
  `ven_end_cidade` varchar(80) DEFAULT NULL,
  `ven_end_estado` varchar(2) DEFAULT NULL,
  `ven_observacao` text,
  `ven_ativo` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tb_vendedor`
--

INSERT INTO `tb_vendedor` (`ven_id`, `ven_nome`, `ven_cpf_cnpj`, `ven_rg_ie`, `ven_tel_ddd`, `ven_tel_numero`, `ven_cel_ddd`, `ven_cel_numero`, `ven_end_cep`, `ven_end_tp_lgr`, `ven_end_logradouro`, `ven_end_numero`, `ven_end_bairro`, `ven_end_cidade`, `ven_end_estado`, `ven_observacao`, `ven_ativo`) VALUES
(3, 'Vendedor Teste', '377.645.258-71', '123456', '11', '2222-2222', '33', '4444*4444', '13460-000', 'Avenida', 'Carlos Rosenfeld', '185', 'Cachoeira', 'Nova Odessa', 'SP', 'Teste!\r\n123', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_cliente`
--
ALTER TABLE `tb_cliente`
  ADD PRIMARY KEY (`cli_id`);

--
-- Indexes for table `tb_cont_pagar`
--
ALTER TABLE `tb_cont_pagar`
  ADD PRIMARY KEY (`ctp_id`);

--
-- Indexes for table `tb_cont_receber`
--
ALTER TABLE `tb_cont_receber`
  ADD PRIMARY KEY (`ctr_id`),
  ADD KEY `fk_ctr_cli_id` (`ctr_cli_id`),
  ADD KEY `fk_ctr_vda_id` (`ctr_vda_id`),
  ADD KEY `fk_ctr_ven_id` (`ctr_ven_id`);

--
-- Indexes for table `tb_estado`
--
ALTER TABLE `tb_estado`
  ADD PRIMARY KEY (`est_id`),
  ADD UNIQUE KEY `uk_est_sigla` (`est_sigla`);

--
-- Indexes for table `tb_menu`
--
ALTER TABLE `tb_menu`
  ADD PRIMARY KEY (`men_id`);

--
-- Indexes for table `tb_produto`
--
ALTER TABLE `tb_produto`
  ADD PRIMARY KEY (`pro_id`),
  ADD UNIQUE KEY `uk_pro_descricao` (`pro_descricao`);

--
-- Indexes for table `tb_produto_est_log`
--
ALTER TABLE `tb_produto_est_log`
  ADD PRIMARY KEY (`pel_id`),
  ADD KEY `fk_pel_pro_id` (`pel_pro_id`);

--
-- Indexes for table `tb_usuario`
--
ALTER TABLE `tb_usuario`
  ADD PRIMARY KEY (`usu_id`),
  ADD UNIQUE KEY `usu_id` (`usu_id`),
  ADD UNIQUE KEY `uk_usu_login` (`usu_login`);

--
-- Indexes for table `tb_venda`
--
ALTER TABLE `tb_venda`
  ADD PRIMARY KEY (`vda_id`),
  ADD KEY `fk_vda_cli_id` (`vda_cli_id`),
  ADD KEY `fk_vda_status` (`vda_status`),
  ADD KEY `fk_vda_ven_id` (`vda_ven_id`),
  ADD KEY `fk_vda_usu_id` (`vda_usu_id`);

--
-- Indexes for table `tb_venda_itens`
--
ALTER TABLE `tb_venda_itens`
  ADD PRIMARY KEY (`vdi_id`),
  ADD KEY `fk_vdi_pro_id` (`vdi_pro_id`),
  ADD KEY `fk_vdi_vda_id` (`vdi_vda_id`),
  ADD KEY `fk_vdi_status` (`vdi_status`);

--
-- Indexes for table `tb_venda_mostruario`
--
ALTER TABLE `tb_venda_mostruario`
  ADD PRIMARY KEY (`vdm_id`),
  ADD KEY `vdm_ven_id` (`vdm_ven_id`),
  ADD KEY `vdm_vda_id` (`vdm_vda_id`);

--
-- Indexes for table `tb_venda_mostruario_itens`
--
ALTER TABLE `tb_venda_mostruario_itens`
  ADD PRIMARY KEY (`vmi_id`),
  ADD KEY `vmi_vdm_id` (`vmi_vdm_id`),
  ADD KEY `vmi_pro_id` (`vmi_pro_id`);

--
-- Indexes for table `tb_venda_mostruario_itens_ret`
--
ALTER TABLE `tb_venda_mostruario_itens_ret`
  ADD PRIMARY KEY (`vmir_id`),
  ADD KEY `vmir_vdm_id` (`vmir_vdm_id`),
  ADD KEY `vmir_pro_id` (`vmir_pro_id`);

--
-- Indexes for table `tb_venda_status`
--
ALTER TABLE `tb_venda_status`
  ADD PRIMARY KEY (`vds_id`);

--
-- Indexes for table `tb_vendedor`
--
ALTER TABLE `tb_vendedor`
  ADD PRIMARY KEY (`ven_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_cliente`
--
ALTER TABLE `tb_cliente`
  MODIFY `cli_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tb_cont_pagar`
--
ALTER TABLE `tb_cont_pagar`
  MODIFY `ctp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tb_cont_receber`
--
ALTER TABLE `tb_cont_receber`
  MODIFY `ctr_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `tb_estado`
--
ALTER TABLE `tb_estado`
  MODIFY `est_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `tb_menu`
--
ALTER TABLE `tb_menu`
  MODIFY `men_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tb_produto`
--
ALTER TABLE `tb_produto`
  MODIFY `pro_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `tb_produto_est_log`
--
ALTER TABLE `tb_produto_est_log`
  MODIFY `pel_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `tb_usuario`
--
ALTER TABLE `tb_usuario`
  MODIFY `usu_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tb_venda`
--
ALTER TABLE `tb_venda`
  MODIFY `vda_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `tb_venda_itens`
--
ALTER TABLE `tb_venda_itens`
  MODIFY `vdi_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `tb_venda_mostruario`
--
ALTER TABLE `tb_venda_mostruario`
  MODIFY `vdm_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tb_venda_mostruario_itens`
--
ALTER TABLE `tb_venda_mostruario_itens`
  MODIFY `vmi_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tb_venda_mostruario_itens_ret`
--
ALTER TABLE `tb_venda_mostruario_itens_ret`
  MODIFY `vmir_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `tb_venda_status`
--
ALTER TABLE `tb_venda_status`
  MODIFY `vds_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tb_vendedor`
--
ALTER TABLE `tb_vendedor`
  MODIFY `ven_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_cont_receber`
--
ALTER TABLE `tb_cont_receber`
  ADD CONSTRAINT `fk_ctr_cli_id` FOREIGN KEY (`ctr_cli_id`) REFERENCES `tb_cliente` (`cli_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ctr_vda_id` FOREIGN KEY (`ctr_vda_id`) REFERENCES `tb_venda` (`vda_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ctr_ven_id` FOREIGN KEY (`ctr_ven_id`) REFERENCES `tb_vendedor` (`ven_id`) ON UPDATE CASCADE;

--
-- Constraints for table `tb_produto_est_log`
--
ALTER TABLE `tb_produto_est_log`
  ADD CONSTRAINT `fk_pel_pro_id` FOREIGN KEY (`pel_pro_id`) REFERENCES `tb_produto` (`pro_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tb_venda`
--
ALTER TABLE `tb_venda`
  ADD CONSTRAINT `fk_vda_cli_id` FOREIGN KEY (`vda_cli_id`) REFERENCES `tb_cliente` (`cli_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_vda_status` FOREIGN KEY (`vda_status`) REFERENCES `tb_venda_status` (`vds_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_vda_usu_id` FOREIGN KEY (`vda_usu_id`) REFERENCES `tb_usuario` (`usu_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_vda_ven_id` FOREIGN KEY (`vda_ven_id`) REFERENCES `tb_vendedor` (`ven_id`) ON UPDATE CASCADE;

--
-- Constraints for table `tb_venda_itens`
--
ALTER TABLE `tb_venda_itens`
  ADD CONSTRAINT `fk_vdi_pro_id` FOREIGN KEY (`vdi_pro_id`) REFERENCES `tb_produto` (`pro_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_vdi_status` FOREIGN KEY (`vdi_status`) REFERENCES `tb_venda_status` (`vds_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_vdi_vda_id` FOREIGN KEY (`vdi_vda_id`) REFERENCES `tb_venda` (`vda_id`) ON UPDATE CASCADE;

--
-- Constraints for table `tb_venda_mostruario`
--
ALTER TABLE `tb_venda_mostruario`
  ADD CONSTRAINT `tb_venda_mostruario_ibfk_1` FOREIGN KEY (`vdm_ven_id`) REFERENCES `tb_vendedor` (`ven_id`),
  ADD CONSTRAINT `tb_venda_mostruario_ibfk_2` FOREIGN KEY (`vdm_vda_id`) REFERENCES `tb_venda` (`vda_id`);

--
-- Constraints for table `tb_venda_mostruario_itens`
--
ALTER TABLE `tb_venda_mostruario_itens`
  ADD CONSTRAINT `tb_venda_mostruario_itens_ibfk_1` FOREIGN KEY (`vmi_vdm_id`) REFERENCES `tb_venda_mostruario` (`vdm_id`),
  ADD CONSTRAINT `tb_venda_mostruario_itens_ibfk_2` FOREIGN KEY (`vmi_pro_id`) REFERENCES `tb_produto` (`pro_id`);

--
-- Constraints for table `tb_venda_mostruario_itens_ret`
--
ALTER TABLE `tb_venda_mostruario_itens_ret`
  ADD CONSTRAINT `tb_venda_mostruario_itens_ret_ibfk_1` FOREIGN KEY (`vmir_vdm_id`) REFERENCES `tb_venda_mostruario` (`vdm_id`),
  ADD CONSTRAINT `tb_venda_mostruario_itens_ret_ibfk_2` FOREIGN KEY (`vmir_pro_id`) REFERENCES `tb_produto` (`pro_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
