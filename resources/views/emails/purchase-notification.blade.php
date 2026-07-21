<!DOCTYPE html>
<html>
<head><meta charset="utf-8"></head>
<body style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">
    <div style="background: #1E293B; padding: 20px; text-align: center;">
        <h1 style="color: white; margin: 0;">Mtokoma Motorcycle Parts</h1>
    </div>
    <div style="padding: 20px; background: #f8fafc;">
        <h2 style="color: #3B82F6;">&#128230; New Purchase Order</h2>
        <p style="color: #334155; font-size: 14px; line-height: 1.6;">A new purchase order has been recorded. Below are the details:</p>

        <table style="width: 100%; border-collapse: collapse; margin: 16px 0; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden;">
            <tr style="background: #1E293B;">
                <td colspan="2" style="padding: 10px 16px; color: white; font-weight: bold; font-size: 14px;">Purchase Information</td>
            </tr>
            <tr>
                <td style="padding: 10px 16px; font-weight: bold; color: #475569; border-bottom: 1px solid #e2e8f0; width: 40%;">Invoice Number</td>
                <td style="padding: 10px 16px; color: #1E293B; border-bottom: 1px solid #e2e8f0;">{{ $purchase->invoice_number }}</td>
            </tr>
            <tr>
                <td style="padding: 10px 16px; font-weight: bold; color: #475569; border-bottom: 1px solid #e2e8f0;">Date</td>
                <td style="padding: 10px 16px; color: #1E293B; border-bottom: 1px solid #e2e8f0;">{{ $purchase->created_at->format('d M Y, h:i A') }}</td>
            </tr>
            <tr>
                <td style="padding: 10px 16px; font-weight: bold; color: #475569; border-bottom: 1px solid #e2e8f0;">Supplier</td>
                <td style="padding: 10px 16px; color: #1E293B; border-bottom: 1px solid #e2e8f0;">{{ $purchase->supplier->name ?? 'N/A' }}</td>
            </tr>
            @if($purchase->supplier && $purchase->supplier->phone)
            <tr>
                <td style="padding: 10px 16px; font-weight: bold; color: #475569; border-bottom: 1px solid #e2e8f0;">Supplier Phone</td>
                <td style="padding: 10px 16px; color: #1E293B; border-bottom: 1px solid #e2e8f0;">{{ $purchase->supplier->phone }}</td>
            </tr>
            @endif
            <tr>
                <td style="padding: 10px 16px; font-weight: bold; color: #475569; border-bottom: 1px solid #e2e8f0;">Status</td>
                <td style="padding: 10px 16px; border-bottom: 1px solid #e2e8f0;">
                    <span style="display: inline-block; padding: 3px 10px; border-radius: 12px; font-size: 12px; font-weight: bold; color: #ffffff; background: {{ ($purchase->status ?? 'received') === 'received' ? '#10B981' : (($purchase->status ?? '') === 'pending' ? '#F59E0B' : '#3B82F6') }};">
                        {{ ucfirst($purchase->status ?? 'received') }}
                    </span>
                </td>
            </tr>
            <tr>
                <td style="padding: 10px 16px; font-weight: bold; color: #475569;">Recorded By</td>
                <td style="padding: 10px 16px; color: #1E293B;">{{ $purchase->user->name ?? 'N/A' }}</td>
            </tr>
        </table>

        <p style="color: #475569; font-size: 13px; font-weight: bold; margin: 16px 0 8px 0;">Items Ordered:</p>
        <table style="width: 100%; border-collapse: collapse; margin: 0 0 16px 0; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden;">
            <tr style="background: #1E293B;">
                <td style="padding: 8px 12px; color: white; font-weight: bold; font-size: 12px; text-align: left;">Item</td>
                <td style="padding: 8px 12px; color: white; font-weight: bold; font-size: 12px; text-align: center;">Qty</td>
                <td style="padding: 8px 12px; color: white; font-weight: bold; font-size: 12px; text-align: right;">Unit Price</td>
                <td style="padding: 8px 12px; color: white; font-weight: bold; font-size: 12px; text-align: right;">Subtotal</td>
            </tr>
            @foreach($purchase->items as $index => $item)
            <tr style="background: {{ $index % 2 === 0 ? '#ffffff' : '#f8fafc' }};">
                <td style="padding: 8px 12px; color: #1E293B; font-size: 13px; border-bottom: 1px solid #f1f5f9;">{{ $item->item->name ?? $item->name ?? 'Item' }}</td>
                <td style="padding: 8px 12px; color: #475569; font-size: 13px; text-align: center; border-bottom: 1px solid #f1f5f9;">{{ $item->quantity ?? $item->pivot->quantity ?? '' }}</td>
                <td style="padding: 8px 12px; color: #475569; font-size: 13px; text-align: right; border-bottom: 1px solid #f1f5f9;">TZS {{ number_format($item->unit_cost ?? $item->pivot->unit_cost ?? $item->cost_price ?? 0, 2) }}</td>
                <td style="padding: 8px 12px; color: #1E293B; font-size: 13px; text-align: right; border-bottom: 1px solid #f1f5f9;">TZS {{ number_format($item->subtotal ?? $item->pivot->subtotal ?? (($item->unit_cost ?? $item->cost_price ?? 0) * ($item->quantity ?? $item->pivot->quantity ?? 0)), 2) }}</td>
            </tr>
            @endforeach
        </table>

        <table style="width: 100%; border-collapse: collapse; margin: 16px 0; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden;">
            @if(isset($purchase->discount) && $purchase->discount > 0)
            <tr>
                <td style="padding: 10px 16px; color: #475569; border-bottom: 1px solid #e2e8f0;">Discount</td>
                <td style="padding: 10px 16px; color: #EF4444; text-align: right; border-bottom: 1px solid #e2e8f0;">- TZS {{ number_format($purchase->discount, 2) }}</td>
            </tr>
            @endif
            @if(isset($purchase->tax) && $purchase->tax > 0)
            <tr>
                <td style="padding: 10px 16px; color: #475569; border-bottom: 1px solid #e2e8f0;">Tax</td>
                <td style="padding: 10px 16px; color: #1E293B; text-align: right; border-bottom: 1px solid #e2e8f0;">TZS {{ number_format($purchase->tax, 2) }}</td>
            </tr>
            @endif
            <tr style="background: #3B82F6;">
                <td style="padding: 12px 16px; color: white; font-weight: bold; font-size: 16px;">Total Amount</td>
                <td style="padding: 12px 16px; color: white; font-weight: bold; font-size: 16px; text-align: right;">TZS {{ number_format($purchase->total_amount, 2) }}</td>
            </tr>
        </table>

        <div style="text-align: center; margin: 24px 0;">
            <a href="{{ url('/purchases/' . $purchase->id) }}" style="display: inline-block; background: #3B82F6; color: #ffffff; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: bold; font-size: 14px;">View Purchase Details</a>
        </div>
    </div>
    <div style="padding: 16px; text-align: center; color: #94a3b8; font-size: 12px;">
        &copy; {{ date('Y') }} Mtokoma Motorcycle Parts &mdash; This is an automated notification.
    </div>
</body>
</html>
