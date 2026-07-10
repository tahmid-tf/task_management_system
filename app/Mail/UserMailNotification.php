<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserMailNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public string $subjectLine,
        public string $heading,
        public string $intro,
        public array $tasks = [],
        public string $customMessage = '',
        public string $closingLine = '',
    ) {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subjectLine,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.user-mail-notification',
            with: [
                'heading'       => $this->heading,
                'intro'         => $this->intro,
                'tasks'         => $this->tasks,
                'customMessage' => $this->customMessage,
                'closingLine'   => $this->closingLine,
            ],
        );
    }
}
