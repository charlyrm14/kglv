<?php

namespace App\Mail;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;

class UserRegisteredMail extends Mailable
{
    use Queueable, SerializesModels;

    private User $user;
    private string $temporary_password;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, string $temporary_password)
    {
        $this->user = $user;
        $this->temporary_password = $temporary_password;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('support@king-dreams.com', 'King Dreams'),
            subject: 'Bienvenido a King Dreams',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails/users/user-registered',
            with: [
                'name' => $this->user->name,
                'temporary_password' => $this->temporary_password,
                'user_code' => $this->user->user_code,
                'frontend_url' => config('app.frontend_url'),
                'current_year' => Carbon::now()->year
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
