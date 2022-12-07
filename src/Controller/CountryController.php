<?php

namespace App\Controller;

use App\Entity\Country;
use App\Entity\Stat;
use App\Form\CountryType;
use App\Repository\CountryRepository;
use App\Repository\StatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Doctrine\Collections\CollectionAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilder;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

#[Route('/country')]
class CountryController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'country.index', methods: ['GET'])]
    public function index(Request $request, CountryRepository $countryRepository): Response
    {
        $search = $request->query->get('search');
        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 10);
        $continent = $request->query->get('continent');
        $sortBy = $request->query->get('sortBy');
        $direction = $request->query->get('direction');

        $continentList = $countryRepository->getAllContinentList();
        $pager = $countryRepository->findAllWithSearchPager($search, $page, $limit, $continent, $sortBy, $direction);

        return $this->render('country/index.html.twig', [
            'pager' => $pager,
            'continentList' => $continentList
        ]);
    }

    #[Route('/{slug}', name: 'country.show', methods: ['GET'])]
    public function show(Country $country, Request $request): Response
    {
        $allStats = $country->getStats();

        $page = $request->query->getInt('page', 1);
        $sortBy = $request->query->get('sortBy');
        $direction = $request->query->get('direction');

        if ($sortBy && $direction) {
            $criteria = Criteria::create()
                ->orderBy([$sortBy => $direction]);
            $allStats = $allStats->matching($criteria);
        }
        $pager = Pagerfanta::createForCurrentPageWithMaxPerPage(new CollectionAdapter($allStats), $page, 10);


        $chart = $this->createChat($country);

        return $this->render('country/show.html.twig', [
            'country' => $country,
            'chart' => $chart,
            'pager' => $pager
        ]);
    }

    public function createChat(Country $country): Chart
    {
        $repo = $this->entityManager->getRepository(Stat::class);
        $data = $repo->findBy(['country' => $country], ['apiTimestamp' => 'ASC']);

        $confirmed = array_map(function (Stat $item) {
            return $item->getConfirmed();
        }, $data);
        $date = array_map(function (Stat $item) {
            return $item->getApiTimestamp()->format('Y-m');
        }, $data);

        $chart = (new ChartBuilder())->createChart(Chart::TYPE_LINE);
        $chart->setData([
            'labels' => $date,
            'datasets' => [
                [
                    'label' => 'My First dataset',
                    'backgroundColor' => 'rgb(255, 99, 132)',
                    'data' => $confirmed,
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
        dump($chart);
        return $chart;
    }


    #[Route('/{id}', name: 'country_delete', methods: ['POST'])]
    public function delete(Request $request, Country $country, CountryRepository $countryRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $country->getId(), $request->request->get('_token'))) {
            $countryRepository->remove($country, true);
        }

        return $this->redirectToRoute('country.index', [], Response::HTTP_SEE_OTHER);
    }
}
