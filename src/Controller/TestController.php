<?php

namespace App\Controller;

use App\Exception\ApiException;
use App\Service\ApiClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TestController extends AbstractController
{
    /**
     */
    #[Route('/test', name: 'app_test')]
    public function index(ApiClient $apiClient, ValidatorInterface $validator): Response
    {
        try {
            $a = $apiClient->getCountriesList();
        } catch (ApiException $e) {
            dump($e);
        }

        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }
}
