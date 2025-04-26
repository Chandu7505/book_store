# 📚 Bookstore API Project

This is a simple *PHP + MySQL* project that provides a basic *REST API* to manage a bookstore (add, list, update, delete books).  
Frontend is a simple *HTML + JavaScript* app to interact with the API.

---
## 🎬 Video Demo

[Watch the video](https://drive.google.com/file/d/1bGbMBZWfiEoHqpJc87d1-BkujbgXOftj/view?usp=sharing)
---

## ✨ Features

- Add new books 📖

- View all books 📚

- Update book details ✏️

- Delete a book 🗑️

- Search books by title 🔍

- Filter books by author or publication year 📆

- Clean URL routing using .htaccess 🚪

- Frontend client built with HTML 🎨

---


## Installation & Setup Guide
```
git clone https://github.com/your-username/bookstore-api.git
```
- Or just "Download ZIP"

---
### 1. Install Requirements

- Install [XAMPP](https://www.apachefriends.org/index.html)
- Start:
  - Apache server
  - MySQL server (⚡ set MySQL port to *3307* if needed)

---

### 2. Database Setup

Open *phpMyAdmin* or MySQL CLI and run:

```sql
CREATE DATABASE bookstore;
USE bookstore;

CREATE TABLE books (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  author VARCHAR(255) NOT NULL,
  isbn VARCHAR(50) NOT NULL UNIQUE,
  publication_year INT NOT NULL
);
```

- ✅ This creates the bookstore database and books table.
---
### 3. Project Structure
```
bookstore-api/
├── api/
│   ├── config.php
│   ├── db.php
│   ├── BookController.php
│   └── index.php
├── public/
│   └── index.html
├── tests/
│   └── test_books_api.php
├── .htaccess
├── README.md
└── database.sql
└── README.md
```

#### .htaccess

```
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^(.*)$ api/index.php [QSA,L]
```
---
### 4. API Endpoints

| Method | URL        | Description             |
|:------:|:-----------|:-------------------------|
| GET    | `/api/`     | List all books           |
| GET    | `/api/{id}` | Get book by ID           |
| POST   | `/api/  `   | Create new book          |
| PUT    | `/api/{id}` | Update existing book     |
| DELETE | `/api/{id} `| Delete book              |


---

## ⚙️ Running the Project
- Copy the project folder into your XAMPP htdocs/ directory:

```C:\xampp\htdocs\bookstore-api\```

- Start Apache and MySQL servers from XAMPP Control Panel.

- Open your browser and go to:
```
http://localhost/bookstore-api/public/index.html
```
- Now you can add, view, edit, and delete books easily!

  ---

## 🛠 Troubleshooting

- Database Connection Failed
  - Check your db.php credentials (port, username, password).

- DELETE/PUT Methods Not Working
  - Ensure .htaccess file is correctly placed in /api/ and Apache mod_rewrite is enabled.
