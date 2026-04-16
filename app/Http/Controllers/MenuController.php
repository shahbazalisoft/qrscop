<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataSetting;
use App\Models\Item;
use App\Models\Store;
use App\Models\StoreSchedule;
use App\Models\Category;
use App\Models\Banner;
use App\Models\MenuTemplate;
use App\Models\TodaySpecial;
use Carbon\Carbon;

class MenuController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('menu');
    }

    /**
     * Show the store wise menu and items.
     *
     * @param Store $store
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function store_menu(Store $store)
    {
        if (!$store) {
            abort(404);
        }

        if (!Category::where('store_id', $store->id)->count()) {
            return back()->withErrors(['Please menu category create first!']);
        }

        // Check if store is closed
        if (!$store->active) { // || $this->isStoreClosed($store)
            $schedules = StoreSchedule::where('store_id', $store->id)->orderBy('day')->orderBy('opening_time')->get();
            return view('menu-templates.store-closed', compact('store', 'schedules'));
        }

        // Track visit
        $store->increment('total_visits');

        // Get store's selected menu template ID
        $templateId = $store->menu_template ?? null;

        // Get template from database
        $template = null;
        if ($templateId) {
            $template = MenuTemplate::find($templateId);
        }

        $viewName = 'menu-templates.style-' . $templateId;
        // if (!$template || !view()->exists($viewName)) {
        //     abort(404);
        // }
        
        // Get categories for this store
        $categories = Category::active()
                ->with(['items' => function ($q) {
                    $q->where('status', 1)->with('tags');
                }])
                ->whereHas('items', function ($q) {
                    $q->where('status', 1);
                })
                ->where('store_id', $store->id)
                ->where('position', 0)
                ->orderBy('priority', 'ASC')
                ->get();

        $banners = Banner::where('store_id', $store->id)
            ->where('status', 1)
            ->get();

        $todaySpecials = TodaySpecial::where('store_id', $store->id)
            ->where('status', 1)
            ->where('day', strtolower(now()->format('l')))
            ->with('item')
            ->get();

        return view("menu-templates.style-$templateId", compact('store', 'categories', 'banners', 'template', 'todaySpecials'));
    }
    



    /**
     * Show the store menu by slug (for QR code scanning)
     *
     * @param string $slug
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function store_menu_by_slug($slug)
    {
        $store = Store::where('slug', $slug)->first();

        if (!$store) {
            abort(404);
        }

        return $this->store_menu($store);
    }

    /**
     * Show store menu with a specific style/template
     */
    public function storeMenuWithStyle($slug, $styleId)
    {
        $store = Store::with('storeConfig')->where('slug', $slug)->first();
        
        if (!$store) {
            abort(404);
        }

        $template = MenuTemplate::where('template_no',$styleId);
        $viewName = 'menu-templates.style-' . $styleId;

        if (!$template || !view()->exists($viewName)) {
            abort(404);
        }

        $categories = Category::with(['items' => function ($q) {
                $q->where('status', 1)->with('tags');
            }])
            ->where('store_id', $store->id)
            ->where('status', 1)
            ->where('position', 0)
            ->orderBy('priority', 'ASC')
            ->get();

        $items = Item::where('store_id', $store->id)
            ->where('status', 1)
            ->get();

        $banners = Banner::where('store_id', $store->id)
            ->where('status', 1)
            ->get();

        $todaySpecials = TodaySpecial::where('store_id', $store->id)
            ->where('status', 1)
            ->where('day', strtolower(now()->format('l')))
            ->with('item')
            ->get();

        return view($viewName, compact('store', 'categories', 'items', 'banners', 'template', 'todaySpecials'));
    }

    /**
     * Preview menu template (static demo for vendor dashboard)
     *
     * @param string $templateSlug
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function previewTemplate($templateSlug)
    {
        // Map template parameter to blade view
        $templateMap = [
            'style-1' => 'menu-templates.style-1',
            'style-2' => 'menu-templates.style-2',
            'style-3' => 'menu-templates.style-3',
            'style-4' => 'menu-templates.style-4',
            'style-5' => 'menu-templates.style-5',
            'style-6' => 'menu-templates.style-6',
            'style-7' => 'menu-templates.style-7',
            'style-8' => 'menu-templates.style-8',
            'style-9' => 'menu-templates.style-9',
            'style-10' => 'menu-templates.style-10',
            'style-11' => 'menu-templates.style-11',
            'style-12' => 'menu-templates.style-12',
        ];

        if (!isset($templateMap[$templateSlug])) {
            abort(404);
        }

        // Create dummy store data for preview
        $store = (object) [
            'id' => 0,
            'name' => 'Demo Restaurant',
            'slug' => 'demo-restaurant',
            'logo' => null,
            'cover_photo' => null,
            'address' => '123 Food Street, City',
            'phone' => '+1 234 567 8900',
        ];

        // Empty collections for preview
        $categories = collect([]);
        $items = collect([]);
        $banners = collect([]);
        $todaySpecials = collect([]);
        $template = null;

        return view($templateMap[$templateSlug], compact('store', 'categories', 'items', 'banners', 'template', 'todaySpecials'));
    }

    /**
     * Check if store is currently closed based on off_day
     */
    private function isStoreClosed(Store $store): bool
    {
        $today = Carbon::now()->dayOfWeek;
        $now = Carbon::now()->format('H:i:s');

        // Check off day
        $offDays = $store->off_day ? explode(',', $store->off_day) : [];
        if (in_array($today, $offDays)) {
            return true;
        }

        // Check schedule - if no schedules exist, store is considered open
        $schedules = StoreSchedule::where('store_id', $store->id)->where('day', $today)->get();
        if ($schedules->isEmpty()) {
            return false;
        }

        // Check if current time falls within any schedule
        foreach ($schedules as $schedule) {
            if ($now >= $schedule->opening_time && $now <= $schedule->closing_time) {
                return false;
            }
        }

        return true;
    }
}
