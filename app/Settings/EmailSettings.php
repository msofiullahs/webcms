<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class EmailSettings extends Settings
{
    public ?string $contact_notification_email;
    public string $smtp_from_name;
    public string $smtp_from_email;
    public bool $notification_enabled;

    public static function group(): string
    {
        return 'email';
    }
}
