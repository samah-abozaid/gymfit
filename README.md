
# gymfit
# 🏋️ GymFit — Site web  et mobil for Gym Management

GymFit is a site web&Mobile designed to manage a premium gym.  
It allows administrators to manage members, courses, and subscriptions, while providing a modern interface for users to explore services and their membership.

---

## 🚀 Project Overview

This project is developed as a **final training project in Web & Mobile Development**.

GymFit aims to solve common issues in gym management such as:
- Manual tracking of members
- Lack of digital tools
- Poor visibility of subscriptions and revenue

---

## 🎯 Features (MVP)

### 👤 Public
- View homepage (services, stats, planning)
- Browse courses
- View subscription plans
- Register / Login

### 👥 Member Area
- View profile information
- View active subscription
- Access weekly schedule

### 🛠️ Admin Dashboard
- Secure login
- View KPIs:
  - Active members
  - Monthly courses
  - Active subscriptions
  - Monthly revenue
- Manage members (CRUD)
- Manage courses (CRUD)
- View subscriptions distribution

---

## ⚙️ Tech Stack

### Front-end
- HTML5
- CSS3 (Flexbox / Grid)
- JavaScript (ES6)

### Back-end
- PHP 8 (MVC Architecture)
- MySQL 8
- PDO (secure database access)
- PHP Sessions (authentication)

---

## 🏗️ Architecture

The project follows a **custom MVC architecture**:
---

## 🗄️ Database

Main tables:
- `members`
- `courses`
- `subscriptions`
- `admins`

### Business Rules
- A member can have **only one active subscription**
- Member status: `active`, `inactive`, `pending`
- Courses are **read-only in MVP (no booking)**
- Administrators can manage gym classes and members.
- Revenue is calculated from **active subscriptions only**

---

## 🔐 Security

- Password hashing (bcrypt)
- Prepared statements (PDO)
- CSRF protection
- Session security
- XSS protection (`htmlspecialchars`)

---

