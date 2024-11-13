CREATE DATABASE comercio
USE comercio;
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    apellido VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    contraseña VARCHAR(255) NOT NULL,
    dni VARCHAR(15),
    telefono VARCHAR(15)
);
USE comercio;
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    shipping_info VARCHAR(255),
    image_url VARCHAR(255),
    category VARCHAR(100),
    brand VARCHAR(100),
    gender VARCHAR(10),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
use comercio;
ALTER TABLE usuarios ADD COLUMN is_admin TINYINT(1) DEFAULT 0;
DESCRIBE usuarios;
use comercio;
INSERT INTO usuarios (nombre, apellido, email, contraseña, dni, telefono, is_admin) 
VALUES ('Admin', 'Super', 'admin@example.com', '2401', '12345678', '123456789', 1);

UPDATE usuarios 
SET contraseña = '$2y$10$eXq3NkOeA4pQdR5Jl6GTIOzptQ/eEqjzpHXHcfPaZGqSuFv7iTiAW' 
WHERE email = 'admin@example.com';




