<?php

namespace App\Controller;

use App\Message\UpdateCountriesList;
use App\MessageHandler\UpdateCountriesListHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\BusNameStamp;
use Symfony\Component\Messenger\Stamp\ConsumedByWorkerStamp;
use Symfony\Component\Messenger\Stamp\DelayStamp;
use Symfony\Component\Messenger\Stamp\RouterContextStamp;
use Symfony\Component\Messenger\Stamp\SentStamp;
use Symfony\Component\Messenger\Transport\Sender\SendersLocator;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    /**
     */
    #[Route('/test', name: 'app_test')]
    public function index( UpdateCountriesListHandler $handler): Response
    {
        call_user_func($handler, new UpdateCountriesList());


        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }
}
