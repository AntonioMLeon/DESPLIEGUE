# DESPLIEGUE

En esta práctica utilizaremos Symfony para dockerizarla y usarla como network, donde gracias a docker-compose podremos tener 3 contenededores: php, nginx y mysql.

##NGINX

Tenemos una carpeta llamada nginx con una configuración llamada __default.conf__ con la siguiente configuración:

```
default.conf: server {
    listen 80;
    root /var/www/symfony/public;

    location / {
        try_files $uri /index.php$is_args$args;
    }
    location ~ ^/index\.php(/|$) {
        # Connect to the Docker service using fpm
        fastcgi_pass php:9000;
        fastcgi_split_path_info ^(.+\.php)(/.*)$;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        fastcgi_param DOCUMENT_ROOT $realpath_root;
        internal;
    }
    location ~ \.php$ {
        return 404;
    }
    error_log /dev/stdout info;
    access_log /var/log/nginx/project_access.log;
}

```

Y fuera de la carpeta tenemos nuestro __Dockerfile-nginx__ con la siguiente configuración:

```
FROM nginx:latest

COPY ./nginx/default.conf /etc/nginx/conf.d/

```

##PHP

Tenemos un __Dockerfile-php__ con la siguiente configuración:

```
FROM php:8.2-fpm

RUN apt-get update && apt-get install -y

RUN apt-get update && apt-get install -y \
        git \
        zlib1g-dev \
        libxml2-dev \
        libzip-dev \
    && docker-php-ext-install \
        zip \
        intl \
		mysqli \
        pdo pdo_mysql
    
RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer
RUN curl -sS https://get.symfony.com/cli/installer | bash
RUN mv /root/.symfony5/bin/symfony /usr/local/bin/symfony

RUN git config --global user.email "antoniomleonjimenez@gmail.com"
RUN git config --global user.name "AntonioMLeon"

RUN symfony check:requirements

COPY symfony/ /var/www/symfony
WORKDIR /var/www/symfony/

```
##Docker-compose

Nuestro __Docker-compose__ se verá de la siguiente forma:

```
version: '3'

services:
    nginx:
      build:
        context: .
        dockerfile: Dockerfile-nginx
      volumes:
          - ./symfony/:/var/www/symfony/
      ports:
        - 8080:80
      networks:
        - symfony
    php:
      build:
        context: .
        dockerfile: Dockerfile-php
      volumes:
           - ./symfony/:/var/www/symfony/
      networks:
        - symfony
      depends_on:
        - mysql
    mysql:
      image: mysql
      ports:
        - 3310:3306
      volumes:
        - ./mysql:/var/lib/mysql
      networks:
        - symfony
networks:
  symfony:

```
Una vez hecho todo lo anterior la distribución de nuestros archivos y carpetas se verá de la siguiente forma:

![captura1](distribucioncarpetas.jpg)

##En el terminal

Dentro de nuestro repositorio construimos el __Docker-compose__ con el siguiente comando:

```
Docker-compose build

```
Y una vez creado, levantamos el __Docker-compose__ usando el siguiente comando:

```
Docker-compose up

```
Una vez hayamos conectado la base de datos, empezamos a crear las tablas con el siguiente comando:

```
bin/console make:entity

```

Lo siguiente sería rellenar las tablas con datos para poder empezar a hacer las consultas

##MÉTODOS

__GET__

```
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

```
__PUT__

```
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
```
__DELETE__

```
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
```

__POST__

```
#[Route('/api/estudiantes', methods: ['POST'])]
    public function createEstudiantes(Request $request, ManagerRegistry $registry): JsonResponse
    {
    $entityManager = $registry->getManager();

    
    $data = json_decode($request->getContent(), true);

    
    $newEstudiante = new Estudiantes();
    $newEstudiante->setNombre($data['nombre'] ?? null);
    $newEstudiante->setApellido($data['apellido'] ?? null);
    $newEstudiante->setFechaNacimiento($data['fecha_nacimiento'] ?? null);
    $newEstudiante->setDireccion($data['direccion'] ?? null);
    $newEstudiante->setTelefono($data['telefono'] ?? null);
    $newEstudiante->setCodigoPostal($data['codigo_postal'] ?? null);
    $newEstudiante->setEmail($data['email'] ?? null);
    

    
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
                'email' => $newEstudiante->getEmail(),
                
            ]
        ]
    ]);
}
```
Todos estos métodos se aplican para la tabla estudiantes, para la tabla profesores sirve igual, simplemente habría que cambiar los nombre de los objetos,variables,etc adaptado al constructor de profesores.

DATABASE_URL="mysql://root:password@mysql:3306/db_symfony?serverVersion=10.11.2"
