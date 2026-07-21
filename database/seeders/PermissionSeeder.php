<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $permissions = [
                // Dashboard
                [
                    'name'        => 'View Dashboard',
                    'slug'        => 'view_dashboard',
                    'module'      => 'Dashboard',
                    'description' => 'Access the main dashboard',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],

                // Items
                [
                    'name'        => 'View Items',
                    'slug'        => 'view_items',
                    'module'      => 'Items',
                    'description' => 'View list of items',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'name'        => 'Create Items',
                    'slug'        => 'create_items',
                    'module'      => 'Items',
                    'description' => 'Add new items',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'name'        => 'Edit Items',
                    'slug'        => 'edit_items',
                    'module'      => 'Items',
                    'description' => 'Edit existing items',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'name'        => 'Delete Items',
                    'slug'        => 'delete_items',
                    'module'      => 'Items',
                    'description' => 'Delete items',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],

                // Categories
                [
                    'name'        => 'View Categories',
                    'slug'        => 'view_categories',
                    'module'      => 'Categories',
                    'description' => 'View list of categories',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'name'        => 'Create Categories',
                    'slug'        => 'create_categories',
                    'module'      => 'Categories',
                    'description' => 'Add new categories',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'name'        => 'Edit Categories',
                    'slug'        => 'edit_categories',
                    'module'      => 'Categories',
                    'description' => 'Edit existing categories',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'name'        => 'Delete Categories',
                    'slug'        => 'delete_categories',
                    'module'      => 'Categories',
                    'description' => 'Delete categories',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],

                // Suppliers
                [
                    'name'        => 'View Suppliers',
                    'slug'        => 'view_suppliers',
                    'module'      => 'Suppliers',
                    'description' => 'View list of suppliers',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'name'        => 'Create Suppliers',
                    'slug'        => 'create_suppliers',
                    'module'      => 'Suppliers',
                    'description' => 'Add new suppliers',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'name'        => 'Edit Suppliers',
                    'slug'        => 'edit_suppliers',
                    'module'      => 'Suppliers',
                    'description' => 'Edit existing suppliers',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'name'        => 'Delete Suppliers',
                    'slug'        => 'delete_suppliers',
                    'module'      => 'Suppliers',
                    'description' => 'Delete suppliers',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],

                // Customers
                [
                    'name'        => 'View Customers',
                    'slug'        => 'view_customers',
                    'module'      => 'Customers',
                    'description' => 'View list of customers',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'name'        => 'Create Customers',
                    'slug'        => 'create_customers',
                    'module'      => 'Customers',
                    'description' => 'Add new customers',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'name'        => 'Edit Customers',
                    'slug'        => 'edit_customers',
                    'module'      => 'Customers',
                    'description' => 'Edit existing customers',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'name'        => 'Delete Customers',
                    'slug'        => 'delete_customers',
                    'module'      => 'Customers',
                    'description' => 'Delete customers',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],

                // Purchases
                [
                    'name'        => 'View Purchases',
                    'slug'        => 'view_purchases',
                    'module'      => 'Purchases',
                    'description' => 'View purchase records',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'name'        => 'Create Purchases',
                    'slug'        => 'create_purchases',
                    'module'      => 'Purchases',
                    'description' => 'Create new purchase orders',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'name'        => 'Edit Purchases',
                    'slug'        => 'edit_purchases',
                    'module'      => 'Purchases',
                    'description' => 'Edit existing purchases',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'name'        => 'Delete Purchases',
                    'slug'        => 'delete_purchases',
                    'module'      => 'Purchases',
                    'description' => 'Delete purchase records',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'name'        => 'Receive Purchases',
                    'slug'        => 'receive_purchases',
                    'module'      => 'Purchases',
                    'description' => 'Mark purchases as received and update stock',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],

                // Sales
                [
                    'name'        => 'View Sales',
                    'slug'        => 'view_sales',
                    'module'      => 'Sales',
                    'description' => 'View sales records',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'name'        => 'Create Sales',
                    'slug'        => 'create_sales',
                    'module'      => 'Sales',
                    'description' => 'Create new sales',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'name'        => 'Edit Sales',
                    'slug'        => 'edit_sales',
                    'module'      => 'Sales',
                    'description' => 'Edit existing sales',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'name'        => 'Delete Sales',
                    'slug'        => 'delete_sales',
                    'module'      => 'Sales',
                    'description' => 'Delete sales records',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'name'        => 'Void Sales',
                    'slug'        => 'void_sales',
                    'module'      => 'Sales',
                    'description' => 'Void completed sales',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],

                // POS
                [
                    'name'        => 'Access POS',
                    'slug'        => 'access_pos',
                    'module'      => 'POS',
                    'description' => 'Access the point of sale terminal',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],

                // Stock
                [
                    'name'        => 'View Stock',
                    'slug'        => 'view_stock',
                    'module'      => 'Stock',
                    'description' => 'View stock levels',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'name'        => 'Adjust Stock',
                    'slug'        => 'adjust_stock',
                    'module'      => 'Stock',
                    'description' => 'Create stock adjustments',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'name'        => 'View Stock Movements',
                    'slug'        => 'view_stock_movements',
                    'module'      => 'Stock',
                    'description' => 'View stock movement history',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],

                // Reconciliations
                [
                    'name'        => 'View Reconciliations',
                    'slug'        => 'view_reconciliations',
                    'module'      => 'Reconciliations',
                    'description' => 'View reconciliation records',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'name'        => 'Create Reconciliations',
                    'slug'        => 'create_reconciliations',
                    'module'      => 'Reconciliations',
                    'description' => 'Perform stock reconciliations',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],

                // Expenses
                [
                    'name'        => 'View Expenses',
                    'slug'        => 'view_expenses',
                    'module'      => 'Expenses',
                    'description' => 'View expense records',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'name'        => 'Create Expenses',
                    'slug'        => 'create_expenses',
                    'module'      => 'Expenses',
                    'description' => 'Record new expenses',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'name'        => 'Edit Expenses',
                    'slug'        => 'edit_expenses',
                    'module'      => 'Expenses',
                    'description' => 'Edit existing expenses',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'name'        => 'Delete Expenses',
                    'slug'        => 'delete_expenses',
                    'module'      => 'Expenses',
                    'description' => 'Delete expense records',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],

                // Expense Categories
                [
                    'name'        => 'View Expense Categories',
                    'slug'        => 'view_expense_categories',
                    'module'      => 'Expense Categories',
                    'description' => 'View expense categories',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'name'        => 'Create Expense Categories',
                    'slug'        => 'create_expense_categories',
                    'module'      => 'Expense Categories',
                    'description' => 'Add new expense categories',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'name'        => 'Edit Expense Categories',
                    'slug'        => 'edit_expense_categories',
                    'module'      => 'Expense Categories',
                    'description' => 'Edit expense categories',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'name'        => 'Delete Expense Categories',
                    'slug'        => 'delete_expense_categories',
                    'module'      => 'Expense Categories',
                    'description' => 'Delete expense categories',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],

                // Reports
                [
                    'name'        => 'View Reports',
                    'slug'        => 'view_reports',
                    'module'      => 'Reports',
                    'description' => 'Access reporting module',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'name'        => 'Export Reports',
                    'slug'        => 'export_reports',
                    'module'      => 'Reports',
                    'description' => 'Export reports to CSV or PDF',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],

                // Import/Export
                [
                    'name'        => 'Import Data',
                    'slug'        => 'import_data',
                    'module'      => 'Import/Export',
                    'description' => 'Import data from CSV or Excel files',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'name'        => 'Export Data',
                    'slug'        => 'export_data',
                    'module'      => 'Import/Export',
                    'description' => 'Export data to CSV or Excel files',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],

                // Roles
                [
                    'name'        => 'View Roles',
                    'slug'        => 'view_roles',
                    'module'      => 'Roles',
                    'description' => 'View roles and permissions',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'name'        => 'Create Roles',
                    'slug'        => 'create_roles',
                    'module'      => 'Roles',
                    'description' => 'Create new roles',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'name'        => 'Edit Roles',
                    'slug'        => 'edit_roles',
                    'module'      => 'Roles',
                    'description' => 'Edit existing roles and their permissions',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'name'        => 'Delete Roles',
                    'slug'        => 'delete_roles',
                    'module'      => 'Roles',
                    'description' => 'Delete roles',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],

                // Users
                [
                    'name'        => 'View Users',
                    'slug'        => 'view_users',
                    'module'      => 'Users',
                    'description' => 'View user accounts',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'name'        => 'Create Users',
                    'slug'        => 'create_users',
                    'module'      => 'Users',
                    'description' => 'Create new user accounts',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'name'        => 'Edit Users',
                    'slug'        => 'edit_users',
                    'module'      => 'Users',
                    'description' => 'Edit user accounts',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'name'        => 'Delete Users',
                    'slug'        => 'delete_users',
                    'module'      => 'Users',
                    'description' => 'Delete user accounts',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],

                // Settings
                [
                    'name'        => 'View Settings',
                    'slug'        => 'view_settings',
                    'module'      => 'Settings',
                    'description' => 'View system settings',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'name'        => 'Edit Settings',
                    'slug'        => 'edit_settings',
                    'module'      => 'Settings',
                    'description' => 'Modify system settings',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],

                // Activity
                [
                    'name'        => 'View Activity',
                    'slug'        => 'view_activity',
                    'module'      => 'Activity',
                    'description' => 'View the activity log',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
            ];

            Permission::insert($permissions);
        });
    }
}
