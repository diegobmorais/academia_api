<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendMailWelcomeToUser extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $type_plan;
    public $limit_student;
    public function __construct($name, $type_plan, $limit_student)
    {
        $this->name = $name;
        $this->type_plan = $type_plan;
        $this->limit_student = $limit_student;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Bem vindo a Academia SysTrain',
        );
    }

    public function content(): Content
    {
        return new Content(
            html: 'emails.welcomeUsers',
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
