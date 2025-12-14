<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class ProfileImageReminderMail extends Mailable
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
        $template = \App\Models\EmailTemplate::where('key', 'profile_image_reminder')->first();
        return new Envelope(
            subject: $template?->subject ?? 'Complete your profile to get noticed!',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $template = \App\Models\EmailTemplate::where('key', 'profile_image_reminder')->first();
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
                'subject' => $template?->subject ?? 'Complete your profile to get noticed!',
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
