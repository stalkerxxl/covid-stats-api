<?php

namespace App\MessageHandler;

use App\Entity\Stat;
use App\Exception\ApiException;
use App\Message\UpdateStatsByCountry;
use App\Repository\CountryRepository;
use App\Repository\StatRepository;
use App\Service\ApiClient;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class UpdateStatsByCountryHandler implements MessageHandlerInterface
{
    private ApiClient $apiClient;
    private EntityManagerInterface $entityManager;
    private CountryRepository $countryRepository;
    private StatRepository $statRepository;
    private ValidatorInterface $validator;
    private LoggerInterface $logger;

    public function __construct(ApiClient              $apiClient,
                                EntityManagerInterface $entityManager,
                                CountryRepository      $countryRepository,
                                StatRepository         $statRepository,
                                ValidatorInterface     $validator,
                                LoggerInterface        $messengerLogger)
    {
        $this->apiClient = $apiClient;
        $this->entityManager = $entityManager;
        $this->countryRepository = $countryRepository;
        $this->statRepository = $statRepository;
        $this->validator = $validator;
        $this->logger = $messengerLogger;
    }

    /**
     * @throws Exception
     */
    public function __invoke(UpdateStatsByCountry $message)
    {
        try {
            $response = $this->apiClient->getTotalDayOneByCountrySlug($message->getSlug());
        } catch (ApiException $e) {
            $this->logger->error($e->getMessage(), ['exception' => $e]);
            throw new Exception($e->getMessage());
        }

        foreach ($response as $item) {
            $country = $this->countryRepository->findOneBy(['name' => $item['Country']]);
            $stat = $this->statRepository->findOneBy(['country' => $country,
                'apiTimestamp' => new \DateTimeImmutable($item['Date'], new \DateTimeZone('UTC'))]);
            if ($stat)
                continue;

            $stat = new Stat();
            $stat->setConfirmed($item['Confirmed'])
                ->setDeaths($item['Deaths'])
                ->setRecovered($item['Recovered'])
                ->setApiTimestamp(new \DateTimeImmutable($item['Date'], new \DateTimeZone('UTC')))
                ->setCountry($country);

            $errors = $this->validator->validate($stat);
            if ($errors->count() > 0) {
                $this->logger->error('ошибка валидации', ['item' => $item, 'errors' => (string)$errors]);
                continue;
            }
            $this->entityManager->persist($stat);
        }
        $this->entityManager->flush();
    }
}
