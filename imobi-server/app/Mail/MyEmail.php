<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MyEmail extends Mailable
{
    use Queueable, SerializesModels;

    protected $tableau = [];
    /**
     * Create a new message instance.
     */
    public function __construct($tableau)
    {
        $this->tableau = $tableau;
    }
   /*  public function test($tableau){
        dd($tableau['name']);
    } */
    /**
     * Get the message envelope.
     */
    // expediteur
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'My Email',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.contact',
            with: ['contact' => $this->tableau]
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
