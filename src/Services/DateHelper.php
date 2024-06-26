<?php

declare(strict_types=1);

namespace Yormy\Dateformatter\Services;

use Carbon\Carbon;
use Yormy\Dateformatter\Exceptions\InvalidConfigException;

class DateHelper
{
    public static function formatDate(?Carbon $date): string
    {
        if (! $date) {
            return '';
        }

        return $date->format(config('dateformatter.date.format'));
    }

    public static function nowUTC(): Carbon
    {
        return Carbon::now('UTC');
    }

    //    public static function dateFromSQL($string)
    //    {
    //        return $string->format('Y-m-d');
    //    }

    public static function formatDateTime($date)
    {
        if (! $date) {
            return '';
        }

        $format = config('dateformatter.datetime.format');
        if (! $format) {
            throw new InvalidConfigException('missing config: dateformatter.datetime.format');
        }

        return $date->format($format);
    }

    public static function formatDateTimeFromString(?string $dateString)
    {
        if (empty($dateString)) {
            return '';
        }

        $date = Carbon::parse($dateString);

        return self::formatDateTime($date);
    }

    //    public static function inUserTimezone($user, $date)
    //    {
    //        $userDate = $date->setTimezone(self::inTimezone($user));
    //        return $userDate;
    //    }

    //    public static function formatDateTimeInUserTimezone($user, $date = null) : string
    //    {
    //        if (!$date) {
    //            return "";
    //        }
    //
    //        $date = self::inUserTimezone($user, $date);
    //        return self::formatDateTime($date);
    //    }

    //    public static function formatDateTimeForUser($date = null) : string
    //    {
    //        $user= Auth::user();
    //        return self::formatDateTimeInUserTimezone($user, $date);
    //    }

    //    private static function inTimezone($user)
    //    {
    //        if (!$user || !$user->timezone) {
    //            return config('datetime.timezone_default');
    //        }
    //        return $user->timezone;
    //    }
}
