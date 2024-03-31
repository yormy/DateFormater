<?php

declare(strict_types=1);

namespace Yormy\Dateformatter;

use Illuminate\Support\Facades\Facade;

class DateformatterFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return Dateformatter::class;
    }
}
