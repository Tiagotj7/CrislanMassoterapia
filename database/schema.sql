-- Banco de dados: Crislan Massoterapeuta
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin','staff') DEFAULT 'admin',
  active TINYINT(1) DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE clients (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  phone VARCHAR(20) NOT NULL,
  email VARCHAR(150) DEFAULT NULL,
  notes TEXT DEFAULT NULL,
  total_appointments INT DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_phone (phone)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE services (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  description TEXT DEFAULT NULL,
  duration_minutes INT NOT NULL DEFAULT 60,
  price DECIMAL(10,2) NOT NULL DEFAULT 0,
  image VARCHAR(255) DEFAULT NULL,
  active TINYINT(1) DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE appointments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  client_id INT NOT NULL,
  service_id INT NOT NULL,
  appointment_date DATE NOT NULL,
  appointment_time TIME NOT NULL,
  status ENUM('pendente','confirmado','concluido','cancelado') DEFAULT 'pendente',
  notes TEXT DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE,
  FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE RESTRICT,
  UNIQUE KEY unique_slot (appointment_date, appointment_time),
  INDEX idx_date (appointment_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE settings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  setting_key VARCHAR(100) NOT NULL UNIQUE,
  setting_value TEXT DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE blocked_dates (
  id INT AUTO_INCREMENT PRIMARY KEY,
  blocked_date DATE NOT NULL,
  reason VARCHAR(255) DEFAULT NULL,
  UNIQUE KEY unique_date (blocked_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE blocked_hours (
  id INT AUTO_INCREMENT PRIMARY KEY,
  blocked_date DATE NOT NULL,
  blocked_time TIME NOT NULL,
  reason VARCHAR(255) DEFAULT NULL,
  UNIQUE KEY unique_datetime (blocked_date, blocked_time)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE gallery (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(150) DEFAULT NULL,
  image VARCHAR(255) NOT NULL,
  order_position INT DEFAULT 0,
  active TINYINT(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE testimonials (
  id INT AUTO_INCREMENT PRIMARY KEY,
  client_name VARCHAR(150) NOT NULL,
  comment TEXT NOT NULL,
  rating TINYINT DEFAULT 5,
  active TINYINT(1) DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE logs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT DEFAULT NULL,
  action VARCHAR(255) NOT NULL,
  description TEXT DEFAULT NULL,
  ip_address VARCHAR(45) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Configurações iniciais
INSERT INTO settings (setting_key, setting_value) VALUES
('site_name', 'Crislan Massoterapeuta'),
('whatsapp', '5599999999999'),
('instagram', 'https://instagram.com/crislanmassoterapeuta'),
('address', 'Endereço completo aqui'),
('google_maps_embed', ''),
('opening_time', '08:00'),
('closing_time', '19:00'),
('lunch_start', '12:00'),
('lunch_end', '13:30'),
('works_sunday', '0'),
('slot_interval_minutes', '60'),
('auto_message', 'Obrigado por agendar conosco!');

-- Serviço de exemplo
INSERT INTO services (name, description, duration_minutes, price, active) VALUES
('Massagem Esportiva', 'Indicada para atletas, foco em recuperação muscular.', 60, 150.00, 1);

-- Usuário admin padrão (gerar hash com password_hash('SUASENHA', PASSWORD_DEFAULT))
-- INSERT INTO users (name, email, password) VALUES ('Crislan', 'admin@crislan.com', '$2y$10$HASH_AQUI');