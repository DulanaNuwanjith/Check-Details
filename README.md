# 🏦 Check-Details Management System

[![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
[![Alpine.js](https://img.shields.io/badge/Alpine.js-8BC0D0?style=for-the-badge&logo=alpine.js&logoColor=white)](https://alpinejs.dev)

A modern, robust, and intuitive **Cheque Management System** built with **Laravel 13**. Designed to streamline the process of tracking cheques, managing bank accounts, and monitoring financial activities with a premium user experience.

---

## ✨ Key Features

- 📑 **Cheque Tracking**: Complete CRUD for cheques with status management and history.
- 🏦 **Bank Account Management**: Manage multiple bank accounts across different companies.
- 📊 **Dynamic Dashboard**: Overview of cheque health and financial summaries.
- 📜 **Activity Logs**: Detailed tracking of all user actions for auditing.
- 👤 **SuperAdmin Controls**: Secure user and role management.
- 🎨 **Modern UI**: Clean, responsive interface powered by Tailwind CSS and Alpine.js.

---

## 🛠️ Technology Stack

| Layer | Technology |
| :--- | :--- |
| **Backend** | [Laravel 13](https://laravel.com) |
| **Frontend** | [Tailwind CSS](https://tailwindcss.com), [Alpine.js](https://alpinejs.dev) |
| **Database** | MySQL / PostgreSQL / SQLite |
| **Asset Bundling** | [Vite](https://vitejs.dev) |
| **Language** | [PHP 8.3+](https://php.net) |

---

## 🚀 Getting Started

Follow these steps to set up the project locally.

### 1. Prerequisites

- PHP `^8.3`
- Composer
- Node.js & NPM
- A database server (MySQL/MariaDB)

### 2. Installation

```bash
# Clone the repository
git clone https://github.com/DulanaNuwanjith/Check-Details.git
cd Check-Details

# Install PHP dependencies
composer install

# Install Frontend dependencies
npm install
npm run build # or 'npm run dev' for development
```

### 3. Environment Configuration

```bash
cp .env.example .env
php artisan key:generate
```
*Make sure to configure your database settings in the `.env` file.*

### 4. Database Setup (CRITICAL)

> [!IMPORTANT]
> **Registration is disabled in this application.** You **MUST** run the migration with the `--seed` flag to create your initial administrative user.

```bash
php artisan migrate --seed
```

### 5. Running the Application

```bash
php artisan serve
```

---

## 🔑 Default Credentials

Once you've seeded your database, use the following credentials to access the system:

- **Email**: `admin@gmail.com`
- **Password**: `12345678`
- **Role**: `SuperAdmin`

---

## 📂 Project Structure

- `app/Http/Controllers`: Backend logic for Cheques, Bank Accounts, and Users.
- `app/Models`: Database models (Cheque, BankAccount, ActivityLog, User).
- `resources/views`: Blade templates for the frontend.
- `routes/web.php`: Application routing.
- `database/seeders`: Seeds for initial setup and test data.

---

## 📄 License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

