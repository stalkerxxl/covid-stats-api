<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
    }

    public function getDependencies(): array
    {
        return [
            CountryFixtures::class,
        ];
    }
}
