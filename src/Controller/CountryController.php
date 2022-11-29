<?php

namespace App\Controller;

use App\Entity\Country;
use App\Form\CountryType;
use App\Repository\CountryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
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
    #[Route('/', name: 'country.index', methods: ['GET'])]
    public function index(Request $request, CountryRepository $countryRepository): Response
    {
        $query = $countryRepository->findAll();
        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 10);
        $pager = (new Pagerfanta(new ArrayAdapter($query)));
        $pager->setCurrentPage($page)->setMaxPerPage($limit);

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

        $chart = $this->createChat();

        return $this->render('country/show.html.twig', [
            'country' => $country,
            'chart' => $chart,
            //'allStats' => $allStats,
            'paginator' => $paginator
        ]);
    }

    public function createChat(): Chart
    {
        $chart = (new ChartBuilder())->createChart(Chart::TYPE_LINE);
        $chart->setData([
            'datasets' => [
                [
                    'data' => [['x' => 5, 'y' => 2]]
                ]
            ],
        ]);

        $chart->setOptions([
            'scales' => [
                'y' => [
                    'suggestedMin' => 0,
                    'suggestedMax' => 100,
                ],
            ],
        ]);

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
