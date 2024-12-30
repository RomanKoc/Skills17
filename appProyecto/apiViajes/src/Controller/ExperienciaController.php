<?php

namespace App\Controller;

use App\Entity\Experiencia;
use App\Form\ExperienciaType;
use App\Repository\ExperienciaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

use App\Entity\Usuario;
use App\Entity\Localizacion;
use App\Entity\Comentario;
use App\Entity\Subcategoria;
use App\Entity\Imagen;

#[Route('/experiencia')]
class ExperienciaController extends AbstractController
{
    #[Route('/', name: 'app_experiencia_index', methods: ['GET'])]
    public function index(ExperienciaRepository $experienciaRepository): JsonResponse
    {
        $experiencias = $experienciaRepository->findAll();

        $experienciasArray = [];
        foreach ($experiencias as $experiencia) {
            $experienciaArray = [
                'id' => $experiencia->getId(),
                'titulo' => $experiencia->getTitulo(),
                'texto' => $experiencia->getTexto(),
                'puntuacion' => $experiencia->getPuntuacion(),
                'fecha' => $experiencia->getFecha() ? $experiencia->getFecha()->format('Y-m-d') : null,
                'usuario' => [
                    'id' => $experiencia->getUsuario() ? $experiencia->getUsuario()->getId() : null,
                    'nombre' => $experiencia->getUsuario() ? $experiencia->getUsuario()->getNombre() : null,
                    'mail' => $experiencia->getUsuario() ? $experiencia->getUsuario()->getMail() : null,
                    'ciudad' => $experiencia->getUsuario() ? $experiencia->getUsuario()->getCiudad() : null,
                ],
                'localizacion' => [
                    'id' => $experiencia->getLocalizacion() ? $experiencia->getLocalizacion()->getId() : null,
                    'nombre' => $experiencia->getLocalizacion() ? $experiencia->getLocalizacion()->getNombre() : null,
                    'provincia' => [
                        'id' => $experiencia->getLocalizacion() ? $experiencia->getLocalizacion()->getProvincia()->getId() : null,
                        'nombre' => $experiencia->getLocalizacion() ? $experiencia->getLocalizacion()->getProvincia()->getNombre() : null,
                        'comunidad' => [
                            'id' => $experiencia->getLocalizacion() ? $experiencia->getLocalizacion()->getProvincia()->getComunidad()->getId() : null,
                            'nombre' => $experiencia->getLocalizacion() ? $experiencia->getLocalizacion()->getProvincia()->getComunidad()->getNombre() : null,
                        ],
                    ],
                ],
                'categoria' => [
                    'id' => $experiencia->getSubcategoria() ? $experiencia->getSubcategoria()->getCategoria()->getId() : null,
                    'nombre' => $experiencia->getSubcategoria() ? $experiencia->getSubcategoria()->getCategoria()->getNombre() : null,
                    'subcategoria' => [
                        'id' => $experiencia->getSubcategoria() ? $experiencia->getSubcategoria()->getId() : null,
                        'nombre' => $experiencia->getSubcategoria() ? $experiencia->getSubcategoria()->getNombre() : null,
                    ],
                ],
                'comentarios' => [],
            ];

            foreach ($experiencia->getComentarios() as $comentario) {
                $experienciaArray['comentarios'][] = [
                    'id' => $comentario->getId(),
                    'texto' => $comentario->getTexto(),
                    'fecha' => $comentario->getFecha() ? $comentario->getFecha()->format('Y-m-d') : null,
                    'usuario_id' =>  $comentario->getUsuario() ? $comentario->getUsuario()->getId() : null,
                ];
            }

            $experienciasArray[] = $experienciaArray;
        }
        return new JsonResponse($experienciasArray);
    }
    #[Route('/new', name: 'app_experiencia_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        // Obtener los datos de la solicitud JSON
        $data = json_decode($request->getContent(), true);

        $usuarioRepository = $entityManager->getRepository(Usuario::class);
        $usuario = $usuarioRepository->find($data['usuarioId']);

        // Verificar si se encontró el usuario
        if (!$usuario) {
            return new JsonResponse(['error' => 'Usuario no encontrado'], Response::HTTP_NOT_FOUND);
        }

        // Buscar la localización por ID
        $localizacionRepository = $entityManager->getRepository(Localizacion::class);
        $localizacion = $localizacionRepository->find($data['localizacionId']);

        // Verificar si se encontró la localización
        if (!$localizacion) {
            return new JsonResponse(['error' => 'Localización no encontrada'], Response::HTTP_NOT_FOUND);
        }
        if (empty($localizacion)) {
            $localizacion = 1;
        }

        // Buscar la subcategoría por ID
        $subcategoriaRepository = $entityManager->getRepository(Subcategoria::class);
        $subcategoria = $subcategoriaRepository->find($data['subcategoriaId']);

        if (empty($subcategoria)) {
            $subcategoria = 1;
        }
        // Verificar si se encontró la subcategoría
        if (!$subcategoria) {
            return new JsonResponse(['error' => 'Subcategoría no encontrada'], Response::HTTP_NOT_FOUND);
        }

        $fecha = \DateTime::createFromFormat('Y-m-d', $data['fecha']);

        $experiencia = new Experiencia();
        $experiencia->setTitulo($data['titulo']);
        $experiencia->setTexto($data['texto']);
        $experiencia->setPuntuacion($data['puntuacion']);
        $experiencia->setFecha($fecha);
        $experiencia->setUsuario($usuario);
        $experiencia->setLocalizacion($localizacion);
        $experiencia->setSubcategoria($subcategoria);

        $entityManager->persist($experiencia);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Experiencia insertada correctamente'], Response::HTTP_CREATED);
    }

    /* PRUEBA PARA INSERTAR UNA IMG FIJA */
    #[Route('/newFija', name: 'app_experiencia_newFija', methods: ['GET', 'POST'])]
    public function imgFija(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        // Obtener los datos de la solicitud JSON
        $data = json_decode($request->getContent(), true);

        $usuarioRepository = $entityManager->getRepository(Usuario::class);
        $usuario = $usuarioRepository->find($data['usuarioId']);

        // Verificar si se encontró el usuario
        if (!$usuario) {
            return new JsonResponse(['error' => 'Usuario no encontrado'], Response::HTTP_NOT_FOUND);
        }

        // Buscar la localización por ID
        $localizacionRepository = $entityManager->getRepository(Localizacion::class);
        $localizacion = $localizacionRepository->find($data['localizacionId']);

        // Verificar si se encontró la localización
        if (!$localizacion) {
            return new JsonResponse(['error' => 'Localización no encontrada'], Response::HTTP_NOT_FOUND);
        }

        // Buscar la subcategoría por ID
        $subcategoriaRepository = $entityManager->getRepository(Subcategoria::class);
        $subcategoria = $subcategoriaRepository->find($data['subcategoriaId']);

        // Verificar si se encontró la subcategoría
        if (!$subcategoria) {
            return new JsonResponse(['error' => 'Subcategoría no encontrada'], Response::HTTP_NOT_FOUND);
        }

        $fecha = \DateTime::createFromFormat('Y-m-d', $data['fecha']);

        $experiencia = new Experiencia();
        $experiencia->setTitulo($data['titulo']);
        $experiencia->setTexto($data['texto']);
        $experiencia->setPuntuacion($data['puntuacion']);
        $experiencia->setFecha($fecha);
        $experiencia->setUsuario($usuario);
        $experiencia->setLocalizacion($localizacion);
        $experiencia->setSubcategoria($subcategoria);

        $entityManager->persist($experiencia);
        $entityManager->flush();


        // Crear y asociar la imagen a la experiencia
        //$imagen = new Imagen();
        //$imagenBlob = file_get_contents('../public/img/default.jpg');
        //$imagenBlob = base64_encode($imagenBlob);
        //$imagen->setNombre($imagenBlob); // Reemplaza 'nombre_de_la_imagen.jpg' por el nombre de la imagen que deseas asociar
        //$imagen->setExperiencia($experiencia); /* dudo si esto funcionara */

        // Guardar la imagen en la base de datos
        //$entityManager->persist($imagen);
        //$entityManager->flush();

        return new JsonResponse(['message' => 'Experiencia y imagen insertadas correctamente'], Response::HTTP_CREATED);
    }

    /* #[Route('/{id}', name: 'app_experiencia_show', methods: ['GET'])]
    public function show(Experiencia $experiencium): Response
    {
        return $this->render('experiencia/show.html.twig', [
            'experiencium' => $experiencium,
        ]);
    } */

    #[Route('/borrar', name: 'app_experiencia_delete', methods: ['POST'])]
    public function delete(Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);
        $experienciaId = $data['id'];

        // Buscar la experiencia por su ID
        $experienciaRepository = $entityManager->getRepository(Experiencia::class);
        $experiencia = $experienciaRepository->find($experienciaId);

        // Verificar si se encontró la experiencia
        if (!$experiencia) {
            return new JsonResponse(['error' => 'Experiencia no encontrada'], Response::HTTP_NOT_FOUND);
        }

        // Eliminar la experiencia
        $entityManager->remove($experiencia);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Experiencia eliminada correctamente'], Response::HTTP_OK);
    }
}
