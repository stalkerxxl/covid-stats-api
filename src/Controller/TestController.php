<?php

namespace App\Controller;

use App\DataFixtures\CountriesData;
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
      //call_user_func($handler, new UpdateCountries(CountriesData::getJsonData()));

        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }
}
