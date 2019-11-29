<?php

namespace App\Mail;

use App\Mail\FactoryMail\ActiveCustomerMail;

class MailGenerateFactory
{

    public function createEmailType($type)
    {
        switch ($type) {
            case MailDefine::RESET_PASSWORD:
                $mailType = new FactoryMail\ResetPasswordMail();
                break;
            case MailDefine::ACTIVE_PROFILE:
                $mailType = new FactoryMail\ActiveProfileMail();
                break;
            default:
                $mailType = null;
                break;
        }

        return $mailType;
    }
}
