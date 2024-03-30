<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EmailBirthday extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $quantityBooksReadYear;
    public $quantityPagesReadTotal;

    /**
     * Create a new message instance.
     */
    public function __construct($name, $quantityBooksReadYear, $quantityPagesReadTotal)
    {
        $this->name = $name;
        $this->quantityBooksReadYear = $quantityBooksReadYear;
        $this->quantityPagesReadTotal = $quantityPagesReadTotal;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Email Birthday',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'Birthday',
            with : [
                'name' => $this->name,
                'quantityBooksReadYear' => $this->quantityBooksReadYear,
                'quantityPagesReadTotal' => $this->quantityPagesReadTotal,
            ]
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
