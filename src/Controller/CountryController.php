<?php

namespace App\Controller;

use App\Entity\Country;
use App\Form\CountryType;
use App\Repository\CountryRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/country')]
class CountryController extends AbstractController
{
    #[Route('/', name: 'country_index', methods: ['GET'])]
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

    #[Route('/new', name: 'country_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CountryRepository $countryRepository): Response
    {
        $country = new Country();
        $form = $this->createForm(CountryType::class, $country);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $countryRepository->save($country, true);

            return $this->redirectToRoute('country_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('country/new.html.twig', [
            'country' => $country,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'country_show', methods: ['GET'])]
    public function show(Country $country): Response
    {
        return $this->render('country/show.html.twig', [
            'country' => $country,
        ]);
    }

    #[Route('/{id}/edit', name: 'country_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Country $country, CountryRepository $countryRepository): Response
    {
        $form = $this->createForm(CountryType::class, $country);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $countryRepository->save($country, true);

            return $this->redirectToRoute('country_index', [], Response::HTTP_SEE_OTHER);
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

        return $this->redirectToRoute('country_index', [], Response::HTTP_SEE_OTHER);
    }
}
