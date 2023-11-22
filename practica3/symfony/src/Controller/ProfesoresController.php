<?php

// src/Controller/BlogApiController.php
namespace App\Controller;

use App\Entity\Profesores;
use App\Repository\ProfesoresRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// ...
class ProfesoresController extends AbstractController
{
    #[Route('/api/profesores/{id}', methods: ['GET', 'HEAD'])]
    public function showProfesoresId(int $id,ManagerRegistry $registry): JsonResponse
    {
    $objProfesores = new ProfesoresRepository($registry);
    $profesores=$objProfesores->find($id);
    $response = new JsonResponse();
    $response->setData([
        'success' => true,
        'data' => [
            [
                'id' => $profesores->getId(),
                'nombre' => $profesores->getNombre(),
                'apellido' => $profesores->getApellido(),
                'fecha_nacimiento' => $profesores->getFechaNacimiento(),
                'direccion' => $profesores->getDireccion(),
                'telefono' => $profesores->getTelefono(),
                'codigo_postal' => $profesores->getCodigoPostal(),
                'email' => $profesores->getEmail(),
                'especialidad' => $profesores->getEspecialidad()
            ]
        ]
    ]);
    return $response;
    }
    #[Route('/api/profesores/{id}', methods: ['PUT'])]
    public function editProfesorId(int $id,ManagerRegistry $registry): JsonResponse
    {
    $objProfesores = new ProfesoresRepository($registry);
    $profesores=$objProfesores->find($id);
    $response = new JsonResponse();
    $response->setData([
        'success' => true,
        'data' => [
            [
                'id' => $profesores->getId(),
                'nombre' => $profesores->getNombre(),
                'apellido' => $profesores->getApellido(),
                'fecha_nacimiento' => $profesores->getFechaNacimiento(),
                'direccion' => $profesores->getDireccion(),
                'telefono' => $profesores->getTelefono(),
                'codigo_postal' => $profesores->getCodigoPostal(),
                'email' => $profesores->getEmail(),
                'especialidad' => $profesores->getEspecialidad()
            ]
        ]
    ]);
    return $response;
    }
    #[Route('/api/profesores/{id}', methods: ['DELETE'])]
    public function deleteProfesorId(int $id,ManagerRegistry $registry): JsonResponse
    {
    $entityManager= $registry->getManager();
    $objProfesores = $entityManager->getRepository(Profesores::class)->find($id);

    if (!$objProfesores) {
        $response = new JsonResponse();
        $response->setData([
            'success' => false,
            'message' => "Profesor no encontrado"
        ]);
        return $response;
    }

    $entityManager->remove($objProfesores);
    $entityManager->flush();

    $response = new JsonResponse();
    $response->setData([
        'success' => true,
        'message' => "Profesor eliminado correctamente"
    ]);

    return $response;
    }
    #[Route('/api/profesores', methods: ['POST'])]
    public function postEstudianteId(Request $request,ManagerRegistry $registry): JsonResponse
    {
    $entityManager= $registry->getManager();
    $data=json_decode($request->getContent(),true);
    
    $newProfesor= new Profesores();
    $newProfesor->setNombre($data['nombre']);
    $newProfesor->setApellido($data('apellido'));
    $newProfesor->setFechaNacimiento($data('fecha_nacimiento'));
    $newProfesor->setDireccion($data('direccion'));
    $newProfesor->setTelefono($data('telefono'));
    $newProfesor->setCodigoPostal($data['codigo_postal']);
    $newProfesor->setEmail($data['email']);
    $newProfesor->setEspecialidad($data['especialidad']);

    $entityManager->persist($newProfesor);
    $entityManager->flush();

    $response = new JsonResponse();
    $response->setData([
        'success' => true,
        'data' => [
            [
                'id' => $newProfesor->getId(),
                'nombre' => $newProfesor->getNombre(),
                'apellido' => $newProfesor->getApellido(),
                'fecha_nacimiento' => $newProfesor->getFechaNacimiento(),
                'direccion' => $newProfesor->getDireccion(),
                'telefono' => $newProfesor->getTelefono(),
                'codigo_postal' => $newProfesor->getCodigoPostal(),
                'email' => $newProfesor->getEmail(),
                'especialidad' => $newProfesor->getEspecialidad()
            ]
        ]
    ]);

    return $response;
    }
}

