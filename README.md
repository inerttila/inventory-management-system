# ✨ Comprehensive Inventory Management System

A robust, enterprise-grade **Inventory Management System** built with **Laravel 12** and **Livewire**. Designed specifically to streamline inventory tracking, sales, purchasing processes, and financial ledger management with dynamic localization.

![Dashboard Preview](public/images/screenshot.png)

## 🌟 Key Modules & Features

- **📊 Advanced Analytics Dashboard**
  - Real-time Total Sales & Net Cash Flow tracking.
  - Interactive ApexCharts for Sales & Cash Flow trends.
  - Quick insights: Top Selling Products, Top Customers, and Low Stock Alerts.

- **💳 Sales & POS (Point of Sale)**
  - Fast, intuitive POS interface designed for rapid checkouts.
  - Support for Global Discounts, Exact Cash computation, and Change tracking.
  - Direct integration with Invoice/Receipt printing.
  - Persistent cart state across sessions.

- **📦 Purchases & Receiving**
  - End-to-end Purchase Order workflow.
  - Seamless "Receive Items" action that updates real inventory balances automatically.
  - Supplier tracking and history filtering.

- **🗃️ Master Data Management**
  - **Products**: Manage stock, pricing (Buy/Sell margins), and associations.
  - **Categories & Units**: Structured tagging for efficient reporting.
  - **Customers & Suppliers**: Comprehensive contact books integrated globally.

- **💰 Finance Ledger & Cash Flow**
  - Integrated Double-entry style tracking for all Income and Expenses.
  - Dynamic Cash Flow reporting mapping POS sales to Income and Purchases to Expenses automatically.
  - Custom Income/Expense categorization.

- **⚙️ Dynamic Localization & Settings**
  - Global Store Information management.
  - **Fully Dynamic Currency Framework**: Customizable currency symbols, positions (left/right), thousands separators, decimal separators, and fractional precision. Changes apply globally to charts, tables, inputs, and receipts instantly.

## 🛠️ Tech Stack & Library Used

- **Framework**: Laravel 12.x
- **Frontend/Reactivity**: Laravel Livewire 3 + Alpine.js
- **Styling**: Tailwind CSS (Shadcn-inspired components)
- **Data Tables**: Livewire PowerGrid (with customized AJAX filters)
- **Charts**: ApexCharts
- **Icons**: Blade Heroicons
- **Database**: MySQL

## 🚀 Quick Start

### Option A: Docker (Recommended)

Run the entire stack — **Nginx, PHP, frontend assets, and PostgreSQL** — with one command. No local PHP, Composer, Node, or database installation required.

#### Prerequisites
- [Docker](https://docs.docker.com/get-docker/) 20.10+
- [Docker Compose](https://docs.docker.com/compose/install/) v2+

#### Start the application

1. **Clone the repository:**
    ```bash
    git clone https://github.com/fajarghifar/inventory-management-system.git
    cd inventory-management-system
    ```

2. **Build and start the containers:**
    ```bash
    docker compose up --build -d
    ```

3. **Open the app** at [http://localhost:8080](http://localhost:8080)

4. **Login with the default admin credentials:**
    - **Username:** `admin`
    - **Password:** `password`

On first start, Docker automatically:
- Builds frontend assets (Vite)
- Starts a PostgreSQL container (no local DB needed)
- Runs migrations and seeders
- Starts Nginx, PHP-FPM, and the queue worker

#### Useful Docker commands

| Command | Description |
|---------|-------------|
| `docker compose up --build -d` | Build and start in the background |
| `docker compose down` | Stop and remove containers |
| `docker compose down -v` | Stop and remove containers **and** database/storage volumes |
| `docker compose logs -f` | Follow application logs |
| `docker compose exec app php artisan migrate` | Run migrations manually |
| `docker compose exec app php artisan db:seed` | Run seeders manually |

#### Configuration

Default Docker settings are in `.env.docker` and `docker-compose.yml`:

| Variable | Default |
|----------|---------|
| `APP_PORT` | `8080` |
| `DB_HOST` | `postgres` (Docker service — not your local machine) |
| `DB_PORT` | `5432` |
| `DB_DATABASE` | `inventory` |
| `DB_USERNAME` | `inventory` |
| `DB_PASSWORD` | `secret` |

To use a different port, create a `.env` file in the project root (or export the variable):

```bash
APP_PORT=9000 docker compose up --build -d
```

Data is persisted in Docker volumes (`postgres_data`, `storage_data`) so it survives container restarts.

---

### Option B: Manual Installation

Follow these steps to set up the project locally without Docker.

#### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js & NPM
- MySQL Database

#### Installation Steps

1. **Clone the repository:**
    ```bash
    git clone https://github.com/fajarghifar/inventory-management-system.git
    ```

2. **Navigate to the project folder:**
    ```bash
    cd inventory-management-system
    ```

3. **Install PHP dependencies:**
    ```bash
    composer install
    ```

4. **Copy `.env` configuration:**
    ```bash
    cp .env.example .env
    ```

5. **Generate application key:**
    ```bash
    php artisan key:generate
    ```

6. **Configure your Database:**
    Open the `.env` file and set up your MySQL connection credentials:
    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=your_database_name
    DB_USERNAME=root
    DB_PASSWORD=
    ```

7. **Run database migrations and seeders:**
    This command will migrate all tables and inject default users, settings, products, and categories.
    ```bash
    php artisan migrate:fresh --seed
    ```

8. **Link storage for media/image files:**
    ```bash
    php artisan storage:link
    ```

9. **Install node modules and compile assets:**
    ```bash
    npm install
    npm run build
    ```

10. **Start the Laravel development server:**
    ```bash
    php artisan serve
    ```

11. **Login using the default admin credentials:**
    - **Username:** `admin`
    - **Password:** `password`

## 💡 Contributing

Have ideas to improve the system? Architecture enhancements, UI tweaks, or bug reports are welcome!

### Getting started as a contributor

1. **Fork** the repository and clone your fork locally.
2. **Start the app with Docker** (fastest way to verify your changes):
    ```bash
    docker compose up --build -d
    ```
3. Make your changes and test them at [http://localhost:8080](http://localhost:8080).
4. Submit a **Pull Request (PR)** with a clear description of what changed and why.
5. Create an **Issue** for feature requests or bugs you cannot fix yourself.

### Docker architecture

The project ships with a Docker Compose setup:

| Service | Role |
|---------|------|
| **app** | Nginx + PHP-FPM + Laravel backend + queue worker |
| **postgres** | PostgreSQL 16 database (runs inside Docker, not on your PC) |

Relevant files:

- `Dockerfile` — multi-stage build (Node for assets, PHP for runtime)
- `docker-compose.yml` — app and PostgreSQL service definitions
- `docker/entrypoint.sh` — waits for PostgreSQL, runs migrations and seeding
- `.env.docker` — environment template for the app container

## 📄 License

Licensed under the [MIT License](LICENSE).

---

> Crafted by [Fajar Ghifar](https://github.com/fajarghifar) &nbsp;&middot;&nbsp; [YouTube](https://www.youtube.com/@fajarghifar) &nbsp;&middot;&nbsp; [Instagram](https://instagram.com/fajarghifar) &nbsp;&middot;&nbsp; [LinkedIn](https://www.linkedin.com/in/fajarghifar/)
