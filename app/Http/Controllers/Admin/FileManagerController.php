<?php

namespace App\Http\Controllers\Admin;

ini_set('post_max_size','1024M');
ini_set('upload_max_filesize','1024M');

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use App\CentralLogics\FileManagerLogic;
use Brian2694\Toastr\Facades\Toastr;
use Madnest\Madzipper\Facades\Madzipper;

class FileManagerController extends Controller
{
    public function index($folder_path = "cHVibGlj")
    {
        $file = Storage::files(base64_decode($folder_path));
        $directories = Storage::directories(base64_decode($folder_path));

        $folders = FileManagerLogic::format_file_and_folders($directories, 'folder');
        $files = FileManagerLogic::format_file_and_folders($file, 'file');

        $data = array_merge($folders, $files);
        return view('admin-views.file-manager.index', compact('data', 'folder_path'));
    }


    public function apiIndex($folder_path = "cHVibGlj")
    {
        $decodedPath = base64_decode($folder_path);
        $files = Storage::files($decodedPath);
        $directories = Storage::directories($decodedPath);

        $folders = FileManagerLogic::format_file_and_folders($directories, 'folder');
        $fileData = FileManagerLogic::format_file_and_folders($files, 'file');

        // Vendor side: only show banner, category, product folders at root level
        $isVendor = auth('vendor')->check() || auth('vendor_employee')->check();
        $allowedVendorFolders = ['banner', 'category', 'product'];

        $folderItems = [];
        foreach ($folders as $folder) {
            if ($isVendor && $decodedPath === 'public' && !in_array($folder['name'], $allowedVendorFolders)) {
                continue;
            }
            $folderItems[] = [
                'name' => $folder['name'],
                'path' => base64_encode($folder['path']),
                'type' => 'folder',
            ];
        }

        $fileItems = [];
        foreach ($fileData as $file) {
            $imgUrl = url('storage/' . preg_replace('/^public\//', '', $file['path']));
            $fileItems[] = [
                'name' => $file['name'],
                'path' => $file['path'],
                'db_path' => $file['db_path'],
                'img_url' => $imgUrl,
                'type' => 'file',
            ];
        }

        return response()->json([
            'current_path' => $folder_path,
            'decoded_path' => $decodedPath,
            'folders' => $folderItems,
            'files' => $fileItems,
        ]);
    }

    public function upload(Request $request)
    {
        $request->validate([
            'images' => 'required_without:file',
            'file' => 'required_without:images',
            'path' => 'required',
        ]);

        if ($request->hasfile('images')) {
            $images = $request->file('images');
            foreach($images as $image) {
                $name = $image->getClientOriginalName();
                Storage::disk('local')->put($request->path . '/' . $name, file_get_contents($image));
            }
        }

        if ($request->hasfile('file')) {
            $file = $request->file('file');
            Madzipper::make($file)->extractTo('storage/app/'.$request->path);
        }

        Toastr::success(translate('messages.image_uploaded_successfully'));
        return back()->with('success', translate('messages.image_uploaded_successfully'));
    }


    public function download($file_name)
    {
        return Storage::download(base64_decode($file_name));
    }


    public function destroy($file_path)
    {
        Storage::disk('local')->delete(base64_decode($file_path));
        Toastr::success(translate('messages.image_deleted_successfully'));
        return back()->with('success', translate('messages.image_deleted_successfully'));
    }
}
