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
    Route::get('items', [ItemController::class, 'index'])->name('items.index')->middleware('permission:view_items');
    Route::get('items/create', [ItemController::class, 'create'])->name('items.create')->middleware('permission:create_items');
    Route::post('items', [ItemController::class, 'store'])->name('items.store')->middleware('permission:create_items');
    Route::get('items/{item}', [ItemController::class, 'show'])->name('items.show')->middleware('permission:view_items');
    Route::get('items/{item}/edit', [ItemController::class, 'edit'])->name('items.edit')->middleware('permission:edit_items');
    Route::put('items/{item}', [ItemController::class, 'update'])->name('items.update')->middleware('permission:edit_items');
    Route::delete('items/{item}', [ItemController::class, 'destroy'])->name('items.destroy')->middleware('permission:delete_items');

    // Categories
    Route::get('categories', [CategoryController::class, 'index'])->name('categories.index')->middleware('permission:view_categories');
    Route::get('categories/create', [CategoryController::class, 'create'])->name('categories.create')->middleware('permission:create_categories');
    Route::post('categories', [CategoryController::class, 'store'])->name('categories.store')->middleware('permission:create_categories');
    Route::get('categories/{category}', [CategoryController::class, 'show'])->name('categories.show')->middleware('permission:view_categories');
    Route::get('categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit')->middleware('permission:edit_categories');
    Route::put('categories/{category}', [CategoryController::class, 'update'])->name('categories.update')->middleware('permission:edit_categories');
    Route::delete('categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy')->middleware('permission:delete_categories');

    // Suppliers
    Route::get('suppliers', [SupplierController::class, 'index'])->name('suppliers.index')->middleware('permission:view_suppliers');
    Route::get('suppliers/create', [SupplierController::class, 'create'])->name('suppliers.create')->middleware('permission:create_suppliers');
    Route::post('suppliers', [SupplierController::class, 'store'])->name('suppliers.store')->middleware('permission:create_suppliers');
    Route::get('suppliers/{supplier}', [SupplierController::class, 'show'])->name('suppliers.show')->middleware('permission:view_suppliers');
    Route::get('suppliers/{supplier}/edit', [SupplierController::class, 'edit'])->name('suppliers.edit')->middleware('permission:edit_suppliers');
    Route::put('suppliers/{supplier}', [SupplierController::class, 'update'])->name('suppliers.update')->middleware('permission:edit_suppliers');
    Route::delete('suppliers/{supplier}', [SupplierController::class, 'destroy'])->name('suppliers.destroy')->middleware('permission:delete_suppliers');

    // Customers
    Route::get('customers', [CustomerController::class, 'index'])->name('customers.index')->middleware('permission:view_customers');
    Route::get('customers/create', [CustomerController::class, 'create'])->name('customers.create')->middleware('permission:create_customers');
    Route::post('customers', [CustomerController::class, 'store'])->name('customers.store')->middleware('permission:create_customers');
    Route::get('customers/{customer}', [CustomerController::class, 'show'])->name('customers.show')->middleware('permission:view_customers');
    Route::get('customers/{customer}/edit', [CustomerController::class, 'edit'])->name('customers.edit')->middleware('permission:edit_customers');
    Route::put('customers/{customer}', [CustomerController::class, 'update'])->name('customers.update')->middleware('permission:edit_customers');
    Route::delete('customers/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy')->middleware('permission:delete_customers');

    // Purchases
    Route::get('purchases', [PurchaseController::class, 'index'])->name('purchases.index')->middleware('permission:view_purchases');
    Route::get('purchases/create', [PurchaseController::class, 'create'])->name('purchases.create')->middleware('permission:create_purchases');
    Route::post('purchases', [PurchaseController::class, 'store'])->name('purchases.store')->middleware('permission:create_purchases');
    Route::get('purchases/{purchase}', [PurchaseController::class, 'show'])->name('purchases.show')->middleware('permission:view_purchases');
    Route::get('purchases/{purchase}/edit', [PurchaseController::class, 'edit'])->name('purchases.edit')->middleware('permission:edit_purchases');
    Route::put('purchases/{purchase}', [PurchaseController::class, 'update'])->name('purchases.update')->middleware('permission:edit_purchases');
    Route::delete('purchases/{purchase}', [PurchaseController::class, 'destroy'])->name('purchases.destroy')->middleware('permission:delete_purchases');
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
    Route::get('reconciliations', [ReconciliationController::class, 'index'])->name('reconciliations.index')->middleware('permission:view_reconciliations');
    Route::get('reconciliations/create', [ReconciliationController::class, 'create'])->name('reconciliations.create')->middleware('permission:create_reconciliations');
    Route::post('reconciliations', [ReconciliationController::class, 'store'])->name('reconciliations.store')->middleware('permission:create_reconciliations');
    Route::get('reconciliations/{reconciliation}', [ReconciliationController::class, 'show'])->name('reconciliations.show')->middleware('permission:view_reconciliations');
    Route::get('/api/reconciliation/expected', [ReconciliationController::class, 'expected'])->name('api.reconciliation.expected');

    // Expenses
    Route::get('expenses', [ExpenseController::class, 'index'])->name('expenses.index')->middleware('permission:view_expenses');
    Route::get('expenses/create', [ExpenseController::class, 'create'])->name('expenses.create')->middleware('permission:create_expenses');
    Route::post('expenses', [ExpenseController::class, 'store'])->name('expenses.store')->middleware('permission:create_expenses');
    Route::get('expenses/{expense}', [ExpenseController::class, 'show'])->name('expenses.show')->middleware('permission:view_expenses');
    Route::get('expenses/{expense}/edit', [ExpenseController::class, 'edit'])->name('expenses.edit')->middleware('permission:edit_expenses');
    Route::put('expenses/{expense}', [ExpenseController::class, 'update'])->name('expenses.update')->middleware('permission:edit_expenses');
    Route::delete('expenses/{expense}', [ExpenseController::class, 'destroy'])->name('expenses.destroy')->middleware('permission:delete_expenses');

    // Expense Categories
    Route::get('expense-categories', [ExpenseCategoryController::class, 'index'])->name('expense-categories.index')->middleware('permission:view_expense_categories');
    Route::get('expense-categories/create', [ExpenseCategoryController::class, 'create'])->name('expense-categories.create')->middleware('permission:create_expense_categories');
    Route::post('expense-categories', [ExpenseCategoryController::class, 'store'])->name('expense-categories.store')->middleware('permission:create_expense_categories');
    Route::get('expense-categories/{expense_category}', [ExpenseCategoryController::class, 'show'])->name('expense-categories.show')->middleware('permission:view_expense_categories');
    Route::get('expense-categories/{expense_category}/edit', [ExpenseCategoryController::class, 'edit'])->name('expense-categories.edit')->middleware('permission:edit_expense_categories');
    Route::put('expense-categories/{expense_category}', [ExpenseCategoryController::class, 'update'])->name('expense-categories.update')->middleware('permission:edit_expense_categories');
    Route::delete('expense-categories/{expense_category}', [ExpenseCategoryController::class, 'destroy'])->name('expense-categories.destroy')->middleware('permission:delete_expense_categories');

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
    Route::get('roles', [RoleController::class, 'index'])->name('roles.index')->middleware('permission:view_roles');
    Route::get('roles/create', [RoleController::class, 'create'])->name('roles.create')->middleware('permission:create_roles');
    Route::post('roles', [RoleController::class, 'store'])->name('roles.store')->middleware('permission:create_roles');
    Route::get('roles/{role}', [RoleController::class, 'show'])->name('roles.show')->middleware('permission:view_roles');
    Route::get('roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit')->middleware('permission:edit_roles');
    Route::put('roles/{role}', [RoleController::class, 'update'])->name('roles.update')->middleware('permission:edit_roles');
    Route::delete('roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy')->middleware('permission:delete_roles');

    // Users
    Route::get('users', [UserController::class, 'index'])->name('users.index')->middleware('permission:view_users');
    Route::get('users/create', [UserController::class, 'create'])->name('users.create')->middleware('permission:create_users');
    Route::post('users', [UserController::class, 'store'])->name('users.store')->middleware('permission:create_users');
    Route::get('users/{user}', [UserController::class, 'show'])->name('users.show')->middleware('permission:view_users');
    Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit')->middleware('permission:edit_users');
    Route::put('users/{user}', [UserController::class, 'update'])->name('users.update')->middleware('permission:edit_users');
    Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy')->middleware('permission:delete_users');
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
