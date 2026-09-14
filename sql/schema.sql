-- =====================================================
-- LARPERTO - SCHEMA DO BANCO DE DADOS (MVP)
-- Foco inicial: Posse-GO e região
-- Nomes das tabelas e colunas em português
-- Charset: utf8mb4 | Engine: InnoDB
-- =====================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- -----------------------------------------------------
-- Tabela: usuarios
-- -----------------------------------------------------
CREATE TABLE `usuarios` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(120) NOT NULL,
  `email` VARCHAR(150) NOT NULL,
  `telefone` VARCHAR(20) DEFAULT NULL,
  `senha` VARCHAR(255) NOT NULL,
  `papel` ENUM('usuario', 'admin') NOT NULL DEFAULT 'usuario',
  `status` ENUM('ativo', 'suspenso') NOT NULL DEFAULT 'ativo',
  `criado_em` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `atualizado_em` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_usuarios_email` (`email`),
  KEY `idx_usuarios_status` (`status`),
  KEY `idx_usuarios_papel` (`papel`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Tabela: recuperacao_senha
-- -----------------------------------------------------
CREATE TABLE `recuperacao_senha` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(150) NOT NULL,
  `token` VARCHAR(100) NOT NULL,
  `criado_em` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_recuperacao_email` (`email`),
  KEY `idx_recuperacao_token` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Tabela: categorias
-- -----------------------------------------------------
CREATE TABLE `categorias` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(80) NOT NULL,
  `slug` VARCHAR(100) NOT NULL,
  `icone` VARCHAR(50) DEFAULT NULL,
  `ativo` TINYINT(1) NOT NULL DEFAULT 1,
  `ordem` INT NOT NULL DEFAULT 0,
  `criado_em` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_categorias_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Tabela: cidades
-- -----------------------------------------------------
CREATE TABLE `cidades` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(100) NOT NULL,
  `estado` CHAR(2) NOT NULL DEFAULT 'GO',
  `slug` VARCHAR(120) NOT NULL,
  `ativo` TINYINT(1) NOT NULL DEFAULT 1,
  `criado_em` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_cidades_slug` (`slug`),
  KEY `idx_cidades_estado` (`estado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Tabela: bairros
-- -----------------------------------------------------
CREATE TABLE `bairros` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `cidade_id` INT UNSIGNED NOT NULL,
  `nome` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(120) NOT NULL,
  `criado_em` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_bairros_cidade` (`cidade_id`),
  UNIQUE KEY `uk_bairros_cidade_slug` (`cidade_id`, `slug`),
  CONSTRAINT `fk_bairros_cidade` FOREIGN KEY (`cidade_id`) REFERENCES `cidades` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Tabela: imoveis
-- -----------------------------------------------------
CREATE TABLE `imoveis` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `usuario_id` INT UNSIGNED NOT NULL,
  `categoria_id` INT UNSIGNED NOT NULL,
  `cidade_id` INT UNSIGNED NOT NULL,
  `bairro` VARCHAR(100) DEFAULT NULL,
  `titulo` VARCHAR(180) NOT NULL,
  `slug` VARCHAR(200) NOT NULL,
  `descricao` TEXT NOT NULL,
  `finalidade` ENUM('venda', 'aluguel') NOT NULL,
  `preco` DECIMAL(12,2) NOT NULL,
  `quartos` TINYINT UNSIGNED DEFAULT 0,
  `banheiros` TINYINT UNSIGNED DEFAULT 0,
  `suites` TINYINT UNSIGNED DEFAULT 0,
  `vagas` TINYINT UNSIGNED DEFAULT 0,
  `area_construida` DECIMAL(10,2) DEFAULT NULL,
  `area_terreno` DECIMAL(10,2) DEFAULT NULL,
  `endereco` VARCHAR(255) DEFAULT NULL,
  `caracteristicas` TEXT DEFAULT NULL,
  `status` ENUM('pendente', 'publicado', 'recusado', 'inativo') NOT NULL DEFAULT 'pendente',
  `destaque` TINYINT(1) NOT NULL DEFAULT 0,
  `visualizacoes` INT UNSIGNED NOT NULL DEFAULT 0,
  `nome_contato` VARCHAR(120) DEFAULT NULL,
  `telefone_contato` VARCHAR(20) DEFAULT NULL,
  `motivo_recusa` VARCHAR(255) DEFAULT NULL,
  `criado_em` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `atualizado_em` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `publicado_em` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_imoveis_slug` (`slug`),
  KEY `idx_imoveis_usuario` (`usuario_id`),
  KEY `idx_imoveis_categoria` (`categoria_id`),
  KEY `idx_imoveis_cidade` (`cidade_id`),
  KEY `idx_imoveis_status` (`status`),
  KEY `idx_imoveis_finalidade` (`finalidade`),
  KEY `idx_imoveis_preco` (`preco`),
  KEY `idx_imoveis_destaque` (`destaque`),
  KEY `idx_imoveis_criado` (`criado_em`),
  CONSTRAINT `fk_imoveis_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_imoveis_categoria` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`),
  CONSTRAINT `fk_imoveis_cidade` FOREIGN KEY (`cidade_id`) REFERENCES `cidades` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Tabela: imovel_imagens
-- -----------------------------------------------------
CREATE TABLE `imovel_imagens` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `imovel_id` INT UNSIGNED NOT NULL,
  `arquivo` VARCHAR(255) NOT NULL,
  `principal` TINYINT(1) NOT NULL DEFAULT 0,
  `ordem` INT NOT NULL DEFAULT 0,
  `criado_em` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_imovel_imagens_imovel` (`imovel_id`),
  CONSTRAINT `fk_imovel_imagens_imovel` FOREIGN KEY (`imovel_id`) REFERENCES `imoveis` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Tabela: favoritos
-- -----------------------------------------------------
CREATE TABLE `favoritos` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `usuario_id` INT UNSIGNED NOT NULL,
  `imovel_id` INT UNSIGNED NOT NULL,
  `criado_em` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_favoritos_usuario_imovel` (`usuario_id`, `imovel_id`),
  KEY `idx_favoritos_imovel` (`imovel_id`),
  CONSTRAINT `fk_favoritos_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_favoritos_imovel` FOREIGN KEY (`imovel_id`) REFERENCES `imoveis` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Tabela: denuncias
-- -----------------------------------------------------
CREATE TABLE `denuncias` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `imovel_id` INT UNSIGNED NOT NULL,
  `usuario_id` INT UNSIGNED DEFAULT NULL,
  `motivo` VARCHAR(100) NOT NULL,
  `descricao` TEXT DEFAULT NULL,
  `status` ENUM('pendente', 'resolvida', 'descartada') NOT NULL DEFAULT 'pendente',
  `criado_em` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_denuncias_imovel` (`imovel_id`),
  KEY `idx_denuncias_status` (`status`),
  CONSTRAINT `fk_denuncias_imovel` FOREIGN KEY (`imovel_id`) REFERENCES `imoveis` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_denuncias_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Tabela: espacos_publicidade
-- -----------------------------------------------------
CREATE TABLE `espacos_publicidade` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(80) NOT NULL,
  `posicao` VARCHAR(50) NOT NULL,
  `codigo` TEXT DEFAULT NULL,
  `ativo` TINYINT(1) NOT NULL DEFAULT 1,
  `criado_em` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `atualizado_em` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_espacos_posicao` (`posicao`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Tabela: configuracoes
-- -----------------------------------------------------
CREATE TABLE `configuracoes` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `chave` VARCHAR(100) NOT NULL,
  `valor` TEXT DEFAULT NULL,
  `atualizado_em` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_configuracoes_chave` (`chave`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

-- =====================================================
-- DADOS INICIAIS (SEED)
-- =====================================================

-- Categorias de imóveis
INSERT INTO `categorias` (`nome`, `slug`, `icone`, `ordem`) VALUES
('Casa', 'casa', 'bi-house', 1),
('Apartamento', 'apartamento', 'bi-building', 2),
('Kitnet', 'kitnet', 'bi-door-open', 3),
('Terreno', 'terreno', 'bi-geo', 4),
('Chácara', 'chacara', 'bi-tree', 5),
('Fazenda', 'fazenda', 'bi-tree-fill', 6),
('Ponto Comercial', 'ponto-comercial', 'bi-shop', 7),
('Sala Comercial', 'sala-comercial', 'bi-briefcase', 8),
('Galpão', 'galpao', 'bi-box-seam', 9),
('Outros', 'outros', 'bi-three-dots', 10);

-- Cidade inicial
INSERT INTO `cidades` (`nome`, `estado`, `slug`) VALUES
('Posse', 'GO', 'posse-go');

-- Espaços de publicidade
INSERT INTO `espacos_publicidade` (`nome`, `posicao`, `codigo`, `ativo`) VALUES
('Banner Home Topo', 'home_topo', '<!-- Código Google AdSense ou personalizado -->', 1),
('Banner Home Lateral', 'home_lateral', '<!-- Código Google AdSense ou personalizado -->', 1),
('Banner Página do Imóvel', 'imovel_lateral', '<!-- Código Google AdSense ou personalizado -->', 1),
('Banner Listagem', 'busca_lateral', '<!-- Código Google AdSense ou personalizado -->', 1);

-- Configurações do site LarPerto
INSERT INTO `configuracoes` (`chave`, `valor`) VALUES
('nome_site', 'LarPerto'),
('descricao_site', 'Imóveis para venda e aluguel em Posse-GO e região'),
('email_contato', 'contato@larperto.com.br'),
('whatsapp_contato', '5564999999999'),
('itens_por_pagina', '12'),
('max_imagens_por_imovel', '15'),
('max_tamanho_imagem_mb', '5');

-- Usuário administrador
-- E-mail: admin@larperto.com.br
-- Senha: admin123 (altere depois)
INSERT INTO `usuarios` (`nome`, `email`, `telefone`, `senha`, `papel`, `status`) VALUES
('Administrador', 'admin@larperto.com.br', '64999999999', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'ativo');