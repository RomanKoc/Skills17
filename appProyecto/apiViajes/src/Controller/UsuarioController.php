<?php


namespace App\Controller;

use App\Entity\Usuario;
use App\Form\UsuarioType;
use App\Repository\UsuarioRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/usuario')]
class UsuarioController extends AbstractController
{
    /* #[Route('/', name: 'app_usuario_index', methods: ['GET'])]
    public function index(UsuarioRepository $usuarioRepository): Response
    {
        return $this->render('usuario/index.html.twig', [
            'usuarios' => $usuarioRepository->findAll(),
        ]);
    } */
    #[Route('/', name: 'app_usuario_index', methods: ['GET'])]
    public function index(UsuarioRepository $usuarioRepository): JsonResponse
    {
        $usuarios = $usuarioRepository->findAll();

        $usuariosArray = [];
        foreach ($usuarios as $usuario) {
            $usuariosArray[] = [
                'id' => $usuario->getId(),
                'nombre' => $usuario->getNombre(),
                'apellidos' => $usuario->getApellidos(),
                'mail' => $usuario->getMail(),
                'ciudad' => $usuario->getCiudad(),
                'password' => $usuario->getPassword(),
                'rol' => [
                    $usuario->getRol()->getId(),
                ],
            ];
        }

        return new JsonResponse($usuariosArray);
    }


    #[Route('/new', name: 'app_usuario_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);
        //ORIGINAL
        // Crear una nueva instancia de Usuario y asignar los datos recibidos
        $usuario = new Usuario();
        $usuario->setNombre($data['nombre']);
        $usuario->setApellidos($data['apellidos']);
        $usuario->setMail($data['mail']);
        $usuario->setCiudad($data['ciudad']);
        $usuario->setPassword($data['password']);

        //Rol por defecto user!
        $rolPorDefecto = $entityManager->getReference('App\Entity\Rol', 2);
        $usuario->setRol($rolPorDefecto);

        $entityManager->persist($usuario);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Usuario insertado correctamente'], Response::HTTP_CREATED);
    }

    #[Route('/edit', name: 'app_usuario_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);

        // Verifica si se proporcionó un ID de usuario
        if (!isset($data['id'])) {
            return new JsonResponse(['error' => 'Se requiere un ID de usuario para la edición'], Response::HTTP_BAD_REQUEST);
        }

        $usuarioRepository = $entityManager->getRepository(Usuario::class);

        $usuario = $usuarioRepository->find($data['id']);
        if (!$usuario) {
            return new JsonResponse(['error' => 'Usuario no encontrado'], Response::HTTP_NOT_FOUND);
        }
        // Actualizar los datos del usuario con los datos proporcionados en la solicitud
        if (isset($data['nombre']) && $data['nombre'] !== '') {
            $usuario->setNombre($data['nombre']);
        }
        if (isset($data['apellidos']) && $data['apellidos'] !== '') {
            $usuario->setApellidos($data['apellidos']);
        }
        if (isset($data['mail']) && $data['mail'] !== '') {
            $usuario->setMail($data['mail']);
        }
        if (isset($data['ciudad']) && $data['ciudad'] !== '') {
            $usuario->setCiudad($data['ciudad']);
        }
        if (isset($data['password']) && $data['password'] !== '') {
            $usuario->setPassword($data['password']);
        }

        $entityManager->flush();

        return new JsonResponse(['message' => 'Usuario modificado correctamente'], Response::HTTP_OK);
    }


    #[Route('/delete', name: 'app_usuario_delete', methods: ['POST'])]
    public function delete(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Obtener el ID del usuario a eliminar del cuerpo de la solicitud JSON
        $data = json_decode($request->getContent(), true);
        $usuarioId = $data['id']; // Suponiendo que el ID del usuario está en el campo 'id' del JSON

        // Buscar el usuario por su ID
        $usuarioRepository = $entityManager->getRepository(Usuario::class);
        $usuario = $usuarioRepository->find($usuarioId);

        // Verificar si se encontró el usuario
        if (!$usuario) {
            return new JsonResponse(['error' => 'Usuario no encontrado'], Response::HTTP_NOT_FOUND);
        }

        // Eliminar el usuario
        $entityManager->remove($usuario);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Usuario eliminado correctamente'], Response::HTTP_OK);
    }
}
