<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Item;
use App\Models\Supplier;
use App\Services\ActivityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ImportExportController extends Controller
{
    public function index()
    {
        $types = [
            'items' => ['label' => 'Items', 'columns' => ['name', 'sku', 'category', 'supplier', 'cost_price', 'selling_price', 'tax_rate', 'unit', 'min_stock', 'reorder_point']],
            'categories' => ['label' => 'Categories', 'columns' => ['name', 'description', 'parent']],
            'customers' => ['label' => 'Customers', 'columns' => ['name', 'email', 'phone', 'address', 'city', 'tin_number', 'customer_type']],
            'suppliers' => ['label' => 'Suppliers', 'columns' => ['name', 'contact_person', 'email', 'phone', 'address', 'city', 'tin_number', 'payment_terms']],
        ];

        return view('import-export.index', compact('types'));
    }

    public function import(Request $request, string $type)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:5120',
        ]);

        $file = $request->file('file');
        $handle = fopen($file->getPathname(), 'r');
        $header = fgetcsv($handle);

        DB::beginTransaction();
        try {
            $imported = 0;
            $skipped = 0;

            while (($row = fgetcsv($handle)) !== false) {
                $data = array_combine($header, $row);

                switch ($type) {
                    case 'items':
                        $this->importItem($data);
                        break;
                    case 'categories':
                        $this->importCategory($data);
                        break;
                    case 'customers':
                        $this->importCustomer($data);
                        break;
                    case 'suppliers':
                        $this->importSupplier($data);
                        break;
                }
                $imported++;
            }

            fclose($handle);

            $activityService = app(ActivityService::class);
            $activityService->log(
                user: auth()->user(),
                action: "import_{$type}",
                subject: 'App\\Models\\' . ucfirst($type),
                description: "Imported {$imported} {$type} from CSV"
            );

            DB::commit();
            return back()->with('success', "Successfully imported {$imported} {$type}.");
        } catch (\Exception $e) {
            DB::rollBack();
            fclose($handle);
            return back()->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    public function export(string $type)
    {
        $filename = "{$type}_export_" . now()->format('Y-m-d') . ".csv";
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($type) {
            $handle = fopen('php://output', 'w');

            switch ($type) {
                case 'items':
                    fputcsv($handle, ['Name', 'SKU', 'Barcode', 'Category', 'Supplier', 'Cost Price', 'Selling Price', 'Tax Rate', 'Unit', 'Min Stock', 'Reorder Point', 'Status']);
                    $items = Item::with(['category', 'supplier'])->get();
                    foreach ($items as $item) {
                        fputcsv($handle, [
                            $item->name,
                            $item->sku,
                            $item->barcode,
                            $item->category->name ?? '',
                            $item->supplier->name ?? '',
                            $item->cost_price,
                            $item->selling_price,
                            $item->tax_rate,
                            $item->unit,
                            $item->min_stock,
                            $item->reorder_point,
                            $item->is_active ? 'Active' : 'Inactive',
                        ]);
                    }
                    break;

                case 'categories':
                    fputcsv($handle, ['Name', 'Description', 'Parent', 'Status']);
                    $categories = Category::with('parent')->get();
                    foreach ($categories as $cat) {
                        fputcsv($handle, [
                            $cat->name,
                            $cat->description,
                            $cat->parent->name ?? '',
                            $cat->is_active ? 'Active' : 'Inactive',
                        ]);
                    }
                    break;

                case 'customers':
                    fputcsv($handle, ['Name', 'Email', 'Phone', 'Address', 'City', 'TIN Number', 'Type', 'Credit Limit', 'Balance', 'Status']);
                    $customers = Customer::get();
                    foreach ($customers as $customer) {
                        fputcsv($handle, [
                            $customer->name,
                            $customer->email,
                            $customer->phone,
                            $customer->address,
                            $customer->city,
                            $customer->tin_number,
                            $customer->customer_type,
                            $customer->credit_limit,
                            $customer->current_balance,
                            $customer->is_active ? 'Active' : 'Inactive',
                        ]);
                    }
                    break;

                case 'suppliers':
                    fputcsv($handle, ['Name', 'Contact Person', 'Email', 'Phone', 'Address', 'City', 'TIN Number', 'Payment Terms', 'Credit Limit', 'Status']);
                    $suppliers = Supplier::get();
                    foreach ($suppliers as $supplier) {
                        fputcsv($handle, [
                            $supplier->name,
                            $supplier->contact_person,
                            $supplier->email,
                            $supplier->phone,
                            $supplier->address,
                            $supplier->city,
                            $supplier->tin_number,
                            $supplier->payment_terms,
                            $supplier->credit_limit,
                            $supplier->is_active ? 'Active' : 'Inactive',
                        ]);
                    }
                    break;
            }

            fclose($handle);
        };

        $activityService = app(ActivityService::class);
        $activityService->log(
            user: auth()->user(),
            action: "export_{$type}",
            subject: 'App\\Models\\' . ucfirst($type),
            description: "Exported {$type} to CSV"
        );

        return response()->stream($callback, 200, $headers);
    }

    private function importItem(array $data): void
    {
        $categoryId = null;
        if (!empty($data['category'])) {
            $categoryId = Category::where('name', $data['category'])->first()?->id;
        }

        $supplierId = null;
        if (!empty($data['supplier'])) {
            $supplierId = Supplier::where('name', $data['supplier'])->first()?->id;
        }

        Item::updateOrCreate(
            ['sku' => $data['sku'] ?? null],
            [
                'name' => $data['name'],
                'category_id' => $categoryId,
                'supplier_id' => $supplierId,
                'cost_price' => $data['cost_price'] ?? 0,
                'selling_price' => $data['selling_price'] ?? 0,
                'tax_rate' => $data['tax_rate'] ?? 0,
                'unit' => $data['unit'] ?? 'pcs',
                'min_stock' => $data['min_stock'] ?? 0,
                'reorder_point' => $data['reorder_point'] ?? 0,
            ]
        );
    }

    private function importCategory(array $data): void
    {
        $parentId = null;
        if (!empty($data['parent'])) {
            $parentId = Category::where('name', $data['parent'])->first()?->id;
        }

        Category::updateOrCreate(
            ['name' => $data['name']],
            [
                'description' => $data['description'] ?? null,
                'parent_id' => $parentId,
            ]
        );
    }

    private function importCustomer(array $data): void
    {
        Customer::updateOrCreate(
            ['name' => $data['name']],
            [
                'email' => $data['email'] ?? null,
                'phone' => $data['phone'] ?? null,
                'address' => $data['address'] ?? null,
                'city' => $data['city'] ?? null,
                'tin_number' => $data['tin_number'] ?? null,
                'customer_type' => $data['customer_type'] ?? null,
                'current_balance' => 0,
            ]
        );
    }

    private function importSupplier(array $data): void
    {
        Supplier::updateOrCreate(
            ['name' => $data['name']],
            [
                'contact_person' => $data['contact_person'] ?? null,
                'email' => $data['email'] ?? null,
                'phone' => $data['phone'] ?? null,
                'address' => $data['address'] ?? null,
                'city' => $data['city'] ?? null,
                'tin_number' => $data['tin_number'] ?? null,
                'payment_terms' => $data['payment_terms'] ?? null,
                'current_balance' => 0,
            ]
        );
    }
}
