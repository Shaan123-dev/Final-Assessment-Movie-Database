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
  
## Limitations
This project successfully meets the Task 2 requirements, however there are a few limitations:

- The CRUD functionality (Add/Edit/Delete) is restricted only to the **Admin** role for security reasons. Normal users can only view the movie database.
- The application currently supports only a basic movie request feature, but it does not include automatic user notifications when requests are completed.
- Advanced filtering options (such as searching by year range, rating range, or multiple genres) are not implemented.
- The TMDB API autofill depends on an active internet connection and a valid API key.
- The system does not currently include CSRF protection, which could be added for stronger form security.

## Future Work / Improvements
In future versions of the Movie Database system, the following improvements could be implemented:

- Add a full notification system so users can see when their movie requests are approved or completed.
- Implement advanced search and filtering features (e.g., by year, rating, director, runtime).
- Add user profile pages with personalised movie recommendations.
- Improve security further by adding CSRF tokens and stronger password policies.
- Allow users to create watchlists or favourite movie collections.
- Enhance the UI with pagination, sorting, and better mobile optimisation.
- Deploy the project on a live server with HTTPS for real-world usage.
