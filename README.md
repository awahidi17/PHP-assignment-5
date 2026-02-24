🛠 SportsPro Technical Support System

This project is a fully functional PHP & MySQL web application built for Assignment 5.
It demonstrates authentication, database integration, CRUD operations, and structured multi-module development.

📌 Project Overview

The SportsPro Technical Support System allows administrators, technicians, and customers to manage products and technical support incidents.

✅ Assignment Tasks Completed
🔐 Authentication System

Admin login with session protection

Technician login with session protection

Customer login with session protection

Role-based access control

Logout functionality

👨‍💼 Administrator Features

Add / Delete Products

Add / Delete Technicians

Search and Update Customers

Create Incidents

Assign Incidents to Technicians

Display Incidents

Safe Product Deletion (prevents deleting products used in database)

👨‍🔧 Technician Features

Login as Technician

View Assigned Incidents

Update Incident Status

👤 Customer Features

Login as Customer

Register Products

Session validation

💻 Technologies Used

PHP

MySQL

PDO (secure prepared statements)

Bootstrap (UI styling)

XAMPP (local server)

Git & GitHub

🗄 Database Setup

Start Apache and MySQL in XAMPP

Open phpMyAdmin

Create a database named:

tech_support

Import the included tech_support.sql file

Make sure the database name matches database.php

▶️ How to Run the Application

Place the project folder inside:

htdocs

Then open in browser:

http://localhost/PHP/SportsPro-main/
🔑 Login Credentials
👨‍💼 Administrator

Username: admin

Password: sesame

👨‍🔧 Technician

Use any technician email from the database:

tng@sportspro.com

cpatel@sportspro.com

Password: sesame

👤 Customer

Use any customer email from the database:

alex@example.com

jamie@example.com

Password: sesame

🧠 Skills Demonstrated

Secure authentication using sessions

Password verification with hashing

Role-based page access control

Foreign key handling and safe deletion

Organized MVC-style folder structure

Clean Bootstrap interface
