<?php

namespace App\Controller;

use App\Entity\Country;
use App\Repository\CountryRepository;
use App\Service\ChartCreator;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Doctrine\Collections\CollectionAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/country')]
class CountryController extends AbstractController
{
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
        $sortBy = $request->query->get('sortBy', 'apiTimestamp');
        $direction = $request->query->get('direction');

        if ($sortBy && $direction) {
            $criteria = Criteria::create()
                ->orderBy([$sortBy => $direction]);
            $allStats = $allStats->matching($criteria);
        }
        $pager = Pagerfanta::createForCurrentPageWithMaxPerPage(new CollectionAdapter($allStats), $page, 10);

        if ($request->headers->get('Turbo-Frame'))
            return $this->render('country/single_country_table.html.twig', [
                'country' => $country,
                'pager' => $pager
            ]);

        return $this->render('country/show.html.twig', [
            'country' => $country,
            'pager' => $pager
        ]);
    }

    #[Route('/chart/{slug}', name: 'country.chart', methods: ['GET'])]
    public function chart(Country $country, ChartCreator $chartCreator): Response
    {
        $allStats = $country->getStats();
        $countryChart = $chartCreator->createSingleCountryStatsChart($allStats);

        return $this->render('country/chart.html.twig', [
            'countryChart' => $countryChart
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
