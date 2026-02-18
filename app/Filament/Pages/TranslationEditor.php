<?php

namespace App\Filament\Pages;

use App\Models\Language;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\File;

class TranslationEditor extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-language';
    protected static ?string $navigationGroup = 'Settings';
    protected static ?int $navigationSort = 5;
    protected static ?string $title = 'Translation Editor';
    protected static string $view = 'filament.pages.translation-editor';

    public array $translations = [];
    public array $languages = [];
    public string $newKey = '';

    public function mount(): void
    {
        $this->languages = Language::active()->pluck('code')->toArray();
        $this->loadTranslations();
    }

    protected function loadTranslations(): void
    {
        $this->translations = [];
        $allKeys = [];

        foreach ($this->languages as $lang) {
            $path = lang_path("{$lang}.json");
            if (File::exists($path)) {
                $data = json_decode(File::get($path), true) ?? [];
                foreach (array_keys($data) as $key) {
                    $allKeys[$key] = true;
                }
            }
        }

        foreach (array_keys($allKeys) as $key) {
            $row = ['key' => $key];
            foreach ($this->languages as $lang) {
                $path = lang_path("{$lang}.json");
                $data = File::exists($path) ? json_decode(File::get($path), true) ?? [] : [];
                $row[$lang] = $data[$key] ?? '';
            }
            $this->translations[] = $row;
        }
    }

    public function save(): void
    {
        foreach ($this->languages as $lang) {
            $data = [];
            foreach ($this->translations as $row) {
                if (!empty($row['key'])) {
                    $data[$row['key']] = $row[$lang] ?? '';
                }
            }
            ksort($data);
            File::put(
                lang_path("{$lang}.json"),
                json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
            );
        }

        Notification::make()
            ->title('Translations saved successfully')
            ->success()
            ->send();
    }

    public function addKey(): void
    {
        if (empty($this->newKey)) {
            return;
        }

        $row = ['key' => $this->newKey];
        foreach ($this->languages as $lang) {
            $row[$lang] = '';
        }
        $this->translations[] = $row;
        $this->newKey = '';
    }

    public function removeKey(int $index): void
    {
        unset($this->translations[$index]);
        $this->translations = array_values($this->translations);
    }
}
