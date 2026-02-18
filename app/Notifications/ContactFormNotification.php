<?php

namespace App\Notifications;

use App\Models\ContactSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContactFormNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected ContactSubmission $submission
    ) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Contact Form Submission: ' . ($this->submission->subject ?? 'No Subject'))
            ->greeting('New contact form submission received')
            ->line('**Name:** ' . $this->submission->name)
            ->line('**Email:** ' . $this->submission->email)
            ->line('**Message:** ' . $this->submission->message)
            ->action('View in Admin', url('/admin/contact-submissions/' . $this->submission->id));
    }
}
