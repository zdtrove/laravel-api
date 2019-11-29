<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;

class MailSender extends Mailable
{
    use Queueable, SerializesModels;

    public function buildContent($mailDataObject)
    {
        $this->mailDataObject = $mailDataObject;
    }

    public function build()
    {
        return $this
                ->subject($this->mailDataObject->subject)
                ->to($this->mailDataObject->toEmail)
                ->view($this->mailDataObject->template)
                ->with('mailDataObject', $this->mailDataObject);
    }

    public function sendMail()
    {
        return Mail::send($this);
    }
}
