<?php declare(strict_types=1);

namespace Yormy\Dateformatter\Services;

use Illuminate\Support\Carbon;

class CarbonFormatterMacros
{
    public static function register()
    {
        $dateFormat = config('dateformatter.date.format', 'Y-m-d');
        $timeFormat = config('dateformatter.time.format', 'H:i:s');
        $dateTimeFormat = config('dateformatter.datetime.format', 'Y-m-d H:i:s');

        Carbon::macro('formatDate', function () use ($dateFormat) {
            return $this->format($dateFormat);
        });

        Carbon::macro('formatTime', function () use ($timeFormat) {
            return $this->format($timeFormat);
        });

        Carbon::macro('formatDateTime', function () use ($dateTimeFormat) {
            return $this->format($dateTimeFormat);
        });
    }
}
