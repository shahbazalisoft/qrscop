<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\CentralLogics\Helpers;
use App\Models\MenuTemplate;
use Illuminate\Http\Request;


class MenuSettingController extends Controller
{
    public function index(Request $request)
    {
        $key = explode(' ', $request['search']);
        $rows = MenuTemplate::
            when(isset($key), function ($q) use ($key) {
                $q->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->where('title', 'like', "%{$value}%");
                    }
                });
            })
            ->latest()->paginate(config('default_pagination'));

        return view('admin-views.menu-template.index', compact('rows'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'template' => 'required',
        ]);

        $category = new MenuTemplate();
        $category->title = $request->title;
        $category->template = Helpers::upload('menu-template/', 'png', $request->file('template'));
        $category->save();

        return response()->json([
            'status' => true,
            'message' => translate('messages.template_added_successfully')
        ]);
    }

    public function edit($id)
    {
        $template = MenuTemplate::findOrFail($id);
        return view('admin-views.menu-template.edit', compact('template'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'template' => 'sometimes|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'template.image' => translate('invalid_image'),
            'template.mimes' => translate('image_format_not_supported'),
            'template.max'   => translate('image_max_2mb'),
        ]);
        
        $template = MenuTemplate::find($id);
        $template->title = $request->title;
        $template->template_no = $request->template_no;
        $template->template = $request->has('template') ? Helpers::update('menu-template/', $template->template, 'png', $request->file('template')) : $template->template;
        $template->save();

        Toastr::success(translate('messages.template_updated_successfully'));
        return back();
    }

    public function updateStatus(Request $request)
    {
        $template = MenuTemplate::find($request->id);
        $template->status = $request->status;
        $template->save();
        Toastr::success(translate('messages.status_updated_successfully'));
        return back();
    }

    public function update_priority(MenuTemplate $template, Request $request)
    {
        $priority = $request->priority??0;
        $template->priority = $priority;
        $template->save();
        Toastr::success(translate('messages.template_priority_updated successfully'));
        return back();

    }

    public function distroy(Request $request)
    {
        $template = MenuTemplate::findOrFail($request->id);
        $template->delete();
        Toastr::success('Template removed!');
        return back();
    }
}
