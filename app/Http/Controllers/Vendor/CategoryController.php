<?php

namespace App\Http\Controllers\Vendor;

use App\Models\Category;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Exports\StoreCategoryExport;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Maatwebsite\Excel\Facades\Excel;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Exports\StoreSubCategoryExport;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    function index(Request $request)
    {
        $key = explode(' ', $request['search']);
        $categories = Category::where(['store_id'=>Helpers::get_store_id(),'position'=>0])//, 'store_id'=>Helpers::get_store_id()
        ->when(isset($key) , function($q) use($key){
            $q->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('name', 'like', "%{$value}%");
                }
            });
        })
        ->orderBy('priority')->latest()->paginate(config('default_pagination'));

        return view('vendor-views.category.index',compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            // 'image' => 'required|max:2048',
        ]);

        $store = Helpers::get_store_data();
        $category = new Category();
        $category->name = $request->name;
        $category->position = $request->position;
        if ($request->has('image')) {
            $category->image = Helpers::upload('category/', 'png', $request->file('image'));
        } elseif ($request->has('gallery_thumbnail')) {
            $galleryThumbPath = $request->gallery_thumbnail;
            if (Storage::disk('local')->exists($galleryThumbPath)) {
                $ext = pathinfo($galleryThumbPath, PATHINFO_EXTENSION) ?: 'png';
                $newName = Carbon::now()->toDateString() . "-" . uniqid() . "." . $ext;
                $disk = Helpers::getDisk();
                Storage::disk($disk)->put("category/{$newName}", Storage::disk('local')->get($galleryThumbPath));
                $category->image = $newName;
            }
        }
        $category->store_id = $store->id;
        $category->module_id = 1;
        $category->parent_id = $request->parent_id == null ? 0 : $request->parent_id;
        $category->save();

        return response()->json([
            'status' => true,
            'message' => $request->position == 0 ? translate('messages.menu_added_successfully') : translate('messages.Sub_menu_added_successfully')
        ]);
    }

    public function edit($id)
    {
        $category = Category::withoutGlobalScope('translate')->findOrFail($id);

        return response()->json([
            'status' => true,
            'category' => [
                'id' => $category->id,
                'name' => $category->name,
                'image' => $category->image ? $category->image_full_url : asset('public/assets/admin/img/upload-img.png'),
            ]
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:100',
        ],[
            'name.required'=>translate('name_is_required'),
        ]);

        $category = Category::find($id);
        $slug = Str::slug($request->name);
        $category->slug = $category->slug? $category->slug :"{$slug}{$category->id}";
        $category->name = $request->name;
        if ($request->has('image')) {
            $category->image = Helpers::update('category/', $category->image, 'png', $request->file('image'));
        } elseif ($request->has('gallery_thumbnail')) {
            $galleryThumbPath = $request->gallery_thumbnail;
            if (Storage::disk('local')->exists($galleryThumbPath)) {
                Helpers::check_and_delete('category/', $category->image);
                $ext = pathinfo($galleryThumbPath, PATHINFO_EXTENSION) ?: 'png';
                $newName = Carbon::now()->toDateString() . "-" . uniqid() . "." . $ext;
                $disk = Helpers::getDisk();
                Storage::disk($disk)->put("category/{$newName}", Storage::disk('local')->get($galleryThumbPath));
                $category->image = $newName;
            }
        }
        $category->save();

        return response()->json([
            'status' => true,
            'message' => translate('messages.menu_updated_successfully')
        ]);
    }

    public function get_all(Request $request){
        $data = Category::where('name', 'like', '%'.$request->q.'%')->limit(8)->get([DB::raw('id, CONCAT(name) as text')]);
        if(isset($request->all))
        {
            $data[]=(object)['id'=>'all', 'text'=>translate('messages.all')];
        }
        return response()->json($data);
    }

    function sub_index(Request $request)
    {
        $key = explode(' ', $request['search']);
        $categories=Category::with(['parent'])
        // ->whereHas('parent',function($query){
        //     $query->module(Helpers::get_store_data()->module_id);
        // })
        ->where(['store_id'=>Helpers::get_store_data()->id,'position'=>1])
        ->when(isset($key) , function($q) use($key){
                $q->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('name', 'like', "%{$value}%");
                    }
                });
            })
        ->latest()->paginate(config('default_pagination'));
        $categoryList = Category::where(['store_id'=>Helpers::get_store_data()->id,'parent_id'=>0, 'status'=>1])->get();
        // \App\Models\Category::with('module')->where(['position'=>0])->module(Config::get('module.current_module_id'))->get()
        return view('vendor-views.category.sub-index',compact('categories','categoryList'));
    }

    public function sub_edit(Request $request)
    {
        
        $category = Category::findOrFail($request->id);
        $categoryList = Category::where(['store_id'=>Helpers::get_store_data()->id,'parent_id'=>0, 'status'=>1])->get();

        $html = view('vendor-views.category.sub_edit', compact('category','categoryList'))->render();

        return response()->json([
            'html' => $html
        ]);
    }

    public function sub_update(Request $request)
    {
        $request->validate([
            'name' => 'required|max:100',
        ],[
            'name.required'=>translate('name_is_required'),
        ]);

        $category = Category::find($request->id);
        $slug = Str::slug($request->name);
        $category->slug = $category->slug? $category->slug :"{$slug}{$category->id}";
        $category->name = $request->name;
        $category->parent_id = $request->parent_id;
        $category->save();

        return response()->json([
            'status' => true,
            'message' => translate('messages.sub_menu_updated_successfully')
        ]);
    }

    public function distroy(Request $request)
    {
        $category = Category::findOrFail($request->id);
        if ($category->childes->count()==0){
            $category->translations()->delete();
            $category->delete();
            Toastr::success('Category removed!');
        }else{
            Toastr::warning(translate('messages.remove_sub_categories_first'));
        }
        return back();
    }

    public function updateStatus(Request $request)
    {
        $category = Category::find($request->id);
        $category->status = $request->status;
        $category->save();
        Toastr::success(translate('messages.status_updated_successfully'));
        return back();
    }
    

    public function updateOrder(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer',
        ]);

        $storeId = Helpers::get_store_id();

        foreach ($request->order as $index => $id) {
            Category::where('id', $id)->where('store_id', $storeId)->update(['priority' => $index]);
        }

        return response()->json([
            'status' => true,
            'message' => translate('messages.category_order_updated_successfully'),
        ]);
    }

    // public function search(Request $request){
    //     $key = explode(' ', $request['search']);
    //     $categories=Category::where(['position'=>0])
    //     ->module(Helpers::get_store_data()->module_id)
    //     ->where(function ($q) use ($key) {
    //         foreach ($key as $value) {
    //             $q->orWhere('name', 'like', "%{$value}%");
    //         }
    //     })
    //     ->latest()
    //     ->limit(50)->get();
    //     return response()->json([
    //         'view'=>view('vendor-views.category.partials._table',compact('categories'))->render(),
    //         'count'=>$categories->count()
    //     ]);
    // }

//    public function sub_search(Request $request){
//        $key = explode(' ', $request['search']);
//        $categories=Category::with(['parent'])
//        ->where(function ($q) use ($key) {
//            foreach ($key as $value) {
//                $q->orWhere('name', 'like', "%{$value}%");
//            }
//        })
//        ->where(['position'=>1])->limit(50)->get();
//
//        return response()->json([
//            'view'=>view('vendor-views.category.partials._sub_table',compact('categories'))->render(),
//            'count'=>$categories->count()
//        ]);
//    }
}
