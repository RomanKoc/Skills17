<?php

namespace App\Controller;

use App\Entity\Comunidad;
use App\Form\ComunidadType;
use App\Repository\ComunidadRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/comunidad')]
class ComunidadController extends AbstractController
{
    #[Route('/', name: 'app_comunidad_index', methods: ['GET'])]
    public function index(ComunidadRepository $comunidadRepository): Response
    {
        return $this->render('comunidad/index.html.twig', [
            'comunidads' => $comunidadRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_comunidad_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $comunidad = new Comunidad();
        $form = $this->createForm(ComunidadType::class, $comunidad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($comunidad);
            $entityManager->flush();

            return $this->redirectToRoute('app_comunidad_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('comunidad/new.html.twig', [
            'comunidad' => $comunidad,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_comunidad_show', methods: ['GET'])]
    public function show(Comunidad $comunidad): Response
    {
        return $this->render('comunidad/show.html.twig', [
            'comunidad' => $comunidad,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_comunidad_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Comunidad $comunidad, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ComunidadType::class, $comunidad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_comunidad_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('comunidad/edit.html.twig', [
            'comunidad' => $comunidad,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_comunidad_delete', methods: ['POST'])]
    public function delete(Request $request, Comunidad $comunidad, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$comunidad->getId(), $request->request->get('_token'))) {
            $entityManager->remove($comunidad);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_comunidad_index', [], Response::HTTP_SEE_OTHER);
    }
}
