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

-- Seed data (password di-hash dengan PHP password_hash bcrypt)
-- admin123  -> hash
-- user123   -> hash
INSERT INTO users (username, password, role) VALUES
  ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
  ('user1', '$2y$10$TKh8H1.PfbuNi73AdLnrV.855.ohVX.mSBEa6VGNvQ3F7F5xV.tAm', 'user'),
  ('user2', '$2y$10$TKh8H1.PfbuNi73AdLnrV.855.ohVX.mSBEa6VGNvQ3F7F5xV.tAm', 'user');
