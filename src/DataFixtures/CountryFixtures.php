<?php

namespace App\DataFixtures;

use App\Message\UpdateCountries;
use App\MessageHandler\UpdateCountriesHandler;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CountryFixtures extends Fixture
{
    private UpdateCountriesHandler $updateCountryHandler;

    public function __construct(UpdateCountriesHandler $updateCountryHandler)
    {
        $this->updateCountryHandler = $updateCountryHandler;
    }

    public function load(ObjectManager $manager): void
    {
        call_user_func($this->updateCountryHandler, new UpdateCountries(CountriesData::getJsonData()));
    }

}
