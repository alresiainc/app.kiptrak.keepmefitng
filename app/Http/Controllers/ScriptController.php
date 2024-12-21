<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Script;

class ScriptController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'header_scripts' => 'nullable|string',
            'footer_scripts' => 'nullable|string',
        ]);

        $scripts = Script::firstOrCreate();
        $scripts->update($request->only('header_scripts', 'footer_scripts'));

        return back()->with('success', 'Scripts updated successfully!');
    }
    public function scripts(Request $request)
    {
        return view('pages.settings.scripts');
    }
}
