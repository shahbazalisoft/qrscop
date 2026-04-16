<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\CentralLogics\Helpers;
use App\Models\TodaySpecial;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Brian2694\Toastr\Facades\Toastr;

class TodaySpecialController extends Controller
{
    public function index(Request $request): View
    {
        $store = Helpers::get_store_data();
        $days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];

        $specials = TodaySpecial::where('store_id', $store->id)
            ->with('item')
            ->get()
            ->groupBy('day');

        $items = $store->items()->where('status', 1)->get();

        return view('vendor-views.today-special.index', compact('days', 'specials', 'items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'day' => 'required|in:sunday,monday,tuesday,wednesday,thursday,friday,saturday',
        ]);

        $store = Helpers::get_store_data();

        // Check if the item belongs to this store
        $itemBelongsToStore = $store->items()->where('id', $request->item_id)->exists();
        if (!$itemBelongsToStore) {
            Toastr::error(translate('messages.item_not_found'));
            return back();
        }

        // Check for duplicate
        $exists = TodaySpecial::where('store_id', $store->id)
            ->where('item_id', $request->item_id)
            ->where('day', $request->day)
            ->exists();

        if ($exists) {
            Toastr::warning(translate('messages.item_already_added'));
            return back();
        }

        TodaySpecial::create([
            'store_id' => $store->id,
            'item_id' => $request->item_id,
            'day' => $request->day,
        ]);

        Toastr::success(translate('messages.item_added_successfully'));
        return back();
    }

    public function destroy($id)
    {
        $store = Helpers::get_store_data();
        $special = TodaySpecial::where('store_id', $store->id)->findOrFail($id);
        $special->delete();

        Toastr::success(translate('messages.item_removed_successfully'));
        return back();
    }

    public function status($id, $status)
    {
        $store = Helpers::get_store_data();
        $special = TodaySpecial::where('store_id', $store->id)->findOrFail($id);
        $special->status = $status;
        $special->save();

        Toastr::success(translate('messages.status_updated'));
        return back();
    }
}
