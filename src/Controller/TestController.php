<?php

namespace App\Controller;

use App\Message\UpdateCountriesList;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    /**
     */
    #[Route('/test', name: 'app_test')]
    public function index(MessageBusInterface $messageBus): Response
    {
        try {
            $messageBus->dispatch(new UpdateCountriesList());
        } catch (\Exception $e) {
            dump($e);
        }

        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }
}
