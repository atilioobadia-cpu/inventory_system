<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mtokoma - Print')</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            font-size: 12px;
            color: #111827;
            line-height: 1.4;
        }
        .receipt {
            max-width: 300px;
            margin: 0 auto;
            padding: 10px;
        }
        .receipt-header {
            text-align: center;
            border-bottom: 2px dashed #ccc;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        .receipt-header h1 {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 4px;
        }
        .receipt-header p {
            font-size: 10px;
            color: #666;
        }
        .receipt-body table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }
        .receipt-body th,
        .receipt-body td {
            text-align: left;
            padding: 3px 0;
            vertical-align: top;
        }
        .receipt-body th {
            border-bottom: 1px solid #ddd;
            font-weight: 600;
        }
        .receipt-body td:last-child,
        .receipt-body th:last-child {
            text-align: right;
        }
        .receipt-totals {
            border-top: 2px dashed #ccc;
            padding-top: 10px;
            margin-top: 10px;
        }
        .receipt-totals .row {
            display: flex;
            justify-content: space-between;
            padding: 2px 0;
        }
        .receipt-totals .total {
            font-weight: bold;
            font-size: 14px;
            border-top: 1px solid #333;
            padding-top: 4px;
            margin-top: 4px;
        }
        .receipt-footer {
            text-align: center;
            border-top: 2px dashed #ccc;
            padding-top: 10px;
            margin-top: 10px;
            font-size: 10px;
            color: #666;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .mt-2 { margin-top: 8px; }
        .mb-2 { margin-bottom: 8px; }
        @media print {
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .no-print { display: none !important; }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="no-print" style="text-align:center; padding:10px; background:#f0f0f0; margin-bottom:10px;">
        <button onclick="window.print()" style="padding:8px 24px; background:#3B82F6; color:white; border:none; border-radius:4px; cursor:pointer; font-size:14px;">
            Print Receipt
        </button>
        <button onclick="window.close()" style="padding:8px 24px; background:#6b7280; color:white; border:none; border-radius:4px; cursor:pointer; font-size:14px; margin-left:8px;">
            Close
        </button>
    </div>

    <div class="receipt">
        @yield('receipt')
    </div>

    <script>
        window.onload = function() {
            var printParam = new URLSearchParams(window.location.search).get('print');
            if (printParam === '1') {
                setTimeout(function() { window.print(); }, 500);
            }
        };
    </script>
</body>
</html>