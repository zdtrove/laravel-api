<?php
namespace App\Mail\FactoryMail;

use App\Mail\MailAbtract;
use App\Mail\MailDefine;

class ResetPasswordMail extends MailAbtract
{
    public function generateData()
    {
        
        $this->type = MailDefine::RESET_PASSWORD;
        $this->template = 'mails.accounts.reset-password';
        $this->subject = 'RESET PASSWORD';
        $this->content = 'Reset Password content';
    }
}
