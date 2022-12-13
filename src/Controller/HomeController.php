<?php

namespace App\Controller;

use App\Repository\CountryRepository;
use App\Repository\StatRepository;
use App\Service\ChartCreator;
use Doctrine\Common\Collections\ArrayCollection;
use Psr\Cache\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

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
    public function index(StatRepository $statRepository): Response
    {
        //TODO чарт по миру (посчитать из БД)
        $allStats = $statRepository->getSumDataGroupByMonth();
        dump($allStats);
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

    /**
     * @throws InvalidArgumentException
     */
    #[Route('/last-updated-time.html.twig', name: 'home_last_updated_time')]
    public function lastUpdatedTime(CacheInterface $cache): Response
    {

        $time = $cache->get('last_updated_time', function (ItemInterface $item) {
            $item->expiresAfter(60 * 60 * 24);
            return new \DateTimeImmutable('-1 hour');
        });

        return $this->render('home/last-updated-time.html.twig', [
            'time' => $time
        ]);
    }
}
