Project Documentation: Ticket Booking System
Project Plan
Scope:
This PHP-based Ticket Booking System enables users to view, reserve, and manage tickets for events. The application connects to a MySQL database to store events, users, and ticket information. It features a user-friendly interface that allows guests to register, log in, and reserve tickets for available events.

Objectives:
User Registration & Login: Allow users to create accounts and log in securely.
Event Management: Provide an easy-to-use interface to browse events and reserve tickets.
Cart Management: Users can add, view, and remove tickets from their cart, then proceed to checkout.
Database Integration: Store all relevant data such as events, users, and tickets in a relational database.
Security: Ensure safe handling of user data and ticket purchases with password encryption and secure session handling.
Initial Design:
The app follows a typical MVC (Model-View-Controller) architecture with a simplified flow:

app.php: Manages database connections and logic for user authentication, ticket reservations, and event management.
events.php: Displays available events and handles ticket reservations.
cart.php: Manages user carts, allowing users to remove tickets and proceed to checkout.
user authentication: Secure login and registration, using hashed passwords.
Database Design: A simple relational schema connecting users, events, and tickets.
Technical Details
File Overview:
app.php: Central file for all backend logic, including database connection, user authentication, ticket reservations, and cart management.
index.php: The homepage or landing page, where users are presented with the option to log in or register.
events.php: Displays a list of available events and allows users to reserve tickets.
cart.php: Shows the user’s cart with the reserved tickets, allows removal of tickets, and facilitates checkout.
cart_styles.css / event_styles.css / styles.css: CSS files to style the cart page, events page, and general layout.
script.js: A JavaScript file for handling any front-end logic such as form validations, dynamic content loading, etc.
database.sql / queries.sql: Contains SQL statements for setting up the database and common queries.
Development Process:
Step 1: Setting Up the Database
We started by designing the MySQL database using a relational model to store user, event, and ticket data.

sql
-- database.sql
CREATE DATABASE ticket_sales;

USE ticket_sales;

-- Creating Users Table
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- Creating Events Table
CREATE TABLE events (
    event_id INT AUTO_INCREMENT PRIMARY KEY,
    event_name VARCHAR(255) NOT NULL,
    event_date DATETIME NOT NULL,
    event_location VARCHAR(255) NOT NULL,
    available_seats INT NOT NULL
);

-- Creating Tickets Table
CREATE TABLE tickets (
    ticket_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    event_id INT,
    seat_number INT NOT NULL,
    payment_status VARCHAR(255) DEFAULT 'pending',
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (event_id) REFERENCES events(event_id)
);

Step 2: Building the Backend Logic
Database Connection: We used PDO (PHP Data Objects) for database interaction due to its security and flexibility in handling queries. This was implemented in app.php.
User Authentication: Implemented secure login and registration forms. We used PHP’s password_hash() function to hash user passwords during registration and password_verify() to check the password during login.
Event Display & Reservation: We displayed events on the events.php page using a simple query that checks for events with available seats. The user can click to reserve a ticket, which is added to the tickets table.
Step 3: Handling Cart and Checkout
The cart.php file was created to display the user’s reserved tickets in a cart format. The user can remove tickets from their cart or proceed to checkout, where the payment status of tickets is updated to "purchased."

Database Design
The database schema is designed to store all necessary information related to the system:

Users Table:
Stores user details for authentication, including username, email, and password. The primary key is user_id.

Events Table:
Contains details about events, including event name, location, date, and available seats.

Tickets Table:
Stores ticket reservations for users, linking the user_id and event_id. It tracks the seat number and payment status.

The relationships between the tables are as follows:

Users to Tickets (One-to-Many): One user can reserve many tickets.
Events to Tickets (One-to-Many): One event can have many tickets reserved.
Example Query (Join for Cart Data):

sql
SELECT t.ticket_id, e.event_name, e.event_date, e.event_location, t.seat_number, t.payment_status
FROM tickets t
JOIN events e ON t.event_id = e.event_id
WHERE t.user_id = :user_id;
Security Measures
Password Hashing: We used PHP’s password_hash() and password_verify() functions to securely handle user passwords.
Session Management: PHP sessions are used to keep track of logged-in users. Session tokens are stored securely to prevent unauthorized access.
SQL Injection Protection: We used prepared statements with PDO to prevent SQL injection attacks.
CSRF Protection: Although not explicitly mentioned in the app, measures could be implemented in future iterations to protect against CSRF attacks by using tokens in forms.
User Guide
Overview:
This application allows you to browse events, reserve tickets, manage your cart, and proceed to checkout for a seamless ticket booking experience.

1. Registering an Account
Navigate to the index.php page.
Fill in your username, email, and password.
Click "Register" to create an account.
2. Logging In
Enter your credentials (username and password).
Click "Login" to access the events page.
3. Viewing Events
On the events.php page, view available events.
Each event shows the number of available seats.
Click "Reserve Ticket" next to an event to add a ticket to your cart.
4. Managing Your Cart
On the cart.php page, view your reserved tickets.
You can remove tickets or proceed to checkout.
Checkout updates the payment status of your tickets.
Problems Faced and Solutions
Login Issues:

Initially, the login process wasn’t working because the password comparison was done incorrectly.
Solution: I used PHP’s password_verify() function to correctly compare the hashed password stored in the database with the input password.
Ticket Reservation Logic:

The reservation logic failed to account for the case where there were no available seats in an event.
Solution: I added a check for available seats before allowing a reservation.
Cart Management:

Users were able to reserve tickets but couldn’t easily remove them.
Solution: I added a feature to remove tickets from the cart and update the tickets table accordingly.
Checkout Process:

The checkout process wasn’t updating the payment status correctly.
Solution: I added a query to update the payment_status of tickets to "purchased" upon checkout.
Conclusion
This project showcases a simple but functional ticket booking system using PHP and MySQL. The key features include user authentication, event browsing, ticket reservation, and cart management, all while focusing on security and usability. Despite some challenges with user login and ticket management, the system is robust and provides a seamless experience for users.

