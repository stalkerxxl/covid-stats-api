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
    private CountryRepository $countryRepository;
    private ChartCreator $chartCreator;

    public function __construct(CountryRepository $countryRepository, ChartCreator $chartCreator)
    {
        $this->countryRepository = $countryRepository;
        $this->chartCreator = $chartCreator;
    }

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig');
    }

    #[Route('/top-by-new-confirmed', name: 'home_top_by_new_confirmed')]
    public function topByNewConfirmed(): Response
    {
        $data = $this->countryRepository
            ->matching(CountryRepository::newConfirmedCriteria(10))
            ->getValues();

        $topByNewConfirmedChart = $this->chartCreator
            ->createTopByNewConfirmedChart((new ArrayCollection($data)));

        return $this->render('home/top-by-new-confirmed.html.twig', [
            'topByNewConfirmedChart' => $topByNewConfirmedChart,
        ]);
    }

    #[Route('/top-by-new-deaths', name: 'home_top_by_new_deaths')]
    public function topByNewDeaths(): Response
    {
        $data = $this->countryRepository
            ->matching(CountryRepository::newDeathsCriteria(10))
            ->getValues();

        $topByNewDeathsChart = $this->chartCreator
            ->createTopByNewDeathsChart((new ArrayCollection($data)));

        return $this->render('home/top-by-new-deaths.html.twig', [
            'topByNewDeathsChart' => $topByNewDeathsChart,
        ]);
    }
}
