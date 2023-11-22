<?php

// src/Controller/BlogApiController.php
namespace App\Controller;

use App\Entity\Estudiantes;
use App\Repository\EstudiantesRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// ...
class EstudiantesController extends AbstractController
{
    #[Route('/api/estudiantes/{id}', methods: ['GET', 'HEAD'])]
    public function showEstudianteId(int $id,ManagerRegistry $registry): JsonResponse
    {
    $objEstudiantes = new EstudiantesRepository($registry);
    $estudiantes=$objEstudiantes->find($id);
    $response = new JsonResponse();
    $response->setData([
        'success' => true,
        'data' => [
            [
                'id' => $estudiantes->getId(),
                'nombre' => $estudiantes->getNombre(),
                'apellido' => $estudiantes->getApellido(),
                'fecha_nacimiento' => $estudiantes->getFechaNacimiento(),
                'direccion' => $estudiantes->getDireccion(),
                'telefono' => $estudiantes->getTelefono(),
                'codigo_postal' => $estudiantes->getCodigoPostal(),
                'email' => $estudiantes->getEmail()
            ]
        ]
    ]);
    return $response;
    }
    #[Route('/api/estudiantes/{id}', methods: ['PUT'])]
    public function editEstudianteId(int $id,ManagerRegistry $registry): JsonResponse
    {
    $objEstudiante = new EstudiantesRepository($registry);
    $estudiantes=$objEstudiante->find($id);
    $response = new JsonResponse();
    $response->setData([
        'success' => true,
        'data' => [
            [
                'id' => $estudiantes->getId(),
                'nombre' => $estudiantes->getNombre(),
                'apellido' => $estudiantes->getApellido(),
                'fecha_nacimiento' => $estudiantes->getFechaNacimiento(),
                'direccion' => $estudiantes->getDireccion(),
                'telefono' => $estudiantes->getTelefono(),
                'codigo_postal' => $estudiantes->getCodigoPostal(),
                'email' => $estudiantes->getEmail()
            ]
        ]
    ]);
    return $response;
    }
    
    #[Route('/api/estudiantes/{id}', methods: ['DELETE'])]
    public function deleteEstudianteId(int $id,ManagerRegistry $registry): JsonResponse
    {
    $entityManager= $registry->getManager();
    $objEstudiante = $entityManager->getRepository(Estudiantes::class)->find($id);

    if (!$objEstudiante) {
        $response = new JsonResponse();
        $response->setData([
            'success' => false,
            'message' => "Estudiante no encontrado"
        ]);
        return $response;
    }

    $entityManager->remove($objEstudiante);
    $entityManager->flush();

    $response = new JsonResponse();
    $response->setData([
        'success' => true,
        'message' => "Estudiante eliminado correctamente"
    ]);

    return $response;
    }
    #[Route('/api/estudiantes', methods: ['POST'])]
    public function postEstudianteId(Request $request,ManagerRegistry $registry): JsonResponse
    {
    $entityManager= $registry->getManager();
    $data=json_decode($request->getContent(),true);
    
    $newEstudiante= new Estudiantes();
    $newEstudiante->setNombre($data['nombre']);
    $newEstudiante->setApellido($data('apellido'));
    $newEstudiante->setFechaNacimiento($data('fecha_nacimiento'));
    $newEstudiante->setDireccion($data('direccion'));
    $newEstudiante->setTelefono($data('telefono'));
    $newEstudiante->setCodigoPostal($data['codigo_postal']);
    $newEstudiante->setEmail($data['email']);

    $entityManager->persist($newEstudiante);
    $entityManager->flush();

    $response = new JsonResponse();
    $response->setData([
        'success' => true,
        'data' => [
            [
                'id' => $newEstudiante->getId(),
                'nombre' => $newEstudiante->getNombre(),
                'apellido' => $newEstudiante->getApellido(),
                'fecha_nacimiento' => $newEstudiante->getFechaNacimiento(),
                'direccion' => $newEstudiante->getDireccion(),
                'telefono' => $newEstudiante->getTelefono(),
                'codigo_postal' => $newEstudiante->getCodigoPostal(),
                'email' => $newEstudiante->getEmail()
            ]
        ]
    ]);

    return $response;
    }
  
}
