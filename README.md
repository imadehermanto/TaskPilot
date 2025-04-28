
# TaskPilot - *Open Source Task Management System with Laravel Filament*



**TaskPilot** is a modern project and task management system based on Laravel 11 and Filament.
Designed to help companies or individuals manage projects, monitor task progress, estimate work time, and distribute workload effectively.

With a Filament-based admin interface, TaskPilot delivers a fast, responsive, and powerful user experience without the need to develop a dashboard from scratch.
## ✨ Features

- Project & Task Management 🛠️
- Time Estimation & Realization 🛠️
- Kanban Board & Progress Chart 🛠️
- Multi-user Assignment 🛠️
- Time Tracking 🛠️
- Role and Permission Management ✅
- Light/dark mode toggle ✅
- PWA Integration ✅


##  🚀 Tech Stack

- Laravel 11
- Filament v3
- Livewire 3
- Spatie Permission


## 📋 Requirement
- PHP >= 8.2
- Composer
- Node.js + NPM
- MySQL / PostgreSQL
## ⚡ Quickstart
Clone the Repository
```bash
git clone https://github.com/imadehermanto/taskpilot.git
cd taskpilot
```

Install PHP Dependencies
```bash
composer install
```

Install Frontend Dependencies
```bash
npm install
npm run dev
```

Copy the `.env` File
```bash
cp .env.example .env
```

Generate an App Key
```bash
php artisan key:generate
```

Configure the Database
```Bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=taskpilot
DB_USERNAME=root
DB_PASSWORD=yourpassword
```

Run Migrations
```Bash
php artisan migrate
```

Create a user
```Bash
php artisan make:filament-user
```

Start the Local Development Server
```Bash
php artisan serve
```
- Open in your browser: http://127.0.0.1:8000



## Contributing

Contributions are always welcome!

See `contributing.md` for ways to get started.

Please adhere to this project's `code of conduct`.


## License
Licensed under the [MIT](https://choosealicense.com/licenses/mit/) License.
