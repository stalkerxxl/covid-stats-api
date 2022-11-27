<?php

namespace App\DataFixtures;

use App\Exception\ApiException;
use App\Message\UpdateCountriesList;
use App\MessageHandler\UpdateCountriesListHandler;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    private UpdateCountriesListHandler $countryHandler;

    public function __construct(UpdateCountriesListHandler $countriesListHandler)
    {
        $this->countryHandler = $countriesListHandler;
    }

    /**
     * @throws ApiException
     */
    public function load(ObjectManager $manager): void
    {
        $this->makeCountries();
    }

    private function makeCountries()
    {
        call_user_func($this->countryHandler, new UpdateCountriesList());
    }
}
