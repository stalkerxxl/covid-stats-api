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
        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 10);
        $search = $request->query->get('search');
        $sortBy = $request->query->get('sortBy');
        $direction = $request->query->get('direction');
        $pager = $countryRepository->findAllWithSearchPager($search, $page, $limit, $sortBy, $direction);

        return $this->render('country/index.html.twig', [
            'pager' => $pager,
        ]);
    }

    #[Route('/{slug}', name: 'country.show', methods: ['GET'])]
    public function show(Country $country, Request $request, PaginatorInterface $paginator): Response
    {
        $allStats = $country->getStats()->toArray();

        $page = $request->query->getInt('page', 1);
        $sort = $request->query->get('sort');
        $direction = $request->query->get('direction');

        if ($sort && $direction) {
            $criteria = Criteria::create()
                ->orderBy([$sort => $direction]);
            $allStats = (new ArrayCollection($allStats))->matching($criteria);
        }
        $paginator = $paginator->paginate($allStats, $page);

        $chart = $this->createChat($country);

        return $this->render('country/show.html.twig', [
            'country' => $country,
            'chart' => $chart,
            //'allStats' => $allStats,
            'paginator' => $paginator
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
            return $item->getApiTimestamp()->format('Y');
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

    #[Route('/{id}/edit', name: 'country_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Country $country, CountryRepository $countryRepository): Response
    {
        $form = $this->createForm(CountryType::class, $country);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $countryRepository->save($country, true);

            return $this->redirectToRoute('country.index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('country/edit.html.twig', [
            'country' => $country,
            'form' => $form->createView(),
        ]);
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
