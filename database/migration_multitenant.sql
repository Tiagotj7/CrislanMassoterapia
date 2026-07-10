-- ===================================================================
-- Tabela de Tenants (clientes da Impact: massoterapeutas, fisioterapeutas etc.)
-- ===================================================================
CREATE TABLE tenants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(60) NOT NULL UNIQUE,           -- usado na URL: /crislan/
    name VARCHAR(150) NOT NULL,                 -- Nome do negócio
    business_type ENUM(
        'massoterapia', 'fisioterapia', 'estetica', 
        'nutricao', 'psicologia', 'outro'
    ) NOT NULL DEFAULT 'outro',
    plan ENUM('trial', 'basic', 'pro') DEFAULT 'trial',
    active TINYINT(1) DEFAULT 1,
    trial_ends_at DATE DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ===================================================================
-- Super Admins (equipe da Impact — gerenciam todos os tenants)
-- ===================================================================
CREATE TABLE super_admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ===================================================================
-- Adiciona tenant_id em todas as tabelas de dados operacionais
-- ===================================================================
ALTER TABLE users        ADD COLUMN tenant_id INT NOT NULL DEFAULT 1, ADD INDEX idx_tenant (tenant_id);
ALTER TABLE clients      ADD COLUMN tenant_id INT NOT NULL DEFAULT 1, ADD INDEX idx_tenant (tenant_id);
ALTER TABLE services     ADD COLUMN tenant_id INT NOT NULL DEFAULT 1, ADD INDEX idx_tenant (tenant_id);
ALTER TABLE appointments ADD COLUMN tenant_id INT NOT NULL DEFAULT 1, ADD INDEX idx_tenant (tenant_id);
ALTER TABLE settings     ADD COLUMN tenant_id INT NOT NULL DEFAULT 1, ADD INDEX idx_tenant (tenant_id);
ALTER TABLE blocked_dates ADD COLUMN tenant_id INT NOT NULL DEFAULT 1, ADD INDEX idx_tenant (tenant_id);
ALTER TABLE blocked_hours ADD COLUMN tenant_id INT NOT NULL DEFAULT 1, ADD INDEX idx_tenant (tenant_id);
ALTER TABLE gallery       ADD COLUMN tenant_id INT NOT NULL DEFAULT 1, ADD INDEX idx_tenant (tenant_id);
ALTER TABLE testimonials  ADD COLUMN tenant_id INT NOT NULL DEFAULT 1, ADD INDEX idx_tenant (tenant_id);
ALTER TABLE logs          ADD COLUMN tenant_id INT DEFAULT NULL, ADD INDEX idx_tenant (tenant_id);

-- Remove a constraint antiga de slot único e recria considerando o tenant
ALTER TABLE appointments DROP INDEX unique_slot;
ALTER TABLE appointments ADD UNIQUE KEY unique_slot_tenant (tenant_id, appointment_date, appointment_time);

ALTER TABLE settings DROP INDEX setting_key;
ALTER TABLE settings ADD UNIQUE KEY unique_setting_per_tenant (tenant_id, setting_key);

ALTER TABLE blocked_dates DROP INDEX unique_date;
ALTER TABLE blocked_dates ADD UNIQUE KEY unique_date_tenant (tenant_id, blocked_date);

ALTER TABLE blocked_hours DROP INDEX unique_datetime;
ALTER TABLE blocked_hours ADD UNIQUE KEY unique_datetime_tenant (tenant_id, blocked_date, blocked_time);

-- ===================================================================
-- Cria o tenant "Crislan" (id=1) representando os dados já existentes
-- ===================================================================
INSERT INTO tenants (id, slug, name, business_type, plan, active) VALUES
(1, 'crislan', 'Crislan Massoterapeuta', 'massoterapia', 'pro', 1);

-- Cria o primeiro super admin (TROCAR senha antes de usar em produção)
-- Gerar hash com password_hash('SUASENHA', PASSWORD_DEFAULT)
-- INSERT INTO super_admins (name, email, password) VALUES ('Impact', 'contato@impact.com', '$2y$10$HASH_AQUI');