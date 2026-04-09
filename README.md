# Task Manager

A simple and clean task management system built for the Qtec Solution Limited Full Stack Laravel Developer assessment. It allows users to create, manage, and track tasks with status and priority.

---

## Tech Stack

- Laravel 11  
- Blade Templates  
- Tailwind CSS  
- SQLite (development) / MySQL (production)  
- PHPUnit  

---

## Setup Instructions

```bash
# Clone the repository
git clone https://github.com/your-username/task-manager.git
cd task-manager

# Install dependencies
composer install
npm install && npm run build

# Environment setup
cp .env.example .env
php artisan key:generate

# Database setup (SQLite)
touch database/database.sqlite
php artisan migrate --seed

# Run the server
php artisan serve
