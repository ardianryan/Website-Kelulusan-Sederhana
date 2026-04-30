-- SQL Setup for Kelulusan SMAN 1 Sooko
-- Database: MariaDB 10.6

CREATE DATABASE IF NOT EXISTS kelulusan_sman1sooko;
USE kelulusan_sman1sooko;

-- Admin table
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Student table
CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nisn VARCHAR(255) NOT NULL UNIQUE,
    nipd VARCHAR(50),
    nama VARCHAR(100) NOT NULL,
    jk VARCHAR(10) NOT NULL,
    tempat_lahir VARCHAR(100),
    tanggal_lahir VARCHAR(50),
    rombel VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    lulus BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Settings table
CREATE TABLE IF NOT EXISTS settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    `key` VARCHAR(50) NOT NULL UNIQUE,
    `value` TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Default Settings
INSERT IGNORE INTO settings (`key`, `value`) VALUES ('school_name', 'SMA Negeri 1 Sooko');
INSERT IGNORE INTO settings (`key`, `value`) VALUES ('welcome_text', 'Selamat Datang di Portal Pengumuman Kelulusan');
INSERT IGNORE INTO settings (`key`, `value`) VALUES ('meta_description', 'Portal Resmi Pengumuman Kelulusan Siswa SMA Negeri 1 Sooko Tahun Pelajaran 2025/2026.');
INSERT IGNORE INTO settings (`key`, `value`) VALUES ('skl_info', 'Pengambilan SKL dapat dilakukan pada 5 Mei 2026');
INSERT IGNORE INTO settings (`key`, `value`) VALUES ('countdown_date', '2026-05-05 07:00:00');

-- Insert Default Admin (username: admin, password: admin)
INSERT IGNORE INTO admins (username, password) VALUES ('admin', '$2y$10$BiE3omqwQy2KaDF/7ZkkNuc16g55cK4krhB84M8in3iLnAx.G/rce');
