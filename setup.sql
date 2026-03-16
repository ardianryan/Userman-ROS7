-- Create Database
CREATE DATABASE IF NOT EXISTS mikrotik_userman;
USE mikrotik_userman;

-- Create Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') NOT NULL DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create Routers table
CREATE TABLE IF NOT EXISTS routers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    ip_address VARCHAR(50) NOT NULL,
    api_user VARCHAR(50) NOT NULL,
    api_pass VARCHAR(50) NOT NULL,
    secret VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert a default admin user (password: admin123)
-- Password uses password_hash() in PHP
INSERT INTO users (username, password, role) VALUES 
('admin', '$2y$10$yO5hW1X33vC/t.rB168iDeA7Z7OIna6B4e2l1H8Gz/.8k.1mZtOo.', 'admin');
