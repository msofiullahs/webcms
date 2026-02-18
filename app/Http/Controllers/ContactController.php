<?php

namespace App\Http\Controllers;

use App\Models\ContactSubmission;
use App\Notifications\ContactFormNotification;
use App\Settings\EmailSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Inertia\Inertia;

class ContactController extends Controller
{
    public function index()
    {
        return Inertia::render('Contact');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        $submission = ContactSubmission::create($validated);

        $emailSettings = app(EmailSettings::class);
        if ($emailSettings->notification_enabled && $emailSettings->contact_notification_email) {
            Notification::route('mail', $emailSettings->contact_notification_email)
                ->notify(new ContactFormNotification($submission));
        }

        return redirect()->back()->with('success', 'Thank you for your message! We will get back to you soon.');
    }
}
