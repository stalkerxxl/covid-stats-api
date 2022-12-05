<?php

namespace App\Service;

use App\Entity\Country;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class ChartCreator
{
    private ChartBuilderInterface $chartBuilder;

    public function __construct(ChartBuilderInterface $chartBuilder)
    {
        $this->chartBuilder = $chartBuilder;
    }

    public function createTopByNewConfirmedChart(ArrayCollection $topByNewConfirmedData): Chart
    {
        $countryNames = $topByNewConfirmedData->map(function (Country $item) {
            return $item->getName();
        });
        $newConfirmed = $topByNewConfirmedData->map(function (Country $item) {
            return $item->getNewConfirmed();
        });

        $topByNewConfirmedChart = $this->chartBuilder->createChart(Chart::TYPE_BAR);
        $topByNewConfirmedChart->setData([
            'labels' => $countryNames->toArray(),
            'datasets' => [
                [
                    'label' => 'Top 10 by New Confirmed',
                    'backgroundColor' => 'rgb(255, 99, 132)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => $newConfirmed->toArray(),
                ],
            ],
        ]);

        $topByNewConfirmedChart->setOptions([
            'indexAxis' => 'y'
        ]);

        return $topByNewConfirmedChart;
    }
}