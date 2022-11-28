<?php

namespace App\MessageHandler;

use App\Entity\Country;
use App\Exception\ApiException;
use App\Message\UpdateCountry;
use App\Service\ApiClient;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Exception;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class UpdateCountryHandler implements MessageHandlerInterface
{
    private ApiClient $apiClient;
    private EntityManagerInterface $entityManager;
    private ValidatorInterface $validator;
    private ?array $data = null;

    public function __construct(ApiClient              $apiClient,
                                EntityManagerInterface $entityManager,
                                ValidatorInterface     $validator)
    {
        $this->apiClient = $apiClient;
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }

    /**
     * @throws Exception
     */
    public function __invoke(UpdateCountry $message)
    {
        try {
            if (null == $message->getOfflineData()) {
                $this->data = $this->apiClient->getSummaryStat();
                if ($this->data['Message'] != '') {
                    throw new Exception($this->data['Message']);
                }
            } else {
                $this->data = $message->getOfflineData();
            }
            /* dd($message->getOfflineData());*/
            $this->makeWorldStat();
            foreach ($this->data['Countries'] as $item) {
                try {
                    $this->updateCountryData($item);
                } catch (Exception $e) {
                    continue;
                    //FIXME писать в лог
                }
            }
            $this->entityManager->flush();
        } catch (Exception $e) {
            //FIXME писать в лог
            throw new Exception($e->getMessage());
        }
    }

    private function makeWorldStat()
    {
        return;
        //TODO писать в кеш статистику по миру
    }

    /**
     * @throws EntityNotFoundException
     * @throws Exception
     */
    private function updateCountryData(array $item): void
    {
        $countryRepository = $this->entityManager->getRepository(Country::class);
        $country = $countryRepository->findOneBy(['name' => $item['Country']]);

        if (null == $country) {
            $country = new Country();
            $country->setName($item['Country']);
            $countryRepository->save($country);
        }

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
    }
}
