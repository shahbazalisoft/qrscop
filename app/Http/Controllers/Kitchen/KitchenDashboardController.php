<?php

namespace App\Http\Controllers\Kitchen;

use App\Http\Controllers\Controller;
use App\Models\MenuOrder;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class KitchenDashboardController extends Controller
{
    private function getStoreId()
    {
        return Auth::guard('kitchen')->user()->store_id;
    }

    private function getStore()
    {
        return Auth::guard('kitchen')->user()->store;
    }

    public function index()
    {
        $storeId = $this->getStoreId();
        $today = Carbon::today();

        $pendingCount = MenuOrder::where('store_id', $storeId)
            ->where('status', 'pending')->count();

        $confirmedCount = MenuOrder::where('store_id', $storeId)
            ->where('status', 'confirmed')->count();

        $preparingCount = MenuOrder::where('store_id', $storeId)
            ->where('status', 'preparing')->count();

        $completedToday = MenuOrder::where('store_id', $storeId)
            ->where('status', 'completed')
            ->whereDate('created_at', $today)->count();

        $totalToday = MenuOrder::where('store_id', $storeId)
            ->whereDate('created_at', $today)->count();

        $activeOrders = MenuOrder::with('items')
            ->where('store_id', $storeId)
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        $store = $this->getStore();

        return view('kitchen.dashboard', compact(
            'pendingCount', 'confirmedCount', 'preparingCount',
            'completedToday', 'totalToday', 'activeOrders', 'store'
        ));
    }

    public function stats()
    {
        $storeId = $this->getStoreId();
        $today = Carbon::today();

        $hourlyData = [];
        for ($h = 0; $h < 24; $h++) {
            $count = MenuOrder::where('store_id', $storeId)
                ->whereDate('created_at', $today)
                ->whereTime('created_at', '>=', sprintf('%02d:00:00', $h))
                ->whereTime('created_at', '<', sprintf('%02d:00:00', $h + 1))
                ->count();
            $hourlyData[] = [
                'hour' => sprintf('%02d:00', $h),
                'count' => $count,
            ];
        }

        return response()->json(['hourly' => $hourlyData]);
    }

    public function orders(Request $request)
    {
        $storeId = $this->getStoreId();
        $status = $request->status;
        $orderType = $request->order_type;

        $orders = MenuOrder::with('items')
            ->where('store_id', $storeId)
            ->when($status && $status !== 'all', function ($q) use ($status) {
                $q->where('status', $status);
            })
            ->when($orderType && $orderType !== 'all', function ($q) use ($orderType) {
                $q->where('order_type', $orderType);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $store = $this->getStore();

        $statusCounts = [
            'all' => MenuOrder::where('store_id', $storeId)->count(),
            'pending' => MenuOrder::where('store_id', $storeId)->where('status', 'pending')->count(),
            'confirmed' => MenuOrder::where('store_id', $storeId)->where('status', 'confirmed')->count(),
            'preparing' => MenuOrder::where('store_id', $storeId)->where('status', 'preparing')->count(),
            'completed' => MenuOrder::where('store_id', $storeId)->where('status', 'completed')->count(),
            'cancelled' => MenuOrder::where('store_id', $storeId)->where('status', 'cancelled')->count(),
        ];

        return view('kitchen.orders', compact('orders', 'status', 'orderType', 'statusCounts', 'store'));
    }

    public function updateOrderStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,preparing,completed,cancelled',
        ]);

        $storeId = $this->getStoreId();
        $order = MenuOrder::where('store_id', $storeId)->findOrFail($id);
        $order->status = $request->status;
        $order->checked = 1;
        $order->save();

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'status' => $order->status]);
        }

        return back()->with('success', 'Order status updated');
    }

    public function items(Request $request)
    {
        $storeId = $this->getStoreId();
        $search = $request->search;

        $items = Item::where('store_id', $storeId)
            ->when($search, function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->paginate(15);

        $store = $this->getStore();

        return view('kitchen.items', compact('items', 'search', 'store'));
    }

    public function updateItemStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:0,1',
        ]);

        $storeId = $this->getStoreId();
        $item = Item::where('store_id', $storeId)->findOrFail($id);
        $item->status = $request->status;
        $item->save();

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'status' => $item->status]);
        }

        return back()->with('success', 'Item status updated');
    }

    public function checkNewOrders(Request $request)
    {
        $storeId = $this->getStoreId();
        $lastId = (int) $request->last_id;

        $newOrders = MenuOrder::with('items')
            ->where('store_id', $storeId)
            ->where('id', '>', $lastId)
            ->where('status', 'pending')
            ->orderBy('id', 'asc')
            ->get();

        $ordersData = [];
        foreach ($newOrders as $order) {
            $items = [];
            foreach ($order->items as $item) {
                $items[] = [
                    'name' => $item->item_name,
                    'quantity' => $item->quantity,
                    'price' => $item->item_price,
                    'size' => $item->size ?? '',
                ];
            }
            $ordersData[] = [
                'id' => $order->id,
                'order_id' => $order->order_id,
                'status' => $order->status,
                'order_type' => $order->order_type ?? '',
                'table_no' => $order->table_no,
                'customer_name' => $order->customer_name ?? '',
                'customer_phone' => $order->customer_phone ?? '',
                'delivery_address' => $order->delivery_address ?? '',
                'instructions' => $order->instructions ?? '',
                'subtotal' => (float) ($order->subtotal ?? 0),
                'discount' => (float) ($order->discount ?? 0),
                'delivery_fee' => (float) ($order->delivery_fee ?? 0),
                'total' => (float) ($order->total ?? 0),
                'created_at' => $order->created_at->format('d M Y, h:i A'),
                'time_ago' => $order->created_at->diffForHumans(),
                'items' => $items,
            ];
        }

        return response()->json([
            'new_orders' => $ordersData,
            'pending_count' => MenuOrder::where('store_id', $storeId)->where('status', 'pending')->count(),
            'active_count' => MenuOrder::where('store_id', $storeId)->whereNotIn('status', ['completed', 'cancelled'])->count(),
        ]);
    }

    public function logout()
    {
        $user = Auth::guard('kitchen')->user();
        if ($user) {
            $user->is_logged_in = 0;
            $user->save();
        }
        Auth::guard('kitchen')->logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect()->route('kitchen.login');
    }
}
