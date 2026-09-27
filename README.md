# RISEN: Journey — PHP Demo

A PHP and MySQL implementation of selected concepts from **RISEN: Journey**, a journey-journaling application I originally developed using React, Vite, Supabase and Capacitor.

This project was created to demonstrate my PHP and relational database development skills by rebuilding selected RISEN functionality using PHP 8, MySQL/MariaDB and PDO.

## About RISEN: Journey

RISEN: Journey is an application for documenting meaningful journeys over time.

A journey is called a **Rise**.

Users can create a Rise and document its development through:

- Moments
- Memories
- Milestones
- Rise Stories

The full RISEN: Journey application is available at:

https://risenapp.io

This repository is a separate PHP demonstration and is not the production RISEN application.

## PHP Demo Features

The demo includes:

- Create, view, edit and delete Rises
- Search saved Rises
- Add, edit and delete Moments
- Upload image Memories
- Edit and delete Memories
- Add, edit and delete Milestones
- Generate a chronological Rise Story
- Combine records from multiple related database tables
- Browser-based PDF export of a Rise Story
- Server-side form processing and validation
- PDO database access
- Prepared SQL statements
- Relational MySQL database design
- Foreign keys and cascading deletion
- File upload handling

## Technology

- PHP 8
- MySQL / MariaDB
- PDO
- HTML5
- CSS
- Apache
- XAMPP

## Database Structure

The application uses four related tables:

### `rises`

Stores the main journey.

### `moments`

Stores everyday entries associated with a Rise.

### `memories`

Stores uploaded images, captions and dates associated with a Rise.

### `milestones`

Stores significant events or achievements associated with a Rise.

Moments, Memories and Milestones reference the parent Rise through `rise_id`.

Foreign-key relationships use `ON DELETE CASCADE`, so related records are removed when their parent Rise is deleted.

## Rise Story

The Rise Story demonstrates working with data from multiple relational tables.

PHP retrieves the Rise's:

- Moments
- Memories
- Milestones

The records are combined and sorted chronologically to create one unified journey timeline.

The story can also be printed or saved as a PDF using the browser's print functionality.

## Local Setup

### Requirements

- PHP 8+
- MySQL or MariaDB
- Apache
- XAMPP or a similar local PHP environment

### Installation

1. Clone or download this repository.

2. Place the project inside your Apache web directory.

For XAMPP:

`C:\xampp\htdocs\risen-php-demo`

3. Start Apache and MySQL.

4. Open phpMyAdmin.

5. Import:

`database.sql`

6. Confirm the database configuration in:

`config/database.php`

The default XAMPP configuration is:

- Host: localhost
- Database: risen_php_demo
- Username: root
- Password: empty

7. Open the project in your browser:

`http://localhost/risen-php-demo/`

## Project Purpose

This project is intentionally a focused PHP implementation rather than a complete recreation of the production RISEN application.

It demonstrates practical experience with:

- PHP application structure
- CRUD operations
- SQL
- relational data
- prepared statements
- server-side validation
- file uploads
- database-driven interfaces
- combining data from multiple tables
- debugging and local development

## Original Product

RISEN: Journey:

https://risenapp.io

The production application and this PHP demonstration use different technology stacks.

## Author

**Khangelani Mpongwana**

Creator and developer of RISEN: Journey
