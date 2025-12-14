<?php

namespace App\Mail;

use App\Models\EmailTemplate;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProfileCompletionMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $templateKey;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, string $templateKey)
    {
        $this->user = $user;
        $this->templateKey = $templateKey;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $template = EmailTemplate::where('key', $this->templateKey)->first();

        return new Envelope(
            subject: $template?->subject ?? 'Complete your profile!',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $template = EmailTemplate::where('key', $this->templateKey)->first();
        $content = $template?->content ?? '';

        // Replace placeholders
        $placeholders = [
            '{{ name }}' => $this->user->name,
            '{{ profile_link }}' => route('profile'),
        ];

        $content = str_replace(array_keys($placeholders), array_values($placeholders), $content);

        return new Content(
            view: 'emails.dynamic_template',
            with: [
                'content' => $content,
                'subject' => $template?->subject ?? 'Complete your profile!',
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
