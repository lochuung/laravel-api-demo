<?php

namespace App\Utilities;

class CommonUtil
{
    public static function getIntLength(int $number): int
    {
        return strlen((string)abs($number));
    }
}
