<?php

use Carbon\Carbon;

if (!function_exists('format_datetime_local')) {
    function format_datetime_local(?string $datetime): ?string
    {
        if (!$datetime) return null;

        try {
            return Carbon::parse($datetime)->format('Y-m-d\TH:i');
        } catch (\Exception $e) {
            return null;
        }
    }
}
