-- Database setup for SQL Injection Demo
-- Create database (run this in phpMyAdmin or MySQL command line)

CREATE DATABASE IF NOT EXISTS injection_demo;
USE injection_demo;

-- Users table for vulnerable system
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    role VARCHAR(20) DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Users table for secure system (with hashed passwords)
CREATE TABLE secure_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    role VARCHAR(20) DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert sample data for vulnerable system (plain text passwords)
INSERT INTO users (username, password, email, role) VALUES
('admin', 'admin123', 'admin@example.com', 'admin'),
('user', 'user123', 'user@example.com', 'user'),
('john_doe', 'password123', 'john@example.com', 'user'),
('jane_smith', 'qwerty456', 'jane@example.com', 'user');

-- Insert sample data for secure system (hashed passwords)
-- Password hashes for: admin123, user123, password123, qwerty456
INSERT INTO secure_users (username, password_hash, email, role) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@example.com', 'admin'),
('user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user@example.com', 'user'),
('john_doe', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'john@example.com', 'user'),
('jane_smith', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'jane@example.com', 'user');

-- Additional table for demonstration purposes
CREATE TABLE sensitive_data (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    secret_info VARCHAR(255),
    confidential_data TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

INSERT INTO sensitive_data (user_id, secret_info, confidential_data) VALUES
(1, 'Admin Secret Key: ADM-2024-SECRET', 'This is confidential admin information that should not be accessible.'),
(2, 'User Personal Data', 'User private information and personal details.'),
(3, 'John Credit Card: 1234-5678-9012-3456', 'Johns personal financial information.'),
(4, 'Jane Social Security: 123-45-6789', 'Janes sensitive personal identification.');

-- Create a logs table for command injection demonstration
CREATE TABLE system_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50),
    action VARCHAR(100),
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ip_address VARCHAR(45),
    user_agent TEXT
);
