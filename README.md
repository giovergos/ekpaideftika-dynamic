# Dynamic Educational Website (HTML, PHP, MySQL)

This project is a dynamic educational website designed to support the teaching of HTML and basic web technologies.  
It extends a previously static version of the site by adding full backend functionality using **PHP**, **MySQL**, and **role‑based access control**.

The website allows authenticated users to access course material, announcements, documents, and assignments.  
Users with the **Tutor** role have full management capabilities (CRUD operations), while **Students** have read‑only access.

---

## 🔐 Authentication System

The website includes a secure login system implemented in PHP.  
Only authenticated users can access the site.

Each user stored in the database has the following fields:

- **First name**
- **Last name**
- **Loginame** (email, unique)
- **Password** (stored in plain text for simplicity, as required)
- **Role**: `Tutor` or `Student`

Sessions are used to maintain login state and enforce access restrictions.

---

## 🗄 Database Structure (MySQL)

The project uses a MySQL database with the following tables:

### **1. users**
Stores all registered users.

### **2. announcements**
Contains course announcements with:
- ID
- Date
- Title
- Body text

### **3. documents**
Stores downloadable course documents:
- ID
- Title
- Description
- Filename/path

### **4. homeworks**
Stores assignments:
- ID
- Goals
- Assignment file (filename/path)
- Deliverables
- Deadline

When a new homework is added, the system automatically creates a corresponding announcement.

A full SQL schema is included in `database.sql`.

---

## 🧑‍🏫 Role‑Based Functionality

### **Student**
- View announcements  
- View documents  
- View assignments  
- Use the communication form  

### **Tutor**
Includes all student capabilities **plus**:

- Add / Edit / Delete announcements  
- Add / Edit / Delete documents  
- Add / Edit / Delete assignments  
- Add / Edit / Delete users  
- Automatic announcement creation when a new homework is posted  

All management actions are performed through parameter‑based PHP pages  
(e.g., `announcements.php?action=edit&id=3`).

---

## 📬 Communication System

The communication page includes a web form that sends an email to **all Tutors** using PHP’s `mail()` function.

A direct `mailto:` link is also provided.

---

## 🎨 Layout and Styling

The website uses the same layout as the original static version:

- Left sidebar navigation  
- Main content area  
- Pink color theme  
- Shared header and footer through `header.php` and `footer.php`  
- Styling handled entirely by `style.css`  

---

## 📁 Project Structure

project/
├─ config.php
├─ auth.php
├─ header.php
├─ footer.php
├─ login.php
├─ logout.php
├─ index.php
├─ announcements.php
├─ documents.php
├─ homeworks.php
├─ communication.php
├─ users.php
├─ style.css
└─ database.sql

---

## 🚀 Deployment

This project requires a server that supports:

- PHP 7+  
- MySQL  
- Apache or Nginx  

It can be hosted for free on platforms such as:

- **000webhost**
- **InfinityFree**
- **AwardSpace**

GitHub Pages cannot run PHP/MySQL, but the repository can still be stored on GitHub.

---

## 🎯 Purpose

This dynamic version of the website was created to demonstrate:

- Server‑side programming with PHP  
- Database design and interaction using MySQL  
- User authentication and session handling  
- Role‑based access control  
- CRUD operations for educational content  
- Integration of backend logic into an existing static layout  

---

## 👨‍🏫 Author

Developed as part of an educational project for learning web development with HTML, PHP, and MySQL.
