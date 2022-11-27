<?php

namespace App\DataFixtures;

use App\Entity\Country;
use App\Exception\ApiException;
use App\Service\ApiClient;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class AppFixtures extends Fixture
{
    private ApiClient $apiClient;
    private ValidatorInterface $validator;

    public function __construct(ApiClient $apiClient, ValidatorInterface $validator)
    {
        $this->apiClient = $apiClient;
        $this->validator = $validator;
    }

    /**
     * @throws ApiException
     */
    public function load(ObjectManager $manager): void
    {
        $countriesList = $this->getCountriesListFromApi();

        foreach ($countriesList as $item) {
            $country = (new Country())
                ->setName($item['Country'])
                ->setSlug($item['Slug'])
                ->setCode($item['ISO2'])
                ->setCreatedAt(new DateTimeImmutable());
            $errors = $this->validator->validate($country);
            if ($errors->count() > 0) {
                continue;
            }

            $manager->persist($country);
        }
        $manager->flush();
    }

    /**
     * @throws ApiException
     */
    private function getCountriesListFromApi(): array
    {
        return $this->apiClient->getCountriesList();
    }
}
