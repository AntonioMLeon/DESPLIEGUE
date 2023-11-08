# DESPLIEGUE

En esta práctica utilizaremos Symfony para dockerizarla y usarla como network, donde gracias a docker-compose podremos tener 3 contenededores: php, nginx y mysql.

##NGINX

Tenemos una carpeta llamada nginx con una configuración llamada default.conf:

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



