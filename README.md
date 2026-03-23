# 🎟️ Event Reservation System

![Symfony](https://img.shields.io/badge/Symfony-6.4-black?logo=symfony)
![PHP](https://img.shields.io/badge/PHP-8.1+-blue?logo=php)
![Doctrine](https://img.shields.io/badge/Doctrine-ORM-orange)
![Status](https://img.shields.io/badge/Status-In%20Progress-yellow)

> A full-stack web application for managing event reservations.

---

## 📌 Overview

This project is a **web-based event reservation system** built with Symfony.  
It allows users to browse events and reserve seats, while administrators manage events and reservations through a secure dashboard.

---

## ✨ Features

### 👤 User

- Login system
- Browse events
- View event details
- Reserve seats
- Confirmation feedback

### 🔐 Admin

- Secure authentication
- Dashboard interface
- Full CRUD for events
- View reservations

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
````

---

## 🛠️ Tech Stack

| Layer      | Technology       |
| ---------- | ---------------- |
| Backend    | Symfony 6.4      |
| Language   | PHP 8.1+         |
| ORM        | Doctrine         |
| Database   | MariaDB          |
| Frontend   | Twig + Bootstrap |
| Versioning | Git & GitHub     |

---

## ⚙️ Installation

```bash
git clone https://github.com/Witchyass/MiniProjet2A-EventReservation-TonNom.git
cd MiniProjet2A-EventReservation-TonNom

composer install

cp .env .env.local
# Configure DATABASE_URL

php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate

symfony server:start
```

---

## 🗃️ Database Schema

| Entity      | Fields                                           |
| ----------- | ------------------------------------------------ |
| Event       | title, description, date, location, seats, image |
| Reservation | event_id, name, email, phone, created_at         |
| User        | username, password_hash                          |
| Admin       | username, password_hash                          |

---

## 📁 Project Structure

```text
src/
 ├── Controller/
 ├── Entity/
 ├── Repository/

templates/
config/
migrations/
```

---

## 🔀 Git Workflow

```text
main     → stable production code
dev      → integration
feature/ → development branches
```

---

## 🚧 Roadmap

- [x] Project setup
- [x] Database configuration
- [ ] Entities & migrations
- [ ] CRUD events
- [ ] Reservation system
- [ ] Authentication
- [ ] JWT integration
- [ ] Passkeys (WebAuthn)
- [ ] Docker setup

---

## 👨‍💻 Author

**Ghofran Zouaghi**
Engineering Student — Software & Systems
