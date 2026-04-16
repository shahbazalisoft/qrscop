<?php

namespace App\Http\Controllers\Vendor;

use Carbon\Carbon;
use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;
use App\Models\CommonBanner;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;


class BannerController extends Controller
{
    function list(Request $request)
    {
        $key = explode(' ', $request['search']);
        $banners=Banner::where('store_id',Helpers::get_store_id())
        ->when($key, function($query)use($key){
            $query->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('title_one', 'like', "%". $value."%");
                }
            });
        })
        ->latest()->paginate(config('default_pagination'));
        return view('vendor-views.banner.index',compact('banners'));
    }


    public function status(Request $request)
    {
        $banner = Banner::findOrFail($request->id);
        $store_id = $request->status;
        $store_ids = json_decode($banner->restaurant_ids);
        if(in_array($store_id, $store_ids))
        {
            unset($store_ids[array_search($store_id, $store_ids)]);
        }
        else
        {
            array_push($store_ids, $store_id);
        }

        $banner->restaurant_ids = json_encode($store_ids);
        $banner->save();
        Toastr::success(translate('messages.capmaign_participation_updated'));
        return back();
    }

    public function store(Request $request)
    {
        $request->validate([
            // 'title' => 'required',
            'image' => 'required_without:gallery_thumbnail|max:2048',
            // 'default_link' => 'max:255',
        ]);

        $store = Helpers::get_store_data();
        $banner = new Banner;
        $banner->title_one = $request->title_one;
        $banner->title_two = $request->title_two;
        $banner->type = 'store_wise';

        if ($request->has('gallery_thumbnail')) {
            // Store reference only — no file copy
            $banner->image = basename($request->gallery_thumbnail);
        } elseif ($request->hasFile('image')) {
            $banner->image = Helpers::upload('banner/', 'png', $request->file('image'));
        }

        $banner->store_id = $store->id;
        $banner->save();
        return response()->json([
            'status' => true,
            'message' => translate('messages.banner_added_successfully')
        ]);
    }

    public function edit($id)
    {
        $banner = Banner::withoutGlobalScope('translate')->findOrFail($id);
        return response()->json([
            'status' => true,
            'banner' => [
                'id' => $banner->id,
                'title_one' => $banner->title_one,
                'title_two' => $banner->title_two,
                'image' => $banner['image_full_url'],
            ]
        ]);
    }

    public function status_update(Request $request)
    {
        $banner = Banner::findOrFail($request->id);
        $banner->status = $request->status;
        $banner->save();
        Toastr::success(translate('messages.banner_status_updated'));
        return back();
    }
    public function update(Request $request, Banner $banner)
    {
        $request->validate([
            'image' => 'max:2048',
        ]);
        $banner->title_one = $request->title_one;
        $banner->title_two = $request->title_two;

        if ($request->has('gallery_thumbnail')) {
            // Store reference only — no file copy
            $banner->image = basename($request->gallery_thumbnail);
        } elseif ($request->hasFile('image')) {
            $banner->image = Helpers::update('banner/', $banner->image, 'png', $request->file('image'));
        }

        $banner->save();
        return response()->json([
            'status' => true,
            'message' => translate('messages.banner_updated_successfully')
        ]);
    }

    public function delete(Banner $banner)
    {
   
        Helpers::check_and_delete('banner/' , $banner['image']);
        
        // $banner->translations()->delete();
        $banner->delete();
        Toastr::success(translate('messages.banner_deleted_successfully'));
        return back();
    }

    public function commonBanners()
    {
        $usedIds = Banner::where('store_id', Helpers::get_store_id())
            ->whereNotNull('common_banner_id')
            ->pluck('common_banner_id')
            ->toArray();

        $commonBanners = CommonBanner::where('status', 1)
            ->whereNotIn('id', $usedIds)
            ->latest()->get();
        return response()->json([
            'status' => true,
            'banners' => $commonBanners->map(function ($b) {
                return [
                    'id' => $b->id,
                    'title_one' => $b->title_one,
                    'title_two' => $b->title_two,
                    'image' => $b->image,
                    'image_url' => $b->image_full_url,
                ];
            })
        ]);
    }

    public function storeFromCommon(Request $request)
    {
        $request->validate([
            'common_banner_id' => 'required|exists:common_banners,id',
        ]);

        $commonBanner = CommonBanner::findOrFail($request->common_banner_id);
        $store = Helpers::get_store_data();

        $banner = new Banner;
        $banner->title_one = $commonBanner->title_one;
        $banner->title_two = $commonBanner->title_two;
        $banner->image = $commonBanner->image;
        $banner->type = 'store_wise';
        $banner->store_id = $store->id;
        $banner->common_banner_id = $commonBanner->id;
        $banner->save();

        return response()->json([
            'status' => true,
            'message' => translate('messages.banner_added_successfully')
        ]);
    }

    // public function search(Request $request){
    //     $key = explode(' ', $request['search']);
    //     $banners=Banner::where('data',Helpers::get_store_id())->where('created_by','store')->where(function ($q) use ($key) {
    //         foreach ($key as $value) {
    //             $q->orWhere('title', 'like', "%{$value}%");
    //         }
    //     })->limit(50)->get();
    //     return response()->json([
    //         'view'=>view('vendor-views.banner.partials._table',compact('banners'))->render(),
    //         'count'=>$banners->count()
    //     ]);
    // }

}
