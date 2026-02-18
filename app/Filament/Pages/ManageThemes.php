<?php

namespace App\Filament\Pages;

use App\Models\Theme;
use App\Services\ThemeService;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Livewire\WithFileUploads;

class ManageThemes extends Page implements HasForms
{
    use InteractsWithForms;
    use WithFileUploads;

    protected static ?string $navigationIcon = 'heroicon-o-paint-brush';
    protected static ?string $navigationGroup = 'Appearance';
    protected static ?int $navigationSort = 2;
    protected static ?string $title = 'Themes';
    protected static string $view = 'filament.pages.manage-themes';

    public $theme_zip = null;
    public array $data = [];

    public function mount(): void
    {
        $this->uploadForm->fill();
    }

    public function getViewData(): array
    {
        $themeService = app(ThemeService::class);
        $discoveredThemes = $themeService->discoverThemes();
        $activeTheme = Theme::active()->first();

        return [
            'themes' => $discoveredThemes,
            'activeThemeSlug' => $activeTheme?->slug,
        ];
    }

    public function uploadForm(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('theme_zip')
                    ->label('Theme ZIP File')
                    ->acceptedFileTypes(['application/zip', 'application/x-zip-compressed'])
                    ->maxSize(10240) // 10MB in KB
                    ->disk('local')
                    ->directory('temp-themes')
                    ->helperText('Upload a .zip file containing a theme folder with config.json and css/theme.css'),
            ])
            ->statePath('data');
    }

    public function installTheme(): void
    {
        $data = $this->uploadForm->getState();

        if (empty($data['theme_zip'])) {
            Notification::make()
                ->title('Please select a ZIP file to upload')
                ->warning()
                ->send();
            return;
        }

        // Get the uploaded file from storage
        $filePath = storage_path('app/private/' . $data['theme_zip']);
        if (!file_exists($filePath)) {
            $filePath = storage_path('app/' . $data['theme_zip']);
        }

        if (!file_exists($filePath)) {
            Notification::make()
                ->title('Upload failed')
                ->body('Could not locate uploaded file.')
                ->danger()
                ->send();
            return;
        }

        $uploadedFile = new \Illuminate\Http\UploadedFile(
            $filePath,
            basename($data['theme_zip']),
            'application/zip',
            null,
            true
        );

        $themeService = app(ThemeService::class);
        $result = $themeService->installFromZip($uploadedFile);

        // Clean up temp file
        @unlink($filePath);

        if ($result['success']) {
            Notification::make()
                ->title($result['message'])
                ->success()
                ->send();

            $this->uploadForm->fill();
            $this->reset('data');
        } else {
            Notification::make()
                ->title('Installation failed')
                ->body($result['message'])
                ->danger()
                ->send();
        }
    }

    public function activateTheme(string $slug): void
    {
        $themeService = app(ThemeService::class);
        $themeService->activateTheme($slug);

        Notification::make()
            ->title('Theme activated successfully')
            ->success()
            ->send();
    }

    public function deleteTheme(string $slug): void
    {
        $themeService = app(ThemeService::class);
        $result = $themeService->deleteTheme($slug);

        if ($result['success']) {
            Notification::make()
                ->title($result['message'])
                ->success()
                ->send();
        } else {
            Notification::make()
                ->title('Cannot delete theme')
                ->body($result['message'])
                ->danger()
                ->send();
        }
    }

    protected function getForms(): array
    {
        return [
            'uploadForm',
        ];
    }
}
