<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function integration()
    {
        return view('pages.settings.integration');
    }
}
