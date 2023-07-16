<?php

namespace Yormy\Dateformatter;

use Illuminate\Support\Carbon;
use Illuminate\Support\ServiceProvider;

class DateformatterServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            //            $this->publishes([
            //                __DIR__ . '/../config/config.php' => config_path('xid.php'),
            //            ], 'config');
        }

        //        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'xid');

        $this->extendCarbon();
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'dateformatter');
    }

    private function extendCarbon()
    {
        // This returns false invalid date, and also works for leap year checking.
        Carbon::macro('isValidDate', function ($date, $format = 'Y-m-d H:i:s') {
            $carbonDate = Carbon::createFromFormat($format, $date);

            return $carbonDate->format($format) === $date;
        });

        // Both Carbon and DateTime accepts 2023-06-31 as a valid input and parses that as a date being 2023-07-01
        // Use isDateValid to check if the string is a valid date before parsing it to carbon.
        Carbon::macro('createFromFormatSafe', function ($date, $format = 'Y-m-d H:i:s') {
            $validDate = Carbon::isValidDate($date, $format);
            if ($validDate) {
                return Carbon::createFromFormat($format, $date);
            }
        });
    }
}
