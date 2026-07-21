<!DOCTYPE html>
<html>
<head><meta charset="utf-8"></head>
<body style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">
    <div style="background: #1E293B; padding: 20px; text-align: center;">
        <h1 style="color: white; margin: 0;">Mtokoma Motorcycle Parts</h1>
    </div>
    <div style="padding: 20px; background: #f8fafc;">
        <h2 style="color: #F59E0B;">&#9888;&#65039; Low Stock Alert</h2>
        <p style="color: #334155; font-size: 14px; line-height: 1.6;">The following item is running low on stock and requires your attention:</p>
        <table style="width: 100%; border-collapse: collapse; margin: 16px 0; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden;">
            <tr style="background: #1E293B;">
                <td colspan="2" style="padding: 10px 16px; color: white; font-weight: bold; font-size: 14px;">Item Details</td>
            </tr>
            <tr>
                <td style="padding: 10px 16px; font-weight: bold; color: #475569; border-bottom: 1px solid #e2e8f0; width: 40%;">Item</td>
                <td style="padding: 10px 16px; color: #1E293B; border-bottom: 1px solid #e2e8f0;">{{ $item->name }}</td>
            </tr>
            <tr>
                <td style="padding: 10px 16px; font-weight: bold; color: #475569; border-bottom: 1px solid #e2e8f0;">SKU</td>
                <td style="padding: 10px 16px; color: #1E293B; border-bottom: 1px solid #e2e8f0;">{{ $item->sku }}</td>
            </tr>
            @if($item->category)
            <tr>
                <td style="padding: 10px 16px; font-weight: bold; color: #475569; border-bottom: 1px solid #e2e8f0;">Category</td>
                <td style="padding: 10px 16px; color: #1E293B; border-bottom: 1px solid #e2e8f0;">{{ $item->category->name }}</td>
            </tr>
            @endif
            <tr>
                <td style="padding: 10px 16px; font-weight: bold; color: #475569; border-bottom: 1px solid #e2e8f0;">Current Stock</td>
                <td style="padding: 10px 16px; color: #EF4444; font-weight: bold; font-size: 16px; border-bottom: 1px solid #e2e8f0;">{{ $currentStock }}</td>
            </tr>
            <tr>
                <td style="padding: 10px 16px; font-weight: bold; color: #475569; border-bottom: 1px solid #e2e8f0;">Reorder Point</td>
                <td style="padding: 10px 16px; color: #1E293B; border-bottom: 1px solid #e2e8f0;">{{ $item->reorder_point }}</td>
            </tr>
            @if($item->cost_price)
            <tr>
                <td style="padding: 10px 16px; font-weight: bold; color: #475569;">Cost Price</td>
                <td style="padding: 10px 16px; color: #1E293B;">TZS {{ number_format($item->cost_price, 2) }}</td>
            </tr>
            @endif
        </table>

        @if($item->supplier)
        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 16px; margin: 16px 0;">
            <p style="margin: 0 0 8px 0; color: #475569; font-size: 13px; font-weight: bold;">Preferred Supplier:</p>
            <p style="margin: 0; color: #1E293B;">{{ $item->supplier->name }}</p>
        </div>
        @endif

        <p style="color: #334155; font-size: 14px; line-height: 1.6;">Please restock this item as soon as possible to avoid running out of inventory.</p>

        <div style="text-align: center; margin: 24px 0;">
            <a href="{{ url('/items/' . $item->id) }}" style="display: inline-block; background: #F59E0B; color: #ffffff; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: bold; font-size: 14px;">View Item</a>
        </div>
    </div>
    <div style="padding: 16px; text-align: center; color: #94a3b8; font-size: 12px;">
        &copy; {{ date('Y') }} Mtokoma Motorcycle Parts &mdash; This is an automated notification.
    </div>
</body>
</html>
