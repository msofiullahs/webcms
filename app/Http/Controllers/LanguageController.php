<?php

namespace App\Http\Controllers;

use App\Models\Language;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function switch(Request $request, string $code)
    {
        $language = Language::where('code', $code)->where('is_active', true)->first();

        if ($language) {
            session(['locale' => $code]);
            app()->setLocale($code);
        }

        return redirect()->back();
    }
}
