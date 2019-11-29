<?php

namespace App\Mail;

class MailFactory extends MailDefine
{
    public $mailGenerateFactory;
    public $mailSender;

    public function __construct(MailGenerateFactory $mailGenerateFactory, MailSender $mailSender)
    {
        $this->mailGenerateFactory = $mailGenerateFactory;
        $this->mailSender = $mailSender;
    }

    public function generateMailType($type)
    {
        $mailTypeObject = $this->mailGenerateFactory->createEmailType($type);
        $mailTypeObject->generateData();
        return $mailTypeObject;
    }

    public function send($mailTypeObject)
    {
        $this->mailSender->buildContent($mailTypeObject);
        $this->mailSender->sendMail();
    }
}
