<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\Response;

class ThemeAssetController extends Controller
{
    /**
     * Serve theme CSS and asset files from resources/themes/{slug}/.
     * Only serves allowed file types (CSS, images, fonts).
     */
    public function __invoke(string $slug, string $path)
    {
        // Sanitize slug and path to prevent directory traversal
        $slug = preg_replace('/[^a-z0-9\-_]/', '', strtolower($slug));
        if (str_contains($path, '..') || str_starts_with($path, '/')) {
            abort(404);
        }

        $filePath = resource_path("themes/{$slug}/{$path}");

        if (!File::exists($filePath) || !File::isFile($filePath)) {
            abort(404);
        }

        // Only serve allowed file types
        $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $allowedMimes = [
            'css' => 'text/css',
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'svg' => 'image/svg+xml',
            'webp' => 'image/webp',
            'ico' => 'image/x-icon',
            'woff' => 'font/woff',
            'woff2' => 'font/woff2',
            'ttf' => 'font/ttf',
            'eot' => 'application/vnd.ms-fontobject',
            'otf' => 'font/otf',
        ];

        if (!isset($allowedMimes[$ext])) {
            abort(404);
        }

        $content = File::get($filePath);
        $mime = $allowedMimes[$ext];

        return response($content, 200)
            ->header('Content-Type', $mime)
            ->header('Cache-Control', 'public, max-age=86400');
    }
}
