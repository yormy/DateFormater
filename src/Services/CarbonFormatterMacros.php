<?php declare(strict_types=1);

namespace Yormy\Dateformatter\Services;

use Illuminate\Support\Carbon;

class CarbonFormatterMacros
{
    public static function register()
    {
        $dateFormat = config('dateformatter.date.format');
        $timeFormat = config('dateformatter.time.format');
        $dateTimeFormat = config('dateformatter.datetime.format');

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
