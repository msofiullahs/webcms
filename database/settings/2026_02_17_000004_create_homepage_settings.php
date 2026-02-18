<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('homepage.hero_title', 'Welcome to WebCMS');
        $this->migrator->add('homepage.hero_subtitle', 'A modern, flexible content management system');
        $this->migrator->add('homepage.hero_image', null);
        $this->migrator->add('homepage.hero_cta_text', 'Get Started');
        $this->migrator->add('homepage.hero_cta_url', '/posts');
        $this->migrator->add('homepage.featured_posts_count', 6);
        $this->migrator->add('homepage.layout_sections', null);
    }
};
