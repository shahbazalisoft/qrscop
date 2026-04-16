<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Store;
use App\Models\MenuOrder;
use App\Models\MenuOrderItem;
use App\Services\TwilioWhatsappService;

class MenuCartController extends Controller
{
    private function sessionKey($storeId)
    {
        return 'menu_cart_' . $storeId;
    }

    private function getCart($storeId)
    {
        return session($this->sessionKey($storeId), []);
    }

    private function saveCart($storeId, $cart)
    {
        session([$this->sessionKey($storeId) => $cart]);
    }

    private function cartResponse($storeId)
    {
        $cart = array_values($this->getCart($storeId));
        $count = 0;
        $subtotal = 0;
        foreach ($cart as $item) {
            $count += $item['qty'];
            $subtotal += $item['price'] * $item['qty'];
        }

        return response()->json([
            'cart' => $cart,
            'count' => $count,
            'subtotal' => $subtotal,
        ]);
    }

    public function add(Request $request)
    {
        $request->validate([
            'store_id' => 'required|integer',
            'cart_key' => 'required|string',
            'index' => 'required|integer',
            'item_id' => 'nullable|integer',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'mrp' => 'nullable|numeric|min:0',
            'img' => 'nullable|string',
            'is_veg' => 'required',
            'size' => 'required|string',
            'qty' => 'required|integer|min:1',
        ]);

        $storeId = $request->store_id;
        $cart = $this->getCart($storeId);
        $cartKey = $request->cart_key;

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['qty'] += $request->qty;
        } else {
            $cart[$cartKey] = [
                'cartKey' => $cartKey,
                'index' => $request->index,
                'item_id' => $request->item_id ?? 0,
                'name' => $request->name,
                'price' => $request->price,
                'mrp' => $request->mrp ?? $request->price,
                'img' => $request->img ?? '',
                'qty' => $request->qty,
                'isVeg' => (bool) $request->is_veg,
                'size' => $request->size,
            ];
        }

        $this->saveCart($storeId, $cart);

        return $this->cartResponse($storeId);
    }

    public function get(Request $request)
    {
        $storeId = $request->query('store_id', 0);

        return $this->cartResponse($storeId);
    }

    public function updateQty(Request $request)
    {
        $request->validate([
            'store_id' => 'required|integer',
            'cart_key' => 'required|string',
            'delta' => 'required|integer',
        ]);

        $storeId = $request->store_id;
        $cart = $this->getCart($storeId);
        $cartKey = $request->cart_key;

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['qty'] += $request->delta;
            if ($cart[$cartKey]['qty'] <= 0) {
                unset($cart[$cartKey]);
            }
        }

        $this->saveCart($storeId, $cart);

        return $this->cartResponse($storeId);
    }

    public function remove(Request $request)
    {
        $request->validate([
            'store_id' => 'required|integer',
            'cart_key' => 'required|string',
        ]);

        $storeId = $request->store_id;
        $cart = $this->getCart($storeId);
        unset($cart[$request->cart_key]);
        $this->saveCart($storeId, $cart);

        return $this->cartResponse($storeId);
    }

    public function clear(Request $request)
    {
        $request->validate([
            'store_id' => 'required|integer',
        ]);

        $storeId = $request->store_id;
        session()->forget($this->sessionKey($storeId));

        return $this->cartResponse($storeId);
    }

    public function placeOrder(Request $request)
    {
        $request->validate([
            'store_id' => 'required|integer',
            'device_id' => 'required|string|max:64',
            'order_type' => 'required|in:dine-in,delivery',
            'customer_name' => 'nullable|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'delivery_address' => 'nullable|string',
            'instructions' => 'nullable|string',
        ]);

        $storeId = $request->store_id;
        $cart = $this->getCart($storeId);

        if (empty($cart)) {
            return response()->json(['error' => 'Cart is empty'], 422);
        }

        $subtotal = 0;
        $mrpTotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['qty'];
            $mrpTotal += ($item['mrp'] ?? $item['price']) * $item['qty'];
        }

        $discount = $mrpTotal - $subtotal;
        if ($discount < 0) $discount = 0;

        $store = Store::find($storeId);
        $deliveryFee = ($request->order_type === 'delivery' && $store) ? ($store->delivery_charg ?? 0) : 0;
        $total = $subtotal + $deliveryFee;

        $orderId = 'ORD-' . strtoupper(substr(base_convert(time(), 10, 36), -4)) . rand(10, 99);

        $order = MenuOrder::create([
            'store_id' => $storeId,
            'device_id' => $request->device_id,
            'order_id' => $orderId,
            'order_type' => $request->order_type,
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'delivery_address' => $request->delivery_address,
            'instructions' => $request->instructions,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'delivery_fee' => $deliveryFee,
            'total' => $total,
            'status' => 'pending',
            'table_no' => $request->table_no ?? null,

        ]);

        foreach ($cart as $item) {
            MenuOrderItem::create([
                'menu_order_id' => $order->id,
                'item_id' => $item['item_id'] ?? null,
                'item_name' => $item['name'],
                'item_price' => $item['price'],
                'quantity' => $item['qty'],
                'size' => $item['size'] ?? 'default',
                'image' => $item['img'] ?? '',
            ]);

            // Increment item order_count
            if (!empty($item['item_id'])) {
                Item::where('id', $item['item_id'])->increment('order_count');
                if ($store) {
                    $store->increment('total_order');
                }
            }
        }
        $customer = Customer::firstOrCreate(
            ['phone' => $request->customer_phone],
            [
                'name' => $request->customer_name,
                'store_id' => $storeId,
                'total_order' => 0
            ]
        );
        // Update customer_id in order
        $order->update([
            'customer_id' => $customer->id
        ]);
        // increment order count
        $customer->increment('total_order');

        // Send WhatsApp notification to vendor
        if ($store) {
            TwilioWhatsappService::sendOrderNotification($order, $store);
        }

        // Clear cart after order placed
        session()->forget($this->sessionKey($storeId));

        return response()->json([
            'success' => true,
            'order_id' => $orderId,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'delivery_fee' => $deliveryFee,
            'total' => $total,
            'item_count' => array_sum(array_column($cart, 'qty')),
        ]);
    }

    public function getOrders(Request $request)
    {
        $request->validate([
            'store_id' => 'required|integer',
            'device_id' => 'nullable|string|max:64',
            'phone' => 'nullable|string|max:20',
        ]);

        $orders = MenuOrder::where('store_id', $request->store_id)
            ->where(function ($q) use ($request) {
                if ($request->device_id) {
                    $q->where('device_id', $request->device_id);
                }
                if ($request->phone) {
                    $q->orWhere('customer_phone', $request->phone);
                }
            })
            ->with('items')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        return response()->json([
            'orders' => $orders->map(function ($order) {
                return [
                    'order_id' => $order->order_id,
                    'order_type' => $order->order_type,
                    'status' => $order->status,
                    'subtotal' => $order->subtotal,
                    'discount' => $order->discount ?? 0,
                    'delivery_fee' => $order->delivery_fee ?? 0,
                    'total' => $order->total,
                    'date' => $order->created_at->format('d M Y, h:i A'),
                    'items' => $order->items->map(function ($item) {
                        return [
                            'name' => $item->item_name,
                            'price' => $item->item_price,
                            'qty' => $item->quantity,
                            'size' => $item->size,
                        ];
                    }),
                ];
            }),
        ]);
    }

    public function getOrderedItems(Request $request)
    {
        $request->validate([
            'store_id' => 'required|integer',
            'device_id' => 'nullable|string|max:64',
            'phone' => 'nullable|string|max:20',
        ]);

        $orderIds = MenuOrder::where('store_id', $request->store_id)
            ->where(function ($q) use ($request) {
                if ($request->device_id) {
                    $q->where('device_id', $request->device_id);
                }
                if ($request->phone) {
                    $q->orWhere('customer_phone', $request->phone);
                }
            })
            ->pluck('id');

        if ($orderIds->isEmpty()) {
            return response()->json(['items' => []]);
        }

        $orderedItems = MenuOrderItem::whereIn('menu_order_id', $orderIds)
            ->select('item_id', \DB::raw('SUM(quantity) as total_qty'), \DB::raw('MAX(created_at) as last_ordered'))
            ->groupBy('item_id')
            ->orderByDesc('last_ordered')
            ->limit(20)
            ->get();

        $itemIds = $orderedItems->pluck('item_id')->filter();
        $items = Item::withoutGlobalScopes()->whereIn('id', $itemIds)->get()->keyBy('id');

        $result = [];
        foreach ($orderedItems as $oi) {
            $item = $items->get($oi->item_id);
            if (!$item) continue;

            $discount = $item->discount ?? 0;
            $discountType = $item->discount_type ?? 'percent';
            $discountedPrice = $discount > 0
                ? ($discountType === 'percent'
                    ? round($item->price - ($item->price * $discount / 100))
                    : max(0, $item->price - $discount))
                : $item->price;

            $result[] = [
                'id' => $item->id,
                'name' => $item->name,
                'price' => $discountedPrice,
                'mrp' => $item->price,
                'image' => $item->image_full_url,
                'veg' => $item->veg,
                'description' => $item->description ?? '',
                'discount' => $discount,
                'discount_type' => $discountType,
                'total_ordered' => $oi->total_qty,
            ];
        }

        return response()->json(['items' => $result]);
    }

    public function itemDetail(Request $request)
    {
        $request->validate([
            'item_id' => 'required|integer',
        ]);

        $item = Item::withoutGlobalScopes()->find($request->item_id);

        if (!$item) {
            return response()->json(['error' => 'Item not found'], 404);
        }

        $foodVariations = $item->food_variations
            ? json_decode($item->food_variations, true)
            : [];

        return response()->json([
            'id' => $item->id,
            'name' => $item->name,
            'description' => $item->description ?? '',
            'price' => $item->price,
            'discount' => $item->discount ?? 0,
            'discount_type' => $item->discount_type ?? 'percent',
            'image' => $item->image_full_url,
            'images' => $item->images_full_url,
            'veg' => $item->veg,
            'food_variations' => $foodVariations,
            'available_time_starts' => $item->available_time_starts,
            'available_time_ends' => $item->available_time_ends,
        ]);
    }
}
