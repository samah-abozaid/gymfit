-- ============================================
-- GymFit — Database Structure
-- Version : 1.0
-- Date : Avril 2026
-- ============================================

CREATE DATABASE IF NOT EXISTS gymfit
CHARACTER SET utf8
COLLATE utf8_general_ci;

USE gymfit;

-- Table des abonnements
CREATE TABLE IF NOT EXISTS subscriptions (
    id_subscription  INT           AUTO_INCREMENT PRIMARY KEY,
    name             VARCHAR(50)   NOT NULL,
    monthly_price    DECIMAL(10,2) NOT NULL,
    description      TEXT,
    class_access     BOOLEAN       DEFAULT FALSE,
    coaching_access  BOOLEAN       DEFAULT FALSE,
    sauna_access     BOOLEAN       DEFAULT FALSE
);

-- Table des membres
CREATE TABLE IF NOT EXISTS members (
    id_member         INT           AUTO_INCREMENT PRIMARY KEY,
    first_name        VARCHAR(100)  NOT NULL,
    last_name         VARCHAR(100)  NOT NULL,
    email             VARCHAR(150)  UNIQUE NOT NULL,
    password          VARCHAR(255)  NOT NULL,
    phone             VARCHAR(20),
    registration_date DATETIME      DEFAULT CURRENT_TIMESTAMP,
    status            ENUM('active','inactive','pending') DEFAULT 'pending',
    id_subscription   INT,
    FOREIGN KEY (id_subscription) REFERENCES subscriptions(id_subscription)
);

-- Table des cours
CREATE TABLE IF NOT EXISTS courses (
    id_class     INT          AUTO_INCREMENT PRIMARY KEY,
    name         VARCHAR(100) NOT NULL,
    type         VARCHAR(50),
    level        VARCHAR(50),
    coach        VARCHAR(100),
    day          VARCHAR(20),
    start_time   TIME,
    end_time     TIME,
    max_capacity INT          DEFAULT 20
);

-- Table des admins
CREATE TABLE IF NOT EXISTS admins (
    id_admin   INT          AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(100),
    email      VARCHAR(150) UNIQUE NOT NULL,
    password   VARCHAR(255) NOT NULL,
    role       VARCHAR(50)  DEFAULT 'admin',
    created_at DATETIME     DEFAULT CURRENT_TIMESTAMP
);

--===========================================
-- ============================================
-- GymFit — Test Data (Fixtures)
-- Mot de passe de test : password
-- ============================================

USE gymfit;

-- Abonnements
INSERT INTO subscriptions (name, monthly_price, description, class_access, coaching_access, sauna_access)
VALUES
('Basic',   29.00, 'Free gym access to get started.',       FALSE, FALSE, FALSE),
('Premium', 49.00, 'Everything you need to progress fast.', TRUE,  TRUE,  FALSE),
('VIP',     79.00, 'The complete experience, no limits.',   TRUE,  TRUE,  TRUE);

-- Admin
INSERT INTO admins (name, email, password, role)
VALUES
('Karim Mansour', 'admin@gymfit.com',
'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
'admin');

-- Membres de test
INSERT INTO members (first_name, last_name, email, password, phone, status, id_subscription)
VALUES
('Amira', 'Hassan',  'amira@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+20101234567', 'active',  2),
('Sara',  'Khalil',  'sara@test.com',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+20109876543', 'active',  1),
('Omar',  'Mansour', 'omar@test.com',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+20107654321', 'pending', 3);

-- Cours
INSERT INTO courses (name, type, level, coach, day, start_time, end_time, max_capacity)
VALUES
('Yoga Flow',   'Yoga',   'Beginner',     'Sophie Martin', 'Monday',    '07:00:00', '08:00:00', 15),
('Spinning',    'Cardio', 'Intermediate', 'Mehdi Aziz',    'Tuesday',   '08:00:00', '09:00:00', 20),
('Box Fitness', 'Combat', 'Intermediate', 'Ahmed Khalil',  'Wednesday', '18:00:00', '19:00:00', 12),
('CrossFit',    'Cardio', 'Advanced',     'Karim Benali',  'Thursday',  '07:00:00', '08:30:00', 15),
('Zumba',       'Dance',  'Beginner',     'Ana Rodriguez', 'Friday',    '19:00:00', '20:00:00', 25),
('Pilates',     'Yoga',   'Beginner',     'Marie Laurent', 'Saturday',  '10:00:00', '11:00:00', 12);