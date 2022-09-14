<?php

namespace App\Helpers;

class Utilities
{
    public static function clean($string)
    {
        return trim(preg_replace('/[^A-Za-z0-9 !@#$%^&*().]/u', '', strip_tags($string)));
    }
}
