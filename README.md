# Task Management App

A modern, full-stack task management application built with Laravel and Nuxt.js.

## Features

- **User Authentication** - Secure login/registration with Laravel Sanctum
- **Task Management** - Create, edit, delete, and reorder tasks
- **Date-based Organization** - View and organize tasks by date
- **Task Search** - Search through tasks with real-time filtering
- **Drag & Drop** - Reorder tasks with intuitive drag-and-drop interface
- **Responsive Design** - Works seamlessly on desktop and mobile devices

## Tech Stack

### Backend (API)

- **Laravel 12** - PHP framework with RESTful API
- **Laravel Sanctum** - API authentication
- **MySQL/SQLite** - Database
- **Pest PHP** - Testing framework

### Frontend (Client)

- **Nuxt.js 4** - Vue.js framework with SSR
- **Vue 3** - Progressive JavaScript framework
- **TypeScript** - Type-safe development
- **Pinia** - State management
- **Tailwind CSS** - Utility-first CSS framework
- **Lucide Vue** - Icon library

## Project Structure

```
├── api/          # Laravel backend API
├── client/       # Nuxt.js frontend application
├── README.md     # This file
└── *.sh          # Development scripts
```

## Quick Start

### Prerequisites

- **Docker & Docker Compose** - For Laravel Sail
- **Node.js 18+** - For frontend development
- **pnpm** (recommended) or npm

### Backend Setup (Laravel Sail)

```bash
cd api
cp .env.example .env
./vendor/bin/sail up -d
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate
```

**Alternative: Local PHP Setup**

```bash
cd api
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

### Frontend Setup

```bash
cd client
pnpm install
pnpm dev
```

The application will be available at:

- **Frontend**: http://localhost:3000
- **Backend API (Sail)**: http://localhost:80
- **Backend API (Local)**: http://localhost:8000

## Development

### Using Laravel Sail (Recommended)

- Backend runs in Docker containers on port 80
- Frontend runs on port 3000
- Use `./vendor/bin/sail` commands for backend operations
- Database runs in a separate container

### Local Development

- Backend runs on port 8000 (if using `php artisan serve`)
- Frontend runs on port 3000

### General Notes

- API endpoints are available at `/api/v1/`
- Authentication uses Laravel Sanctum with SPA mode
- Frontend communicates with backend via API calls

## License

MIT License
