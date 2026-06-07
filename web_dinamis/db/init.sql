-- ============================================
-- SEED DATABASE: app_db
-- Auto-loaded by MariaDB via docker-entrypoint-initdb.d
-- ============================================

CREATE DATABASE IF NOT EXISTS app_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE app_db;

-- Tabel users
CREATE TABLE IF NOT EXISTS users (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  username   VARCHAR(64) NOT NULL UNIQUE,
  password   VARCHAR(255) NOT NULL,
  role       ENUM('admin', 'user') DEFAULT 'user',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Seed data (MD5)
-- admin123  -> MD5
-- user123   -> MD5
INSERT INTO users (username, password, role) VALUES
  ('admin', MD5('admin123'), 'admin'),
  ('user1', MD5('user123'), 'user'),
  ('user2', MD5('user123'), 'user');