<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class AdminRegistrationNotification extends Notification
{
    protected $code;

    public function __construct($code)
    {
        $this->code = $code;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        // Link users directly to the verify page with their email (and code optional)
        $verifyUrl = url(route('admin.verify', ['email' => $notifiable->email, 'code' => $this->code], false));

        return (new MailMessage)
            ->subject('Welcome to IPA Admin')
            ->greeting('Hello ' . ($notifiable->name ?? 'Admin') . ',')
            ->line('Thank you for registering. Use the following 6-digit code to verify your account:')
            ->line('')
            ->line("Code: {$this->code}")
            ->line('Click the button below to open the verification page (your email is prefilled):')
            ->action('Verify Email', $verifyUrl)
            ->line('If you did not register, please ignore this email.');
    }
}
