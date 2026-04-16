<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\MenuOrder;
use App\CentralLogics\Helpers;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;

class MenuOrderController extends Controller
{
    public function list(Request $request, $status = 'all')
    {
        $storeId = Helpers::get_store_id();
        $search = $request->search;

        $orders = MenuOrder::with('items')
            ->where('store_id', $storeId)
            ->when($status !== 'all', function ($q) use ($status) {
                $q->where('status', $status);
            })
            ->when($search, function ($q) use ($search) {
                $q->where(function ($query) use ($search) {
                    $query->where('order_id', 'like', "%{$search}%")
                        ->orWhere('customer_name', 'like', "%{$search}%")
                        ->orWhere('customer_phone', 'like', "%{$search}%");
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(25);

        $statusCounts = [
            'all' => MenuOrder::where('store_id', $storeId)->count(),
            'pending' => MenuOrder::where('store_id', $storeId)->where('status', 'pending')->count(),
            'confirmed' => MenuOrder::where('store_id', $storeId)->where('status', 'confirmed')->count(),
            'preparing' => MenuOrder::where('store_id', $storeId)->where('status', 'preparing')->count(),
            'completed' => MenuOrder::where('store_id', $storeId)->where('status', 'completed')->count(),
            'cancelled' => MenuOrder::where('store_id', $storeId)->where('status', 'cancelled')->count(),
        ];

        // Mark unchecked orders as checked
        MenuOrder::where('store_id', $storeId)->where('checked', 0)->update(['checked' => 1]);

        return view('vendor-views.menu-order.list', compact('orders', 'status', 'statusCounts'));
    }

    public function details($id)
    {
        $storeId = Helpers::get_store_id();
        $order = MenuOrder::with('items')
            ->where('store_id', $storeId)
            ->findOrFail($id);

        return view('vendor-views.menu-order.detail', compact('order'));
    }

    public function quickView($id)
    {
        $storeId = Helpers::get_store_id();
        $order = MenuOrder::with('items')
            ->where('store_id', $storeId)
            ->findOrFail($id);

        $html = view('vendor-views.menu-order.quick-view', compact('order'))->render();
        return response()->json(['html' => $html]);
    }

    public function checkNewOrders()
    {
        $storeId = Helpers::get_store_id();
        $newOrders = MenuOrder::with('items')
            ->where('store_id', $storeId)
            ->where('checked', 0)
            ->latest()
            ->get();

        $ordersData = [];
        foreach ($newOrders as $order) {
            $ordersData[] = [
                'id' => $order->id,
                'order_id' => $order->order_id,
                'order_type' => $order->order_type,
                'customer_name' => $order->customer_name ?? 'Guest',
                'customer_phone' => $order->customer_phone ?? '-',
                'instructions' => $order->instructions,
                'subtotal' => $order->subtotal,
                'discount' => $order->discount ?? 0,
                'delivery_fee' => $order->delivery_fee ?? 0,
                'total' => $order->total,
                'status' => $order->status,
                'table_no' => $order->table_no,
                'created_at' => $order->created_at->format('d M Y, h:i A'),
                'items' => $order->items->map(function ($item) {
                    return [
                        'name' => $item->item_name,
                        'qty' => $item->quantity,
                        'price' => $item->item_price,
                        'size' => $item->size,
                    ];
                }),
            ];
        }

        return response()->json([
            'success' => 1,
            'data' => [
                'new_order_count' => $newOrders->count(),
                'orders' => $ordersData,
            ]
        ]);
    }

    public function markChecked($id)
    {
        $storeId = Helpers::get_store_id();
        MenuOrder::where('store_id', $storeId)->where('id', $id)->update(['checked' => 1]);
        return response()->json(['success' => true]);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,preparing,completed,cancelled',
        ]);

        $storeId = Helpers::get_store_id();
        $order = MenuOrder::where('store_id', $storeId)->findOrFail($id);
        $order->status = $request->status;
        $order->save();

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'status' => $order->status]);
        }

        Toastr::success(translate('messages.order_status_updated'));
        return back();
    }

    public function generate_invoice($id)
    {
        $order = MenuOrder::with('details')->where(['id' => $id, 'store_id' => Helpers::get_store_id()])->first();
        return view('vendor-views.menu-order.invoice', compact('order'));
    }
}
