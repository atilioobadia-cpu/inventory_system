<p align="center"><img src="https://laravel.com/assets/img/components/logo-laravel.svg" width="120" alt="Mtokoma Motorcycle Parts"></p>

<h1 align="center">Mtokoma Motorcycle Parts</h1>

<p align="center">
  <strong>Professional Inventory Management System</strong><br>
  Built for motorcycle parts shops in Tanzania
</p>

<p align="center">
  <a href="#features">Features</a> &bull;
  <a href="#tech-stack">Tech Stack</a> &bull;
  <a href="#installation">Installation</a> &bull;
  <a href="#usage">Usage</a> &bull;
  <a href="#deployment">Deployment</a>
</p>

---

## About

**Mtokoma Motorcycle Parts** is a comprehensive inventory management system designed for motorcycle parts retail and wholesale businesses. It handles the complete business cycle from purchasing stock, managing inventory, point-of-sale transactions, expense tracking, and financial reporting — all with full tax compliance (EFD receipts with QR codes).

## Features

### Inventory Management
- **Items** — Full CRUD with images, auto-generated SKUs, barcode support
- **Categories** — Hierarchical categories (parent/child)
- **Stock Tracking** — Real-time stock levels from `stock_movements` table
- **Stock Adjustments** — Manual adjustments with audit trail
- **Low Stock Alerts** — Automatic daily email alerts when items fall below reorder point

### Point of Sale (POS)
- **Touch-friendly POS Interface** — Product grid with search, category filters
- **Shopping Cart** — Add/remove items, quantity controls, quick cash buttons
- **VAT Support** — Toggle VAT (18%) per transaction, VAT exempt option
- **Payment Methods** — Cash and credit sales
- **Customer Selection** — Searchable dropdown, Walk-In default customer
- **Instant Receipts** — EFD thermal receipt with QR code for tax verification

### Purchasing
- **Purchase Orders** — Dynamic item rows, supplier selection, cost tracking
- **Receive Stock** — Auto stock-in on purchase receipt
- **Payment Tracking** — Partial payments, due amounts, payment status

### Sales
- **Sales History** — Full list with filters (date, customer, status)
- **Void Sales** — With reason, automatic stock restoration
- **Receipt Generation** — EFD format with QR code

### Expenses
- **Expense Tracking** — Categories, payment methods, receipt upload
- **Recurring Expenses** — Daily, weekly, monthly, yearly schedules
- **Approval Workflow** — Pending, approved, rejected status

### Financial Reports
- **Sales Report** — Daily/weekly/monthly trends, Chart.js visualizations
- **Purchase Report** — Supplier analysis, cost tracking
- **Inventory Report** — Stock value, category breakdown (pie chart)
- **Expense Report** — By category, monthly trends
- **Profit & Loss** — Revenue, COGS, gross profit, net profit
- **Tax Report** — VAT collected vs paid, net payable
- **Customer & Supplier Reports** — Balances, transaction history

### Reconciliation
- **Daily/Weekly/Monthly** — Auto-calculated expected cash
- **Variance Detection** — Discrepancy alerts when actual ≠ expected

### Multi-Role Access Control
- **6 Pre-built Roles** — Super Admin, Manager, Cashier, Warehouse, Accountant, Viewer
- **56 Granular Permissions** — Across 17 modules
- **Unlimited Custom Roles** — Create roles with custom permission combinations

### Import & Export
- **CSV/Excel Import** — Items, categories, customers, suppliers, opening stock
- **CSV Export** — All major data types

### Email Notifications
- Low stock alerts, sale confirmations, purchase notifications, expense alerts, void notifications, stock adjustment alerts

### Activity Logging
- Complete audit trail — who did what, when, with old/new values

---

## Tech Stack

| Layer | Technology |
|-------|-----------|
| **Backend** | Laravel 11, PHP 8.2+ |
| **Database** | MySQL 8 |
| **Frontend** | Blade Templates, Tailwind CSS 3, Alpine.js 3 |
| **Charts** | Chart.js |
| **QR Code** | SimpleSoftwareIO Simple QR Code |
| **PDF** | DomPDF |
| **Excel** | Maatwebsite Excel |
| **Icons** | Heroicons v2 (inline SVG) |

---

## Installation

### Prerequisites
- PHP 8.2+ with extensions: `gd`, `zip`, `mbstring`, `openssl`, `pdo`, `tokenizer`, `curl`, `xml`
- MySQL 8.0+
- Composer 2.x
- Node.js & NPM (for asset compilation, optional)

### Setup

```bash
# 1. Clone the repository
git clone https://github.com/atilioobadia-cpu/inventory_system.git
cd inventory_system

# 2. Install PHP dependencies
composer install

# 3. Create environment file
cp .env.example .env

# 4. Generate application key
php artisan key:generate

# 5. Configure database in .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mtokoma_db
DB_USERNAME=root
DB_PASSWORD=

# 6. Create database and run migrations
php artisan migrate --force

# 7. Seed default data (roles, permissions, users, categories, settings)
php artisan db:seed --force

# 8. Create storage symlink
php artisan storage:link

# 9. Clear caches
php artisan config:clear && php artisan cache:clear && php artisan view:clear
```

### Local Development Server

```bash
php artisan serve
```

Visit: **http://localhost:8000**

---

## Default Login Credentials

| Role | Email | Password |
|------|-------|----------|
| Super Admin | admin@mtokoma.co.tz | password |
| Manager | manager@mtokoma.co.tz | password |
| Cashier | cashier@mtokoma.co.tz | password |

---

## Project Structure

```
inventory_system/
├── app/
│   ├── Http/
│   │   ├── Controllers/       # 23 controllers (Auth, CRUD, POS, Reports...)
│   │   └── Middleware/         # Role, Permission, Activity Tracking
│   ├── Models/                 # 18 Eloquent models
│   ├── Services/               # Stock, Invoice, Notification, Activity, Reconciliation
│   └── Providers/
├── database/
│   ├── migrations/             # 23 migrations (25 tables)
│   └── seeders/                # 8 seeders
├── resources/
│   └── views/                  # 67 Blade templates
│       ├── layouts/            # Main app layout + print layout
│       ├── pos/                # Point of Sale interface
│       ├── receipts/           # EFD receipts with QR code
│       ├── reports/            # 9 report views
│       ├── emails/             # 6 email notification templates
│       └── ...                 # CRUD views for all modules
├── routes/
│   └── web.php                 # 112 routes
├── config/                     # 13 config files
└── vendor/                     # Composer dependencies
```

---

## Database Schema

25 tables covering:

| Tables | Purpose |
|--------|---------|
| `roles`, `permissions`, `role_permissions` | RBAC system |
| `users` | Application users |
| `categories`, `items` | Product catalog |
| `suppliers`, `customers` | Business partners |
| `stock_movements` | Stock ledger (source of truth) |
| `purchases`, `purchase_items` | Purchase orders |
| `sales`, `sale_items` | Sales transactions |
| `expenses`, `expense_categories` | Expense tracking |
| `payments` | Polymorphic payments |
| `reconciliations` | Cash reconciliation |
| `settings` | Grouped system settings |
| `activity_logs` | Audit trail |
| `notifications` | System notifications |

---

## Deployment (cPanel / Namecheap)

```bash
# 1. Run composer with --no-dev for production
composer install --no-dev --optimize-autoloader

# 2. Upload entire project folder via File Manager or FTP

# 3. Set .env with production database credentials

# 4. Run migrations on production server
php artisan migrate --force

# 5. Seed production database
php artisan db:seed --force

# 6. Create storage symlink
php artisan storage:link

# 7. Set folder permissions
chmod -R 755 storage bootstrap/cache
chmod -R 775 storage/framework
chmod -R 775 storage/logs
```

**Point your document root to the `public/` directory.**

---

## Key Design Decisions

- **Stock as Source of Truth** — Stock levels are always calculated from `stock_movements`, never stored on the items table
- **Invoice Auto-Generation** — Format: `PUR-2026-00001`, `SAL-2026-00001` (yearly reset)
- **EFD Compliance** — Receipts include TIN, VAT breakdown, and QR code for tax verification
- **Permission-Based UI** — Buttons, links, and menus are hidden via `@can()` Blade directives
- **TZS Currency** — All monetary values formatted as `TZS 1,500,000`

---

## License

Proprietary — Mtokoma Motorcycle Parts

---

<p align="center">Built with Laravel 11 &bull; Mtokoma Motorcycle Parts &copy; 2026</p>
