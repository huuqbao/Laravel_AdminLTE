<?php

namespace App\Helpers;

use Carbon\Carbon;

class DateHelper
{
    public static function formatForDateTimeLocal(?string $datetime): ?string
    {
        if (!$datetime) return null;

        try {
            return Carbon::parse($datetime)->format('Y-m-d\TH:i');
        } catch (\Exception $e) {
            return null;
        }
    }
}
