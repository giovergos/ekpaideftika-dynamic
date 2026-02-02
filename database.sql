CREATE DATABASE IF NOT EXISTS html_site CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE html_site;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    firstname VARCHAR(100) NOT NULL,
    lastname VARCHAR(100) NOT NULL,
    loginame VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('Tutor','Student') NOT NULL
);

CREATE TABLE announcements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    date DATE NOT NULL,
    title VARCHAR(255) NOT NULL,
    body TEXT NOT NULL
);

CREATE TABLE documents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    filename VARCHAR(255) NOT NULL
);

CREATE TABLE homeworks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    goals TEXT NOT NULL,
    filename VARCHAR(255) NOT NULL,
    deliverables TEXT NOT NULL,
    deadline DATE NOT NULL
);

-- Δείγμα χρηστών
INSERT INTO users (firstname, lastname, loginame, password, role) VALUES
('Maria', 'Tutorou', 'tutor@example.com', 'tutor123', 'Tutor'),
('Giorgos', 'Studentis', 'student@example.com', 'student123', 'Student');
