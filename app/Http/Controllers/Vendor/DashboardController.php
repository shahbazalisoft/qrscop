<?php

namespace App\Http\Controllers\Vendor;

use Carbon\Carbon;
use App\Models\Item;
use App\Models\Order;
use App\Models\Store;
use App\Models\Vendor;
use App\Models\MenuOrder;
use App\Models\Category;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\Models\OrderTransaction;
use Illuminate\Support\Facades\DB;
use Modules\Rental\Entities\Trips;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\QrScanner;

class DashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        if(Helpers::get_store_data()->module_type == 'rental'){
            return to_route('vendor.providerDashboard');
        }
        $params = [
            'statistics_type' => $request['statistics_type'] ?? 'overall'
        ];
        session()->put('dash_params', $params);

        $store = Helpers::get_store_data();

        // Stats cards
        $total_menu = Item::where('store_id', $store->id)->distinct('category_id')->count('category_id');
        $total_items = Item::where('store_id', $store->id)->count();
        $total_visits = $store->total_visits ?? 0;
        $total_orders = MenuOrder::where('store_id', $store->id)->count();

        // Package total days
        $store_full = Store::with('store_sub')->find($store->id);
        $package_days_left = 0;
        if ($store_full->store_sub) {
            $package_days_left = $store_full->store_sub->validity ?? 0;
        }

        // Total customers (distinct)
        $total_customers = MenuOrder::where('store_id', $store->id)
            ->whereNotNull('customer_phone')
            ->distinct('customer_phone')
            ->count('customer_phone');

        // Order status counts
        $order_pending = MenuOrder::where('store_id', $store->id)->where('status', 'pending')->count();
        $order_preparing = MenuOrder::where('store_id', $store->id)->where('status', 'preparing')->count();
        $order_completed = MenuOrder::where('store_id', $store->id)->where('status', 'completed')->count();
        $order_cancelled = MenuOrder::where('store_id', $store->id)->where('status', 'cancelled')->count();

        // Monthly order data for chart (current year)
        $monthly_orders = MenuOrder::where('store_id', $store->id)
            ->whereYear('created_at', Carbon::now()->year)
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as count, SUM(total) as revenue')
            ->groupBy('month')
            ->pluck('count', 'month')->toArray();

        $monthly_revenue = MenuOrder::where('store_id', $store->id)
            ->whereYear('created_at', Carbon::now()->year)
            ->selectRaw('MONTH(created_at) as month, SUM(total) as revenue')
            ->groupBy('month')
            ->pluck('revenue', 'month')->toArray();

        $order_chart_data = [];
        $revenue_chart_data = [];
        for ($i = 1; $i <= 12; $i++) {
            $order_chart_data[] = $monthly_orders[$i] ?? 0;
            $revenue_chart_data[] = round($monthly_revenue[$i] ?? 0, 2);
        }

        // Top ordered items
        $top_items = Item::where('store_id', $store->id)
            ->where('order_count', '>', 0)
            ->orderBy('order_count', 'desc')
            ->take(5)
            ->get();
        // Top ordered items
        $top_customers = Customer::where('store_id', $store->id)
            ->orderBy('total_order', 'desc')
            ->take(5)
            ->get();

        $tables = QrScanner::select('table_no as no')
                ->where('store_id', $store->id)
                ->where('status',1)
                ->whereNotNull('table_no')
                ->get();
        $tables = $tables->map(function ($table) use ($store) {
            $hasActiveOrder = MenuOrder::where('store_id', $store->id)
                ->where('table_no', $table->no)
                ->whereIn('status', ['pending', 'confirmed', 'preparing'])
                ->exists();

            $table->status = $hasActiveOrder ? 'booked' : 'available';

            return $table;
        });
        
        $data = self::dashboard_order_stats_data();

        return view('vendor-views.dashboard', compact(
            'total_menu', 'total_items', 'total_visits', 'total_orders',
            'package_days_left', 'total_customers',
            'order_pending', 'order_preparing', 'order_completed', 'order_cancelled',
            'order_chart_data', 'revenue_chart_data', 'top_items', 'top_customers', 'data','tables'
        ));
    }

    public function store_data()
    {

        $store= Helpers::get_store_data();
        if($store->module_type == 'rental'){
            $type='trip';
            $new_pending_order=Trips::where(['checked' => 0])->where('provider_id', $store->id)->count();

        } else{
            $new_pending_order = DB::table('orders')->where(['checked' => 0])->where('store_id', $store->id)->where('order_status','pending');
            if(config('order_confirmation_model') != 'store' && !$store->sub_self_delivery)
            {
                $new_pending_order = $new_pending_order->where('order_type', 'take_away');
            }
            $new_pending_order = $new_pending_order->count();
            $new_confirmed_order = DB::table('orders')->where(['checked' => 0])->where('store_id', $store->id)->whereIn('order_status',['confirmed', 'accepted'])->whereNotNull('confirmed')->count();
            $type= 'store_order';
        }

        return response()->json([
            'success' => 1,
            'data' => ['new_pending_order' => $new_pending_order, 'new_confirmed_order' => $new_confirmed_order?? 0, 'order_type' =>$type]
        ]);
    }

    public function order_stats(Request $request)
    {
        $params = session('dash_params');
        foreach ($params as $key => $value) {
            if ($key == 'statistics_type') {
                $params['statistics_type'] = $request['statistics_type'];
            }
        }
        session()->put('dash_params', $params);

        $data = self::dashboard_order_stats_data();
        return response()->json([
            'view' => view('vendor-views.partials._dashboard-order-stats', compact('data'))->render()
        ], 200);
    }

    public function dashboard_order_stats_data()
    {
        $params = session('dash_params');
        $today = $params['statistics_type'] == 'today' ? 1 : 0;
        $this_month = $params['statistics_type'] == 'this_month' ? 1 : 0;

        $data = [
            'confirmed' => 1,
            'cooking' => 2,
            'ready_for_delivery' => 2,
            'item_on_the_way' => 2,
            'delivered' => 2,
            'refunded' => 2,
            'scheduled' => 2,
            'all' => 2,
        ];

        return $data;
    }

    public function updateDeviceToken(Request $request)
    {
        $vendor = Vendor::find(Helpers::get_vendor_id());
        $vendor->firebase_token =  $request->token;

        $vendor->save();

        return response()->json(['Token successfully stored.']);
    }
}
