CREATE DATABASE IF NOT EXISTS artesanias;
USE artesanias;

-- Crear tabla usuarios
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Si la tabla ya existe y sólo necesitas agregar la columna user_type
ALTER TABLE usuarios ADD COLUMN IF NOT EXISTS user_type VARCHAR(50) NOT NULL;

-- Crear tabla CATEGORIA
CREATE TABLE IF NOT EXISTS CATEGORIA (
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL
);

-- Crear tabla PRODUCTOS con columna estado
CREATE TABLE IF NOT EXISTS PRODUCTOS (
    id_producto INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    tamaño VARCHAR(50) NOT NULL,
    precio DECIMAL(10, 2) NOT NULL,
    cantidad INT NOT NULL,
    id_categoria INT,
    estado VARCHAR(50) DEFAULT 'activo',
    FOREIGN KEY (id_categoria) REFERENCES CATEGORIA(id_categoria) ON DELETE SET NULL
);

-- Crear tabla tickets
CREATE TABLE IF NOT EXISTS tickets (
    id_ticket INT AUTO_INCREMENT PRIMARY KEY,
    nombre_cliente VARCHAR(50) NOT NULL,
    fecha DATE NOT NULL,
    nombre_personal VARCHAR(50) NOT NULL,
    telefono_empresa VARCHAR(10) NOT NULL,
    domicilio_empresa VARCHAR(100) NOT NULL,
    total DECIMAL(10, 2) NOT NULL
);

-- Crear tabla productos_ticket con ON DELETE CASCADE
CREATE TABLE IF NOT EXISTS productos_ticket (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ticket_id INT NOT NULL,
    producto_id INT NOT NULL,
    cantidad INT NOT NULL,
    precio DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (ticket_id) REFERENCES tickets(id_ticket) ON DELETE CASCADE,
    FOREIGN KEY (producto_id) REFERENCES PRODUCTOS(id_producto) ON DELETE CASCADE
);

-- Crear tabla pedidos
CREATE TABLE IF NOT EXISTS pedidos (
    id_clientes INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    apellidos VARCHAR(50) NOT NULL,
    correo VARCHAR(50) NOT NULL UNIQUE,
    telefono VARCHAR(15) NOT NULL UNIQUE,
    negocio VARCHAR(100) NOT NULL,
    pedidos TEXT,
    comentarios TEXT
);

-- Crear tabla proveedores
CREATE TABLE IF NOT EXISTS proveedores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombres VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    telefono VARCHAR(15) NOT NULL,
    correo VARCHAR(100) NOT NULL,
    negocio VARCHAR(100) NOT NULL,
    productos TEXT NOT NULL,
    comentarios TEXT
);
