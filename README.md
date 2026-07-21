<p align="center"><img src="https://laravel.com/assets/img/components/logo-laravel.svg" width="120" alt="Mtokoma Motorcycle Parts"></p>

<h1 align="center">Mtokoma Motorcycle Parts</h1>

<p align="center">
  <strong>Inventory Management System</strong><br>
  Built for motorcycle parts retail & wholesale businesses in Tanzania
</p>

<p align="center">
  <a href="#features">Features</a> &bull;
  <a href="#tech-stack">Tech Stack</a> &bull;
  <a href="#installation">Installation</a> &bull;
  <a href="#deployment">Deployment</a>
</p>

---

## About

**Mtokoma Motorcycle Parts** is a comprehensive inventory management system designed for motorcycle parts retail and wholesale businesses. It handles the complete business cycle — from purchasing stock, managing inventory, point-of-sale transactions, expense tracking, and financial reporting — all with full tax compliance including EFD receipts with QR codes.

## Features

### Inventory Management
- Full item catalog with images, auto-generated SKUs, and barcode support
- Hierarchical categories (parent/child)
- Real-time stock levels with complete movement history
- Manual stock adjustments with audit trail
- Automatic low-stock email alerts

### Point of Sale
- Touch-friendly interface with product grid, search, and category filters
- **Smart autocomplete**: shows first 5 customers on focus, filters as you type
- Shopping cart with quantity controls and quick cash buttons
- Per-transaction VAT toggle (18%)
- Walk-In customer default with searchable customer selection
- Instant EFD thermal receipts with QR code for tax verification
- **Fully responsive** — mobile layout with in-cart item search

### Purchasing
- Purchase orders with dynamic item rows and **smart supplier/item autocomplete**
- **Auto-fill cost price** when selecting items from dropdown
- Shows first 5 suppliers and items on focus, filters as you type
- Auto stock-in on purchase receipt
- Partial payment tracking with due amounts

### Sales
- Full sales history with date, customer, and status filters
- Void sales with reason and automatic stock restoration
- EFD receipt generation with QR code

### Expenses
- Category-based expense tracking with receipt upload
- Recurring expenses (daily, weekly, monthly, yearly)
- Approval workflow (pending, approved, rejected)

### Financial Reports
- Sales, purchase, inventory, and expense reports with Chart.js visualizations
- Profit & Loss statement (revenue, COGS, gross/net profit)
- Tax report (VAT collected vs paid, net payable)
- Customer and supplier balance reports

### Reconciliation
- Daily, weekly, and monthly cash reconciliation
- Auto-calculated expected cash with variance detection

### Access Control
- 2 pre-built roles: **Admin** (full access) and **User** (limited access)
- 56 granular permissions across 17 modules
- Custom roles with configurable permission sets
- Permission-based UI — buttons, links, and menus hidden automatically

### Import & Export
- CSV/Excel import for items, categories, customers, suppliers, and opening stock
- CSV export for all major data types

### Notifications & Logging
- Email alerts for low stock, sales, purchases, expenses, voids, and adjustments
- Complete audit trail with old/new value tracking

---

## Design System

The UI follows a **Frappe ERPNext-inspired** design system:

- **Font**: Nunito (Google Fonts)
- **Colors**: Solid colors — no shadows, no gradients
  - Primary: `#171717` (buttons, headings)
  - Accent: `#2563EB` (links, active states, focus rings)
  - Success: `#46b37e` / Danger: `#e03636` / Warning: `#fb8b2c`
- **Compact sizing**: Tighter padding, smaller text, professional density
- **Icons**: Heroicons v2 inline SVGs throughout all forms and navigation
- **Responsive**: All pages work on mobile without horizontal scrolling
  - Tables use `overflow-x-auto` with hidden columns on small screens
  - Grids collapse to single column on mobile
  - POS has dedicated mobile layout with in-cart search

---

## Tech Stack

| Layer | Technology |
|-------|-----------|
| **Backend** | Laravel 11, PHP 8.2+ |
| **Database** | MySQL 8 |
| **Frontend** | Blade Templates, Tailwind CSS 3 (CDN), Alpine.js 3 |
| **Charts** | Chart.js |
| **PDF / Excel** | DomPDF, Maatwebsite Excel |
| **QR Codes** | simplesoftwareio/simple-qrcode |
| **Icons** | Heroicons v2 (inline SVG) |

---

## Installation

### Prerequisites
- PHP 8.2+ with extensions: `gd`, `zip`, `mbstring`, `openssl`, `pdo`, `tokenizer`, `curl`, `xml`
- MySQL 8.0+
- Composer 2.x

### Setup

```bash
# Clone the repository
git clone https://github.com/atilioobadia-cpu/inventory_system.git
cd inventory_system

# Install PHP dependencies
composer install

# Create environment file and generate app key
cp .env.example .env
php artisan key:generate

# Configure your database in .env, then run:
php artisan migrate --force
php artisan db:seed --force

# Create storage symlink and clear caches
php artisan storage:link
php artisan config:clear && php artisan cache:clear && php artisan view:clear
```

Start the local development server:

```bash
php artisan serve
```

Visit **http://localhost:8000** to access the application.

### Default Login

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@mtokoma.co.tz | password |
| User | user@mtokoma.co.tz | password |

---

## Deployment (cPanel / Namecheap)

```bash
# 1. Install production dependencies
composer install --no-dev --optimize-autoloader

# 2. Upload the entire project folder via File Manager or FTP

# 3. Set .env with your production database credentials

# 4. Run migrations and seed default data
php artisan migrate --force
php artisan db:seed --force

# 5. Create storage symlink and set permissions
php artisan storage:link
chmod -R 755 storage bootstrap/cache
chmod -R 775 storage/framework
chmod -R 775 storage/logs
```

**Point your document root to the `public/` directory.**

---

## Key Design Decisions

- **Stock as Source of Truth** — Stock levels are always calculated from movement history, never stored directly on items
- **Invoice Auto-Generation** — Yearly-reset invoice numbers (e.g., `PUR-2026-00001`, `SAL-2026-00001`)
- **EFD Compliance** — Receipts include TIN, VAT breakdown, and QR code for tax verification
- **TZS Currency** — All monetary values formatted as TZS (Tanzanian Shillings)
- **Smart Autocomplete** — All selection dropdowns (customers, suppliers, items) show first 5 results on focus with debounced search
- **Auto-fill Pricing** — Selecting an item in POS auto-fills selling price; in purchases, auto-fills cost price
- **Frappe Design** — Clean, professional UI inspired by ERPNext with solid colors, no shadows, and compact layout

---

## License

Proprietary — Mtokoma Motorcycle Parts

---

<p align="center">Built with Laravel 11 &bull; Mtokoma Motorcycle Parts &copy; 2026</p>
