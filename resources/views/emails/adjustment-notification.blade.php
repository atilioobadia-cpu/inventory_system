<!DOCTYPE html>
<html>
<head><meta charset="utf-8"></head>
<body style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">
    <div style="background: #1E293B; padding: 20px; text-align: center;">
        <h1 style="color: white; margin: 0;">Mtokoma Motorcycle Parts</h1>
    </div>
    <div style="padding: 20px; background: #f8fafc;">
        <h2 style="color: #8B5CF6;">&#128203; Stock Adjustment Made</h2>
        <p style="color: #334155; font-size: 14px; line-height: 1.6;">A stock adjustment has been recorded in the system. Below are the details:</p>

        <table style="width: 100%; border-collapse: collapse; margin: 16px 0; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden;">
            <tr style="background: #1E293B;">
                <td colspan="2" style="padding: 10px 16px; color: white; font-weight: bold; font-size: 14px;">Adjustment Details</td>
            </tr>
            <tr>
                <td style="padding: 10px 16px; font-weight: bold; color: #475569; border-bottom: 1px solid #e2e8f0; width: 40%;">Item Name</td>
                <td style="padding: 10px 16px; color: #1E293B; border-bottom: 1px solid #e2e8f0;">{{ $adjustment->item->name ?? $item->name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td style="padding: 10px 16px; font-weight: bold; color: #475569; border-bottom: 1px solid #e2e8f0;">SKU</td>
                <td style="padding: 10px 16px; color: #1E293B; border-bottom: 1px solid #e2e8f0;">{{ $adjustment->item->sku ?? $item->sku ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td style="padding: 10px 16px; font-weight: bold; color: #475569; border-bottom: 1px solid #e2e8f0;">Adjustment Type</td>
                <td style="padding: 10px 16px; border-bottom: 1px solid #e2e8f0;">
                    <span style="display: inline-block; padding: 3px 10px; border-radius: 12px; font-size: 12px; font-weight: bold; color: #ffffff; background: {{ ($adjustment->type ?? $adjustment->adjustment_type ?? 'addition') === 'addition' ? '#10B981' : '#EF4444' }};">
                        {{ ucfirst($adjustment->type ?? $adjustment->adjustment_type ?? 'Addition') }}
                    </span>
                </td>
            </tr>
            <tr>
                <td style="padding: 10px 16px; font-weight: bold; color: #475569; border-bottom: 1px solid #e2e8f0;">Previous Stock</td>
                <td style="padding: 10px 16px; color: #1E293B; border-bottom: 1px solid #e2e8f0;">{{ $adjustment->previous_stock ?? $previousStock ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td style="padding: 10px 16px; font-weight: bold; color: #475569; border-bottom: 1px solid #e2e8f0;">New Stock</td>
                <td style="padding: 10px 16px; color: #1E293B; border-bottom: 1px solid #e2e8f0; font-weight: bold;">{{ $adjustment->new_stock ?? $newStock ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td style="padding: 10px 16px; font-weight: bold; color: #475569; border-bottom: 1px solid #e2e8f0;">Difference</td>
                <td style="padding: 10px 16px; font-weight: bold; border-bottom: 1px solid #e2e8f0;">
                    @php
                        $diff = $adjustment->difference ?? (($adjustment->new_stock ?? $newStock ?? 0) - ($adjustment->previous_stock ?? $previousStock ?? 0));
                        $diffColor = $diff >= 0 ? '#10B981' : '#EF4444';
                    @endphp
                    <span style="color: {{ $diffColor }};">{{ $diff >= 0 ? '+' : '' }}{{ $diff }}</span>
                </td>
            </tr>
            @if(isset($adjustment->reason) || isset($adjustment->notes))
            <tr>
                <td style="padding: 10px 16px; font-weight: bold; color: #475569; border-bottom: 1px solid #e2e8f0;">Notes</td>
                <td style="padding: 10px 16px; color: #1E293B; border-bottom: 1px solid #e2e8f0;">{{ $adjustment->reason ?? $adjustment->notes ?? '' }}</td>
            </tr>
            @endif
            <tr>
                <td style="padding: 10px 16px; font-weight: bold; color: #475569; border-bottom: 1px solid #e2e8f0;">Date</td>
                <td style="padding: 10px 16px; color: #1E293B; border-bottom: 1px solid #e2e8f0;">{{ ($adjustment->created_at ?? now())->format('d M Y, h:i A') }}</td>
            </tr>
            <tr>
                <td style="padding: 10px 16px; font-weight: bold; color: #475569;">Adjusted By</td>
                <td style="padding: 10px 16px; color: #1E293B;">{{ $adjustment->user->name ?? $adjustedBy ?? 'N/A' }}</td>
            </tr>
        </table>

        <div style="text-align: center; margin: 24px 0;">
            <a href="{{ url('/stock/movements') }}" style="display: inline-block; background: #8B5CF6; color: #ffffff; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: bold; font-size: 14px;">View Stock Movements</a>
        </div>
    </div>
    <div style="padding: 16px; text-align: center; color: #94a3b8; font-size: 12px;">
        &copy; {{ date('Y') }} Mtokoma Motorcycle Parts &mdash; This is an automated notification.
    </div>
</body>
</html>
