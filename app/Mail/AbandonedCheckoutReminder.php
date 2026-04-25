<?php

namespace App\Mail;

use App\Models\AbandonedCheckout;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AbandonedCheckoutReminder extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public AbandonedCheckout $checkout,
        public ?string $customMessage = null
    ) {
        $this->customMessage = $customMessage ?? "We noticed you left some exquisite items in your cart. We've saved them for you, but they won't wait forever. Would you like to complete your order now?";
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'You left something behind — complete your order',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.abandoned-checkout',
        );
    }
}
