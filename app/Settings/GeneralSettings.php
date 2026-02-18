<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    public string $site_title;
    public string $site_description;
    public ?string $site_logo;
    public ?string $site_favicon;
    public string $footer_text;

    public static function group(): string
    {
        return 'general';
    }
}
