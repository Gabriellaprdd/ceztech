# CEZTech — Recruitment & Job Application System

## Overview

CEZTech is a recruitment website where users can browse job openings and submit Expressions of Interest (EOIs). Managers have a separate dashboard to review applications, search for candidates, and update their application status.

I built this project to practise developing a database-driven website, focusing on form validation, user authentication, and handling application records.

---

# System Architecture

The website uses PHP for backend processing, JavaScript for frontend interactions, and TiDB Cloud for storing application data.

## 1. Frontend — HTML, CSS & JavaScript

The frontend handles navigation, job listings, and application forms.

JavaScript is used for:

- Form validation
- Passing job references between pages
- Saving partially completed form details
- Managing interactive UI components

When a user selects a job, its reference number is saved in browser storage and automatically filled into the application form.

## 2. Backend — PHP

PHP handles server-side processing and database communication.

Responsibilities:

- Validating application submissions
- Saving applicant records
- Managing authentication and sessions
- Processing manager actions
- Retrieving and updating applications

## 3. Database — TiDB Cloud

The system uses two main tables:

### EOI

Stores applicant information, job references, skills, and application status.

### Manager

Stores manager account details used for authentication.

Each application receives an automatically generated EOI number and one of three statuses: `New`, `Current`, or `Final`.

---

# Design Decisions

## Why use both JavaScript and PHP validation?

JavaScript was used to give users immediate feedback when filling out the form.

However, frontend validation alone is not enough because it can be bypassed.

PHP checks the submitted information again before saving it to the database.

This required maintaining validation rules in two places, but it helped prevent invalid submissions from relying only on browser checks.

## Why use PHP instead of a framework?

I wanted to understand how backend processing works without relying on a framework.

Using plain PHP gave me more experience with form handling, sessions, database queries, and request processing.

The downside is that the code can become harder to maintain as the application grows.

## Why use browser storage?

Browser storage was used to remember selected job references and partially completed form information.

This makes it easier for applicants to continue filling out the form without starting over.

However, storing personal details in the browser also means privacy needs to be considered, especially on shared devices.

---

# Main Features

## Job Listings

Visitors can browse available positions and select a role they are interested in.

The selected job reference is automatically carried over to the application form.

## Expression of Interest Form

Applicants can submit information including:

- Personal details
- Contact information
- Address
- Skills
- Job reference number

The form checks required fields and validates input before submission.

## Manager Authentication

Managers can register, log in, and access a dashboard using PHP sessions.

Newly registered manager accounts use password hashing.

## Application Management

The manager dashboard allows users to:

- View applicant records
- Search and filter applications
- Sort results
- Update application status
- Delete applications by job reference

## Dashboard Statistics

Managers can view application counts based on their status, making it easier to see how many applications are new, current, or final.

---

# Development Challenges

## 1. Passing Job References Between Pages

One challenge was connecting the careers page with the application form.

Since users select a job before opening the form, the selected reference needed to be carried over correctly.

Using browser storage allowed the form to retrieve the selected reference automatically.

## 2. Handling Form Validation

The application form contains several fields with different validation requirements.

For example, the system checks email formats, dates of birth, and whether Australian postcodes match the selected state.

Making these checks work on both the frontend and backend required more attention than simply creating the form.

## 3. Managing Applicant Records

The manager dashboard needed to handle searching, sorting, and updating records without allowing unexpected database changes.

Prepared SQL statements were used for user-provided values, while sorting options were restricted to predefined database columns.

---

# Testing and Results

The implementation includes validation for:

- Missing required fields
- Invalid email addresses
- Incorrect phone number formats
- Mismatched states and postcodes
- Invalid application status changes
- Unsuccessful database submissions

All **14 PHP files passed PHP syntax validation** using `php -l`.

The system uses two main database tables and three application statuses.

Automated integration tests and production performance benchmarks were not included in the supplied project files.

One area that still needs attention is manager account security. Although new passwords are hashed, the login code has a plain-text compatibility fallback, and manager registration is publicly accessible. Both should be addressed before production use.

---

# Technical Skills Gained

Through this project, I gained experience in:

- PHP backend development
- HTML, CSS, and JavaScript
- Form validation
- MySQL database integration
- CRUD operations
- Session management
- Password hashing
- Prepared SQL statements
- Applicant data management
- Responsive web design

---

# Lessons Learned

This project taught me that building a form involves more than just designing the interface. The data needs to be checked properly, stored correctly, and made easy to manage afterwards.

I also learned how frontend and backend validation work together, and why both are important when handling user-submitted information.

---

# Future Improvements

- Restrict manager account registration
- Remove the plain-text password fallback
- Clear saved applicant details after submission
- Store job openings in the database
- Add automated testing
- Improve backend code organisation
- Complete the documented Docker setup

---

# Technologies Used

- PHP 8.3
- HTML
- CSS
- JavaScript
- MySQLi
- TiDB Cloud
- Apache

## Deploying the profile picture

The About page uses the bundled `images/photo.png` file, so no environment variable is needed to show the picture. Make sure the `images/` directory is committed and included in your deployment. File names are case-sensitive on Linux hosts: `photo.png` is not the same as `Photo.png`.

If deploying from GitHub, commit and push both `about.php` and `images/photo.png`, then redeploy the site. If deploying the source ZIP directly, deploy the contents of the `ceztech` folder as the PHP application root (or use the supplied `Dockerfile` from that folder).
