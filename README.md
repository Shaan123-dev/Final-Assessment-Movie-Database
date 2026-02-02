# Final Assessment Movie Database (Task 2)

MovieDB is a PHP and MySQL web application developed for the Final Assessment (Task 2).  
The system allows users to browse movies, while administrators can manage the movie database using full CRUD functionality.

## Login Credentials

### Admin Account
- Username: admin  
- Password: admin123  

### User Account
- Username: user  
- Password: user123  

## Setup Instructions 

## Follow these steps to run the project in herald server:

## Server Requirements
- Herald Server Credentials
- PHP 8.0 or higher
- MySQL / MariaDB Database
- Apache Web Server
- Deployment Steps
- Upload Project Folder into server

## Deployment Steps
- Log in to server by using crendentials
- upload your project folder into server inside public_html/ folder.
- Create MySQL Tables in server

- Go to public_html and write code mysql -p
- Use the defult databse.
- Create Tables using mysql queries.
- Configure Database Connection
- Update the database credentials in: db.php

- run the project in any browser using this link : https://student.heraldcollege.edu.np/~np03cy4s250026/assessment/public/ 

## Follow these steps to run the project locally:
1. Copy the project folder into:

   xampp/htdocs/assessment/

2. Start **Apache** and **MySQL** in the XAMPP Control Panel.

3. Open **phpMyAdmin** and create/import the database:

   - Import the file: `database.sql`

4. Configure database connection:

   File: `config/db.php`

   Ensure:

   - Database name: movie_db  
   - Username: root  
   - Password: (empty by default in XAMPP)

5. Run the application in your browser:

   http://localhost/assessment/public/index.php

## Features Implemented

- User login system with role-based access (Admin and User)
- Admin movie management (CRUD):
  - Add movies
  - Edit movies
  - Delete movies
- Movie listing with posters
- Movie detail page showing:
  - Description
  - Genre
  - Cast
  - Director
  - Runtime
  - Poster
- Live AJAX search by title, genre, and cast
- TMDB API autofill support (optional)
- Security measures:
  - Prepared statements (SQL Injection protection)
  - Output escaping using htmlspecialchars() (XSS protection)
  - CSRF protection for forms

## Known Issues (Future Work)

- TMDB autofill requires a valid API key and internet connection.
- Advanced filtering (rating/year range) is not implemented.
- Request completion notification system is not included.

Final Assessment Submission – Task 2
