<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Setting;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ReceiptController extends Controller
{
    public function show(Sale $sale)
    {
        $sale->load(['customer', 'items.item', 'createdBy', 'payments']);
        $settings = Setting::getGroup('business');

        $qrCode = QrCode::size(150)->generate(
            route('receipts.show', $sale)
        );

        return view('receipts.show', compact('sale', 'settings', 'qrCode'));
    }

    public function print(Sale $sale)
    {
        $sale->load(['customer', 'items.item', 'createdBy', 'payments']);
        $settings = Setting::getGroup('business');

        $qrCode = QrCode::size(150)->generate(
            route('receipts.show', $sale)
        );

        return view('receipts.show', compact('sale', 'settings', 'qrCode'));
    }
}
