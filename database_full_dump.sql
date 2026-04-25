-- Database Schema for Cavari (GemStore)

CREATE DATABASE IF NOT EXISTS cavari;
USE cavari;

-- Users Table
CREATE TABLE IF NOT EXISTS users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(255) DEFAULT 'customer', -- 'admin' or 'customer'
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

-- Password Reset Tokens (Standard Laravel)
CREATE TABLE IF NOT EXISTS password_reset_tokens (
    email VARCHAR(255) PRIMARY KEY,
    token VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL
);

-- Sessions (Standard Laravel)
CREATE TABLE IF NOT EXISTS sessions (
    id VARCHAR(255) PRIMARY KEY,
    user_id BIGINT UNSIGNED NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    payload LONGTEXT NOT NULL,
    last_activity INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Categories Table
CREATE TABLE IF NOT EXISTS categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    description TEXT NULL,
    image VARCHAR(255) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

-- Products Table
CREATE TABLE IF NOT EXISTS products (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_id BIGINT UNSIGNED NOT NULL,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    description TEXT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    gemstone_type VARCHAR(255) NULL,
    images JSON NULL,
    is_featured BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

-- Orders Table
CREATE TABLE IF NOT EXISTS orders (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    status VARCHAR(255) NOT NULL DEFAULT 'pending', -- pending, processing, shipped, completed, cancelled
    total_price DECIMAL(10, 2) NOT NULL,
    shipping_address TEXT NOT NULL,
    payment_status VARCHAR(255) NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Order Items Table
CREATE TABLE IF NOT EXISTS order_items (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id BIGINT UNSIGNED NOT NULL,
    product_id BIGINT UNSIGNED NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL, -- Price at time of purchase
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Reviews Table
CREATE TABLE IF NOT EXISTS reviews (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    product_id BIGINT UNSIGNED NOT NULL,
    rating INT NOT NULL, -- 1-5
    comment TEXT NULL,
    is_approved BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Wishlists Table
CREATE TABLE IF NOT EXISTS wishlists (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    product_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    UNIQUE KEY (user_id, product_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);


-- =============================================
-- SEED DATA (Sample Data)
-- =============================================

-- 1. Users
-- Password is 'password' (hashed)
INSERT INTO users (name, email, password, role, created_at, updated_at) VALUES 
('Admin User', 'admin@example.com', '$2y$12$K.1.1.1.1.1.1.1.1.1.1.1.1.1.1.1.1.1.1.1.1.1.1.1.1', 'admin', NOW(), NOW()),
('John Doe', 'john@example.com', '$2y$12$K.1.1.1.1.1.1.1.1.1.1.1.1.1.1.1.1.1.1.1.1.1.1.1.1', 'customer', NOW(), NOW());

-- 2. Categories
INSERT INTO categories (name, slug, description, image, created_at, updated_at) VALUES
('Rings', 'rings', 'Beautiful rings for every occasion.', 'rings.jpg', NOW(), NOW()),
('Necklaces', 'necklaces', 'Beautiful necklaces for every occasion.', 'necklaces.jpg', NOW(), NOW()),
('Earrings', 'earrings', 'Beautiful earrings for every occasion.', 'earrings.jpg', NOW(), NOW()),
('Bracelets', 'bracelets', 'Beautiful bracelets for every occasion.', 'bracelets.jpg', NOW(), NOW());

-- 3. Products
-- Note: Assuming IDs for categories match insertion order (1, 2, 3, 4)

INSERT INTO products (category_id, name, slug, description, price, stock, gemstone_type, images, is_featured, created_at, updated_at) VALUES
(1, 'Sapphire Engagement Ring', 'sapphire-engagement-ring', 'A stunning blue sapphire ring set in 18k white gold.', 1200.00, 5, 'Sapphire', '["https://via.placeholder.com/400"]', 1, NOW(), NOW()),
(1, 'Gold Band', 'gold-band', 'Classic 24k gold band.', 450.00, 10, 'None', '["https://via.placeholder.com/400"]', 0, NOW(), NOW()),
(2, 'Diamond Pendant', 'diamond-pendant', 'Elegant diamond pendant with silver chain.', 2500.00, 3, 'Diamond', '["https://via.placeholder.com/400"]', 1, NOW(), NOW());
