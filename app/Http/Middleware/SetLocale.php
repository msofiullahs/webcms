<?php

namespace App\Http\Middleware;

use App\Models\Language;
use Closure;
use Illuminate\Http\Request;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $locale = session('locale');

        if (!$locale) {
            $defaultLang = Language::where('is_default', true)->first();
            $locale = $defaultLang?->code ?? config('app.locale', 'en');
        }

        $activeLanguage = Language::where('code', $locale)->where('is_active', true)->first();
        if ($activeLanguage) {
            app()->setLocale($locale);
        } else {
            app()->setLocale(config('app.locale', 'en'));
        }

        return $next($request);
    }
}
