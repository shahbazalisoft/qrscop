<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        return view('admin-views.settings.index');
    }
    
    public function general_edit()
    {
        dd(123);
        return view('admin-views.settings.index');
    }
}
