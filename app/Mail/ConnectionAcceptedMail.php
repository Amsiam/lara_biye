<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ConnectionAcceptedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $accepter;
    public $receiver;

    /**
     * Create a new message instance.
     */
    public function __construct($accepter, $receiver)
    {
        $this->accepter = $accepter;
        $this->receiver = $receiver;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Connection Request Accepted by ' . $this->accepter->name,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.connection.accepted',
            with: [
                'url' => route('profile', $this->accepter->id),
            ],
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
