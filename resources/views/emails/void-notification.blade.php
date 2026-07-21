<!DOCTYPE html>
<html>
<head><meta charset="utf-8"></head>
<body style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">
    <div style="background: #1E293B; padding: 20px; text-align: center;">
        <h1 style="color: white; margin: 0;">Mtokoma Motorcycle Parts</h1>
    </div>
    <div style="padding: 20px; background: #f8fafc;">
        <h2 style="color: #EF4444;">&#10060; Sale Voided</h2>
        <p style="color: #334155; font-size: 14px; line-height: 1.6;">A sale has been voided. Please review the details below:</p>

        <table style="width: 100%; border-collapse: collapse; margin: 16px 0; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden;">
            <tr style="background: #1E293B;">
                <td colspan="2" style="padding: 10px 16px; color: white; font-weight: bold; font-size: 14px;">Voided Sale Information</td>
            </tr>
            <tr>
                <td style="padding: 10px 16px; font-weight: bold; color: #475569; border-bottom: 1px solid #e2e8f0; width: 40%;">Invoice Number</td>
                <td style="padding: 10px 16px; color: #1E293B; border-bottom: 1px solid #e2e8f0;">{{ $sale->invoice_number }}</td>
            </tr>
            <tr>
                <td style="padding: 10px 16px; font-weight: bold; color: #475569; border-bottom: 1px solid #e2e8f0;">Sale Date</td>
                <td style="padding: 10px 16px; color: #1E293B; border-bottom: 1px solid #e2e8f0;">{{ $sale->created_at->format('d M Y, h:i A') }}</td>
            </tr>
            <tr>
                <td style="padding: 10px 16px; font-weight: bold; color: #475569; border-bottom: 1px solid #e2e8f0;">Customer</td>
                <td style="padding: 10px 16px; color: #1E293B; border-bottom: 1px solid #e2e8f0;">{{ $sale->customer->name ?? 'Walk-in Customer' }}</td>
            </tr>
            <tr>
                <td style="padding: 10px 16px; font-weight: bold; color: #475569; border-bottom: 1px solid #e2e8f0;">Original Amount</td>
                <td style="padding: 10px 16px; color: #EF4444; font-weight: bold; font-size: 16px; border-bottom: 1px solid #e2e8f0;">TZS {{ number_format($sale->total_amount, 2) }}</td>
            </tr>
            <tr>
                <td style="padding: 10px 16px; font-weight: bold; color: #475569; border-bottom: 1px solid #e2e8f0;">Void Reason</td>
                <td style="padding: 10px 16px; color: #1E293B; border-bottom: 1px solid #e2e8f0;">{{ $sale->void_reason ?? 'No reason provided' }}</td>
            </tr>
            <tr>
                <td style="padding: 10px 16px; font-weight: bold; color: #475569; border-bottom: 1px solid #e2e8f0;">Voided By</td>
                <td style="padding: 10px 16px; color: #1E293B; border-bottom: 1px solid #e2e8f0;">{{ $sale->voided_by ?? $sale->voidedBy->name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td style="padding: 10px 16px; font-weight: bold; color: #475569;">Voided At</td>
                <td style="padding: 10px 16px; color: #1E293B;">{{ $sale->voided_at instanceof \Carbon\Carbon ? $sale->voided_at->format('d M Y, h:i A') : ($sale->voided_at ?? 'N/A') }}</td>
            </tr>
        </table>

        @if(isset($sale->items) && $sale->items->count())
        <p style="color: #475569; font-size: 13px; font-weight: bold; margin: 16px 0 8px 0;">Items in Voided Sale:</p>
        <table style="width: 100%; border-collapse: collapse; margin: 0 0 16px 0; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden;">
            <tr style="background: #1E293B;">
                <td style="padding: 8px 12px; color: white; font-weight: bold; font-size: 12px; text-align: left;">Item</td>
                <td style="padding: 8px 12px; color: white; font-weight: bold; font-size: 12px; text-align: center;">Qty</td>
                <td style="padding: 8px 12px; color: white; font-weight: bold; font-size: 12px; text-align: right;">Subtotal</td>
            </tr>
            @foreach($sale->items as $index => $item)
            <tr style="background: {{ $index % 2 === 0 ? '#ffffff' : '#f8fafc' }};">
                <td style="padding: 8px 12px; color: #1E293B; font-size: 13px; border-bottom: 1px solid #f1f5f9;">{{ $item->name ?? $item->item->name ?? 'Item' }}</td>
                <td style="padding: 8px 12px; color: #475569; font-size: 13px; text-align: center; border-bottom: 1px solid #f1f5f9;">{{ $item->quantity ?? $item->pivot->quantity ?? '' }}</td>
                <td style="padding: 8px 12px; color: #1E293B; font-size: 13px; text-align: right; border-bottom: 1px solid #f1f5f9;">TZS {{ number_format($item->subtotal ?? $item->pivot->subtotal ?? ($item->price ?? 0) * ($item->quantity ?? $item->pivot->quantity ?? 0), 2) }}</td>
            </tr>
            @endforeach
        </table>
        @endif

        <div style="background: #FEF2F2; border: 1px solid #FECACA; border-radius: 8px; padding: 16px; margin: 16px 0; text-align: center;">
            <p style="margin: 0; color: #991B1B; font-size: 13px;">Stock levels have been restored for the voided items. Please verify inventory counts if necessary.</p>
        </div>

        <div style="text-align: center; margin: 24px 0;">
            <a href="{{ url('/sales/' . $sale->id) }}" style="display: inline-block; background: #EF4444; color: #ffffff; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: bold; font-size: 14px;">View Sale Details</a>
        </div>
    </div>
    <div style="padding: 16px; text-align: center; color: #94a3b8; font-size: 12px;">
        &copy; {{ date('Y') }} Mtokoma Motorcycle Parts &mdash; This is an automated notification.
    </div>
</body>
</html>
