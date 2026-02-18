<?php

namespace App\Http\Middleware;

use App\Models\Theme;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SetTheme
{
    public function handle(Request $request, Closure $next)
    {
        $activeTheme = Theme::where('is_active', true)->first();
        $slug = $activeTheme?->slug ?? 'default';

        config(['app.theme' => $slug]);

        // Discover which CSS files the theme provides
        $cssFiles = $this->discoverThemeCssFiles($slug);
        config(['app.theme_css_files' => $cssFiles]);

        return $next($request);
    }

    /**
     * Discover available CSS files in the theme's css/ directory.
     * Returns an ordered array of URL paths to load.
     */
    private function discoverThemeCssFiles(string $slug): array
    {
        $cssDir = resource_path("themes/{$slug}/css");
        if (!File::isDirectory($cssDir)) {
            return [];
        }

        // Define load order — theme.css must always come first
        $orderedFiles = ['theme.css', 'header.css', 'footer.css', 'blocks.css', 'pages.css'];
        $cssFiles = [];

        // Load ordered files first
        foreach ($orderedFiles as $file) {
            if (File::exists($cssDir . '/' . $file)) {
                $cssFiles[] = "/themes/{$slug}/css/{$file}";
            }
        }

        // Then load any additional CSS files not in the ordered list
        foreach (File::files($cssDir) as $file) {
            if ($file->getExtension() === 'css') {
                $filename = $file->getFilename();
                if (!in_array($filename, $orderedFiles)) {
                    $cssFiles[] = "/themes/{$slug}/css/{$filename}";
                }
            }
        }

        return $cssFiles;
    }
}
