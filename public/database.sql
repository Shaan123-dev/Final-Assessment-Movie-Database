DROP DATABASE IF EXISTS movie_db;
CREATE DATABASE IF NOT EXISTS movie_db;
USE movie_db;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user'
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE movies (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  year INT NOT NULL,
  rating DECIMAL(3,1) NOT NULL,
  genre VARCHAR(150) NOT NULL,
  cast_members TEXT NOT NULL,

  description TEXT NULL,
  director VARCHAR(150) NULL,
  runtime INT NULL,

  poster_path VARCHAR(255) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Default Admin Account (admin / admin123)
INSERT INTO users (username, password, role) VALUES 
('admin', '$2y$10$zCB9.6gSuPp0ojzeHVmZTeVmoZyGiPoCx7X91Jdz3z3zf.IXq2Zom', 'admin');