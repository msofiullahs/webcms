<?php

namespace App\Services;

use App\Models\Theme;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use ZipArchive;

class ThemeService
{
    public function getActiveTheme(): ?Theme
    {
        return Theme::where('is_active', true)->first();
    }

    public function getThemeConfig(string $slug): array
    {
        $configPath = resource_path("themes/{$slug}/config.json");
        if (File::exists($configPath)) {
            return json_decode(File::get($configPath), true) ?? [];
        }
        return [];
    }

    public function discoverThemes(): array
    {
        $themesPath = resource_path('themes');
        if (!File::isDirectory($themesPath)) {
            File::makeDirectory($themesPath, 0755, true);
            return [];
        }

        $themes = [];
        foreach (File::directories($themesPath) as $dir) {
            $slug = basename($dir);
            $config = $this->getThemeConfig($slug);

            // Discover CSS files in the theme
            $cssFiles = [];
            $cssDir = $dir . '/css';
            if (File::isDirectory($cssDir)) {
                foreach (File::files($cssDir) as $file) {
                    if ($file->getExtension() === 'css') {
                        $cssFiles[] = $file->getFilename();
                    }
                }
                sort($cssFiles);
            }

            $themes[] = [
                'slug' => $slug,
                'name' => $config['name'] ?? ucfirst($slug),
                'description' => $config['description'] ?? '',
                'version' => $config['version'] ?? '1.0.0',
                'author' => $config['author'] ?? '',
                'thumbnail' => $config['thumbnail'] ?? null,
                'css_files' => $cssFiles,
            ];
        }
        return $themes;
    }

    public function activateTheme(string $slug): void
    {
        Theme::query()->update(['is_active' => false]);

        $config = $this->getThemeConfig($slug);
        Theme::updateOrCreate(
            ['slug' => $slug],
            [
                'name' => $config['name'] ?? ucfirst($slug),
                'description' => $config['description'] ?? '',
                'is_active' => true,
                'config' => $config,
            ]
        );
    }

    /**
     * Install a theme from a ZIP file.
     *
     * Expected ZIP structure:
     *   theme-name/
     *     config.json  (required - must contain "name")
     *     css/
     *       theme.css  (required)
     *     ... other files (images, fonts, etc.)
     *
     * @return array{success: bool, message: string, slug?: string}
     */
    public function installFromZip(UploadedFile $file): array
    {
        if ($file->getClientOriginalExtension() !== 'zip') {
            return ['success' => false, 'message' => 'File must be a ZIP archive.'];
        }

        if ($file->getSize() > 10 * 1024 * 1024) {
            return ['success' => false, 'message' => 'ZIP file must be smaller than 10MB.'];
        }

        $zip = new ZipArchive();
        $tmpPath = $file->getRealPath();

        if ($zip->open($tmpPath) !== true) {
            return ['success' => false, 'message' => 'Could not open ZIP file.'];
        }

        // Security: check for path traversal and dangerous files
        $securityCheck = $this->validateZipSecurity($zip);
        if (!$securityCheck['success']) {
            $zip->close();
            return $securityCheck;
        }

        // Find the theme root directory (the folder containing config.json)
        $themeRoot = $this->findThemeRoot($zip);
        if ($themeRoot === null) {
            $zip->close();
            return ['success' => false, 'message' => 'Invalid theme: could not find config.json. The ZIP must contain a folder with a config.json file.'];
        }

        // Read and validate config.json
        $configContent = $zip->getFromName($themeRoot . 'config.json');
        if (!$configContent) {
            $zip->close();
            return ['success' => false, 'message' => 'Could not read config.json from ZIP.'];
        }

        $config = json_decode($configContent, true);
        if (!$config || empty($config['name'])) {
            $zip->close();
            return ['success' => false, 'message' => 'Invalid config.json: must contain at least a "name" field.'];
        }

        // Check that css/theme.css exists
        $hasThemeCss = false;
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $name = $zip->getNameIndex($i);
            if ($name === $themeRoot . 'css/theme.css') {
                $hasThemeCss = true;
                break;
            }
        }

        if (!$hasThemeCss) {
            $zip->close();
            return ['success' => false, 'message' => 'Invalid theme: missing css/theme.css file.'];
        }

        // Determine the slug from the folder name
        $slug = rtrim($themeRoot, '/');
        if (str_contains($slug, '/')) {
            $slug = basename($slug);
        }
        $slug = preg_replace('/[^a-z0-9\-_]/', '-', strtolower($slug));

        if (empty($slug)) {
            $slug = preg_replace('/[^a-z0-9\-_]/', '-', strtolower($config['name']));
        }

        $themesPath = resource_path('themes');
        $targetPath = $themesPath . '/' . $slug;

        // If theme already exists, remove old version
        if (File::isDirectory($targetPath)) {
            File::deleteDirectory($targetPath);
        }

        // Extract the theme
        File::makeDirectory($targetPath, 0755, true);

        for ($i = 0; $i < $zip->numFiles; $i++) {
            $entryName = $zip->getNameIndex($i);

            // Skip entries outside the theme root
            if (!str_starts_with($entryName, $themeRoot)) {
                continue;
            }

            // Get relative path within theme
            $relativePath = substr($entryName, strlen($themeRoot));
            if (empty($relativePath)) {
                continue;
            }

            $destPath = $targetPath . '/' . $relativePath;

            // If it's a directory
            if (str_ends_with($entryName, '/')) {
                File::makeDirectory($destPath, 0755, true, true);
                continue;
            }

            // Ensure parent directory exists
            $parentDir = dirname($destPath);
            if (!File::isDirectory($parentDir)) {
                File::makeDirectory($parentDir, 0755, true, true);
            }

            // Extract file content
            $content = $zip->getFromIndex($i);
            if ($content !== false) {
                File::put($destPath, $content);
            }
        }

        $zip->close();

        // Sync to database
        Theme::updateOrCreate(
            ['slug' => $slug],
            [
                'name' => $config['name'],
                'description' => $config['description'] ?? '',
                'config' => $config,
            ]
        );

        return [
            'success' => true,
            'message' => "Theme \"{$config['name']}\" installed successfully.",
            'slug' => $slug,
        ];
    }

    /**
     * Delete a theme (files + database record).
     * Cannot delete the currently active theme.
     */
    public function deleteTheme(string $slug): array
    {
        $theme = Theme::where('slug', $slug)->first();
        if ($theme && $theme->is_active) {
            return ['success' => false, 'message' => 'Cannot delete the active theme. Activate another theme first.'];
        }

        $themePath = resource_path("themes/{$slug}");
        if (File::isDirectory($themePath)) {
            File::deleteDirectory($themePath);
        }

        if ($theme) {
            $theme->delete();
        }

        return ['success' => true, 'message' => 'Theme deleted successfully.'];
    }

    /**
     * Validate ZIP for security issues.
     */
    private function validateZipSecurity(ZipArchive $zip): array
    {
        $allowedExtensions = [
            'css', 'json', 'txt', 'md',
            'png', 'jpg', 'jpeg', 'gif', 'svg', 'webp', 'ico',
            'woff', 'woff2', 'ttf', 'eot', 'otf',
            'js', 'map',
        ];

        for ($i = 0; $i < $zip->numFiles; $i++) {
            $name = $zip->getNameIndex($i);

            // Check for path traversal
            if (str_contains($name, '..')) {
                return ['success' => false, 'message' => "Security error: ZIP contains path traversal ('{$name}')."];
            }

            // Check for absolute paths
            if (str_starts_with($name, '/') || preg_match('/^[A-Za-z]:/', $name)) {
                return ['success' => false, 'message' => "Security error: ZIP contains absolute path ('{$name}')."];
            }

            // Skip directories
            if (str_ends_with($name, '/')) {
                continue;
            }

            // Check for allowed file extensions only
            $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
            if (!in_array($ext, $allowedExtensions)) {
                return ['success' => false, 'message' => "Disallowed file type '.{$ext}' found in '{$name}'. Themes may only contain CSS, JSON, images, fonts, and JS files."];
            }
        }

        return ['success' => true, 'message' => ''];
    }

    /**
     * Find the root directory of the theme inside the ZIP (the folder containing config.json).
     */
    private function findThemeRoot(ZipArchive $zip): ?string
    {
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $name = $zip->getNameIndex($i);
            if (basename($name) === 'config.json') {
                return str_replace('config.json', '', $name);
            }
        }
        return null;
    }
}
