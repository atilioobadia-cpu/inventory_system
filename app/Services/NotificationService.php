<?php

namespace App\Services;

use App\Models\Expense;
use App\Models\Item;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    public function sendLowStockAlert(Item $item, int $currentStock): void
    {
        if (!$this->isEmailEnabled()) {
            return;
        }

        $recipients = $this->getRecipients('low_stock');

        if (empty($recipients)) {
            return;
        }

        $data = [
            'item_name'     => $item->name,
            'sku'           => $item->sku,
            'current_stock' => $currentStock,
            'reorder_point' => $item->reorder_point ?? 0,
        ];

        foreach ($recipients as $email) {
            Mail::raw(
                "Low Stock Alert: {$item->name} ({$item->sku})\n"
                . "Current Stock: {$currentStock}\n"
                . "Reorder Point: {$item->reorder_point}\n"
                . "Please create a purchase order to restock.",
                function ($message) use ($email, $item) {
                    $message->to($email)
                        ->subject("Low Stock Alert - {$item->name}")
                        ->from(config('mail.from.address'), config('mail.from.name'));
                }
            );
        }
    }

    public function sendSaleNotification(Sale $sale): void
    {
        if (!$this->isEmailEnabled()) {
            return;
        }

        $recipients = $this->getRecipients('sale');

        if (empty($recipients)) {
            return;
        }

        $sale->load('items', 'customer', 'createdBy');

        $body = "New Sale Completed\n"
            . "Receipt #: {$sale->invoice_number}\n"
            . "Date: {$sale->sale_date->format('d M Y, H:i')}\n"
            . "Total: TZS " . number_format($sale->total_amount) . "\n"
            . "Payment Status: {$sale->payment_status}";

        foreach ($recipients as $email) {
            Mail::raw($body, function ($message) use ($email, $sale) {
                $message->to($email)
                    ->subject("New Sale - Receipt #{$sale->invoice_number} - TZS " . number_format($sale->total_amount))
                    ->from(config('mail.from.address'), config('mail.from.name'));
            });
        }
    }

    public function sendPurchaseNotification(Purchase $purchase): void
    {
        if (!$this->isEmailEnabled()) {
            return;
        }

        $recipients = $this->getRecipients('purchase');

        if (empty($recipients)) {
            return;
        }

        $purchase->load('items', 'supplier', 'createdBy');

        $body = "New Purchase Created\n"
            . "Invoice #: {$purchase->invoice_number}\n"
            . "Date: {$purchase->purchase_date->format('d M Y')}\n"
            . "Supplier: {$purchase->supplier->name}\n"
            . "Total: TZS " . number_format($purchase->total_amount) . "\n"
            . "Status: {$purchase->status}";

        foreach ($recipients as $email) {
            Mail::raw($body, function ($message) use ($email, $purchase) {
                $message->to($email)
                    ->subject("New Purchase - {$purchase->invoice_number} - TZS " . number_format($purchase->total_amount))
                    ->from(config('mail.from.address'), config('mail.from.name'));
            });
        }
    }

    public function sendExpenseNotification(Expense $expense): void
    {
        if (!$this->isEmailEnabled()) {
            return;
        }

        $recipients = $this->getRecipients('expense');

        if (empty($recipients)) {
            return;
        }

        $body = "New Expense Recorded\n"
            . "Description: {$expense->description}\n"
            . "Amount: TZS " . number_format($expense->amount) . "\n"
            . "Date: {$expense->expense_date->format('d M Y')}";

        foreach ($recipients as $email) {
            Mail::raw($body, function ($message) use ($email, $expense) {
                $message->to($email)
                    ->subject("New Expense - TZS " . number_format($expense->amount))
                    ->from(config('mail.from.address'), config('mail.from.name'));
            });
        }
    }

    public function sendVoidNotification(Sale $sale, string $reason): void
    {
        if (!$this->isEmailEnabled()) {
            return;
        }

        $recipients = $this->getRecipients('void');

        if (empty($recipients)) {
            return;
        }

        $body = "Sale Voided\n"
            . "Receipt #: {$sale->invoice_number}\n"
            . "Amount: TZS " . number_format($sale->total_amount) . "\n"
            . "Reason: {$reason}";

        foreach ($recipients as $email) {
            Mail::raw($body, function ($message) use ($email, $sale) {
                $message->to($email)
                    ->subject("Sale Voided - {$sale->invoice_number}")
                    ->from(config('mail.from.address'), config('mail.from.name'));
            });
        }
    }

    public function sendStockAdjustmentNotification(Item $item, int $oldStock, int $newStock): void
    {
        if (!$this->isEmailEnabled()) {
            return;
        }

        $recipients = $this->getRecipients('stock_adjustment');

        if (empty($recipients)) {
            return;
        }

        $difference = $newStock - $oldStock;
        $direction = $difference > 0 ? 'increased' : 'decreased';

        $body = "Stock Adjustment\n"
            . "Item: {$item->name} ({$item->sku})\n"
            . "Previous Stock: {$oldStock}\n"
            . "New Stock: {$newStock}\n"
            . "Stock {$direction} by " . abs($difference);

        foreach ($recipients as $email) {
            Mail::raw($body, function ($message) use ($email, $item) {
                $message->to($email)
                    ->subject("Stock Adjustment - {$item->name}")
                    ->from(config('mail.from.address'), config('mail.from.name'));
            });
        }
    }

    private function getRecipients(string $type): array
    {
        $recipients = [];

        $roleMap = [
            'sale'              => ['manager', 'super-admin'],
            'purchase'          => ['manager', 'warehouse', 'super-admin'],
            'expense'           => ['accountant', 'super-admin'],
            'void'              => ['manager', 'super-admin'],
            'stock_adjustment'  => ['manager', 'warehouse', 'super-admin'],
            'low_stock'         => ['manager', 'warehouse', 'super-admin'],
        ];

        $roleSlugs = $roleMap[$type] ?? ['super-admin'];

        $users = User::whereHas('role', function ($query) use ($roleSlugs) {
            $query->whereIn('slug', $roleSlugs);
        })->where('is_active', true)->get();

        foreach ($users as $user) {
            if ($user->email) {
                $recipients[] = $user->email;
            }
        }

        return array_unique($recipients);
    }

    private function isEmailEnabled(): bool
    {
        return DB::table('settings')
            ->where('group', 'system')
            ->where('key', 'enable_email_notifications')
            ->value('value') === '1';
    }
}
