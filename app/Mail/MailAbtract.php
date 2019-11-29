<?php

namespace App\Mail;

abstract class MailAbtract
{
    
    public $type = null;
    public $template = null;
    public $subject = null;
    public $content = null;
    public $fromEmail = null;
    public $toEmail = null;

    public function __construct()
    {
        $this->content = 'default';
        $this->toEmail = array('tuanlh2907@gmail.com');
        $this->subject = "ABCDEF";
    }

    abstract public function generateData();
}
