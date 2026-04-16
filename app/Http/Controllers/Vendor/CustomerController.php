<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\CentralLogics\Helpers;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    function index(Request $request)
    {
        $key = explode(' ', $request['search']);
        $customers= Customer::where('store_id',Helpers::get_store_id())
        ->when($key, function($query)use($key){
            $query->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('name', 'like', "%". $value."%");
                    $q->orWhere('phone', 'like', "%". $value."%");
                    $q->orWhere('total_order', 'like', "%". $value."%");
                }
            });
        })
        ->latest()->paginate(config('default_pagination'));
        return view('vendor-views.customer.index',compact('customers'));
    }
}
