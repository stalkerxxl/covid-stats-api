<?php

namespace App\MessageHandler;

use App\Entity\Country;
use App\Exception\ApiException;
use App\Message\UpdateCountriesList;
use App\Service\ApiClient;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class UpdateCountriesListHandler implements MessageHandlerInterface
{
    private ApiClient $apiClient;
    private ValidatorInterface $validator;
    private EntityManagerInterface $entityManager;

    public function __construct(ApiClient $apiClient, ValidatorInterface $validator, EntityManagerInterface $entityManager)
    {
        $this->apiClient = $apiClient;
        $this->validator = $validator;
        $this->entityManager = $entityManager;
    }

    /**
     * @throws ApiException
     */
    public function __invoke(UpdateCountriesList $message)
    {

        $countriesList = $this->getCountriesListFromApi();//FIXME сделать DTO + валидацию
        $repo = $this->entityManager->getRepository(Country::class);
        foreach ($countriesList as $item) {
            $country = (new Country())
                ->setName($item['Country'])
                ->setSlug($item['Slug'])
                ->setCode($item['ISO2']);

            $errors = $this->validator->validate($country);
            if ($errors->count() > 0) {
                continue;
            }

            $this->entityManager->persist($country);
        }
        $this->entityManager->flush();
    }

    /**
     * @throws ApiException
     */
    private function getCountriesListFromApi(): array
    {
        return $this->apiClient->getCountriesList();
    }
}
