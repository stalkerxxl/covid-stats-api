<?php

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

class CountriesUpdatedEvent extends Event
{
    public const NAME = 'countries.updated';
}