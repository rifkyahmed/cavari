<?php

namespace App\Helpers;

class CurrencyHelper
{
    public static function format($amount)
    {
        if (is_null($amount)) {
            return '$0.00';
        }
        
        return '$' . number_format((float) $amount, 2);
    }
}
