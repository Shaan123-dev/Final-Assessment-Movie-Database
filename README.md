# Final Assessment Movie Database (Task 2)

MovieDB is a PHP and MySQL web application developed for the Final Assessment (Task 2).  
The system allows users to browse movies, while administrators can manage the movie database using full CRUD functionality.

## Webapplication Link: https://student.heraldcollege.edu.np/~np03cy4s250026/assessment/public/ 

## Github Link: https://github.com/Shaan123-dev/Final-Assessment-Movie-Database

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

## OUTPUT
<img width="1919" height="979" alt="image" src="https://github.com/user-attachments/assets/2bc5a47d-eaa3-4736-ac9d-b468d0e2ff47" />
<img width="1919" height="985" alt="image" src="https://github.com/user-attachments/assets/4cf77820-8346-453d-a9b3-d097d503f20a" />

- Admin's Output
<img width="1902" height="988" alt="image" src="https://github.com/user-attachments/assets/8317848f-6b11-4b45-9f85-e9036533cae3" />
<img width="1897" height="988" alt="image" src="https://github.com/user-attachments/assets/93d42f08-1058-4df7-b997-98e4705d38fa" />
<img width="1891" height="983" alt="image" src="https://github.com/user-attachments/assets/46e0e220-f123-4bf1-b4eb-1156ade4b815" />
<img width="1899" height="983" alt="image" src="https://github.com/user-attachments/assets/92eb0061-54d3-42ec-a24f-03ac1ffbe4e8" />
<img width="1897" height="989" alt="image" src="https://github.com/user-attachments/assets/010272c8-9d43-4ec0-97d6-2a5276f940e3" />

- User's Output
<img width="1895" height="986" alt="image" src="https://github.com/user-attachments/assets/734733dd-74d3-4049-871b-99aa17f1c8ab" />
<img width="1894" height="989" alt="image" src="https://github.com/user-attachments/assets/468bbaf8-82a1-412c-b358-3a8da1480bd8" />
<img width="1897" height="987" alt="image" src="https://github.com/user-attachments/assets/d5fce6f1-6ad3-4723-b5bb-eefe503dd0c9" />


## 
Developed by: **Shaan Shrestha**  
student id : **2548925**
Module: **Fullstack- 5CS045*** (Assessment Project)








