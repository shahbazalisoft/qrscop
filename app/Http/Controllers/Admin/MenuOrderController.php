<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuOrder;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;

class MenuOrderController extends Controller
{
    public function list(Request $request, $status = 'all')
    {
        $search = $request->search;

        $orders = MenuOrder::with(['items', 'store'])
            ->when($status !== 'all', function ($q) use ($status) {
                $q->where('status', $status);
            })
            ->when($search, function ($q) use ($search) {
                $q->where(function ($query) use ($search) {
                    $query->where('order_id', 'like', "%{$search}%")
                        ->orWhere('customer_name', 'like', "%{$search}%")
                        ->orWhere('customer_phone', 'like', "%{$search}%")
                        ->orWhereHas('store', function ($sq) use ($search) {
                            $sq->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(25);

        $statusCounts = [
            'all' => MenuOrder::count(),
            'pending' => MenuOrder::where('status', 'pending')->count(),
            'confirmed' => MenuOrder::where('status', 'confirmed')->count(),
            'preparing' => MenuOrder::where('status', 'preparing')->count(),
            'completed' => MenuOrder::where('status', 'completed')->count(),
            'cancelled' => MenuOrder::where('status', 'cancelled')->count(),
        ];

        return view('admin-views.menu-order.list', compact('orders', 'status', 'statusCounts'));
    }

    public function details($id)
    {
        $order = MenuOrder::with(['items', 'store'])->findOrFail($id);
        return view('admin-views.menu-order.detail', compact('order'));
    }

    public function quickView($id)
    {
        $order = MenuOrder::with(['items', 'store'])->findOrFail($id);
        $html = view('admin-views.menu-order.quick-view', compact('order'))->render();
        return response()->json(['html' => $html]);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,preparing,completed,cancelled',
        ]);

        $order = MenuOrder::findOrFail($id);
        $order->status = $request->status;
        $order->save();

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'status' => $order->status]);
        }

        Toastr::success(translate('messages.order_status_updated'));
        return back();
    }
}
