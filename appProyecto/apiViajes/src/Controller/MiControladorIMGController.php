<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/controllador')]
class MiControladorIMGController extends AbstractController
{
    /**
     * @Route("/subirImagen", name="upload_image", methods={"POST"})
     */
    #[Route('/subirImagen', name: 'subirImagen', methods: ['POST'])]
    public function uploadImage(Request $request): JsonResponse
    {
        $response = new JsonResponse();
        /* Cabeceras */
        $response->headers->set('Access-Control-Allow-Origin', 'http://127.0.0.1:4200');
        $response->headers->set('Access-Control-Allow-Credentials', 'true');
        $response->headers->set('Access-Control-Allow-Headers', 'Origin, Content-Type, X-Auth-Token, Authorization, X-Requested-With');

        // Directorio de destino
        $directorioDestino = $this->getParameter('kernel.project_dir') . '/public/assets/uploads/';

        // Si no existe crea la carpeta
        if (!file_exists($directorioDestino)) {
            mkdir($directorioDestino, 0777, true);
        }

        // Verificar enviado
        if ($request->files->has('imagen')) {
            $archivo = $request->files->get('imagen');
            $nombreArchivo = $archivo->getClientOriginalName();
            $rutaDestino = $directorioDestino . $nombreArchivo;

            // Mover archivo desde ubicacion temporal a directorio de destino
            try {
                $archivo->move($directorioDestino, $nombreArchivo);
                // Si carga envia URL de img
                $response->setData(['urlImagen' => '/assets/uploads/' . $nombreArchivo]);
            } catch (\Exception $e) {
                // Si no error!
                $response->setData(['error' => 'Error al subir el archivo.']);
            }
        } else {
            // Si no encuentra archivo error!
            $response->setData(['error' => 'Archivo no enviado.']);
        }

        return $response;
    }
}
