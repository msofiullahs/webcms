<?php

namespace App\Http\Middleware;

use App\Models\Language;
use App\Models\Menu;
use App\Settings\GeneralSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    public function share(Request $request): array
    {
        $generalSettings = app(GeneralSettings::class);
        $headerMenu = Menu::with(['items.children'])->where('location', 'header')->first();
        $footerMenu = Menu::with(['items.children'])->where('location', 'footer')->first();
        $languages = Language::active()->get();
        $locale = app()->getLocale();

        return array_merge(parent::share($request), [
            'locale' => $locale,
            'languages' => $languages,
            'settings' => [
                'site_title' => $generalSettings->site_title,
                'site_description' => $generalSettings->site_description,
                'site_logo' => $generalSettings->site_logo,
                'site_favicon' => $generalSettings->site_favicon,
                'footer_text' => $generalSettings->footer_text,
            ],
            'menus' => [
                'header' => $headerMenu?->items?->map(fn ($item) => $this->formatMenuItem($item)) ?? [],
                'footer' => $footerMenu?->items?->map(fn ($item) => $this->formatMenuItem($item)) ?? [],
            ],
            'theme' => config('app.theme', 'default'),
            'translations' => $this->loadTranslations($locale),
            'flash' => [
                'success' => $request->session()->get('success'),
                'error' => $request->session()->get('error'),
            ],
        ]);
    }

    protected function formatMenuItem($item): array
    {
        return [
            'id' => $item->id,
            'title' => $item->title,
            'url' => $item->url,
            'target' => $item->target,
            'icon' => $item->icon,
            'children' => $item->children->map(fn ($child) => $this->formatMenuItem($child))->toArray(),
        ];
    }

    protected function loadTranslations(string $locale): array
    {
        $path = lang_path("{$locale}.json");
        if (File::exists($path)) {
            return json_decode(File::get($path), true) ?? [];
        }
        return [];
    }
}
