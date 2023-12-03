<?php declare(strict_types=1);

namespace Yormy\Dateformatter\Models\Traits;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Yormy\Dateformatter\Exceptions\InvalidConfigException;

trait DateFormatterResource
{
    protected function getDates(array $fields): array
    {
        $parent = parent::toArray(Request());

        foreach ($fields as $field) {
            $dates[$field] = $parent[$field];
            $dates[$field . '_local'] = $parent[$field . '_local']; // make sure you have 'use DateFormatter;' in your model
            $dates[$field . '_human'] = $parent[$field . '_human'];
        }

        return $dates;
    }
}
