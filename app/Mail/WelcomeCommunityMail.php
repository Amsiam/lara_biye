<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class WelcomeCommunityMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public User $user)
    {
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $template = \App\Models\EmailTemplate::where('key', 'welcome_community')->first();
        return new Envelope(
            subject: $template?->subject ?? 'Join our Engineering Community!',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $template = \App\Models\EmailTemplate::where('key', 'welcome_community')->first();
        $content = $template?->content ?? '';

        // Replace placeholders
        $placeholders = [
            '{{ name }}' => $this->user->name,
        ];

        $content = str_replace(array_keys($placeholders), array_values($placeholders), $content);

        return new Content(
            view: 'emails.dynamic_template',
            with: [
                'content' => $content,
                'subject' => $template?->subject ?? 'Join our Engineering Community!',
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
