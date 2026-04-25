<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AbandonedCartReminder extends Mailable
{
    use Queueable, SerializesModels;

    public $abandonedCheckout;

    /**
     * Create a new message instance.
     */
    public function __construct(\App\Models\AbandonedCheckout $abandonedCheckout)
    {
        $this->abandonedCheckout = $abandonedCheckout;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'A Treasure Awaits in Your Bag | ' . config('app.name'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.abandoned-cart',
            with: [
                'checkout' => $this->abandonedCheckout,
                'userName' => $this->abandonedCheckout->user_name,
                'cartItems' => $this->abandonedCheckout->cart_data,
                'total' => $this->abandonedCheckout->cart_total,
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
