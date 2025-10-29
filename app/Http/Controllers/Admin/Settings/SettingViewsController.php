<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingViewsController extends Controller
{
    public function settingsIndex() {
        return view("admin.settings.index");
    }


    public function noteSettingsIndex() {
        return view("admin.settings.notes.index");
    }




}
