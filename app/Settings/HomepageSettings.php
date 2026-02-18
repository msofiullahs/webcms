<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class HomepageSettings extends Settings
{
    public ?int $homepage_page_id;
    public int $featured_posts_count;

    public static function group(): string
    {
        return 'homepage';
    }
}
