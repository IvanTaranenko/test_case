<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    public $products;

    public $recipientType;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order, $products, $recipientType = 'customer')
    {
        $this->order = $order;
        $this->products = $products;
        $this->recipientType = $recipientType;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('noreply@example.com', 'noreply'),
            subject: 'Order Confirmation'
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        if ($this->recipientType == 'customer') {
            return new Content(
                view: 'emails.customer_order_confirmation',
                with: [
                    'order' => $this->order,
                    'products' => $this->products,

                ],
            );
        } else {
            return new Content(
                view: 'emails.admin_order_notification',
                with: [
                    'order' => $this->order,
                    'products' => $this->products,

                ],
            );
        }
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
