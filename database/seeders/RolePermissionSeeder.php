<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $allPermissions = Permission::pluck('id', 'slug');

            $rolePermissions = $this->getRolePermissions();

            foreach ($rolePermissions as $roleSlug => $permissionSlugs) {
                $role = Role::where('slug', $roleSlug)->first();

                if ($role) {
                    $permissionIds = [];

                    foreach ($permissionSlugs as $slug) {
                        if (isset($allPermissions[$slug])) {
                            $permissionIds[] = $allPermissions[$slug];
                        }
                    }

                    $role->permissions()->sync($permissionIds);
                }
            }
        });
    }

    private function getRolePermissions(): array
    {
        return [
            'super-admin' => [
                'view_dashboard',
                'view_items', 'create_items', 'edit_items', 'delete_items',
                'view_categories', 'create_categories', 'edit_categories', 'delete_categories',
                'view_suppliers', 'create_suppliers', 'edit_suppliers', 'delete_suppliers',
                'view_customers', 'create_customers', 'edit_customers', 'delete_customers',
                'view_purchases', 'create_purchases', 'edit_purchases', 'delete_purchases', 'receive_purchases',
                'view_sales', 'create_sales', 'edit_sales', 'delete_sales', 'void_sales',
                'access_pos',
                'view_stock', 'adjust_stock', 'view_stock_movements',
                'view_reconciliations', 'create_reconciliations',
                'view_expenses', 'create_expenses', 'edit_expenses', 'delete_expenses',
                'view_expense_categories', 'create_expense_categories', 'edit_expense_categories', 'delete_expense_categories',
                'view_reports', 'export_reports',
                'import_data', 'export_data',
                'view_roles', 'create_roles', 'edit_roles', 'delete_roles',
                'view_users', 'create_users', 'edit_users', 'delete_users',
                'view_settings', 'edit_settings',
                'view_activity',
            ],

            'manager' => [
                'view_dashboard',
                'view_items', 'create_items', 'edit_items', 'delete_items',
                'view_categories', 'create_categories', 'edit_categories', 'delete_categories',
                'view_suppliers', 'create_suppliers', 'edit_suppliers', 'delete_suppliers',
                'view_customers', 'create_customers', 'edit_customers', 'delete_customers',
                'view_purchases', 'create_purchases', 'edit_purchases', 'delete_purchases', 'receive_purchases',
                'view_sales', 'create_sales', 'edit_sales', 'delete_sales', 'void_sales',
                'access_pos',
                'view_stock', 'adjust_stock', 'view_stock_movements',
                'view_reconciliations', 'create_reconciliations',
                'view_expenses', 'create_expenses', 'edit_expenses', 'delete_expenses',
                'view_expense_categories', 'create_expense_categories', 'edit_expense_categories', 'delete_expense_categories',
                'view_reports', 'export_reports',
                'import_data', 'export_data',
                'view_roles', 'create_roles', 'edit_roles',
                'view_users', 'create_users', 'edit_users',
                'view_settings',
                'view_activity',
            ],

            'cashier' => [
                'view_dashboard',
                'view_items',
                'view_customers', 'create_customers',
                'view_sales', 'create_sales', 'void_sales',
                'access_pos',
                'view_stock',
            ],

            'warehouse' => [
                'view_dashboard',
                'view_items', 'edit_items', 'create_items',
                'view_suppliers', 'create_suppliers', 'edit_suppliers',
                'view_purchases', 'create_purchases', 'edit_purchases', 'receive_purchases',
                'view_stock', 'adjust_stock', 'view_stock_movements',
            ],

            'accountant' => [
                'view_dashboard',
                'view_purchases',
                'view_expenses', 'create_expenses', 'edit_expenses',
                'view_expense_categories',
                'view_reports', 'export_reports',
                'view_reconciliations', 'create_reconciliations',
            ],

            'viewer' => [
                'view_dashboard',
                'view_items',
                'view_categories',
                'view_suppliers',
                'view_customers',
                'view_purchases',
                'view_sales',
                'view_stock',
                'view_stock_movements',
                'view_reconciliations',
                'view_expenses',
                'view_expense_categories',
                'view_reports',
                'view_roles',
                'view_users',
                'view_settings',
                'view_activity',
            ],
        ];
    }
}
