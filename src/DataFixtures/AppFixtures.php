<?php

namespace App\DataFixtures;

use App\Entity\Country;
use App\Service\ApiClient;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class AppFixtures extends Fixture
{
    private ApiClient $apiClient;

    public function __construct(ApiClient $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
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
            $manager->persist($country);
        }
        $manager->flush();
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    private function getCountriesListFromApi(): array
    {
        return $this->apiClient->getCountriesList();
    }
}
