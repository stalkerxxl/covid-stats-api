<?php

namespace App\EventSubscriber;

use App\Entity\Country;
use App\Event\CountriesUpdatedEvent;
use App\Message\UpdateStatsByCountry;
use App\Repository\CountryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Messenger\MessageBusInterface;

class ApiEventSubscriber implements EventSubscriberInterface
{
    private LoggerInterface $logger;
    private EntityManagerInterface $entityManager;
    private MessageBusInterface $messageBus;

    public function __construct(LoggerInterface $eventLogger, EntityManagerInterface $entityManager, MessageBusInterface $messageBus)
    {
        $this->logger = $eventLogger;
        $this->entityManager = $entityManager;
        $this->messageBus = $messageBus;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            CountriesUpdatedEvent::class => 'onCountriesUpdated',
        ];
    }

    public function onCountriesUpdated(CountriesUpdatedEvent $event)
    {
        $countries = $this->entityManager->getRepository(Country::class)->findAll();
        $slugList = array_map(function (Country $country) {
            return $this->messageBus->dispatch(new UpdateStatsByCountry($country->getSlug()));
        }, $countries);
        //FIXME писать в лог
    }
}
