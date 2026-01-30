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

## Setup.sql
-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 30, 2026 at 12:49 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `movie_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `movies`
--

CREATE TABLE `movies` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `year` int(11) NOT NULL,
  `rating` decimal(3,1) NOT NULL,
  `genre` varchar(100) NOT NULL,
  `cast_members` text NOT NULL,
  `description` text DEFAULT NULL,
  `director` varchar(150) DEFAULT NULL,
  `runtime` int(11) DEFAULT NULL,
  `poster_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `movies`
--

INSERT INTO `movies` (`id`, `title`, `year`, `rating`, `genre`, `cast_members`, `description`, `director`, `runtime`, `poster_path`) VALUES
(6, 'The Godfather', 1972, 8.7, 'Drama, Crime', 'Marlon Brando, Al Pacino, James Caan, Robert Duvall, Richard S. Castellano', NULL, NULL, NULL, '/3bhkrj58Vtu7enYsRolD1fZdja1.jpg'),
(7, 'Inception', 2010, 8.4, 'Action, Science Fiction, Adventure', 'Leonardo DiCaprio, Joseph Gordon-Levitt, Ken Watanabe, Tom Hardy, Elliot Page', NULL, NULL, NULL, '/xlaY2zyzMfkhk0HSC5VUwzoZPU1.jpg'),
(8, 'Interstellar', 2014, 8.5, 'Adventure, Drama, Science Fiction', 'Matthew McConaughey, Anne Hathaway, Michael Caine, Jessica Chastain, Casey Affleck', NULL, NULL, NULL, '/gEU2QniE6E77NI6lCU6MxlNBvIx.jpg'),
(9, 'The Martian', 2015, 7.7, 'Drama, Adventure, Science Fiction', 'Matt Damon, Jessica Chastain, Kristen Wiig, Jeff Daniels, Michael Peña', NULL, NULL, NULL, '/3ndAx3weG6KDkJIRMCi5vXX6Dyb.jpg'),
(10, 'The Dark Knight', 2008, 8.5, 'Action, Crime, Thriller', 'Christian Bale, Heath Ledger, Aaron Eckhart, Michael Caine, Maggie Gyllenhaal', NULL, NULL, NULL, '/qJ2tW6WMUDux911r6m7haRef0WH.jpg'),
(11, 'Spider-Man', 2002, 7.3, 'Action, Science Fiction', 'Tobey Maguire, Willem Dafoe, Kirsten Dunst, James Franco, Cliff Robertson', NULL, NULL, NULL, '/kjdJntyBeEvqm9w97QGBdxPptzj.jpg'),
(12, 'Avatar', 2009, 7.6, 'Action, Adventure, Fantasy, Science Fiction', 'Sam Worthington, Zoe Saldaña, Sigourney Weaver, Stephen Lang, Michelle Rodriguez', NULL, NULL, NULL, '/gKY6q7SjCkAU6FqvqWybDYgUKIF.jpg'),
(13, 'Chhichhore', 2019, 7.7, 'Romance, Comedy, Drama', 'Sushant Singh Rajput, Shraddha Kapoor, Tahir Raj Bhasin, Varun Sharma, Naveen Polishetty', NULL, NULL, NULL, '/cGDPQtQ5igtPMt3oJ6BCAor6dFp.jpg'),
(14, 'Varshangalkku Shesham', 2024, 5.9, 'Drama, Comedy, Romance', 'Dhyan Sreenivasan, Pranav Mohanlal, Nivin Pauly, Kalyani Priyadarshan, Aju Varghese', NULL, NULL, NULL, '/dVLqE9aEszo8yrc8TDk3bloYHHS.jpg'),
(16, 'Sivaji: The Boss', 2007, 7.0, 'Action, Drama', 'Rajinikanth, Vivek, Suman Talwar, Shriya Saran, Manivannan', NULL, NULL, NULL, '/yBvsYnhrrTs1ZgHQNQTSdVA6uo8.jpg'),
(17, 'Coolie', 2025, 6.3, 'Action, Thriller, Crime', 'Rajinikanth, Nagarjuna Akkineni, Soubin Shahir, Upendra, Shruti Haasan', NULL, NULL, NULL, '/kr36awqmziEI5mfUElsHB0pj9zP.jpg'),
(20, 'Oppenheimer', 2023, 8.0, 'Drama, History', 'Cillian Murphy, Emily Blunt, Matt Damon, Robert Downey Jr., Florence Pugh', 'The story of J. Robert Oppenheimer\'s role in the development of the atomic bomb during World War II.', 'Christopher Nolan', 181, '/8Gxv8gSFCU0XGDykEGv7zR1n2ua.jpg'),
(22, 'The Avengers', 2012, 7.9, 'Science Fiction, Action, Adventure', 'Robert Downey Jr., Chris Evans, Mark Ruffalo, Chris Hemsworth, Scarlett Johansson', 'When an unexpected enemy emerges and threatens global safety and security, Nick Fury, director of the international peacekeeping agency known as S.H.I.E.L.D., finds himself in need of a team to pull the world back from the brink of disaster. Spanning the globe, a daring recruitment effort begins!', 'Joss Whedon', 143, '/RYMX2wcKCBAr24UyPD7xwmjaTn.jpg'),
(23, 'Enthiran', 2010, 6.6, 'Action, Science Fiction, Adventure', 'Rajinikanth, Aishwarya Rai Bachchan, Danny Denzongpa, Santhanam, Karunas', 'Dr. Vaseegaran creates Chitti, a powerful robot in his own image, but it is rejected by the scientific body AIRD due to its lack of human behaviour and emotions. After a lightning strike triggers emotions in Chitti, he begins to develop human-like feelings. However, Chitti falls in love with Dr. Vaseegaran\'s fiancée, Sana, and turns against his creator, leading to dangerous consequences.', 'Shankar', 176, '/hai6CSCLxULO1RThjDP3lWAqOtQ.jpg'),
(24, 'Satyaprem Ki Katha', 2023, 6.8, 'Drama, Romance', 'Kartik Aaryan, Kiara Advani, Gajraj Rao, Supriya Pathak, Siddharth Randeria', 'A middle-class boy in Ahmedabad, Satyaprem falling in one-sided love with Katha, who is coping with her breakup with Tapan. Through the journey, they discover each other\'s life and complement in accomplishing what was left halfway.', 'Sameer Vidwans', 146, '/99DkKzMm7SrNw8p6jozCkKmOnTy.jpg'),
(25, 'Ra.One', 2011, 5.7, 'Adventure, Action, Science Fiction', 'Shah Rukh Khan, Arjun Rampal, Kareena Kapoor Khan, Armaan Verma, Tom Wu', 'When the titular antagonist of an action game takes on physical form, it\'s up to the game\'s less powerful protagonist to save the world.', 'Anubhav Sinha', 156, '/qwC3rbgCI0frJpRxVm74Ak3ktpU.jpg'),
(26, 'Dilwale Dulhania Le Jayenge', 1995, 8.5, 'Comedy, Drama, Romance', 'Kajol, Shah Rukh Khan, Amrish Puri, Farida Jalal, Anupam Kher', 'Raj is a rich, carefree, happy-go-lucky second generation NRI. Simran is the daughter of Chaudhary Baldev Singh, who in spite of being an NRI is very strict about adherence to Indian values. Simran has left for India to be married to her childhood fiancé. Raj leaves for India with a mission at his hands, to claim his lady love under the noses of her whole family. Thus begins a saga.', 'Aditya Chopra', 190, '/2CAL2433ZeIihfX1Hb2139CX0pW.jpg'),
(27, 'Dil Se..', 1998, 6.9, 'Drama, Romance', 'Shah Rukh Khan, Manisha Koirala, Preity Zinta, Mita Vashisht, Arundathi Nag', 'Journalist Amar falls for a mysterious woman on an assignment, but she does not reciprocate his feelings. However, when Amar is about to get married, the woman shows up at his doorstep asking for help.', 'Mani Ratnam', 163, '/rCoGw9cp0EY2Rd504iqqjSDtepF.jpg'),
(28, '12th Fail', 2023, 8.0, 'Drama', 'Vikrant Massey, Medha Shankr, Anant Joshi, Anshumaan Pushkar, Priyanshu Chatterjee', 'Based on the true story of IPS officer Manoj Kumar Sharma, 12th Fail sheds limelight on fearlessly embracing the idea of restarting the academic journey despite the setbacks and challenges and reclaiming one\'s destiny at a place where millions of students attempt the world\'s toughest competitive exam: UPSC.', 'Vidhu Vinod Chopra', 146, '/eebUPRI4Z5e1Z7Hev4JZAwMIFkX.jpg'),
(29, 'Meiyazhagan', 2024, 7.9, 'Family, Drama', 'Arvind Swamy, Karthi, Rajkiran, Jayaprakash, Sri Divya', 'Twenty-two years after losing his home, Arulmozhi Varman returns to his native Thanjavur to attend his cousin\'s wedding. Amidst the celebrations, Arul is reintroduced to an upbeat man whom he cannot recall. With the help of the unknown man, Arul reconnects with his past.', 'C. Prem Kumar', 177, '/ngDEH7YqVaMCAD4LpNxRl6ScJnw.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(1, 'admin', '$2y$10$pvdhWxaPKKa5EGcHSnJWZeeas7j3HD1WgepnPdtZzYkzIscxPBdF2', 'admin'),
(2, 'user', '$2y$10$p2fNkH7lfKz9Bcx2pbx0buC4FQ43tTjyUeRkaDGoZSZEckDyIMu/G', 'user'),
(3, 'rehan', '$2y$10$bfGcmseox0xcEPJ2wmfTreny.TcV28nTXVKcup4lgMvbapSkPZPi2', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `movies`
--
ALTER TABLE `movies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `movies`
--
ALTER TABLE `movies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

