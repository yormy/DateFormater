<?php declare(strict_types=1);

namespace Yormy\Dateformatter\Models\Traits;

use Illuminate\Support\Carbon;

trait CarbonFormatterTrait
{
    public function register()
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
