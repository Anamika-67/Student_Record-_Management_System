-- Create Database
CREATE DATABASE IF NOT EXISTS student_management;
USE student_management;

-- Create Users Table (for Authentication)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert Default Admin User 
-- Username: admin
-- Password: password123 (hashed using bcrypt)
INSERT INTO users (username, password) VALUES 
('admin', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm');

-- Create Students Table
CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    roll_no VARCHAR(20) NOT NULL UNIQUE,
    course VARCHAR(50) NOT NULL,
    marks DECIMAL(5,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert some dummy data
INSERT INTO students (name, roll_no, course, marks) VALUES
('John Doe', 'CS101', 'Computer Science', 85.50),
('Jane Smith', 'CS102', 'Information Technology', 92.00),
('Alice Johnson', 'CS103', 'Software Engineering', 78.25);
