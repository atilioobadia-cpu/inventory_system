<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Services\ActivityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $query = Supplier::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('contact_person', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        $suppliers = $query->orderBy('name')->paginate(25)->withQueryString();

        return view('suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:suppliers,name',
            'contact_person' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'tin_number' => 'nullable|string|max:50',
            'payment_terms' => 'nullable|string|max:255',
            'credit_limit' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);

        DB::beginTransaction();
        try {
            $validated['current_balance'] = 0;
            $supplier = Supplier::create($validated);

            $activityService = app(ActivityService::class);
            $activityService->log(
                user: auth()->user(),
                action: 'create_supplier',
                subject: $supplier,
                description: "Created supplier: {$supplier->name}",
                newValues: $supplier->toArray()
            );

            DB::commit();
            return redirect()->route('suppliers.show', $supplier)->with('success', 'Supplier created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to create supplier: ' . $e->getMessage());
        }
    }

    public function show(Supplier $supplier)
    {
        $purchases = $supplier->purchases()
            ->with('items')
            ->latest()
            ->paginate(15);

        return view('suppliers.show', compact('supplier', 'purchases'));
    }

    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:suppliers,name,' . $supplier->id,
            'contact_person' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'tin_number' => 'nullable|string|max:50',
            'payment_terms' => 'nullable|string|max:255',
            'credit_limit' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);

        DB::beginTransaction();
        try {
            $oldValues = $supplier->toArray();
            $supplier->update($validated);

            $activityService = app(ActivityService::class);
            $activityService->log(
                user: auth()->user(),
                action: 'update_supplier',
                subject: $supplier,
                description: "Updated supplier: {$supplier->name}",
                oldValues: $oldValues,
                newValues: $supplier->toArray()
            );

            DB::commit();
            return redirect()->route('suppliers.show', $supplier)->with('success', 'Supplier updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to update supplier: ' . $e->getMessage());
        }
    }

    public function destroy(Supplier $supplier)
    {
        if ($supplier->purchases()->exists()) {
            return back()->with('error', 'Cannot delete supplier with existing purchases.');
        }

        DB::beginTransaction();
        try {
            $activityService = app(ActivityService::class);
            $activityService->log(
                user: auth()->user(),
                action: 'delete_supplier',
                subject: $supplier,
                description: "Deleted supplier: {$supplier->name}",
                oldValues: $supplier->toArray()
            );

            $supplier->delete();
            DB::commit();
            return redirect()->route('suppliers.index')->with('success', 'Supplier deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete supplier: ' . $e->getMessage());
        }
    }

    public function search(Request $request)
    {
        $search = $request->input('q', '');

        $suppliers = Supplier::where('is_active', true)
            ->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('contact_person', 'like', "%{$search}%");
            })
            ->limit(20)
            ->get();

        return response()->json($suppliers);
    }
}
