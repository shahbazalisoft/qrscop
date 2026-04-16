<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CommonBanner;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Storage;

class CommonBannerController extends Controller
{
    public function list(Request $request)
    {
        $key = explode(' ', $request['search']);
        $banners = CommonBanner::when($request['search'], function ($query) use ($key) {
            $query->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('title_one', 'like', "%{$value}%");
                    $q->orWhere('title_two', 'like', "%{$value}%");
                }
            });
        })
        ->latest()->paginate(config('default_pagination'));
        return view('admin-views.common-banner.index', compact('banners'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required_without:gallery_thumbnail|max:2048',
        ]);

        $banner = new CommonBanner;
        $banner->title_one = $request->title_one;
        $banner->title_two = $request->title_two;

        if ($request->has('gallery_thumbnail')) {
            // Store reference only — no file copy
            $banner->image = basename($request->gallery_thumbnail);
        } elseif ($request->hasFile('image')) {
            $banner->image = Helpers::upload('banner/', 'png', $request->file('image'));
        }

        $banner->save();
        return response()->json([
            'status' => true,
            'message' => translate('messages.banner_added_successfully')
        ]);
    }

    public function edit($id)
    {
        $banner = CommonBanner::findOrFail($id);
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

    public function status_update($id, $status)
    {
        $banner = CommonBanner::findOrFail($id);
        $banner->status = $status;
        $banner->save();
        Toastr::success(translate('messages.banner_status_updated'));
        return back();
    }

    public function update(Request $request, CommonBanner $common_banner)
    {
        $request->validate([
            'image' => 'max:2048',
        ]);
        $common_banner->title_one = $request->title_one;
        $common_banner->title_two = $request->title_two;

        if ($request->has('gallery_thumbnail')) {
            // Store reference only — no file copy
            $common_banner->image = basename($request->gallery_thumbnail);
        } elseif ($request->hasFile('image')) {
            $common_banner->image = Helpers::upload('banner/', 'png', $request->file('image'));
        }

        $common_banner->save();
        return response()->json([
            'status' => true,
            'message' => translate('messages.banner_updated_successfully')
        ]);
    }

    public function delete(CommonBanner $common_banner)
    {
        $common_banner->delete();
        Toastr::success(translate('messages.banner_deleted_successfully'));
        return back();
    }
}
