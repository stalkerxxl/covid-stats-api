<?php

namespace App\Controller;

use App\Entity\Country;
use App\Entity\Stat;
use App\Repository\CountryRepository;
use App\Repository\StatRepository;
use App\Service\ChartCreator;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(CountryRepository $countryRepository, ChartCreator $chartCreator): Response
    {
        $allCountries = new ArrayCollection($countryRepository->findAll());
        $topByNewConfirmedData = $this->topByNewConfirmedCriteria($allCountries);
        $topByNewConfirmedChart = $chartCreator->createTopByNewConfirmedChart($topByNewConfirmedData);

        return $this->render('home/index.html.twig', [
            'topByNewConfirmedChart' => $topByNewConfirmedChart,
        ]);
    }

    private function topByNewConfirmedCriteria(ArrayCollection $data): ArrayCollection
    {
        return $data->matching(CountryRepository::newConfirmedCriteria(10));
    }

}
