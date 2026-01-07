# System Technical Specifications & Features

## Project Overview
This represents a comprehensive **Pharmacy Management System (PMS)** designed to streamline inventory stock, sales, patient history, and financial reporting.

## Technical Stack

### Backend Framework
- **Core**: PHP 8.2+ / Laravel 11.x
- **Authentication**: Laravel Jetstream
- **Database**: MySQL (Compatible with Oracle via `yajra/laravel-datatables-oracle`)
- **Real-time Events**: Pusher (via `pusher-php-server`)

### Frontend Stack
- **Framework**: Laravel Livewire 3.0 (Server-side rendering with dynamic interactions)
- **Styling**: TailwindCSS 3.4
- **JavaScript**: AlpineJS (bundled with Livewire)
- **Bundler**: Vite

### Key Libraries & Packages
- **Data Tables**: `yajra/laravel-datatables` for server-side processing of large datasets.
- **PDF Generation**: `barryvdh/laravel-dompdf` for invoices and reports.
- **Excel Export**: `maatwebsite/excel` for data export.
- **Printing**: `mike42/escpos-php` for thermal receipt printing.
- **Permissions**: `spatie/laravel-permission` for RBAC (Role-Based Access Control).
- **Auditing**: `owen-it/laravel-auditing` and `spatie/laravel-activitylog` for tracking user actions.
- **Notifications**: `laravel/vonage-notification-channel` for SMS alerts.

## Key Modules & Features

### 1. Inventory & Stock Management
- **Models**: `Stock`, `Medicine`, `Category`, `Package`, `Vendor`
- **Features**:
    - Real-time stock tracking.
    - Automatic low-stock alerts.
    - Expiry date monitoring.
    - Batch management.
    - Stock transfers between locations/branches (`StockTransfer`).
    - Periodic stock checking and reconciliation (`StockCheck`).

### 2. Point of Sale (POS) & Sales
- **Models**: `Sales`, `Items`, `SalesReturn`, `SaleNote`, `Installment`
- **Features**:
    - Fast billing interface.
    - Support for cash, credit, and installment payments.
    - Discount management.
    - Sales return handling.
    - Thermal printer integration for receipts (`PrinterSetting`).

### 3. Financial Management
- **Models**: `Expense`, `ExpenseCategory`, `Debt`, `Contract`
- **Features**:
    - Expense tracking with attachment support (`ExpenseAttachment`).
    - Debt management and credit collection.
    - Profit & Loss calculation (Gross vs Net Profit).
    - Daily/Monthly/Yearly financial reports.

### 4. User System & Security
- **Models**: `User`, `Staff`
- **Features**:
    - Role-based access control (Admin, Pharmacist, Cashier, etc.).
    - Activity logging for security audits.
    - Staff performance tracking.

### 5. Communication & CRM
- **Models**: `Message`, `Conversation`, `Notification`, `Agent`
- **Features**:
    - Internal messaging system.
    - Customer notifications via SMS/Email.
    - Real-time dashboard updates via Pusher.

## Database Schema Highlights
The system is built on a relational database model centered around `stocks` and `sales`.
- **Items** table allows multiple medicines to be linked to a single **Sale**.
- **Stock** table tracks quantity per batch/expiry.
- **Installments** link to Sales for credit tracking.

## Setup Requirements for New Instances
To clone or deploy this project for a new service:
1. **Requirements**: PHP 8.2, Composer, Node.js, MySQL.
2. **Installation**:
   ```bash
   composer install
   npm install && npm run build
   php artisan migrate --seed
   php artisan storage:link
   ```
3. **Configuration**: 
   - Set up `.env` with database credentials.
   - Configure Pusher keys for real-time features.
   - Configure Mail/SMS drivers.
