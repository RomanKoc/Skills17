<?php

namespace App\Controller;

use App\Entity\Imagen;
use App\Form\ImagenType;
use App\Repository\ImagenRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Experiencia;

#[Route('/imagen')]
class ImagenController extends AbstractController
{
    /* #[Route('/', name: 'app_imagen_index', methods: ['GET'])]
    public function index(ImagenRepository $imagenRepository): Response
    {
        return $this->render('imagen/index.html.twig', [
            'imagens' => $imagenRepository->findAll(),
        ]);
    } */
    #[Route('/', name: 'app_imagen_index', methods: ['GET'])]
    public function index(ImagenRepository $imagenRepository): JsonResponse
    {
        // Obtener todas las imágenes desde el repositorio
        $imagenes = $imagenRepository->findAll();

        $imagenesArray = [];
        foreach ($imagenes as $imagen) {
            $experiencia = $imagen->getExperiencia();
            $experienciaId = $experiencia ? $experiencia->getId() : null;

            $imagenesArray[] = [
                'id' => $imagen->getId(),
                'nombre' => base64_encode(stream_get_contents($imagen->getNombre())), // Convertir el BLOB a base64
                'experiencia_id' => $experienciaId,
            ];
        }

        return new JsonResponse($imagenesArray);
    }

    /* #[Route('/new', name: 'app_imagen_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $imagen = new Imagen();
        $form = $this->createForm(ImagenType::class, $imagen);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($imagen);
            $entityManager->flush();

            return $this->redirectToRoute('app_imagen_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('imagen/new.html.twig', [
            'imagen' => $imagen,
            'form' => $form,
        ]);
    } */
    #[Route('/new', name: 'app_imagen_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['imagen']) || empty($data['imagen'])) {
            return new JsonResponse(['error' => 'No se proporcionó la imagen'], Response::HTTP_BAD_REQUEST);
        }else{
            return new JsonResponse(['PRUEBA' => 'Se proporcionó la imagen'], Response::HTTP_BAD_REQUEST);
        }

        $imagen = new Imagen();
        $imagenBinaria = $data['imagen'];

        if ($imagenBinaria === false) {
            return new JsonResponse(['error' => 'No se pudo decodificar la imagen'], Response::HTTP_BAD_REQUEST);
        }else{
            return new JsonResponse(['PRUEBA' => 'Se proporcionó la imagen'], Response::HTTP_BAD_REQUEST);
        }
        /* $experiencia = $entityManager->find(Experiencia::class, $data['experienciaId']);
        if (!$experiencia) {
            return new JsonResponse(['error' => 'No se pudo encontrar la experiencia'], Response::HTTP_NOT_FOUND);
        } */

        $imagen->setNombre($imagenBinaria);
        try {
            $entityManager->persist($imagen);
            $entityManager->flush();

            return new JsonResponse(['message' => 'Imagen insertada correctamente'], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Error al insertar la imagen: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /* #[Route('/new', name: 'app_imagen_new', methods: ['POST'])]
    public function nuevo(Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['imagen']) || empty($data['imagen'])) {
            return new JsonResponse(['error' => 'No se proporcionó la imagen'], Response::HTTP_BAD_REQUEST);
        }

        $imagen = new Imagen();
        $imagenBinaria = $data['imagen'];

        echo "imagenBinaria: $imagenBinaria\n";

        if ($imagenBinaria === false) {
            return new JsonResponse(['error' => 'No se pudo decodificar la imagen'], Response::HTTP_BAD_REQUEST);
        }
        $experiencia = $entityManager->find(Experiencia::class, $data['experienciaId']);
        if (!$experiencia) {
            return new JsonResponse(['error' => 'No se pudo encontrar la experiencia'], Response::HTTP_NOT_FOUND);
        }

        $imagen->setNombre($imagenBinaria);
        $imagen->setExperiencia($experiencia);

        // Persistir la imagen en la base de datos
        try {
            $entityManager->persist($imagen);
            $entityManager->flush();

            return new JsonResponse(['message' => 'Imagen insertada correctamente'], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Error al insertar la imagen: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    } */

    #[Route('/{id}', name: 'app_imagen_show', methods: ['GET'])]
    public function show(Imagen $imagen): Response
    {
        return $this->render('imagen/show.html.twig', [
            'imagen' => $imagen,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_imagen_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Imagen $imagen, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ImagenType::class, $imagen);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_imagen_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('imagen/edit.html.twig', [
            'imagen' => $imagen,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_imagen_delete', methods: ['POST'])]
    public function delete(Request $request, Imagen $imagen, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $imagen->getId(), $request->request->get('_token'))) {
            $entityManager->remove($imagen);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_imagen_index', [], Response::HTTP_SEE_OTHER);
    }
}
