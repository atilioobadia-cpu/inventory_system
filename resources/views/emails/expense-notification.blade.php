<!DOCTYPE html>
<html>
<head><meta charset="utf-8"></head>
<body style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">
    <div style="background: #1E293B; padding: 20px; text-align: center;">
        <h1 style="color: white; margin: 0;">Mtokoma Motorcycle Parts</h1>
    </div>
    <div style="padding: 20px; background: #f8fafc;">
        <h2 style="color: #F59E0B;">&#128176; New Expense Recorded</h2>
        <p style="color: #334155; font-size: 14px; line-height: 1.6;">A new expense has been recorded in the system. Below are the details:</p>

        <table style="width: 100%; border-collapse: collapse; margin: 16px 0; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden;">
            <tr style="background: #1E293B;">
                <td colspan="2" style="padding: 10px 16px; color: white; font-weight: bold; font-size: 14px;">Expense Information</td>
            </tr>
            <tr>
                <td style="padding: 10px 16px; font-weight: bold; color: #475569; border-bottom: 1px solid #e2e8f0; width: 40%;">Reference Number</td>
                <td style="padding: 10px 16px; color: #1E293B; border-bottom: 1px solid #e2e8f0;">{{ $expense->reference_number }}</td>
            </tr>
            <tr>
                <td style="padding: 10px 16px; font-weight: bold; color: #475569; border-bottom: 1px solid #e2e8f0;">Date</td>
                <td style="padding: 10px 16px; color: #1E293B; border-bottom: 1px solid #e2e8f0;">{{ $expense->created_at->format('d M Y, h:i A') }}</td>
            </tr>
            <tr>
                <td style="padding: 10px 16px; font-weight: bold; color: #475569; border-bottom: 1px solid #e2e8f0;">Category</td>
                <td style="padding: 10px 16px; color: #1E293B; border-bottom: 1px solid #e2e8f0;">{{ $expense->category->name ?? 'Uncategorized' }}</td>
            </tr>
            <tr>
                <td style="padding: 10px 16px; font-weight: bold; color: #475569; border-bottom: 1px solid #e2e8f0;">Payment Method</td>
                <td style="padding: 10px 16px; color: #1E293B; border-bottom: 1px solid #e2e8f0;">{{ ucfirst(str_replace('_', ' ', $expense->payment_method ?? 'Cash')) }}</td>
            </tr>
            @if($expense->description)
            <tr>
                <td style="padding: 10px 16px; font-weight: bold; color: #475569; border-bottom: 1px solid #e2e8f0;">Description</td>
                <td style="padding: 10px 16px; color: #1E293B; border-bottom: 1px solid #e2e8f0;">{{ $expense->description }}</td>
            </tr>
            @endif
            <tr>
                <td style="padding: 10px 16px; font-weight: bold; color: #475569; border-bottom: 1px solid #e2e8f0;">Recorded By</td>
                <td style="padding: 10px 16px; color: #1E293B; border-bottom: 1px solid #e2e8f0;">{{ $expense->user->name ?? 'N/A' }}</td>
            </tr>
        </table>

        <table style="width: 100%; border-collapse: collapse; margin: 16px 0; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden;">
            <tr style="background: #F59E0B;">
                <td style="padding: 12px 16px; color: white; font-weight: bold; font-size: 16px;">Expense Amount</td>
                <td style="padding: 12px 16px; color: white; font-weight: bold; font-size: 16px; text-align: right;">TZS {{ number_format($expense->amount, 2) }}</td>
            </tr>
        </table>

        <div style="text-align: center; margin: 24px 0;">
            <a href="{{ url('/expenses/' . $expense->id) }}" style="display: inline-block; background: #F59E0B; color: #ffffff; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: bold; font-size: 14px;">View Expense Details</a>
        </div>
    </div>
    <div style="padding: 16px; text-align: center; color: #94a3b8; font-size: 12px;">
        &copy; {{ date('Y') }} Mtokoma Motorcycle Parts &mdash; This is an automated notification.
    </div>
</body>
</html>
