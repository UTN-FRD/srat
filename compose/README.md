# Configuración

- Copiar `.env.dist` a `.env` y configurar las variables de entorno:
    - `COMPOSE_PROJECT_NAME`: Nombre del proyecto
    - `MYSQL_PASSWORD`: Contraseña de la cuenta root
    - `MYSQL_DATABASE`: Nombre de la base de datos
    - `SECURITY_SALT`: Una cadena aleatoria para las funciones HASH
    - `SECURITY_SEED`: Una cadena aleatoria numérica para las funciones de encriptado

    https://book.cakephp.org/2/es/getting-started.html#configuracion-opcional


# Proyecto

## Generación

```bash
$ docker-compose build
```

## Iniciar

```bash
$ docker-compose up
```

Ejecución en segundo plano:

```bash
$ docker-compose up -d
```

## Detener

```bash
$ docker-compose stop
```

## Detener y eliminar recursos

```bash
$ docker-compose down
```

# Consola de SRAT

```bash
$ docker exec -it <nombre_proyecto>_www_1 /var/www/html/app/Console/cake
```

## Restablecer usuarios

```bash
$ docker exec -it <nombre_proyecto>_www_1 /var/www/html/app/Console/cake usuarios restablecer -l <número_legajo>
```

Ejemplo:

```bash
$ docker exec -it srat_www_1 /var/www/html/app/Console/cake usuarios restablecer -l 1
```
