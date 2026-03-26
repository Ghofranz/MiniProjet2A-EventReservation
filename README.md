# 🎟️ EventRes — Event Reservation System

![Symfony](https://img.shields.io/badge/Symfony-6.4-black?logo=symfony)
![PHP](https://img.shields.io/badge/PHP-8.4-blue?logo=php)
![Doctrine](https://img.shields.io/badge/Doctrine-ORM-orange)
![Docker](https://img.shields.io/badge/Docker-Containerized-2496ED?logo=docker)
![Status](https://img.shields.io/badge/Status-Completed-brightgreen)

> A full-stack web application for managing event reservations, built with Symfony 6.4 and containerized with Docker.

---
<img width="1920" height="1080" alt="image" src="https://github.com/user-attachments/assets/aff0f065-5676-43be-95c3-38ed9300e75b" />
<img width="1920" height="1080" alt="image" src="https://github.com/user-attachments/assets/c11368b2-4dae-4e03-b627-febdbde31537" />
<img width="1920" height="1080" alt="image" src="https://github.com/user-attachments/assets/e95f3478-fb23-4a8d-93a3-f97f0820c1d4" />

<img width="1920" height="1080" alt="image" src="https://github.com/user-attachments/assets/03d70002-95ba-4be6-85cf-47f8cab51d90" />
<img width="1920" height="1080" alt="image" src="https://github.com/user-attachments/assets/935e79ed-862e-4da8-b2b1-df6653560e96" />
<img width="1920" height="1080" alt="image" src="https://github.com/user-attachments/assets/b68331fb-fadd-4279-9f85-0d228db563bd" />
<img width="1920" height="1080" alt="image" src="https://github.com/user-attachments/assets/559ccc65-fea1-461f-b738-53559fd37303" />
<img width="1920" height="1080" alt="image" src="https://github.com/user-attachments/assets/0e7f0ed0-7831-4e92-8f3a-d755260dd246" />
<img width="1920" height="1080" alt="image" src="https://github.com/user-attachments/assets/84e5f499-90d2-42a7-bf16-8174f71ddb22" />
<img width="1920" height="1080" alt="image" src="https://github.com/user-attachments/assets/d1a254aa-8385-40ae-bc18-e7d4de17c5fd" />
<img width="1920" height="1080" alt="image" src="https://github.com/user-attachments/assets/8e522f24-10bc-4398-8ab2-18d78bd1bbdb" />
<img width="1920" height="1080" alt="image" src="https://github.com/user-attachments/assets/7a5b58cb-cb85-494d-80c2-df249a81cd55" />
<img width="1920" height="1080" alt="image" src="https://github.com/user-attachments/assets/9a65941f-d935-4fff-a112-aa6d1d2fad9a" />
<img width="1920" height="1080" alt="image" src="https://github.com/user-attachments/assets/f8ceb2c7-057a-4c9c-84ae-21e345d34c6b" />
<img width="1920" height="1080" alt="image" src="https://github.com/user-attachments/assets/155075a3-232f-494e-a5db-218739d7be71" />
<img width="1920" height="1080" alt="image" src="https://github.com/user-attachments/assets/0b7a4d49-c5d9-44c6-a102-50723fdbc15c" />

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
- [ ] JWT integration
- [ ] Passkeys (WebAuthn)

---

## 👩‍💻 Author

**Ghofran Zouaghi**  
Software Engineering student | ISSAT Sousse, FIA2-GL
