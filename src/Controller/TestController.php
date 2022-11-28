<?php

namespace App\Controller;

use App\Message\UpdateStatsByCountry;
use App\MessageHandler\UpdateStatsByCountryHandler;
use Exception;
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
     * @throws Exception
     */
    #[Route('/test', name: 'app_test')]
    public function index(UpdateStatsByCountryHandler $handler): Response
    {
      call_user_func($handler, new UpdateStatsByCountry('denmark'));
       $fromApi = '2022-11-26T00:00:00Z';
       $fromApiconvert = new \DateTimeImmutable($fromApi, new \DateTimeZone('UTC'));
        $time2db = new \DateTimeImmutable($fromApi, new \DateTimeZone('UTC'));
        //dump(strtotime($fromApi) == $time2db->getTimestamp());
        //dump(strtotime($fromApi),$time2db->getTimestamp());
        dump($fromApiconvert == $time2db);



        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }
}
