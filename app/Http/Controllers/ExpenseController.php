<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Services\ActivityService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $query = Expense::with(['category', 'createdBy']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('reference_number', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($categoryId = $request->input('expense_category_id')) {
            $query->where('expense_category_id', $categoryId);
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($from = $request->input('from')) {
            $query->whereDate('expense_date', '>=', $from);
        }

        if ($to = $request->input('to')) {
            $query->whereDate('expense_date', '<=', $to);
        }

        $expenses = $query->latest('expense_date')->paginate(25)->withQueryString();
        $categories = ExpenseCategory::where('is_active', true)->orderBy('name')->get();

        return view('expenses.index', compact('expenses', 'categories'));
    }

    public function create()
    {
        $categories = ExpenseCategory::where('is_active', true)->orderBy('name')->get();

        return view('expenses.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'expense_category_id' => 'required|exists:expense_categories,id',
            'amount' => 'required|numeric|min:0.01',
            'expense_date' => 'required|date',
            'payment_method' => 'required|in:cash,card,mobile,bank,bank_transfer,mobile_money,other',
            'reference' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'receipt' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,pdf|max:2048',
            'is_recurring' => 'boolean',
            'recurring_frequency' => 'nullable|string|max:50',
            'recurring_end_date' => 'nullable|date|after:expense_date',
        ]);

        DB::beginTransaction();
        try {
            if ($request->hasFile('receipt')) {
                $validated['receipt_path'] = $request->file('receipt')->store('receipts', 'public');
            }

            $validated['status'] = 'pending';
            $validated['created_by'] = auth()->id();
            unset($validated['receipt']);

            $expense = Expense::create($validated);

            $activityService = app(ActivityService::class);
            $activityService->log(
                user: auth()->user(),
                action: 'create_expense',
                subject: $expense,
                description: "Created expense: {$expense->reference_number} - TZS " . number_format($expense->amount)
            );

            $notificationService = app(NotificationService::class);
            $notificationService->sendExpenseNotification($expense);

            DB::commit();
            return redirect()->route('expenses.show', $expense)->with('success', 'Expense recorded successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to record expense: ' . $e->getMessage());
        }
    }

    public function show(Expense $expense)
    {
        $expense->load(['category', 'createdBy', 'approvedBy']);

        return view('expenses.show', compact('expense'));
    }

    public function edit(Expense $expense)
    {
        if ($expense->status === 'approved') {
            return back()->with('error', 'Cannot edit approved expenses.');
        }

        $categories = ExpenseCategory::where('is_active', true)->orderBy('name')->get();

        return view('expenses.edit', compact('expense', 'categories'));
    }

    public function update(Request $request, Expense $expense)
    {
        if ($expense->status === 'approved') {
            return back()->with('error', 'Cannot update approved expenses.');
        }

        $validated = $request->validate([
            'expense_category_id' => 'required|exists:expense_categories,id',
            'amount' => 'required|numeric|min:0.01',
            'expense_date' => 'required|date',
            'payment_method' => 'required|in:cash,card,mobile,bank,bank_transfer,mobile_money,other',
            'reference' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'receipt' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,pdf|max:2048',
            'is_recurring' => 'boolean',
            'recurring_frequency' => 'nullable|string|max:50',
            'recurring_end_date' => 'nullable|date|after:expense_date',
        ]);

        DB::beginTransaction();
        try {
            if ($request->hasFile('receipt')) {
                $validated['receipt_path'] = $request->file('receipt')->store('receipts', 'public');
            }

            unset($validated['receipt']);
            $oldValues = $expense->toArray();
            $expense->update($validated);

            $activityService = app(ActivityService::class);
            $activityService->log(
                user: auth()->user(),
                action: 'update_expense',
                subject: $expense,
                description: "Updated expense: {$expense->reference_number}",
                oldValues: $oldValues,
                newValues: $expense->toArray()
            );

            DB::commit();
            return redirect()->route('expenses.show', $expense)->with('success', 'Expense updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to update expense: ' . $e->getMessage());
        }
    }

    public function destroy(Expense $expense)
    {
        if ($expense->status === 'approved') {
            return back()->with('error', 'Cannot delete approved expenses.');
        }

        DB::beginTransaction();
        try {
            $activityService = app(ActivityService::class);
            $activityService->log(
                user: auth()->user(),
                action: 'delete_expense',
                subject: $expense,
                description: "Deleted expense: {$expense->reference_number}",
                oldValues: $expense->toArray()
            );

            $expense->delete();
            DB::commit();
            return redirect()->route('expenses.index')->with('success', 'Expense deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete expense: ' . $e->getMessage());
        }
    }
}
