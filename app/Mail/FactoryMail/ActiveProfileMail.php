<?php
namespace App\Mail\FactoryMail;

use App\Mail\MailAbtract;
use App\Mail\MailDefine;

class ActiveProfileMail extends MailAbtract
{
    
    public function generateData()
    {
        
        $this->type = MailDefine::ACTIVE_PROFILE;
        $this->template = 'mails.accounts.active-profile';
        $this->subject = '【Wntedly】 - Active Profile';
        $this->content = 'Active profile content';
    }
}
