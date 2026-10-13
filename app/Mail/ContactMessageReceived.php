<?php

namespace App\Mail;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Notification envoyée à l'éditeur du site après une demande de contact.
 *
 * L'adresse de réponse est celle du visiteur : répondre depuis sa messagerie
 * suffit, sans repasser par le site.
 */
class ContactMessageReceived extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public ContactMessage $contactMessage) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('mail.contact.subject', [
                'subject' => $this->contactMessage->subjectLabel(),
                'name' => $this->contactMessage->name,
            ], $this->contactMessage->locale),
            replyTo: [
                new Address($this->contactMessage->email, $this->contactMessage->name),
            ],
        );
    }

    public function content(): Content
    {
        return new Content(markdown: 'mail.contact-message-received');
    }
}
