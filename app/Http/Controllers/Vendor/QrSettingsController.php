<?php

namespace App\Http\Controllers\vendor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\DB;
use App\CentralLogics\Helpers;
use App\Models\QrScanner;
use App\Models\QrTemplate;
use Illuminate\Http\Request;
use Illuminate\View\View;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\Store;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;


class QrSettingsController extends Controller
{

    public function index(Request $request): View
    {
        $store = Helpers::get_store_data();
        $get_qr = QrScanner::where('store_id', $store->id)->get();
        $qr_templates = QrTemplate::where('status', 1)->orderBy('style')->get();
        $tab = $request->query('tab', 'active');
        return view('vendor-views.qr-setting.index', compact('get_qr', 'qr_templates', 'tab'));
    }

    public function generateQr(Request $request)
    {
        $request->validate([
            'slug' => 'required|string|max:100',
        ]);

        $store = Helpers::get_store_data();
        $slug = Str::slug($request->slug);

        // Check slug uniqueness (exclude current store)
        $exists = Store::where('slug', $slug)->where('id', '!=', $store->id)->exists();
        if ($exists) {
            Toastr::error(translate('This URL slug is already taken. Please choose a different one.'));
            return back();
        }

        // Update store slug
        $store->slug = $slug;
        $store->save();

        // Generate QR code
        // $menuUrl = url($slug . '/menu');
        // $folder = 'qrcodes';
        // $fileName = $store->id . '_' . time() . '.svg';
        // $path = $folder . '/' . $fileName;

        // Storage::disk('public')->put(
        //     $path,
        //     QrCode::format('svg')
        //         ->size(300)
        //         ->style('round')
        //         ->generate($menuUrl)
        // );
        // Generate QR code
            $menuUrl = url($slug . '/menu');

            $folder = 'qrcodes';
            $fileName = $store->id . '_' . time() . '.svg';

            // Full public path
            $directory = public_path('storage/' . $folder);

            // Create folder if not exists
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            // Full file path
            $filePath = $directory . '/' . $fileName;

            // Save QR code directly
            file_put_contents(
                $filePath,
                QrCode::format('svg')
                    ->size(300)
                    ->style('round')
                    ->generate($menuUrl)
            );

            // Save relative path (for DB)
            $path = $folder . '/' . $fileName;

        // TODO: Uncomment to embed store logo in center of QR code
        // $svgContent = QrCode::format('svg')
        //     ->size(300)
        //     ->style('round')
        //     ->errorCorrection('H')
        //     ->generate($menuUrl);
        // $logoPath = $store->logo ? storage_path('app/public/store/' . $store->logo) : null;
        // if ($logoPath && file_exists($logoPath)) {
        //     $logoData = base64_encode(file_get_contents($logoPath));
        //     $ext = strtolower(pathinfo($logoPath, PATHINFO_EXTENSION));
        //     $mimeMap = ['svg' => 'image/svg+xml', 'png' => 'image/png', 'jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'webp' => 'image/webp'];
        //     $mime = $mimeMap[$ext] ?? 'image/png';
        //     $logoSize = 60;
        //     $logoOffset = (300 - $logoSize) / 2;
        //     $logoBg = '<rect x="' . ($logoOffset - 4) . '" y="' . ($logoOffset - 4) . '" width="' . ($logoSize + 8) . '" height="' . ($logoSize + 8) . '" rx="' . (($logoSize + 8) / 2) . '" fill="#ffffff"/>';
        //     $logoImg = '<image x="' . $logoOffset . '" y="' . $logoOffset . '" width="' . $logoSize . '" height="' . $logoSize . '" href="data:' . $mime . ';base64,' . $logoData . '" clip-path="inset(0% round 50%)"/>';
        //     $svgContent = str_replace('</svg>', $logoBg . $logoImg . '</svg>', $svgContent);
        // }
        // Storage::disk('public')->put($path, $svgContent);

        // Deactivate old QR codes
        QrScanner::where('store_id', $store->id)->update(['status' => 0]);

        // Create new active QR
        QrScanner::create([
            'store_id' => $store->id,
            'qr_scanner' => $fileName,
            'status' => 1,
        ]);

        Toastr::success(translate('QR code generated successfully!'));
        return back();
    }

    public function changeTemplate(Request $request)
    {
        $request->validate([
            'template' => 'required|integer|exists:qr_templates,id',
        ]);

        $store = Helpers::get_store_data();
        $store->qr_template = $request->template;
        $store->save();

        Toastr::success(translate('QR template updated successfully!'));
        return redirect()->route('vendor.business-settings.qr-setup', ['tab' => 'active']);
    }

    public function changeStatus($id)
    {
        $store = Helpers::get_store_data();

        DB::transaction(function () use ($id, $store) {
            QrScanner::where('store_id',$store->id)->where('status', 1)->update(['status' => 0]);
            QrScanner::where('store_id',$store->id)->where('id', $id)->update(['status' => 1]);
        });
        Toastr::success(translate('messages.New_QR-Scanner_activated_now!'));
        return back();
    }

    public function updateFoodImages(Request $request)
    {
        $request->validate([
            'qr_food_image_1' => 'nullable|image|mimes:jpg,jpeg,png,svg,webp|max:2048',
            'qr_food_image_2' => 'nullable|image|mimes:jpg,jpeg,png,svg,webp|max:2048',
            'qr_food_image_3' => 'nullable|image|mimes:jpg,jpeg,png,svg,webp|max:2048',
            'qr_food_image_4' => 'nullable|image|mimes:jpg,jpeg,png,svg,webp|max:2048',
        ]);

        $store = Helpers::get_store_data();

        foreach (['qr_food_image_1','qr_food_image_2','qr_food_image_3','qr_food_image_4'] as $field) {
            if ($request->hasFile($field)) {
                $store->$field = Helpers::update('store/qr-food/', $store->$field, 'png', $request->file($field));
            }
        }

        $store->save();

        Toastr::success(translate('Food images updated successfully!'));
        return redirect()->route('vendor.business-settings.qr-setup', ['tab' => 'customize']);
    }

    public function downloadPdf(Request $request)
    {
        $store = Helpers::get_store_data();
        $activeTemplateId = $store->qr_template ?? 1;
        $template = QrTemplate::find($activeTemplateId);

        if (!$template) {
            Toastr::error(translate('Template not found'));
            return back();
        }

        $tableNo = $request->query('table_no', '');
        $menuUrl = url($store->slug . '/menu');

        // QR image as base64 — use table-specific QR if table_no is provided
        if ($tableNo) {
            $activeQr = QrScanner::where('store_id', $store->id)->where('table_no', $tableNo)->first();
            $menuUrl = url($store->slug . '/menu') . '?table=' . urlencode($tableNo);
        } else {
            $activeQr = QrScanner::where('store_id', $store->id)->where('status', 1)->whereNull('table_no')->first();
        }
        $qrPath = ($activeQr && $activeQr->qr_scanner)
            ? storage_path('app/public/qrcodes/' . $activeQr->qr_scanner)
            : public_path('assets/admin/img/default-qr.svg');
        $qrBase64 = $this->imageToBase64($qrPath);

        // Store logo as base64
        $logoPath = $store->logo
            ? storage_path('app/public/store/' . $store->logo)
            : null;
        if ($logoPath && !file_exists($logoPath)) {
            $logoPath = null;
        }
        $logoBase64 = $logoPath ? $this->imageToBase64($logoPath) : '';

        // If logo not found in storage, try logo_full_url
        if (!$logoBase64 && $store->logo_full_url) {
            $logoBase64 = $this->imageToBase64($store->logo_full_url);
        }

        // Food images as base64 - use custom if uploaded, else defaults
        $defaults = [
            'burger' => public_path('assets/admin/img/qr-dummy/burger.svg'),
            'pizza'  => public_path('assets/admin/img/qr-dummy/pizza.svg'),
            'cake'   => public_path('assets/admin/img/qr-dummy/cake.svg'),
            'salad'  => public_path('assets/admin/img/qr-dummy/salad.svg'),
        ];
        $customMap = [
            'burger' => ['field' => 'qr_food_image_1', 'url_attr' => 'qr_food_image_1_full_url'],
            'pizza'  => ['field' => 'qr_food_image_2', 'url_attr' => 'qr_food_image_2_full_url'],
            'cake'   => ['field' => 'qr_food_image_3', 'url_attr' => 'qr_food_image_3_full_url'],
            'salad'  => ['field' => 'qr_food_image_4', 'url_attr' => 'qr_food_image_4_full_url'],
        ];

        $foodImages = [];
        foreach ($defaults as $key => $defaultPath) {
            $map = $customMap[$key];
            if ($store->{$map['field']}) {
                // Try local storage first
                $customPath = storage_path('app/public/store/qr-food/' . $store->{$map['field']});
                if (file_exists($customPath)) {
                    $foodImages[$key] = $this->imageToBase64($customPath);
                } else {
                    // Fallback to full URL accessor
                    $foodImages[$key] = $this->imageToBase64($store->{$map['url_attr']});
                }
            } else {
                $foodImages[$key] = $this->imageToBase64($defaultPath);
            }
        }

        $data = compact('store', 'template', 'tableNo', 'menuUrl', 'qrBase64', 'logoBase64', 'foodImages');

        $pdf = Pdf::loadView('vendor-views.qr-setting.pdf.style-' . $template->style, $data);
        $pdf->setPaper([0, 0, 300, 550]);
        $pdf->setOption('isRemoteEnabled', true);
        $pdf->setOption('dpi', 150);

        $fileName = 'qr-template-' . Str::slug($store->name);
        if ($tableNo) {
            $fileName .= '-table-' . $tableNo;
        }

        return $pdf->download($fileName . '.pdf');
    }

    public function generateTableQr(Request $request)
    {
        $request->validate([
            'table_no' => 'required|string|max:20',
        ]);

        $store = Helpers::get_store_data();

        if (!$store->slug) {
            Toastr::error(translate('Please generate your main QR code first to set up your menu URL slug.'));
            return back();
        }

        // Check if table_no already exists for this store
        $exists = QrScanner::where('store_id', $store->id)
            ->where('table_no', $request->table_no)
            ->exists();

        if ($exists) {
            Toastr::error(translate('QR code for this table number already exists.'));
            return back();
        }

        // Generate QR code with table param
        $menuUrl = url($store->slug . '/menu') . '?table=' . urlencode($request->table_no);
        $folder = 'qrcodes';
        $fileName = $store->id . '_table_' . $request->table_no . '_' . time() . '.svg';
        $path = $folder . '/' . $fileName;

        Storage::disk('public')->put(
            $path,
            QrCode::format('svg')
                ->size(300)
                ->style('round')
                ->generate($menuUrl)
        );

        QrScanner::create([
            'store_id' => $store->id,
            'qr_scanner' => $fileName,
            'table_no' => $request->table_no,
            'status' => 1,
        ]);

        Toastr::success(translate('Table QR code generated successfully!'));
        return redirect()->route('vendor.business-settings.qr-setup', ['tab' => 'tables']);
    }

    public function deleteTableQr($id)
    {
        $store = Helpers::get_store_data();
        $qr = QrScanner::where('store_id', $store->id)->where('id', $id)->whereNotNull('table_no')->firstOrFail();

        // Delete QR image file
        if ($qr->qr_scanner) {
            Storage::disk('public')->delete('qrcodes/' . $qr->qr_scanner);
        }

        $qr->delete();

        Toastr::success(translate('Table QR code deleted successfully!'));
        return redirect()->route('vendor.business-settings.qr-setup', ['tab' => 'tables']);
    }

    private function imageToBase64($path)
    {
        try {
            if (filter_var($path, FILTER_VALIDATE_URL)) {
                $content = @file_get_contents($path);
            } elseif (file_exists($path)) {
                $content = file_get_contents($path);
            } else {
                return '';
            }

            if (!$content) {
                return '';
            }

            $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
            $mimeMap = [
                'svg' => 'image/svg+xml',
                'png' => 'image/png',
                'jpg' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'gif' => 'image/gif',
                'webp' => 'image/webp',
            ];
            $mime = $mimeMap[$ext] ?? 'image/png';

            return 'data:' . $mime . ';base64,' . base64_encode($content);
        } catch (\Exception $e) {
            return '';
        }
    }
}
