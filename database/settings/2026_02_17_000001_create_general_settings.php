<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.site_title', 'WebCMS');
        $this->migrator->add('general.site_description', 'A modern content management system');
        $this->migrator->add('general.site_logo', null);
        $this->migrator->add('general.site_favicon', null);
        $this->migrator->add('general.footer_text', '© ' . date('Y') . ' WebCMS. All rights reserved.');
    }
};
