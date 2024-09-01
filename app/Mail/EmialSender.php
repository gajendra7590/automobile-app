<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmialSender extends Mailable
{
    use Queueable, SerializesModels;

    public $template;
    public $attachment_data;
    public $subject;
    public $data;

    /**
     * Create a new message instance.
     */
    public function __construct($payload = [])
    {
        $this->data = isset($payload['data']) ? $payload['data'] : [];
        $this->subject = isset($payload['subject']) ? $payload['subject'] : "";
        $this->template = isset($payload['template']) ? $payload['template'] : "";
        $this->attachment_data = isset($payload['attachments']) ? $payload['attachments'] : [];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.'.$this->template);
    }
}
