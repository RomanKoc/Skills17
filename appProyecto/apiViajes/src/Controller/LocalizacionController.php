<?php

namespace App\Controller;

use App\Entity\Localizacion;
use App\Form\LocalizacionType;
use App\Repository\LocalizacionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;



#[Route('/localizacion')]
class LocalizacionController extends AbstractController
{
    /* #[Route('/', name: 'app_localizacion_index', methods: ['GET'])]
    public function index(LocalizacionRepository $localizacionRepository): Response
    {
        return $this->render('localizacion/index.html.twig', [
            'localizacions' => $localizacionRepository->findAll(),
        ]);
    } */

    #[Route('/', name: 'app_localizacion_index', methods: ['GET'])]
    public function index(LocalizacionRepository $localizacionRepository): JsonResponse
    {
        $localizaciones = $localizacionRepository->findAll();

        $localizacionesArray = [];
        foreach ($localizaciones as $localizacion) {
            $localizacionesArray[] = [
                'id' => $localizacion->getId(),
                'codigo' => $localizacion->getCodigo(),
                'nombre' => $localizacion->getNombre(),
                'provincia' => [
                    'id' => $localizacion->getProvincia()->getId(),
                    'nombre' => $localizacion->getProvincia()->getNombre(),
                    'comunidad' => [
                        'id' => $localizacion->getProvincia()->getComunidad()->getId(),
                        'nombre' => $localizacion->getProvincia()->getComunidad()->getNombre()
                    ]
                ],
            ];
        }
        return new JsonResponse($localizacionesArray);
    }

    #[Route('/new', name: 'app_localizacion_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $localizacion = new Localizacion();
        $form = $this->createForm(LocalizacionType::class, $localizacion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($localizacion);
            $entityManager->flush();

            return $this->redirectToRoute('app_localizacion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('localizacion/new.html.twig', [
            'localizacion' => $localizacion,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_localizacion_show', methods: ['GET'])]
    public function show(Localizacion $localizacion): Response
    {
        return $this->render('localizacion/show.html.twig', [
            'localizacion' => $localizacion,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_localizacion_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Localizacion $localizacion, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LocalizacionType::class, $localizacion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_localizacion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('localizacion/edit.html.twig', [
            'localizacion' => $localizacion,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_localizacion_delete', methods: ['POST'])]
    public function delete(Request $request, Localizacion $localizacion, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $localizacion->getId(), $request->request->get('_token'))) {
            $entityManager->remove($localizacion);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_localizacion_index', [], Response::HTTP_SEE_OTHER);
    }
}
