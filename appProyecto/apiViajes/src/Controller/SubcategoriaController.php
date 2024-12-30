<?php

namespace App\Controller;

use App\Entity\Subcategoria;
use App\Form\SubcategoriaType;
use App\Repository\SubcategoriaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\CategoriaRepository;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/subcategoria')]
class SubcategoriaController extends AbstractController
{
    #[Route('/', name: 'app_categorias_index', methods: ['GET'])]
    public function index(CategoriaRepository $categoriaRepository, SubcategoriaRepository $subcategoriaRepository): JsonResponse
    {
        $categorias = $categoriaRepository->findAll();
        $categoriasArray = [];

        foreach ($categorias as $categoria) {
            $subcategoriasArray = [];
            foreach ($categoria->getSubcategorias() as $subcategoria) {
                $subcategoriasArray[] = [
                    'id' => $subcategoria->getId(),
                    'nombre' => $subcategoria->getNombre(),
                ];
            }

            $categoriasArray[] = [
                'id' => $categoria->getId(),
                'nombre' => $categoria->getNombre(),
                'subcategorias' => $subcategoriasArray,
            ];
        }

        return new JsonResponse($categoriasArray);
    }

    #[Route('/new', name: 'app_subcategoria_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $subcategorium = new Subcategoria();
        $form = $this->createForm(SubcategoriaType::class, $subcategorium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($subcategorium);
            $entityManager->flush();

            return $this->redirectToRoute('app_subcategoria_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('subcategoria/new.html.twig', [
            'subcategorium' => $subcategorium,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_subcategoria_show', methods: ['GET'])]
    public function show(Subcategoria $subcategorium): Response
    {
        return $this->render('subcategoria/show.html.twig', [
            'subcategorium' => $subcategorium,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_subcategoria_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Subcategoria $subcategorium, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SubcategoriaType::class, $subcategorium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_subcategoria_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('subcategoria/edit.html.twig', [
            'subcategorium' => $subcategorium,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_subcategoria_delete', methods: ['POST'])]
    public function delete(Request $request, Subcategoria $subcategorium, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $subcategorium->getId(), $request->request->get('_token'))) {
            $entityManager->remove($subcategorium);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_subcategoria_index', [], Response::HTTP_SEE_OTHER);
    }
}
