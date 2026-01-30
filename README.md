# Final-Assessment-Movie-Database
Final assessment
# MovieDB (Task 2) — PHP + MySQL Movie Database

A PHP & MySQL movie database web application built for Task 2.
Includes CRUD (admin), live AJAX search, authentication (admin/user), and TMDB autofill.


## Features
- **Login system** with roles: `admin` and `user`
- **Admin CRUD** for movies: add / edit / delete
- **Read**: movie list + movie details page
- **AJAX Live Search** (title / genre / cast)
- **TMDB API Autofill** (title, year, rating, poster, genre, cast + description/runtime/director if enabled)
- Security:
  - Prepared statements (SQL injection protection)
  - `htmlspecialchars()` output escaping (XSS protection)


## Default Credentials
- **Admin**  
  Username: `admin`  
  Password: `admin123`

- **User**  
  Username: `user`  
  Password: `user123`


## Project Structure
```
assessment/
  public/                # All PHP pages
  assets/                # CSS, JS,
  config/                # db.php, tmdb.php
  includes/              # header.php, footer.php, checkRole.php
  database.sql           # DB structure
```


## Setup (XAMPP)
1) Copy the project folder into:
   `xampp/htdocs/assessment/`

2) Start **Apache** and **MySQL** in XAMPP.

3) Open **phpMyAdmin** → create/import database:
   - Import `database.sql`

4) Update DB connection:
   - File: `config/db.php`
   - Confirm DB name: `movie_db`
   - Confirm user/pass (normally `root` with no password on XAMPP)

5) Open in browser:
   - `http://localhost/assessment/public/index.php`


## TMDB API Setup (optional, for autofill)
1) Get an API key from TMDB.
2) Put the key in:
   - `config/tmdb.php`
3) If TMDB is disabled, manual movie entry still works.


## Notes / Troubleshooting
- If you see session warnings:
  In `config/db.php` use safe session start:
  
  ```php
  if (session_status() === PHP_SESSION_NONE) session_start();
  

