<?php

namespace App\Controller;

use App\Entity\Comentario;
use App\Entity\Usuario;
use App\Entity\Experiencia;
use App\Repository\ComentarioRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;




#[Route('/comentario')]
class ComentarioController extends AbstractController
{
    #[Route('/', name: 'app_comentario_index', methods: ['GET'])]
    public function index(ComentarioRepository $comentarioRepository): JsonResponse
    {
        $comentarios = $comentarioRepository->findAll();

        $comentariosArray = [];
        foreach ($comentarios as $comentario) {
            $fechaFormateada = '';
            if ($comentario->getFecha() !== null) {
                $fechaFormateada = $comentario->getFecha()->format('Y-m-d');

                $comentariosArray[] = [
                    'id' => $comentario->getId(),
                    'texto' => $comentario->getTexto(),
                    'fecha' => $fechaFormateada,
                    'usuario' => [
                        'id' => $comentario->getUsuario()->getId(),
                        'nombre' => $comentario->getUsuario()->getNombre(),
                    ],
                    'experiencia' => [
                        'id' => $comentario->getExperiencia()->getId(),
                        'titulo' => $comentario->getExperiencia()->getTitulo(),
                    ],
                ];
            }
        }
        return new JsonResponse($comentariosArray);
    }
    #[Route('/new', name: 'app_comentario_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);

        $comentario = new Comentario();
        $comentario->setTexto($data['texto']);

        // Establecer la fecha y hora actual
        $fechaActual = new \DateTime();
        $comentario->setFecha($fechaActual);

        $usuario = $entityManager->getRepository(Usuario::class)->find($data['usuario_id']);
        $experiencia = $entityManager->getRepository(Experiencia::class)->find($data['experiencia_id']);

        $comentario->setUsuario($usuario);
        $comentario->setExperiencia($experiencia);

        $entityManager->persist($comentario);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Comentario insertado correctamente'], Response::HTTP_CREATED);
    }




    #[Route('/{id}', name: 'app_comentario_show', methods: ['GET'])]
    public function show(Comentario $comentario): Response
    {
        return $this->render('comentario/show.html.twig', [
            'comentario' => $comentario,
        ]);
    }

    #[Route('/comentario/{id}/edit', name: 'app_comentario_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Comentario $comentario, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);

        $comentario->setTexto($data['texto']);

        $fecha = new \DateTime($data['fecha']);
        $comentario->setFecha($fecha);

        $usuario = $entityManager->getRepository(Usuario::class)->find($data['usuario_id']);
        $experiencia = $entityManager->getRepository(Experiencia::class)->find($data['experiencia_id']);

        $comentario->setUsuario($usuario);
        $comentario->setExperiencia($experiencia);

        $entityManager->flush();

        return new JsonResponse(['message' => 'Comentario modificado correctamente'], Response::HTTP_OK);
    }

    /* #[Route('/borrar', name: 'app_comentario_delete', methods: ['GET', 'POST'])]
    public function delete(Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);
        $usuarioId = $data['id'];

        $entityManager->remove($comentario);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Comentario eliminado correctamente'], Response::HTTP_OK);
    } */
    #[Route('/borrar', name: 'app_comentario_delete', methods: ['POST'])]
    public function delete(Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);
        $comentarioId = $data['id']; // Suponiendo que el ID del comentario está en el campo 'id' del JSON

        // Buscar el comentario por su ID
        $comentarioRepository = $entityManager->getRepository(Comentario::class);
        $comentario = $comentarioRepository->find($comentarioId);

        // Verificar si se encontró el comentario
        if (!$comentario) {
            return new JsonResponse(['error' => 'Comentario no encontrado'], Response::HTTP_NOT_FOUND);
        }

        // Eliminar el comentario
        $entityManager->remove($comentario);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Comentario eliminado correctamente'], Response::HTTP_OK);
    }
}
