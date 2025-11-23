# PHP URL Shortener

A secure and lightweight URL shortening application built with PHP, MySQL, and Bootstrap. Users can register, log in, submit URLs, and receive unique short redirect links.

## Features
- User authentication (register, login, logout)
- URL shortening with unique short codes
- Redirect handler
- Prepared statements for SQL security
- Bootstrap UI
- `.env` configuration support

## Requirements
- PHP 8.1+
- MySQL 5.7+ or MariaDB
- Composer
- Apache/Nginx or MAMP/XAMPP/LAMP
- mod_rewrite recommended

# URL Shortener — Installation Guide

Complete setup instructions for the PHP URL Shortener application.

---

## 1. System Requirements
- PHP 8.1+
- MySQL 5.7+ or MariaDB
- Composer
- Apache/Nginx or MAMP/XAMPP/LAMP
- mod_rewrite enabled

---

## 2. Clone the Repository
git clone https://github.com/your-username/url-shortener.git
cd url-shortener

## 3. Install Composer Dependencies
composer install

## 4. Create .env File

Create a new file in the project root named .env:

APP_ENV=local
DB_HOST=localhost
DB_NAME=url_shortener
DB_USER=root
DB_PASS=
BASE_URL=http://localhost:8888/url-shortener/public


Adjust values based on your environment.

## 5. Create Database
CREATE DATABASE url_shortener;

## 6. Import Required Tables
CREATE TABLE users (
 id INT AUTO_INCREMENT PRIMARY KEY,
 username VARCHAR(50) NOT NULL,
 email VARCHAR(100) NOT NULL UNIQUE,
 password VARCHAR(255) NOT NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE urls (
 id INT AUTO_INCREMENT PRIMARY KEY,
 user_id INT NOT NULL,
 original_url TEXT NOT NULL,
 short_code VARCHAR(10) UNIQUE DEFAULT NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

## 7. Configure Web Server Document Root

The application must be served from the /public directory.

MAMP Example
Applications/MAMP/htdocs/url-shortener/public
