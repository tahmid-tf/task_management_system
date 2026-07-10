<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserStatusChangedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $userName,
        public string $status,
        public string $changedBy = 'System',
    ) {
        //
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: sprintf(
                'Your account has been %s',
                $this->status === 'active' ? 'activated' : 'deactivated'
            ),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.user-status-changed',
            with: [
                'userName'  => $this->userName,
                'status'    => $this->status,
                'changedBy' => $this->changedBy,
            ],
        );
    }
}
