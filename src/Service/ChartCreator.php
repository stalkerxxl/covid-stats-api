<?php

namespace App\Service;

use App\Entity\Country;
use App\Entity\Stat;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Symfony\UX\Chartjs\Builder\ChartBuilder;
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
                    'label' => 'Top 10 by New Confirmed today',
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

    public function createTopByNewDeathsChart(ArrayCollection $topByNewDeathsData): Chart
    {
        $countryNames = $topByNewDeathsData->map(function (Country $item) {
            return $item->getName();
        });
        $newDeaths = $topByNewDeathsData->map(function (Country $item) {
            return $item->getNewDeaths();
        });

        $topByNewDeathsChart = $this->chartBuilder->createChart(Chart::TYPE_BAR);
        $topByNewDeathsChart->setData([
            'labels' => $countryNames->toArray(),
            'datasets' => [
                [
                    'label' => 'Top 10 by New Deaths today',
                    'backgroundColor' => 'rgb(255, 99, 132)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => $newDeaths->toArray(),
                ],
            ],
        ]);

        $topByNewDeathsChart->setOptions([
            'indexAxis' => 'y'
        ]);

        return $topByNewDeathsChart;
    }

    public function createSingleCountryStatsChart(Collection $allStats): Chart
    {
        $criteria = Criteria::create()
            ->orderBy(['apiTimestamp' => Criteria::ASC]);

        /** @var Collection $data */
        $data = $allStats->matching($criteria);

        $confirmed = $data->map(function (Stat $item) {
            return $item->getConfirmed();
        });

        $date = $data->map(function (Stat $item) {
            return $item->getApiTimestamp()->format('Y-m');
        });

        $chart = (new ChartBuilder())->createChart(Chart::TYPE_LINE);
        $chart->setData([
            'labels' => $date->toArray(),
            'datasets' => [
                [
                    'label' => 'My First dataset',
                    'backgroundColor' => 'rgb(255, 99, 132)',
                    'data' => $confirmed->toArray(),
                ]
            ],
        ]);

        $chart->setOptions([
            'scales' => [
                'y' => [
                    'suggestedMin' => 0,
                ],
            ],
        ]);

        return $chart;
    }
}