<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\KitchenStaff;
use App\CentralLogics\Helpers;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class KitchenStaffController extends Controller
{
    public function index(Request $request)
    {
        $storeId = Helpers::get_store_id();
        $search = $request->search;

        $staff = KitchenStaff::where('store_id', $storeId)
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('f_name', 'like', "%{$search}%")
                      ->orWhere('l_name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(25);

        return view('vendor-views.kitchen-staff.list', compact('staff', 'search'));
    }

    public function create()
    {
        return view('vendor-views.kitchen-staff.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'f_name' => 'required|max:100',
            'l_name' => 'nullable|max:100',
            'email' => 'required|email|unique:kitchen_staff,email',
            'phone' => 'required|max:20',
            'password' => 'required|min:8',
        ]);

        $storeData = Helpers::get_store_data();

        KitchenStaff::create([
            'f_name' => $request->f_name,
            'l_name' => $request->l_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'store_id' => $storeData->id,
            'vendor_id' => $storeData->vendor_id,
            'status' => true,
        ]);

        Toastr::success(translate('messages.kitchen_staff_added_successfully'));
        return redirect()->route('vendor.kitchen-staff.list');
    }

    public function edit($id)
    {
        $storeId = Helpers::get_store_id();
        $staff = KitchenStaff::where('store_id', $storeId)->findOrFail($id);

        return view('vendor-views.kitchen-staff.edit', compact('staff'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'f_name' => 'required|max:100',
            'l_name' => 'nullable|max:100',
            'email' => 'required|email|unique:kitchen_staff,email,' . $id,
            'phone' => 'required|max:20',
            'password' => 'nullable|min:8',
        ]);

        $storeId = Helpers::get_store_id();
        $staff = KitchenStaff::where('store_id', $storeId)->findOrFail($id);

        $staff->f_name = $request->f_name;
        $staff->l_name = $request->l_name;
        $staff->email = $request->email;
        $staff->phone = $request->phone;
        if ($request->password) {
            $staff->password = Hash::make($request->password);
        }
        $staff->save();

        Toastr::success(translate('messages.kitchen_staff_updated_successfully'));
        return redirect()->route('vendor.kitchen-staff.list');
    }

    public function destroy($id)
    {
        $storeId = Helpers::get_store_id();
        $staff = KitchenStaff::where('store_id', $storeId)->findOrFail($id);

        $staff->delete();

        Toastr::success(translate('messages.kitchen_staff_deleted_successfully'));
        return redirect()->route('vendor.kitchen-staff.list');
    }
}
