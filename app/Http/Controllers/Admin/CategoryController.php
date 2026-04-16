<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\Translation;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\Exports\CategoryExport;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\RequestCategory;
use App\Models\Store;
use Brian2694\Toastr\Facades\Toastr;
use Maatwebsite\Excel\Facades\Excel;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class CategoryController extends Controller
{
    function index(Request $request)
    {
        $key = explode(' ', $request['search']);
        $categories=Category::where(['position'=>0])
            ->when(isset($key) , function ($q) use($key){
                $q->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('name', 'like', "%{$value}%");
                    }
                });
            })
        ->latest()->paginate(config('default_pagination'));
        $stores = Store::select('id','name')->where('status',1)->get();
        return view('admin-views.category.index',compact('categories','stores'));
    }

    function sub_index(Request $request)
    {
        $key = explode(' ', $request['search']);
        $categories=Category::with(['parent'])->where(['position'=>1])->module(Config::get('module.current_module_id'))
        ->when(isset($key) , function ($q) use($key){
            $q->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('name', 'like', "%{$value}%");
                }
            });
        })
        ->latest()->paginate(config('default_pagination'));
        return view('admin-views.category.sub-index',compact('categories'));
    }

    function sub_sub_index()
    {
        return view('admin-views.category.sub-sub-index');
    }

    function sub_category_index()
    {
        return view('admin-views.category.index');
    }

    function sub_sub_category_index()
    {
        return view('admin-views.category.index');
    }

    function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:100',
            'store_id' => 'required',
        ], [
            'name.required' => translate('messages.Name is required!'),
            'store_id.required' => translate('messages.Store is required!'),
        ]);
        
        $category = new Category();
        $category->name = $request->name;
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
        $category->parent_id = $request->parent_id == null ? 0 : $request->parent_id;
        $category->position = $request->position;
        $category->store_id = $request->store_id;
        $category->module_id = 1;
        $category->save();

        return response()->json([
            'status' => true,
            'message' => $request->position == 0 ? translate('messages.menu_added_successfully') : translate('messages.Sub_menu_added_successfully')
        ]);
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return response()->json([
            'status' => true,
            'category' => [
                'id' => $category->id,
                'name' => $category->name,
                'store_id' => $category->store_id,
                'store_name' => $category->store ? $category->store->name : '',
                'position' => $category->position,
                'image' => $category->image ? asset('storage/app/public/category/' . $category->image) : asset('public/assets/admin/img/upload-img.png'),
            ]
        ]);
    }

    public function status(Request $request)
    {
        $category = Category::find($request->id);
        $category->status = $request->status;
        $category->save();
        Toastr::success(translate('messages.category_status_updated'));
        return back();
    }

    public function featured(Request $request)
    {
        $category = Category::find($request->id);
        $category->featured = $request->featured;
        $category->save();
        Toastr::success(translate('messages.category_featured_updated'));
        return back();
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:100',
            'store_id' => 'required',
        ], [
            'name.required' => translate('messages.Name is required!'),
            'store_id.required' => translate('messages.Store is required!'),
        ]);


        $category = Category::find($id);
        $slug = Str::slug($request->name);
        $category->slug = $category->slug? $category->slug :"{$slug}{$category->id}";
        $category->name = $request->name;
        $category->store_id = $request->store_id;
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

    public function delete(Request $request)
    {
        $category = Category::findOrFail($request->id);
        $category->delete();
        Toastr::success('Category removed!');
        return back();
    }

    public function get_all(Request $request){
        $data = Category::where('name', 'like', '%'.$request->q.'%')->limit(8)->get()

        ->map(function ($category) {
            $data =$category->position == 0 ? translate('messages.main'): translate('messages.sub');
            return [
                'id' => $category->id,
                'text' => $category->name . ' (' .  $data   . ')',
            ];
        });


        $data[]=(object)['id'=>'all', 'text'=>'All'];
        return response()->json($data);
    }

    public function update_priority(Category $category, Request $request)
    {
        $priority = $request->priority??0;
        $category->priority = $priority;
        $category->save();
        Toastr::success(translate('messages.category_priority_updated successfully'));
        return back();

    }

    public function bulk_import_index()
    {
        return view('admin-views.category.bulk-import');
    }

    public function bulk_import_data(Request $request)
    {
        $request->validate([
            'products_file'=>'required|max:2048'
        ]);
        try {
            $collections = (new FastExcel)->import($request->file('products_file'));
        } catch (\Exception $exception) {
            Toastr::error(translate('messages.you_have_uploaded_a_wrong_format_file'));
            return back();
        }
        $module_id = Config::get('module.current_module_id');

        if($request->button == 'import'){
            $data = [];
            foreach ($collections as $collection) {
                if ($collection['Name'] === "") {

                    Toastr::error(translate('messages.please_fill_all_required_fields'));
                    return back();
                }
                $parent_id = is_numeric($collection['ParentId'])?$collection['ParentId']:0;
                array_push($data, [
                    'name' => $collection['Name'],
                    'image' => $collection['Image'],
                    'parent_id' => $parent_id,
                    'module_id' => $module_id,
                    'position' => $collection['Position'],
                    'priority'=>is_numeric($collection['Priority']) ? $collection['Priority']:0,
                    'status' => $collection['Status'] == 'active' ? 1 : 0,
                    'created_at'=>now(),
                    'updated_at'=>now()
                ]);
            }
            try{
                DB::beginTransaction();

                $chunkSize = 100;
                $chunk_categories= array_chunk($data,$chunkSize);

                foreach($chunk_categories as $key=> $chunk_category){
                    DB::table('categories')->insert($chunk_category);
                }
                DB::commit();
            }catch(\Exception $e)
            {
                DB::rollBack();
                info(["line___{$e->getLine()}",$e->getMessage()]);
                Toastr::error(translate('messages.failed_to_import_data'));
                return back();
            }
            Toastr::success(translate('messages.category_imported_successfully', ['count'=>count($data)]));
            return back();
        }

        $data = [];
        foreach ($collections as $collection) {
            if ($collection['Name'] === "") {

                Toastr::error(translate('messages.please_fill_all_required_fields'));
                return back();
            }
            $parent_id = is_numeric($collection['ParentId'])?$collection['ParentId']:0;
            array_push($data, [
                'id' => $collection['Id'],
                'name' => $collection['Name'],
                'image' => $collection['Image'],
                'parent_id' => $parent_id,
                'module_id' => $module_id,
                'position' => $collection['Position'],
                'priority'=>is_numeric($collection['Priority']) ? $collection['Priority']:0,
                'status' => $collection['Status'] == 'active' ? 1 : 0,
                'updated_at'=>now()
            ]);
        }
        try{
            DB::beginTransaction();

            $chunkSize = 100;
            $chunk_categories= array_chunk($data,$chunkSize);

            foreach($chunk_categories as $key=> $chunk_category){
                DB::table('categories')->upsert($chunk_category,['id','module_id'],['name','image','parent_id','position','priority','status']);
            }
            DB::commit();
        }catch(\Exception $e)
        {
            DB::rollBack();
            info(["line___{$e->getLine()}",$e->getMessage()]);
            Toastr::error(translate('messages.failed_to_import_data'));
            return back();
        }
        Toastr::success(translate('messages.category_imported_successfully', ['count'=>count($data)]));
        return back();
    }

    public function bulk_export_index()
    {
        return view('admin-views.category.bulk-export');
    }

    public function bulk_export_data(Request $request)
    {
        $request->validate([
            'type'=>'required',
            'start_id'=>'required_if:type,id_wise',
            'end_id'=>'required_if:type,id_wise',
            'from_date'=>'required_if:type,date_wise',
            'to_date'=>'required_if:type,date_wise'
        ]);
        $categories = Category::when($request['type']=='date_wise', function($query)use($request){
            $query->whereBetween('created_at', [$request['from_date'].' 00:00:00', $request['to_date'].' 23:59:59']);
        })
        ->when($request['type']=='id_wise', function($query)use($request){
            $query->whereBetween('id', [$request['start_id'], $request['end_id']]);
        })->module(Config::get('module.current_module_id'))
        ->get();
        return (new FastExcel(Helpers::export_categories(Helpers::Export_generator($categories))))->download('Categories.xlsx');
    }

    // public function search(Request $request){
    //     $key = explode(' ', $request['search']);
    //     $categories=Category::when($request->sub_category, function($query){
    //         return $query->where('position','1');
    //     })->module(Config::get('module.current_module_id'))
    //     ->where(function ($q) use ($key) {
    //         foreach ($key as $value) {
    //             $q->orWhere('name', 'like', "%{$value}%");
    //         }
    //     })->limit(50)->get();

    //     if($request->sub_category)
    //     {
    //         return response()->json([
    //             'view'=>view('admin-views.category.partials._sub_category_table',compact('categories'))->render(),
    //             'count'=>$categories->count()
    //         ]);
    //     }
    //     return response()->json([
    //         'view'=>view('admin-views.category.partials._table',compact('categories'))->render(),
    //         'count'=>$categories->count()
    //     ]);
    // }

    public function export_categories(Request $request){
        $key = explode(' ', $request['search']);
        $categories=Category::with('module')->where(['position'=>0])->module(Config::get('module.current_module_id'))
            ->when(isset($key) , function ($q) use($key){
                $q->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('name', 'like', "%{$value}%");
                    }
                });
            })
        ->latest()
        ->get();

        $data=[
            'data' =>$categories,
            'search' =>$request['search'] ?? null,
        ];
        if($request->type == 'csv'){
            return Excel::download(new CategoryExport($data), 'Categories.csv');
        }
        return Excel::download(new CategoryExport($data), 'Categories.xlsx');


    }
    
    function pending_category(Request $request)
    {
        $key = explode(' ', $request['search']);
        $requestcategories = RequestCategory::where('status','!=',1)
                ->when(isset($key) , function($q) use($key){
                    $q->where(function ($q) use ($key) {
                        foreach ($key as $value) {
                            $q->orWhere('category', 'like', "%{$value}%");
                        }
                    });
                })
                ->latest()
                ->paginate(config('default_pagination'));
        return view('admin-views.category.pending_request',compact('requestcategories'));
    }

}
