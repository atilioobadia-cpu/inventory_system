<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Services\ActivityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($type = $request->input('customer_type')) {
            $query->where('customer_type', $type);
        }

        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        $customers = $query->orderBy('name')->paginate(25)->withQueryString();

        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:customers,name',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'tin_number' => 'nullable|string|max:50',
            'customer_type' => 'nullable|string|max:50',
            'credit_limit' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
            'is_walk_in' => 'boolean',
            'is_active' => 'boolean',
        ]);

        DB::beginTransaction();
        try {
            $validated['current_balance'] = 0;
            $customer = Customer::create($validated);

            $activityService = app(ActivityService::class);
            $activityService->log(
                user: auth()->user(),
                action: 'create_customer',
                subject: $customer,
                description: "Created customer: {$customer->name}",
                newValues: $customer->toArray()
            );

            DB::commit();
            return redirect()->route('customers.show', $customer)->with('success', 'Customer created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to create customer: ' . $e->getMessage());
        }
    }

    public function show(Customer $customer)
    {
        $sales = $customer->sales()
            ->with('items.item')
            ->latest()
            ->paginate(15);

        $totalPurchases = $customer->sales()->sum('total_amount');

        return view('customers.show', compact('customer', 'sales', 'totalPurchases'));
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:customers,name,' . $customer->id,
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'tin_number' => 'nullable|string|max:50',
            'customer_type' => 'nullable|string|max:50',
            'credit_limit' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
            'is_walk_in' => 'boolean',
            'is_active' => 'boolean',
        ]);

        DB::beginTransaction();
        try {
            $oldValues = $customer->toArray();
            $customer->update($validated);

            $activityService = app(ActivityService::class);
            $activityService->log(
                user: auth()->user(),
                action: 'update_customer',
                subject: $customer,
                description: "Updated customer: {$customer->name}",
                oldValues: $oldValues,
                newValues: $customer->toArray()
            );

            DB::commit();
            return redirect()->route('customers.show', $customer)->with('success', 'Customer updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to update customer: ' . $e->getMessage());
        }
    }

    public function destroy(Customer $customer)
    {
        if ($customer->sales()->exists()) {
            return back()->with('error', 'Cannot delete customer with existing sales.');
        }

        DB::beginTransaction();
        try {
            $activityService = app(ActivityService::class);
            $activityService->log(
                user: auth()->user(),
                action: 'delete_customer',
                subject: $customer,
                description: "Deleted customer: {$customer->name}",
                oldValues: $customer->toArray()
            );

            $customer->delete();
            DB::commit();
            return redirect()->route('customers.index')->with('success', 'Customer deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete customer: ' . $e->getMessage());
        }
    }

    public function search(Request $request)
    {
        $search = $request->input('q', '');

        $customers = Customer::where('is_active', true)
            ->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })
            ->limit(20)
            ->get();

        return response()->json($customers);
    }
}
