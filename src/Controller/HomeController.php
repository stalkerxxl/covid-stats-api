<?php

namespace App\Controller;

use App\Entity\Stat;
use App\Repository\StatRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ChartBuilderInterface $chartBuilder, StatRepository $statRepository): Response
    {

        $stats = $statRepository->findBy([], ['newConfirmed' => 'DESC'], 10, 0);

        $countryNames = array_map(function (Stat $item) {
            return $item->getCountry()->getName();
        }, $stats);

        $newRecorded = array_map(function (Stat $item) {
            return $item->getConfirmed();
        }, $stats);


        $chart = $chartBuilder->createChart(Chart::TYPE_BAR);
        $chart->setData([
            'labels' => $countryNames,
            'datasets' => [
                [
                    'label' => 'My First dataset',
                    'backgroundColor' => 'rgb(255, 99, 132)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => $newRecorded,
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
