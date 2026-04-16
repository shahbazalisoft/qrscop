<?php

namespace App\Services;

use App\Models\MenuOrder;
use App\Models\Store;
use App\Models\Setting;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;

class TwilioWhatsappService
{
    public static function sendOrderNotification(MenuOrder $order, Store $store)
    {
        try {
            $config = Setting::where('key_name', 'twilio_whatsapp')
                ->where('settings_type', 'sms_config')
                ->first();

            if (!$config) {
                return;
            }

            $values = $config->live_values;

            if (empty($values['status']) || $values['status'] == 0) {
                return;
            }

            // Static WhatsApp number for order notifications
            $storePhone = '+918804727597';

            // Build items list
            $order->loadMissing('items');
            $itemLines = '';
            foreach ($order->items as $item) {
                $size = ($item->size && $item->size !== 'default') ? " ({$item->size})" : '';
                $itemLines .= "• {$item->quantity}x {$item->item_name}{$size} — " . number_format($item->item_price * $item->quantity, 2) . "\n";
            }

            $template = $values['order_message_template'] ?? "🛎 *New Order #{order_id}*\n📍 {store_name}\n👤 {customer_name}\n🍽 {order_type}\n\n*Items:*\n{items}\n💰 Subtotal: {subtotal}\n🏷 Discount: {discount}\n🚚 Delivery: {delivery_fee}\n*Total: {total}*";

            $message = str_replace(
                ['{order_id}', '{customer_name}', '{total}', '{order_type}', '{store_name}', '{items}', '{subtotal}', '{discount}', '{delivery_fee}'],
                [
                    $order->order_id,
                    $order->customer_name ?? 'Guest',
                    number_format($order->total, 2),
                    ucfirst($order->order_type),
                    $store->name ?? '',
                    $itemLines,
                    number_format($order->subtotal, 2),
                    number_format($order->discount ?? 0, 2),
                    number_format($order->delivery_fee ?? 0, 2),
                ],
                $template
            );

            // Clean "from" number — strip existing whatsapp: prefix if admin entered it
            $fromNumber = preg_replace('/^whatsapp:/', '', $values['from']);

            $client = new Client($values['sid'], $values['token']);

            $client->messages->create(
                'whatsapp:' . $storePhone,
                [
                    'from' => 'whatsapp:' . $fromNumber,
                    'body' => $message,
                ]
            );
        } catch (\Exception $e) {
            Log::error('Twilio WhatsApp notification failed: ' . $e->getMessage());
        }
    }
}
