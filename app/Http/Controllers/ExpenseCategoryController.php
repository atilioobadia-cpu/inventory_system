<?php

namespace App\Http\Controllers;

use App\Models\ExpenseCategory;
use App\Services\ActivityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExpenseCategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = ExpenseCategory::withCount('expenses');

        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%");
        }

        $categories = $query->orderBy('name')->paginate(25)->withQueryString();

        return view('expense-categories.index', compact('categories'));
    }

    public function create()
    {
        return view('expense-categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:expense_categories,name',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);

        DB::beginTransaction();
        try {
            $category = ExpenseCategory::create($validated);

            $activityService = app(ActivityService::class);
            $activityService->log(
                user: auth()->user(),
                action: 'create_expense_category',
                subject: $category,
                description: "Created expense category: {$category->name}",
                newValues: $category->toArray()
            );

            DB::commit();
            return redirect()->route('expense-categories.index')->with('success', 'Expense category created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to create expense category: ' . $e->getMessage());
        }
    }

    public function show(ExpenseCategory $expenseCategory)
    {
        $expenseCategory->loadCount('expenses');
        $recentExpenses = $expenseCategory->expenses()
            ->with('createdBy')
            ->latest('expense_date')
            ->limit(20)
            ->get();

        return view('expense-categories.show', compact('expenseCategory', 'recentExpenses'));
    }

    public function edit(ExpenseCategory $expenseCategory)
    {
        return view('expense-categories.edit', compact('expenseCategory'));
    }

    public function update(Request $request, ExpenseCategory $expenseCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:expense_categories,name,' . $expenseCategory->id,
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);

        DB::beginTransaction();
        try {
            $oldValues = $expenseCategory->toArray();
            $expenseCategory->update($validated);

            $activityService = app(ActivityService::class);
            $activityService->log(
                user: auth()->user(),
                action: 'update_expense_category',
                subject: $expenseCategory,
                description: "Updated expense category: {$expenseCategory->name}",
                oldValues: $oldValues,
                newValues: $expenseCategory->toArray()
            );

            DB::commit();
            return redirect()->route('expense-categories.index')->with('success', 'Expense category updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to update expense category: ' . $e->getMessage());
        }
    }

    public function destroy(ExpenseCategory $expenseCategory)
    {
        if ($expenseCategory->expenses()->exists()) {
            return back()->with('error', 'Cannot delete expense category with existing expenses.');
        }

        DB::beginTransaction();
        try {
            $activityService = app(ActivityService::class);
            $activityService->log(
                user: auth()->user(),
                action: 'delete_expense_category',
                subject: $expenseCategory,
                description: "Deleted expense category: {$expenseCategory->name}",
                oldValues: $expenseCategory->toArray()
            );

            $expenseCategory->delete();
            DB::commit();
            return redirect()->route('expense-categories.index')->with('success', 'Expense category deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete expense category: ' . $e->getMessage());
        }
    }
}
