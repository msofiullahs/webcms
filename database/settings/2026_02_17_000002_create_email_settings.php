<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('email.contact_notification_email', null);
        $this->migrator->add('email.smtp_from_name', 'WebCMS');
        $this->migrator->add('email.smtp_from_email', 'hello@example.com');
        $this->migrator->add('email.notification_enabled', true);
    }
};
