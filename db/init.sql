-- Script de inicialización de la base de datos
-- Crear la tabla de usuarios

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar tres registros de prueba
INSERT INTO users (nombre, email) VALUES 
    ('Juan Pérez', 'juan.perez@example.com'),
    ('María García', 'maria.garcia@example.com'),
    ('Carlos López', 'carlos.lopez@example.com');

-- Verificar que los datos se insertaron correctamente
SELECT * FROM users;
