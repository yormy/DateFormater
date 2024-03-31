<?php

declare(strict_types=1);

// https://www.qcode.in/managing-users-timezone-in-laravel-app/
// TODO : when to convert timezone and how

namespace Yormy\Dateformatter\Models\Traits;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Yormy\Dateformatter\Exceptions\InvalidConfigException;

trait DateFormatter
{
    /**
     * All the fields other than eloquent model dates array you want to format
     */
    protected array $formattedDates = [];

    /**
     * Flag to disable formatting on demand
     */
    protected bool $noFormat = false;

    protected string $formattedFieldPostfix = '_local';

    protected string $formattedFieldPostfixForHuman = '_human';

    /**
     * Override the models toArray to append the formatted dates fields
     *
     * @return array
     */
    public function toArray()
    {
        $data = parent::toArray();

        if ($this->noFormat) {
            return $data;
        }

        foreach ($this->getFormattedDateFields() as $dateField) {
            $object = $this->toDateObject($this->{$dateField});
            $data[$dateField] = $this->toStringInUtc($object);
            $data[$dateField.$this->formattedFieldPostfix] = $this->toStringInTimezone($object);
            $data[$dateField.$this->formattedFieldPostfixForHuman] = $object['for_human'];
        }

        return $data;
    }

    private function toStringInTimezone(array $object): ?string
    {
        $value = $object['date_in_tz'];

        if ($object['time_in_tz']) {
            $value .= ' '.$object['time_in_tz'];
        }

        return $value;
    }

    private function toStringInUtc(array $object): ?string
    {
        $value = $object['date_in_utc'];

        if ($object['time_in_utc']) {
            $value .= ' '.$object['time_in_utc'];
        }

        return $value;
    }

    /**
     * Built a date object for serialization
     */
    private function toDateObject(?Carbon $dateValue): array
    {
        return [
            'date_in_tz' => $this->formattedDate($dateValue, true),
            'time_in_tz' => $this->formattedTime($dateValue, true),
            'date_in_utc' => $this->formattedDate($dateValue),
            'time_in_utc' => $this->formattedTime($dateValue),
            'for_human' => $this->formattedDiffForHumans($dateValue),
        ];
    }

    private function formattedDate(?Carbon $dateValue, bool $inTimezone = false): ?string
    {
        if (! $dateValue) {
            return null;
        }

        $date = $this->asDateTime($dateValue);
        if ($inTimezone) {
            $date = $this->inUsersTimezone($dateValue);
        }

        if (! config('datetime.date_format')) {
            throw new InvalidConfigException('missing config');
        }

        return $date->format(config('datetime.date_format'));
    }

    private function formattedTime(?Carbon $dateValue, bool $inTimezone = false): ?string
    {
        if (is_null($dateValue)) {
            return null;
        }

        $date = $this->asDateTime($dateValue);
        if ($inTimezone) {
            $date = $this->inUsersTimezone($dateValue);
        }

        return $date->format(config('datetime.time_format'));
    }

    private function formattedDiffForHumans(?Carbon $dateValue): ?string
    {
        if (is_null($dateValue)) {
            return null;
        }

        return $this->inUsersTimezone($dateValue)
            ->diffForHumans();
    }

    /**
     * Return all the fields which needed formatted dates
     */
    private function getFormattedDateFields(): array
    {
        return array_merge($this->formattedDates, $this->getDates());
    }

    public function setFormattedDates(array $formattedDates)
    {
        $this->formattedDates = $formattedDates;
    }

    /**
     * Get the formatted date object for a field
     */
    public function toLocalTime(?string $field = null): array
    {
        $dateValue = is_null($this->{$field}) ? Carbon::now() : $this->{$field};

        return $this->toDateObject($dateValue);
    }

    public function disableFormat()
    {
        $this->noFormat = true;

        return $this;
    }

    public function enableFormat()
    {
        $this->noFormat = false;

        return $this;
    }

    private function inUsersTimezone(Carbon $dateValue): Carbon
    {
        $user = Auth::user();

        $timezone = $user->timezone ?? config('app.timezone');

        return $this->asDateTime($dateValue)
            ->timezone($timezone);
    }

    public function getCreatedAtHumansAttribute(): ?string
    {
        if (! $this->created_at) {
            return null;
        }

        return $this->created_at->diffForHumans();
    }

    public function inDateTime($value)
    {
        $format = $this->getDateFormat();

        // Finally, we will just assume this date is in the format used by default on
        // the database connection and use that format to create the Carbon object
        // that is returned back out to the developers after we convert it here.
        try {
            $date = Date::createFromFormat($format, $value);
        } catch (\Exception $e) {
            $date = false;
        }

        return $date;
    }
}
