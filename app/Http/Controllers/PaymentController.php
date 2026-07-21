<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Purchase;
use App\Models\Sale;
use App\Services\ActivityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['payable', 'createdBy']);

        if ($method = $request->input('payment_method')) {
            $query->where('payment_method', $method);
        }

        if ($type = $request->input('payable_type')) {
            $query->where('payable_type', $type === 'purchase' ? Purchase::class : Sale::class);
        }

        if ($from = $request->input('from_date')) {
            $query->whereDate('payment_date', '>=', $from);
        }

        if ($to = $request->input('to_date')) {
            $query->whereDate('payment_date', '<=', $to);
        }

        $payments = $query->latest('payment_date')->paginate(25)->withQueryString();

        return view('payments.index', compact('payments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'payable_type' => 'required|in:purchase,sale',
            'payable_id' => 'required|integer',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,card,mobile,bank',
            'payment_date' => 'required|date',
            'reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $payableType = $validated['payable_type'] === 'purchase' ? Purchase::class : Sale::class;
            $payable = $payableType::findOrFail($validated['payable_id']);

            $remaining = $payable->total_amount - $payable->paid_amount;
            if ($validated['amount'] > $remaining) {
                return back()->withInput()->with('error', 'Payment amount exceeds remaining balance of TZS ' . number_format($remaining));
            }

            $payment = Payment::create([
                'payable_type' => $payableType,
                'payable_id' => $validated['payable_id'],
                'amount' => $validated['amount'],
                'payment_method' => $validated['payment_method'],
                'payment_date' => $validated['payment_date'],
                'reference' => $validated['reference'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'created_by' => auth()->id(),
            ]);

            $newPaidAmount = $payable->paid_amount + $validated['amount'];
            $newDueAmount = max(0, $payable->total_amount - $newPaidAmount);
            $paymentStatus = 'paid';
            if ($newDueAmount > 0 && $newPaidAmount > 0) {
                $paymentStatus = 'partial';
            }

            $payable->update([
                'paid_amount' => $newPaidAmount,
                'due_amount' => $newDueAmount,
                'payment_status' => $paymentStatus,
            ]);

            $activityService = app(ActivityService::class);
            $activityService->log(
                user: auth()->user(),
                action: 'create_payment',
                subject: $payment,
                description: "Payment of TZS " . number_format($validated['amount']) . " for {$validated['payable_type']}: {$payable->invoice_number}"
            );

            DB::commit();
            return back()->with('success', 'Payment recorded successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to record payment: ' . $e->getMessage());
        }
    }
}
