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
    public function editProfesorId(int $id, Request $request,ManagerRegistry $registry): JsonResponse
    {
    $entityManager = $registry->getManager();
    $objProfesores = $entityManager->getRepository(Profesores::class)->find($id);

    if (!$objProfesores) {
        return new JsonResponse(['success' => false, 'message' => 'Profesor no encontrado'], 404);
    }

    $nombre = $request->query->get('nombre');
    $apellido = $request->query->get('apellido');
    $fecha_nacimiento = $request->query->get('fecha_nacimiento');
    $direccion = $request->query->get('direccion');
    $telefono = $request->query->get('telefono');
    $codigo_postal = $request->query->get('codigo_postal');
    $email = $request->query->get('email');
    $especialidad = $request->query->get('especialidad');
    
    
    $objProfesores->setNombre($nombre ?? $objProfesores->getNombre());
    $objProfesores->setApellido($apellido ?? $objProfesores->getApellido());
    $objProfesores->setFechaNacimiento($fecha_nacimiento ?? $objProfesores->getFechaNacimiento());
    $objProfesores->setDireccion($direccion ?? $objProfesores->getDireccion());
    $objProfesores->setTelefono($telefono ?? $objProfesores->getTelefono());
    $objProfesores->setCodigoPostal($codigo_postal ?? $objProfesores->getCodigoPostal());
    $objProfesores->setEmail($email ?? $objProfesores->getEmail());
    $objProfesores->setEspecialidad($email ?? $objProfesores->getEspecialidad());
    

    $entityManager->flush();

    $response = new JsonResponse();
    $response->setData([
        'success' => true,
        'data' => [
            [
                'id' => $objProfesores->getId(),
                'nombre' => $objProfesores->getNombre(),
                'apellido' => $objProfesores->getApellido(),
                'fecha_nacimiento' => $objProfesores->getFechaNacimiento(),
                'direccion' => $objProfesores->getDireccion(),
                'telefono' => $objProfesores->getTelefono(),
                'codigo_postal' => $objProfesores->getCodigoPostal(),
                'email' => $objProfesores->getEmail(),
                'especialidad' => $objProfesores->getEspecialidad(),

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

