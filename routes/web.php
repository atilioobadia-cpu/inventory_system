<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\StockAdjustmentController;
use App\Http\Controllers\ReconciliationController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ExpenseCategoryController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ImportExportController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ReceiptController;
use Illuminate\Support\Facades\Route;

// Auth Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware(['auth', \App\Http\Middleware\EnsureUserIsActive::class])->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Items
    Route::resource('items', ItemController::class)->middleware('permission:create_items');

    // Categories
    Route::resource('categories', CategoryController::class)->middleware('permission:create_categories');

    // Suppliers
    Route::resource('suppliers', SupplierController::class)->middleware('permission:create_suppliers');

    // Customers
    Route::resource('customers', CustomerController::class)->middleware('permission:create_customers');

    // Purchases
    Route::resource('purchases', PurchaseController::class)->middleware('permission:create_purchases');
    Route::post('purchases/{purchase}/receive', [PurchaseController::class, 'receive'])->name('purchases.receive')->middleware('permission:receive_purchases');
    Route::post('purchases/{purchase}/cancel', [PurchaseController::class, 'cancel'])->name('purchases.cancel');

    // Sales
    Route::resource('sales', SaleController::class)->only(['index', 'show', 'destroy'])->middleware('permission:view_sales');
    Route::post('sales/{sale}/void', [SaleController::class, 'void'])->name('sales.void')->middleware('permission:void_sales');

    // POS
    Route::get('/pos', [PosController::class, 'index'])->name('pos.index')->middleware('permission:access_pos');
    Route::post('/pos/process', [PosController::class, 'processSale'])->name('pos.process');

    // Stock
    Route::get('/stock', [StockController::class, 'index'])->name('stock.index')->middleware('permission:view_stock');
    Route::get('/stock/export', [StockController::class, 'export'])->name('stock.export')->middleware('permission:view_stock');
    Route::get('/stock/movements', [StockController::class, 'movements'])->name('stock.movements')->middleware('permission:view_stock_movements');
    Route::get('/stock/adjust', [StockAdjustmentController::class, 'create'])->name('stock.adjust.form')->middleware('permission:adjust_stock');
    Route::post('/stock/adjust', [StockAdjustmentController::class, 'adjust'])->name('stock.adjust')->middleware('permission:adjust_stock');

    // Reconciliations
    Route::resource('reconciliations', ReconciliationController::class)->only(['index', 'create', 'store', 'show'])->middleware('permission:create_reconciliations');
    Route::get('/api/reconciliation/expected', [ReconciliationController::class, 'expected'])->name('api.reconciliation.expected');

    // Expenses
    Route::resource('expenses', ExpenseController::class)->middleware('permission:create_expenses');
    Route::resource('expense-categories', ExpenseCategoryController::class)->middleware('permission:create_expense_categories');

    // Payments
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');

    // Reports
    Route::prefix('reports')->name('reports.')->middleware('permission:view_reports')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/sales', [ReportController::class, 'sales'])->name('sales');
        Route::get('/purchases', [ReportController::class, 'purchases'])->name('purchases');
        Route::get('/inventory', [ReportController::class, 'inventory'])->name('inventory');
        Route::get('/expenses', [ReportController::class, 'expenses'])->name('expenses');
        Route::get('/profit-loss', [ReportController::class, 'profitLoss'])->name('profit-loss');
        Route::get('/tax', [ReportController::class, 'tax'])->name('tax');
        Route::get('/customers', [ReportController::class, 'customers'])->name('customers');
        Route::get('/suppliers', [ReportController::class, 'suppliers'])->name('suppliers');
        Route::post('/export', [ReportController::class, 'export'])->name('export');
    });

    // Import/Export
    Route::prefix('import-export')->name('import-export.')->middleware('permission:import_data')->group(function () {
        Route::get('/', [ImportExportController::class, 'index'])->name('index');
        Route::post('/import/{type}', [ImportExportController::class, 'import'])->name('import');
        Route::get('/export/{type}', [ImportExportController::class, 'export'])->name('export');
    });

    // Roles
    Route::resource('roles', RoleController::class)->middleware('permission:create_roles');

    // Users
    Route::resource('users', UserController::class)->middleware('permission:create_users');
    Route::patch('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status')->middleware('permission:create_users');

    // Activity Log
    Route::get('/activity', [ActivityController::class, 'index'])->name('activity.index')->middleware('permission:view_activity');
    Route::get('/activity-log', [ActivityController::class, 'index'])->name('activity-log.index')->middleware('permission:view_activity');

    // Settings
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index')->middleware('permission:view_settings');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update')->middleware('permission:edit_settings');

    // Receipts
    Route::get('/receipts/{sale}', [ReceiptController::class, 'show'])->name('receipts.show');
    Route::get('/receipts/{sale}/print', [ReceiptController::class, 'print'])->name('receipts.print');

    // API-like routes for AJAX
    Route::get('/api/items/search', [ItemController::class, 'search'])->name('api.items.search');
    Route::get('/api/customers/search', [CustomerController::class, 'search'])->name('api.customers.search');
    Route::get('/api/suppliers/search', [SupplierController::class, 'search'])->name('api.suppliers.search');
    Route::get('/api/stock/{item}', [StockController::class, 'getItemStock'])->name('api.stock.item');
});
