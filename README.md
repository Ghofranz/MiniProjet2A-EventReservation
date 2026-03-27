# 🎟️ EventRes — Event Reservation System

![Symfony](https://img.shields.io/badge/Symfony-6.4-black?logo=symfony)
![PHP](https://img.shields.io/badge/PHP-8.4-blue?logo=php)
![Doctrine](https://img.shields.io/badge/Doctrine-ORM-orange)
![Docker](https://img.shields.io/badge/Docker-Containerized-2496ED?logo=docker)
![Status](https://img.shields.io/badge/Status-Completed-brightgreen)

> A full-stack web application for managing event reservations, built with Symfony 6.4 and containerized with Docker.

---
<img width="1920" height="1080" alt="image" src="https://github.com/user-attachments/assets/e9f27874-a867-498f-ba77-6b7b2b7b3b9e" />
<img width="1920" height="1080" alt="image" src="https://github.com/user-attachments/assets/4e545dd7-b57a-4eda-9cfb-911559850a1e" />
<img width="1920" height="1080" alt="image" src="https://github.com/user-attachments/assets/cb1183ad-20bf-4356-9c3e-e85172e4bf93" />
<img width="1920" height="1080" alt="image" src="https://github.com/user-attachments/assets/ef60b9fe-4287-43dc-bf87-ed3fdca951cd" />
<img width="1920" height="1080" alt="image" src="https://github.com/user-attachments/assets/4e3b343d-5fdf-4f59-9bc0-2fc76359323d" />
<img width="1920" height="1080" alt="image" src="https://github.com/user-attachments/assets/3df13f88-c9a6-4e4f-8e45-ab999e704629" />
<img width="1917" height="799" alt="image" src="https://github.com/user-attachments/assets/b42b4359-20d4-40bb-a3d9-ef4bf83b188d" />
<img width="1920" height="1080" alt="image" src="https://github.com/user-attachments/assets/1d0c7aa9-91f1-412e-ae33-2ae36e406b75" />
<img width="1920" height="1080" alt="image" src="https://github.com/user-attachments/assets/bd5137db-a527-4231-8e60-f3bd67356afe" />
<img width="1920" height="1080" alt="image" src="https://github.com/user-attachments/assets/f31795f1-a580-428b-948a-08b70162e6c3" />
<img width="1920" height="1080" alt="image" src="https://github.com/user-attachments/assets/1eeab6bc-93ac-4a54-8577-65eda445c2e7" />
<img width="1920" height="1080" alt="image" src="https://github.com/user-attachments/assets/74ad5192-5268-4b70-a15d-8f8a1e562b6d" />
<img width="1920" height="1080" alt="image" src="https://github.com/user-attachments/assets/1bcd4e9c-ef02-4340-bfe1-2855d4ff8a68" />
<img width="1920" height="1080" alt="image" src="https://github.com/user-attachments/assets/2865b73c-e519-4987-8e79-a7a2eb5e16de" />
<img width="1920" height="1080" alt="image" src="https://github.com/user-attachments/assets/4162ded3-5419-4738-ae62-bcc7bf6867d7" />
<img width="1920" height="1080" alt="image" src="https://github.com/user-attachments/assets/0c1054cf-e067-4f76-9678-ac8dcd13b15a" />
<img width="1920" height="1080" alt="image" src="https://github.com/user-attachments/assets/49cba950-483f-456e-9fdb-e7c5d2f9cf10" />

---
## 📌 Overview

**EventRes** is a web-based event reservation system built with Symfony 6.4 following the MVC architecture.  
It allows users to browse events and reserve seats, while administrators manage events and reservations through a secure dashboard with a modern UI inspired by the MiniEvent design system.

---

## ✨ Features

### 👤 Public
- Browse upcoming events with image cards
- View event details (date, location, available seats)
- Reserve a seat via an inline form
- Confirmation feedback with flash messages
- Duplicate reservation prevention per email

### 🔐 Admin
- Secure form-based authentication (ROLE_ADMIN)
- Dashboard with stats (events, reservations, upcoming/past)
- Full CRUD for events with image upload
- View and delete reservations per event or globally
- CSRF protection on all destructive actions

### 🔒 Security Enhancements
- Login throttling (max attempts per minute) to prevent brute-force attacks
- CSRF protection on authentication and critical actions
- Duplicate reservation prevention (same email per event)
- Remember Me authentication support

### 📧 Email Confirmation
- Automatic email confirmation after reservation
- HTML email template with event details
- Mailpit integration for local email testing (no real emails sent in dev)
- Fail-safe email sending (reservation not blocked if email fails)

---

## 🧠 Architecture
```text
Client (Browser)
      ↓
Controller (Symfony)
      ↓
Service Layer (Business Logic)
      ↓
Doctrine ORM
      ↓
Database (MariaDB)
```

---

## 🛠️ Tech Stack

| Layer      | Technology                        |
| ---------- | --------------------------------- |
| Backend    | Symfony 6.4                       |
| Language   | PHP 8.4                           |
| ORM        | Doctrine                          |
| Database   | MariaDB 10.4                      |
| Frontend   | Twig + Custom CSS (MiniEvent UI)  |
| Auth       | Symfony SecurityBundle            |
| Mailer     | Symfony Mailer + Mailpit          |
| Container  | Docker + Docker Compose           |
| Versioning | Git & GitHub                      |

---

## ⚙️ Installation — Standard (Local)
```bash
git clone https://github.com/Ghofranz/MiniProjet2A-EventReservation.git
cd MiniProjet2A-EventReservation

composer install

cp .env .env.local
# Edit DATABASE_URL in .env.local

php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load

symfony server:start
```

Access: `http://127.0.0.1:8000`

---

## 🐳 Installation — Docker
```bash
git clone https://github.com/Ghofranz/MiniProjet2A-EventReservation.git
cd MiniProjet2A-EventReservation

docker-compose up -d --build

docker exec eventres_app php bin/console doctrine:migrations:migrate --no-interaction
docker exec eventres_app php bin/console doctrine:fixtures:load --no-interaction
```

Access: `http://localhost:8080`

### 📬 Mailpit (Email Testing)

Mailpit is included for development email testing.

- Web UI: http://localhost:8025
- SMTP: mailpit:1025

All confirmation emails are captured here instead of being sent externally.

---

## 🔑 Default Credentials

| Role  | Username | Password  |
| ----- | -------- | --------- |
| Admin | admin    | admin123  |


---

## 🗃️ Database Schema

| Entity      | Fields                                           |
| ----------- | ------------------------------------------------ |
| Event       | title, description, date, location, seats, image |
| Reservation | event_id, name, email, phone, created_at         |
| User        | username, password (hashed), roles               |

---

## 🔐 Security & Email Flow

### Reservation Flow
1. User submits reservation form
2. Backend validates:
   - Event availability
   - Event not in the past
   - Duplicate reservation check
3. Reservation is stored in database
4. Confirmation email is generated and sent

### Email System
- Symfony Mailer handles email creation
- Mailpit captures emails in development
- Emails are sent synchronously (no queue) for reliability in dev

---

## 📁 Project Structure
```text
src/
 ├── Controller/
 │   ├── EventController.php
 │   ├── AdminController.php
 │   └── SecurityController.php
 ├── Entity/
 │   ├── Event.php
 │   ├── Reservation.php
 │   └── User.php
 ├── Form/
 │   ├── EventType.php
 │   └── ReservationType.php
 └── Repository/

templates/
 ├── base.html.twig
 ├── event/
 │   ├── index.html.twig
 │   └── show.html.twig
 ├── admin/
 │   ├── base_admin.html.twig
 │   ├── dashboard.html.twig
 │   ├── event/
 │   └── reservation/
 ├── emails/
 │   └── reservation_confirm.html.twig
 └── security/
     └── login.html.twig

public/
 ├── css/minievent.css
 ├── js/minievent.js
 └── uploads/

migrations/
config/
Dockerfile
compose.yaml
```

---

## 🔀 Git Workflow
```text
main         → stable production code
dev          → integration branch
feature/*    → development branches
```

### Tags
| Tag    | Description              |
| ------ | ------------------------ |
| v1.0.0 | Core features (CRUD, Auth) |
| v1.1.0 | UI/UX redesign           |
| v1.2.0 | Docker containerization  |

---

## ✅ Roadmap

- [x] Project setup & Git workflow
- [x] Database configuration (MariaDB)
- [x] Entities & migrations (Event, Reservation, User)
- [x] Public pages (event list, event detail)
- [x] Reservation system with duplicate check
- [x] Authentication (form login, ROLE_ADMIN)
- [x] Admin dashboard with full CRUD
- [x] Image upload for events
- [x] UI/UX redesign (MiniEvent design system)
- [x] Docker setup (PHP 8.4 + Apache + MariaDB)
- [x] Email confirmation system (Mailer + Mailpit)
- [x] Security enhancements (throttling, CSRF, duplicate check)

---

## 👩‍💻 Author

**Ghofran Zouaghi**  
Software Engineering student | ISSAT Sousse, FIA2-GL
