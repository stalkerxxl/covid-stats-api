<?php

namespace App\Controller;

use App\Entity\Country;
use App\Form\CountryType;
use App\Repository\CountryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/country')]
class CountryController extends AbstractController
{
    #[Route('/', name: 'country.index', methods: ['GET'])]
    public function index(Request $request, CountryRepository $countryRepository, PaginatorInterface $paginator): Response
    {
        $query = $countryRepository->findAllQueryBuilder();
        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 10);
        $paginator = $paginator->paginate($query, $page, $limit);

        return $this->render('country/index.html.twig', [
            'countries' => $paginator,
        ]);
    }


    #[Route('/{slug}', name: 'country.show', methods: ['GET'])]
    public function show(Country $country, Request $request, PaginatorInterface $paginator): Response
    {
        $allStats = $country->getStats()->toArray();

        $sort = $request->query->get('sort');
        $direction = $request->query->get('direction');

        if ($sort && $direction) {
            $criteria = Criteria::create()
                ->orderBy([$sort => $direction]);
            $allStats = (new ArrayCollection($allStats))->matching($criteria);
        }
        $page = $request->query->getInt('page', 1);
        $paginator = $paginator->paginate($allStats, $page);

        return $this->render('country/show.html.twig', [
            'country' => $country,
            //'allStats' => $allStats,
            'paginator' => $paginator
        ]);
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
            'form' => $form,
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
