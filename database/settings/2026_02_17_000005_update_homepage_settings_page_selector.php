<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->delete('homepage.hero_title');
        $this->migrator->delete('homepage.hero_subtitle');
        $this->migrator->delete('homepage.hero_image');
        $this->migrator->delete('homepage.hero_cta_text');
        $this->migrator->delete('homepage.hero_cta_url');
        $this->migrator->delete('homepage.layout_sections');
        $this->migrator->add('homepage.homepage_page_id', null);
    }
};
