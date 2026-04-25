<?php

namespace App\Mail;

use App\Models\GiftCard;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class GiftCardMail extends Mailable
{
    use Queueable, SerializesModels;

    public $giftCard;

    public function __construct(GiftCard $giftCard)
    {
        $this->giftCard = $giftCard;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'A Gift of Luxury from ' . $this->giftCard->sender_name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.gift-card',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
