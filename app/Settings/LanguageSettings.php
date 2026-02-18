<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class LanguageSettings extends Settings
{
    public string $default_locale;
    public array $available_locales;
    public bool $show_language_switcher;

    public static function group(): string
    {
        return 'language';
    }
}
