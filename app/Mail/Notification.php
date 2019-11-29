<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Notification extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    /**
     * Create a new message instance.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        // Load mail template based on condition
        $template = isset($this->data['template']) ? $this->data['template'] : 'default';
        switch ($template) {
            case ADMIN:
                $mailable = $this->view('mails.notification_admin');
                break;
            case PROFILE:
                $mailable = $this->view('mails.notification');
                break;
            default:
                $mailable = $this->view('mails.' . $this->data['template']);
        }

        // Set subject
        if (!empty($this->data['subject'])) {
            $mailable->subject($this->data['subject']);
        }

        // Send to cc or BCC
        if (!empty($this->data['cc'])) {
            $mailable->cc($this->data['cc']);
        }
        if (!empty($this->data['bcc'])) {
            $mailable->cc($this->data['bcc']);
        }

        return $mailable;
    }
}
