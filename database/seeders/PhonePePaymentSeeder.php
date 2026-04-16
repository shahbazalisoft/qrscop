<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PhonePePaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if PhonePe already exists
        $exists = DB::table('addon_settings')
            ->where('key_name', 'phonepe')
            ->where('settings_type', 'payment_config')
            ->exists();

        if (!$exists) {
            DB::table('addon_settings')->insert([
                'id' => Str::uuid(),
                'key_name' => 'phonepe',
                'live_values' => json_encode([
                    'gateway' => 'phonepe',
                    'mode' => 'test',
                    'status' => 0,
                    'merchant_id' => '',
                    'salt_key' => '',
                    'salt_index' => '1',
                ]),
                'test_values' => json_encode([
                    'gateway' => 'phonepe',
                    'mode' => 'test',
                    'status' => 0,
                    'merchant_id' => '',
                    'salt_key' => '',
                    'salt_index' => '1',
                ]),
                'settings_type' => 'payment_config',
                'mode' => 'test',
                'is_active' => 0,
                'additional_data' => json_encode([
                    'gateway_title' => 'PhonePe',
                    'gateway_image' => '',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
