<x-filament-panels::page>
    {{-- Upload Theme Section --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
        <div class="flex items-start gap-4">
            <div class="flex-shrink-0">
                <div class="w-10 h-10 rounded-lg bg-primary-50 dark:bg-primary-500/10 flex items-center justify-center">
                    <x-heroicon-o-arrow-up-tray class="w-5 h-5 text-primary-600 dark:text-primary-400" />
                </div>
            </div>
            <div class="flex-1">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Upload Theme</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Install a new theme by uploading a ZIP file. The ZIP should contain a folder with
                    <code class="text-xs bg-gray-100 dark:bg-gray-700 px-1.5 py-0.5 rounded">config.json</code> and
                    <code class="text-xs bg-gray-100 dark:bg-gray-700 px-1.5 py-0.5 rounded">css/theme.css</code>.
                </p>
                <form wire:submit="installTheme" class="mt-4">
                    {{ $this->uploadForm }}
                    <div class="mt-3">
                        <x-filament::button type="submit" icon="heroicon-o-arrow-up-tray">
                            Install Theme
                        </x-filament::button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Installed Themes --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($themes as $theme)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                {{-- Theme Preview --}}
                <div class="h-40 bg-gradient-to-br from-primary-500 to-primary-700 flex items-center justify-center relative">
                    <span class="text-white text-2xl font-bold">{{ $theme['name'] }}</span>
                    @if ($activeThemeSlug === $theme['slug'])
                        <span class="absolute top-3 right-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Active
                        </span>
                    @endif
                </div>

                {{-- Theme Info --}}
                <div class="p-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $theme['name'] }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $theme['description'] ?: 'No description' }}</p>

                    @if (!empty($theme['version']) || !empty($theme['author']))
                        <div class="flex items-center gap-3 mt-2 text-xs text-gray-400 dark:text-gray-500">
                            @if (!empty($theme['version']))
                                <span>v{{ $theme['version'] }}</span>
                            @endif
                            @if (!empty($theme['author']))
                                <span>by {{ $theme['author'] }}</span>
                            @endif
                        </div>
                    @endif

                    {{-- CSS Files --}}
                    @if (!empty($theme['css_files']))
                        <div class="mt-3">
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">CSS Files:</p>
                            <div class="flex flex-wrap gap-1">
                                @foreach ($theme['css_files'] as $cssFile)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 border border-blue-200 dark:border-blue-800">
                                        {{ $cssFile }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Actions --}}
                    <div class="mt-4 flex items-center gap-2">
                        @if ($activeThemeSlug === $theme['slug'])
                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-400 border border-green-200 dark:border-green-800">
                                <x-heroicon-s-check-circle class="w-4 h-4 mr-1.5" />
                                Active Theme
                            </span>
                        @else
                            <x-filament::button wire:click="activateTheme('{{ $theme['slug'] }}')" size="sm">
                                Activate
                            </x-filament::button>
                            <x-filament::button
                                wire:click="deleteTheme('{{ $theme['slug'] }}')"
                                wire:confirm="Are you sure you want to delete the &quot;{{ $theme['name'] }}&quot; theme? This cannot be undone."
                                color="danger"
                                size="sm"
                                outlined
                            >
                                Delete
                            </x-filament::button>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @if (empty($themes))
        <div class="text-center py-12">
            <x-heroicon-o-paint-brush class="mx-auto h-12 w-12 text-gray-400" />
            <h3 class="mt-2 text-sm font-semibold text-gray-900 dark:text-white">No themes</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by uploading a theme ZIP file.</p>
        </div>
    @endif

    {{-- Theme Structure Documentation --}}
    <div class="mt-8 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <div class="flex items-start gap-4">
            <div class="flex-shrink-0">
                <div class="w-10 h-10 rounded-lg bg-amber-50 dark:bg-amber-500/10 flex items-center justify-center">
                    <x-heroicon-o-book-open class="w-5 h-5 text-amber-600 dark:text-amber-400" />
                </div>
            </div>
            <div class="flex-1">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Theme File Structure</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 mb-4">
                    Themes work like WordPress themes &mdash; they override the default site styles. Each CSS file targets a specific section of the site.
                </p>

                <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4 font-mono text-sm text-gray-700 dark:text-gray-300 mb-4">
                    <div>my-theme/</div>
                    <div class="ml-4">config.json <span class="text-gray-400 dark:text-gray-500">&larr; required (name, description, version, colors, fonts)</span></div>
                    <div class="ml-4">css/</div>
                    <div class="ml-8">theme.css <span class="text-gray-400 dark:text-gray-500">&larr; required (CSS variables, base styles)</span></div>
                    <div class="ml-8">header.css <span class="text-gray-400 dark:text-gray-500">&larr; optional (navigation &amp; header overrides)</span></div>
                    <div class="ml-8">footer.css <span class="text-gray-400 dark:text-gray-500">&larr; optional (footer overrides)</span></div>
                    <div class="ml-8">blocks.css <span class="text-gray-400 dark:text-gray-500">&larr; optional (block component styles)</span></div>
                    <div class="ml-8">pages.css <span class="text-gray-400 dark:text-gray-500">&larr; optional (post &amp; page layout styles)</span></div>
                    <div class="ml-4">fonts/ <span class="text-gray-400 dark:text-gray-500">&larr; optional (custom font files)</span></div>
                    <div class="ml-4">images/ <span class="text-gray-400 dark:text-gray-500">&larr; optional (theme images)</span></div>
                </div>

                <div class="space-y-3 text-sm">
                    <div>
                        <h4 class="font-semibold text-gray-900 dark:text-white">config.json</h4>
                        <p class="text-gray-500 dark:text-gray-400">
                            Must contain at least a <code class="text-xs bg-gray-100 dark:bg-gray-700 px-1.5 py-0.5 rounded">name</code> field.
                            Optional: <code class="text-xs bg-gray-100 dark:bg-gray-700 px-1.5 py-0.5 rounded">description</code>,
                            <code class="text-xs bg-gray-100 dark:bg-gray-700 px-1.5 py-0.5 rounded">version</code>,
                            <code class="text-xs bg-gray-100 dark:bg-gray-700 px-1.5 py-0.5 rounded">author</code>,
                            <code class="text-xs bg-gray-100 dark:bg-gray-700 px-1.5 py-0.5 rounded">colors</code>,
                            <code class="text-xs bg-gray-100 dark:bg-gray-700 px-1.5 py-0.5 rounded">fonts</code>.
                        </p>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900 dark:text-white">theme.css <span class="text-xs font-normal text-red-500">(required)</span></h4>
                        <p class="text-gray-500 dark:text-gray-400">
                            Defines CSS custom properties (<code class="text-xs bg-gray-100 dark:bg-gray-700 px-1.5 py-0.5 rounded">--color-primary</code>,
                            <code class="text-xs bg-gray-100 dark:bg-gray-700 px-1.5 py-0.5 rounded">--font-heading</code>, etc.) and base typography.
                            This is the foundation that all other CSS files build upon.
                        </p>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900 dark:text-white">header.css</h4>
                        <p class="text-gray-500 dark:text-gray-400">Overrides for the site header, navigation links, logo, and mobile menu.</p>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900 dark:text-white">footer.css</h4>
                        <p class="text-gray-500 dark:text-gray-400">Overrides for the site footer, footer links, and copyright area.</p>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900 dark:text-white">blocks.css</h4>
                        <p class="text-gray-500 dark:text-gray-400">Styles for page builder blocks: hero banners, post cards, galleries, CTA sections, contact forms, and column layouts.</p>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900 dark:text-white">pages.css</h4>
                        <p class="text-gray-500 dark:text-gray-400">Overrides for single post and page layouts: article typography, sidebar, meta info, and form inputs.</p>
                    </div>
                </div>

                <div class="mt-4 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                    <p class="text-xs text-blue-700 dark:text-blue-300">
                        <strong>Tip:</strong> Theme CSS is loaded after the base styles, so you can override any default style using the same or higher CSS specificity.
                        Use CSS custom properties defined in <code class="bg-blue-100 dark:bg-blue-800 px-1 py-0.5 rounded">theme.css</code> to keep your overrides consistent.
                        Allowed file types in ZIP: CSS, JSON, images (PNG, JPG, GIF, SVG, WebP, ICO), fonts (WOFF, WOFF2, TTF, EOT, OTF), JS, and source maps.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
