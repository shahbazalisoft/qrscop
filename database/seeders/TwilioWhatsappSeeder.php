<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TwilioWhatsappSeeder extends Seeder
{
    public function run(): void
    {
        $exists = DB::table('addon_settings')
            ->where('key_name', 'twilio_whatsapp')
            ->where('settings_type', 'sms_config')
            ->exists();

        if (!$exists) {
            $values = json_encode([
                'gateway' => 'twilio_whatsapp',
                'mode' => 'live',
                'status' => 0,
                'sid' => '',
                'token' => '',
                'from' => '',
                'order_message_template' => "🛎 *New Order #{order_id}*\n📍 {store_name}\n👤 {customer_name}\n🍽 {order_type}\n\n*Items:*\n{items}\n💰 Subtotal: {subtotal}\n🏷 Discount: {discount}\n🚚 Delivery: {delivery_fee}\n*Total: {total}*",
            ]);

            DB::table('addon_settings')->insert([
                'id' => Str::uuid(),
                'key_name' => 'twilio_whatsapp',
                'live_values' => $values,
                'test_values' => $values,
                'settings_type' => 'sms_config',
                'mode' => 'live',
                'is_active' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
