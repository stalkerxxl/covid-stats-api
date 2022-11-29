<?php

namespace App\MessageHandler;

use App\Entity\Country;
use App\Event\CountriesUpdatedEvent;
use App\Message\UpdateCountries;
use App\Service\ApiClient;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class UpdateCountriesHandler implements MessageHandlerInterface
{
    private ApiClient $apiClient;
    private EntityManagerInterface $entityManager;
    private ValidatorInterface $validator;
    private EventDispatcherInterface $eventDispatcher;
    private LoggerInterface $logger;

    public function __construct(ApiClient                $apiClient,
                                EntityManagerInterface   $entityManager,
                                ValidatorInterface       $validator,
                                EventDispatcherInterface $eventDispatcher,
    LoggerInterface $messengerLogger)
    {
        $this->apiClient = $apiClient;
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        $this->eventDispatcher = $eventDispatcher;
        $this->logger = $messengerLogger;
    }

    /**
     * @throws Exception
     */
    public function __invoke(UpdateCountries $message)
    {
        try {
            if (null == $message->getOfflineData()) {
                $data = $this->apiClient->getCountriesSummary();
                if ($data['Message'] != '') {
                    throw new Exception($data['Message']);
                }
            } else {
                $data = $message->getOfflineData();
            }

            $this->makeWorldStat();

            foreach ($data['Countries'] as $item) {
                if ($item['Country'] == 'Antarctica')
                    continue;

                $country = $this->updateCountryData($item);
                $errors = $this->validator->validate($country);
                if ($errors->count() > 0)
                    $this->logger->error('ошибка валидации', ['item' => $item, 'errors' => (string)$errors]);
                    $this->entityManager->detach($country);
            }
            $this->entityManager->flush();

            $event = new CountriesUpdatedEvent();
            $this->eventDispatcher->dispatch($event);

        } catch (Exception $e) {
            $this->logger->error($e->getMessage(), ['exception' => $e]);
            throw new Exception($e->getMessage());
        }
    }

    private function deleteAntarctica()
    {
//TODO
    }

    private function makeWorldStat()
    {
        return;
        //TODO писать в кеш статистику по миру
    }

    /**
     * @throws Exception
     */
    private function updateCountryData(array $item): Country
    {
        $countryRepository = $this->entityManager->getRepository(Country::class);
        $country = $countryRepository->findOneBy(['name' => $item['Country']]);

        if (null == $country) {
            $country = new Country();
            $country->setName($item['Country']);
            $countryRepository->save($country);
        }

        //FIXME DTO или сериалазйер?
        $country->setSlug($item['Slug'])
            ->setCode($item['CountryCode'])
            ->setNewConfirmed($item['NewConfirmed'])
            ->setTotalConfirmed($item['TotalConfirmed'])
            ->setNewDeaths($item['NewDeaths'])
            ->setTotalDeaths($item['TotalDeaths'])
            ->setNewRecovered($item['NewRecovered'])
            ->setTotalRecovered($item['TotalRecovered'])
            ->setApiTimestamp(new \DateTimeImmutable($item['Date']));

        if (!empty($item['Premium']['CountryStats'])) {
            $data = $item['Premium']['CountryStats'];
            $country->setContinent($data['Continent'])
                ->setPopulation($data['Population'])
                ->setPopulationDensity($data['PopulationDensity'])
                ->setMedianAge($data['MedianAge'])
                ->setAged65Older($data['Aged65Older'])
                ->setAged70Older($data['Aged70Older'])
                ->setGdpPerCapita($data['GdpPerCapita'])
                ->setDiabetesPrevalence($data['DiabetesPrevalence'])
                ->setHandwashingFacilities($data['HandwashingFacilities'])
                ->setHospitalBedsPerThousand($data['HospitalBedsPerThousand'])
                ->setLifeExpectancy($data['LifeExpectancy']);
        }

        return $country;
    }
}
