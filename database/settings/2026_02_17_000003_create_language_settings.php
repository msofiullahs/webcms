<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('language.default_locale', 'en');
        $this->migrator->add('language.available_locales', ['en']);
        $this->migrator->add('language.show_language_switcher', false);
    }
};
