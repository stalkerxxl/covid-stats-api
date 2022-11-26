<?php

namespace App\Controller;

use App\Service\ApiClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    #[Route('/test', name: 'app_test')]
    public function index(ApiClient $apiClient): Response
    {
        dump($apiClient->getCountriesList());

        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }
}
