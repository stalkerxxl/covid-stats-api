<?php

namespace App\Controller;

use App\Message\UpdateCountries;
use App\Message\UpdateStatsByCountry;
use App\MessageHandler\UpdateCountriesHandler;
use App\MessageHandler\UpdateStatsByCountryHandler;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    /**
     * @throws Exception
     */
    #[Route('/test', name: 'app_test')]
    public function index(UpdateCountriesHandler $handler): Response
    {
      //call_user_func($handler, new UpdateStatsByCountry('denmark'));
      call_user_func($handler, new UpdateCountries());
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
