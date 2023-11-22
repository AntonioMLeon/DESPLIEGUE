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
    public function editEstudianteId(int $id, Request $request,ManagerRegistry $registry): JsonResponse
    {
    $entityManager = $registry->getManager();
    $objEstudiante = $entityManager->getRepository(Estudiantes::class)->find($id);

    if (!$objEstudiante) {
        return new JsonResponse(['success' => false, 'message' => 'Estudiante no encontrado'], 404);
    }

    $nombre = $request->query->get('nombre');
    $apellido = $request->query->get('apellido');
    $fecha_nacimiento = $request->query->get('fecha_nacimiento');
    $direccion = $request->query->get('direccion');
    $telefono = $request->query->get('telefono');
    $codigo_postal = $request->query->get('codigo_postal');
    $email = $request->query->get('email');
    
    
    $objEstudiante->setNombre($nombre ?? $objEstudiante->getNombre());
    $objEstudiante->setApellido($apellido ?? $objEstudiante->getApellido());
    $objEstudiante->setFechaNacimiento($fecha_nacimiento ?? $objEstudiante->getFechaNacimiento());
    $objEstudiante->setDireccion($direccion ?? $objEstudiante->getDireccion());
    $objEstudiante->setTelefono($telefono ?? $objEstudiante->getTelefono());
    $objEstudiante->setCodigoPostal($codigo_postal ?? $objEstudiante->getCodigoPostal());
    $objEstudiante->setEmail($email ?? $objEstudiante->getEmail());
    

    $entityManager->flush();

    $response = new JsonResponse();
    $response->setData([
        'success' => true,
        'data' => [
            [
                'id' => $objEstudiante->getId(),
                'nombre' => $objEstudiante->getNombre(),
                'apellido' => $objEstudiante->getApellido(),
                'fecha_nacimiento' => $objEstudiante->getFechaNacimiento(),
                'direccion' => $objEstudiante->getDireccion(),
                'telefono' => $objEstudiante->getTelefono(),
                'codigo_postal' => $objEstudiante->getCodigoPostal(),
                'email' => $objEstudiante->getEmail()
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
    $newEstudiante->setNombre($data['nombre']?? null);
    $newEstudiante->setApellido($data['apellido']?? null);
    $newEstudiante->setFechaNacimiento($data['fecha_nacimiento']?? null);
    $newEstudiante->setDireccion($data['direccion']?? null);
    $newEstudiante->setTelefono($data['telefono']?? null);
    $newEstudiante->setCodigoPostal($data['codigo_postal']?? null);
    $newEstudiante->setEmail($data['email']?? null);

    $entityManager->persist($newEstudiante);
    $entityManager->flush();

    return new JsonResponse([
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
    }
  
}
