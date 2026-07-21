@extends('layouts.print')

@section('title', 'EFD Receipt - ' . $sale->invoice_number)

@push('styles')
<style>
    body {
        font-family: 'Courier New', Courier, monospace;
        background: #f0f0f0;
    }
    .receipt-wrapper {
        max-width: 320px;
        margin: 20px auto;
        background: #fff;
        border: 1px dashed #999;
        padding: 15px;
    }
    .receipt-header-section {
        text-align: center;
        margin-bottom: 10px;
    }
    .receipt-header-section h1 {
        font-size: 16px;
        font-weight: bold;
        margin-bottom: 2px;
        letter-spacing: 0.5px;
    }
    .receipt-header-section p {
        font-size: 10px;
        color: #555;
        line-height: 1.3;
    }
    .receipt-divider {
        border: none;
        border-top: 1px dashed #999;
        margin: 8px 0;
    }
    .receipt-info p {
        font-size: 11px;
        margin: 2px 0;
    }
    .receipt-info .label {
        color: #555;
    }
    .receipt-items {
        margin: 8px 0;
    }
    .receipt-item {
        margin-bottom: 6px;
    }
    .receipt-item-name {
        font-size: 11px;
        font-weight: bold;
        margin-bottom: 1px;
    }
    .receipt-item-detail {
        font-size: 10px;
        color: #555;
    }
    .receipt-totals {
        margin-top: 8px;
    }
    .receipt-totals .row {
        display: flex;
        justify-content: space-between;
        font-size: 11px;
        padding: 2px 0;
    }
    .receipt-totals .total-row {
        font-size: 13px;
        font-weight: bold;
        border-top: 1px dashed #999;
        padding-top: 4px;
        margin-top: 4px;
    }
    .receipt-payment {
        margin-top: 8px;
    }
    .receipt-payment p {
        font-size: 11px;
        margin: 2px 0;
    }
    .receipt-qr {
        text-align: center;
        margin: 12px 0;
    }
    .receipt-qr p {
        font-size: 9px;
        color: #777;
        margin-top: 4px;
    }
    .receipt-footer {
        text-align: center;
        margin-top: 10px;
    }
    .receipt-footer p {
        font-size: 10px;
        color: #666;
    }
    .receipt-footer .efd-tag {
        font-size: 11px;
        font-weight: bold;
        margin-top: 4px;
        letter-spacing: 1px;
    }
    @media print {
        body {
            background: #fff;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .receipt-wrapper {
            border: 1px dashed #999;
            margin: 0;
            box-shadow: none;
        }
        .no-print {
            display: none !important;
        }
    }
</style>
@endpush

@section('receipt')
<div class="receipt-wrapper">
    {{-- 1. Header --}}
    <div class="receipt-header-section">
        <h1>{{ $settings['name'] ?? 'Mtokoma Motorcycle Parts' }}</h1>
        <p>{{ $settings['address'] ?? '' }}</p>
        <p>TIN: {{ $settings['tin_number'] ?? '' }}</p>
        <p>{{ $settings['phone'] ?? '' }}</p>
    </div>

    {{-- 2. Divider --}}
    <hr class="receipt-divider">

    {{-- 3. Receipt Info --}}
    <div class="receipt-info">
        <p><span class="label">Receipt:</span> {{ $sale->invoice_number }}</p>
        <p><span class="label">Date:</span> {{ $sale->sale_date->format('d/m/Y H:i') }}</p>
        <p><span class="label">Cashier:</span> {{ $sale->createdBy->name ?? 'N/A' }}</p>
        <p><span class="label">Customer:</span> {{ $sale->customer->name ?? 'Walk-In' }}</p>
        <p><span class="label">Type:</span> {{ ucfirst($sale->sale_type) }}</p>
    </div>

    {{-- 4. Divider --}}
    <hr class="receipt-divider">

    {{-- 5. Items --}}
    <div class="receipt-items">
        @foreach($sale->items as $item)
        <div class="receipt-item">
            <div class="receipt-item-name">{{ substr($item->item->name ?? $item->name ?? '-', 0, 20) }}</div>
            <div class="receipt-item-detail">{{ $item->quantity }} x {{ number_format($item->unit_price) }} = {{ number_format($item->quantity * $item->unit_price) }}</div>
        </div>
        @endforeach
    </div>

    {{-- 6. Divider --}}
    <hr class="receipt-divider">

    {{-- 7. Totals --}}
    <div class="receipt-totals">
        <div class="row">
            <span>Subtotal:</span>
            <span>TZS {{ number_format($sale->subtotal) }}</span>
        </div>
        @if(!$sale->is_vat_exempt)
        <div class="row">
            <span>VAT (18%):</span>
            <span>TZS {{ number_format($sale->tax_amount) }}</span>
        </div>
        @endif
        @if($sale->discount_amount > 0)
        <div class="row">
            <span>Discount:</span>
            <span>-TZS {{ number_format($sale->discount_amount) }}</span>
        </div>
        @endif
        <div class="row total-row">
            <span>TOTAL:</span>
            <span>TZS {{ number_format($sale->total_amount) }}</span>
        </div>
    </div>

    {{-- 8. Divider --}}
    <hr class="receipt-divider">

    {{-- 9. Payment Info --}}
    <div class="receipt-payment">
        <p><span class="label">Method:</span> {{ ucfirst($sale->sale_type) }}</p>
        <p><span class="label">Received:</span> TZS {{ number_format($sale->paid_amount) }}</p>
        @if($sale->due_amount > 0)
        <p><span class="label">Due:</span> TZS {{ number_format($sale->due_amount) }}</p>
        @else
        <p><span class="label">Change:</span> TZS {{ number_format($sale->paid_amount - $sale->total_amount) }}</p>
        @endif
    </div>

    {{-- 10. Divider --}}
    <hr class="receipt-divider">

    {{-- 11. QR Code --}}
    <div class="receipt-qr">
        {!! QrCode::size(120)->generate(json_encode([
            'receipt' => $sale->invoice_number,
            'date' => $sale->sale_date->format('Y-m-d'),
            'total' => $sale->total_amount,
            'tin' => $settings['tin_number'] ?? '123-456-789'
        ])) !!}
        <p>Scan to verify receipt</p>
    </div>

    {{-- 12. Footer --}}
    <hr class="receipt-divider">
    <div class="receipt-footer">
        <p>Thank you for your purchase!</p>
        <p class="efd-tag">*** EFD Receipt ***</p>
    </div>
</div>

{{-- Print Button (below receipt) --}}
<div class="text-center mt-6 no-print">
    <button onclick="window.print()" class="px-6 py-2 bg-primary hover:bg-primary-hover text-white rounded-lg font-medium">Print Receipt</button>
    <a href="{{ route('sales.show', $sale) }}" class="px-6 py-2 bg-white hover:bg-white text-body rounded-lg font-medium ml-2">Back to Sale</a>
</div>
@endsection
