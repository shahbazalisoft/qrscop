<?php

namespace App\Http\Controllers\vendor;

use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\DB;
use App\CentralLogics\Helpers;
use App\Models\MenuTemplate;
use App\Models\Store;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MenuSettingsController extends Controller
{
    public function index(): View
    {
        $store = Helpers::get_store_data();
        $currentTemplate = $store->menu_template ?? null;

        // Get templates from database (Admin manages these via CRUD)
        $templates = MenuTemplate::where('status', 1)
            ->orderBy('template_no', 'ASC')
            ->get();

        return view('vendor-views.menu-template.index', compact('templates', 'currentTemplate', 'store'));
    }

    public function changeStatus($templateId)
    {
        // Validate template exists in database
        $template = MenuTemplate::where('id', $templateId)->where('status', 1)->first();

        if (!$template) {
            Toastr::error(translate('messages.invalid_template'));
            return back();
        }

        $store = Helpers::get_store_data();
        Store::where('id', $store->id)->update(['menu_template' => $template->template_no]);

        Toastr::success(translate('messages.template_activated_successfully'));
        return back();
    }

    public function saveColors(Request $request): JsonResponse
    {
        $request->validate([
            'accent' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'bg' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'surface' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'text' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);

        $store = Helpers::get_store_data();

        Store::where('id', $store->id)->update([
            'menu_colors' => json_encode([
                'accent' => $request->accent,
                'bg' => $request->bg,
                'surface' => $request->surface,
                'text' => $request->text,
            ])
        ]);

        return response()->json(['success' => true, 'message' => translate('messages.colors_saved_successfully')]);
    }

    public function resetColors(): JsonResponse
    {
        $store = Helpers::get_store_data();
        Store::where('id', $store->id)->update(['menu_colors' => null]);

        return response()->json(['success' => true, 'message' => translate('messages.colors_reset_successfully')]);
    }
}
