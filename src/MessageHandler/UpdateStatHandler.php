<?php

namespace App\MessageHandler;

use App\Entity\Country;
use App\Entity\Stat;
use App\Exception\ApiException;
use App\Message\UpdateStat;
use App\Repository\CountryRepository;
use App\Repository\StatRepository;
use App\Service\ApiClient;
use DateTimeImmutable;
use DateTimeZone;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Exception;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class UpdateStatHandler implements MessageHandlerInterface
{
    private ApiClient $apiClient;
    private EntityManagerInterface $entityManager;
    private array $response;
    private CountryRepository $countryRepository;
    private ValidatorInterface $validator;
    private StatRepository $statRepository;

    public function __construct(ApiClient              $apiClient,
                                EntityManagerInterface $entityManager,
                                CountryRepository      $countryRepository,
                                StatRepository         $statRepository,
                                ValidatorInterface     $validator)
    {
        $this->apiClient = $apiClient;
        $this->entityManager = $entityManager;
        $this->countryRepository = $countryRepository;
        $this->validator = $validator;
        $this->statRepository = $statRepository;
    }

    /**
     * @throws ApiException
     * @throws Exception
     */
    public function __invoke(UpdateStat $message)
    {
        //FIXME сделать DTO
        $this->response = $this->apiClient->getSummaryStat();
        if ($this->response['Message'] != '') {
            throw new \Exception($this->response['Message']);
        }
        $this->makeWorldStat();

        foreach ($this->response['Countries'] as $item) {
            $this->updateStat($item);
        }

        $this->entityManager->flush();
    }

    /**
     * @throws EntityNotFoundException
     * @throws Exception
     */
    public function updateStat(array $item)
    {
        $country = $this->countryRepository->findOneBy(['code' => $item['CountryCode']]);
        if (null == $country) {
            throw new EntityNotFoundException();
        }
        $this->makeStatEntity($country, $item);
    }

    /**
     * @throws Exception
     */
    private function makeWorldStat()
    {
        $world = $this->countryRepository->findOneBy(['name' => 'WORLD']);
        if (null == $world) {
            $world = (new Country())
                ->setName('WORLD')
                ->setCode('world')
                ->setSlug('world')
                ->setCreatedAt(new \DateTimeImmutable());
            $this->entityManager->persist($world);
        }

        $worldData = $this->response['Global'];
        $worldData['ID']= $this->response['ID'];

        $this->makeStatEntity($world, $worldData);

    }

    /**
     * @param Country $country
     * @param array $item
     * @return void
     * @throws Exception
     */
    private function makeStatEntity(Country $country, array $item): void
    {
        $stat = $country->getStat();

        if (null == $stat) {
            $stat = new Stat();
            $stat->setCountry($country);
            $this->entityManager->persist($stat);
        } elseif ($stat->getApiUuid() == $item['ID']) {
            $this->entityManager->detach($stat);
            $this->entityManager->detach($country);
            return;
        }
        $stat->setApiUuid($item['ID'])
            ->setNewConfirmed($item['NewConfirmed'])
            ->setTotalConfirmed($item['TotalConfirmed'])
            ->setNewDeaths($item['NewDeaths'])
            ->setTotalDeaths($item['TotalDeaths'])
            ->setNewRecovered($item['NewRecovered'])
            ->setTotalRecovered($item['TotalRecovered'])
            ->setApiTimestamp(new \DateTimeImmutable($item['Date']));

        $errors = $this->validator->validate($stat);
        if ($errors->count() > 0) {
            $this->entityManager->detach($stat);
            //FIXME писать в лог
        }
    }
}
