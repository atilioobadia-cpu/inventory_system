<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $settings = [
                // Business group
                ['group' => 'business', 'key' => 'name', 'value' => 'Mtokoma Motorcycle Parts', 'created_at' => now(), 'updated_at' => now()],
                ['group' => 'business', 'key' => 'address', 'value' => 'Dar es Salaam, Tanzania', 'created_at' => now(), 'updated_at' => now()],
                ['group' => 'business', 'key' => 'phone', 'value' => '+255 123 456 789', 'created_at' => now(), 'updated_at' => now()],
                ['group' => 'business', 'key' => 'email', 'value' => 'info@mtokoma.co.tz', 'created_at' => now(), 'updated_at' => now()],
                ['group' => 'business', 'key' => 'tin_number', 'value' => '123-456-789', 'created_at' => now(), 'updated_at' => now()],
                ['group' => 'business', 'key' => 'vat_number', 'value' => 'VAT-12345678', 'created_at' => now(), 'updated_at' => now()],
                ['group' => 'business', 'key' => 'currency', 'value' => 'TZS', 'created_at' => now(), 'updated_at' => now()],
                ['group' => 'business', 'key' => 'vat_rate', 'value' => '18', 'created_at' => now(), 'updated_at' => now()],

                // Receipt group
                ['group' => 'receipt', 'key' => 'header_text', 'value' => 'Mtokoma Motorcycle Parts', 'created_at' => now(), 'updated_at' => now()],
                ['group' => 'receipt', 'key' => 'footer_text', 'value' => 'Thank you for your purchase!', 'created_at' => now(), 'updated_at' => now()],
                ['group' => 'receipt', 'key' => 'show_logo', 'value' => '1', 'created_at' => now(), 'updated_at' => now()],
                ['group' => 'receipt', 'key' => 'paper_size', 'value' => '80', 'created_at' => now(), 'updated_at' => now()],

                // System group
                ['group' => 'system', 'key' => 'low_stock_threshold', 'value' => '10', 'created_at' => now(), 'updated_at' => now()],
                ['group' => 'system', 'key' => 'enable_email_notifications', 'value' => '1', 'created_at' => now(), 'updated_at' => now()],
                ['group' => 'system', 'key' => 'enable_sms_notifications', 'value' => '0', 'created_at' => now(), 'updated_at' => now()],
            ];

            DB::table('settings')->insert($settings);
        });
    }
}
