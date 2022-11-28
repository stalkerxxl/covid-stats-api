<?php

namespace App\DataFixtures;

use App\Entity\Country;
use App\Message\UpdateCountry;
use App\MessageHandler\UpdateCountryHandler;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CountryFixtures extends Fixture
{
    private UpdateCountryHandler $updateCountryHandler;

    public function __construct(UpdateCountryHandler $updateCountryHandler)
    {
        $this->updateCountryHandler = $updateCountryHandler;
    }

    public function load(ObjectManager $manager): void
    {
        call_user_func($this->updateCountryHandler, new UpdateCountry(CountryData::getJsonData()));
    }

}
