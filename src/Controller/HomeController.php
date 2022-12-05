<?php

namespace App\Controller;

use App\Entity\Country;
use App\Entity\Stat;
use App\Repository\CountryRepository;
use App\Repository\StatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ChartBuilderInterface $chartBuilder, CountryRepository $countryRepository): Response
    {
        $allCountries = $countryRepository->findAll();

        $topByNewConfirmedCriteria = Criteria::create()
            ->orderBy(['newConfirmed' => Criteria::DESC])
            ->setMaxResults(10);

        $topByNewConfirmed = (new ArrayCollection($allCountries))
            ->matching($topByNewConfirmedCriteria)->toArray();


        $countryNames = array_map(function (Country $item) {
            return $item->getName();
        }, $topByNewConfirmed);

        $newConfirmed = array_map(function (Country $item) {
            return $item->getNewConfirmed();
            //return $item->getConfirmedOnPopulation();
        }, $topByNewConfirmed);


        $chart = $chartBuilder->createChart(Chart::TYPE_BAR);
        $chart->setData([
            'labels' => $countryNames,
            'datasets' => [
                [
                    'label' => 'Top 10 by New Confirmed',
                    'backgroundColor' => 'rgb(255, 99, 132)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => $newConfirmed,
                ],
            ],
        ]);

        $chart->setOptions([
            'indexAxis' => 'y'
        ]);

        return $this->render('home/index.html.twig', [
            'chart' => $chart,
        ]);
    }

}
