<?php

namespace App\Util;

use Illuminate\Pagination\Paginator;

class HelperUntil
{

    public static function formatDate($date)
    {
        return date('Y/m/d', strtotime($date));
    }
}
