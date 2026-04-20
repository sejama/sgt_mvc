# Troubleshooting SGT

Guía de problemas frecuentes del entorno local de SGT.

## Orden sugerido de atención

1. Verificar contenedores y logs base.
2. Resolver conectividad a base de datos.
3. Resolver migraciones.
4. Recién después revisar cobertura y optimizaciones.

## Bloqueantes (impiden levantar o usar la app)

| Síntoma | Causa probable | Verificar | Resolver |
|---|---|---|---|
| No abre http://localhost:8080/sgt/public/ | Contenedor PHP detenido o error de arranque | `docker ps` y `docker logs server-php-apache --tail 100` | Desde [entorno-php](../../../): `./iniciar.sh` |
| No se puede entrar con `./consola.sh` | Contenedor `server-php-apache` no existe o está apagado | `docker ps -a | grep server-php-apache` | Levantar entorno y reintentar: `./iniciar.sh` y luego `./consola.sh` |
| Error de conexión a base de datos | `server-mysql` no está activo o `DATABASE_URL` no coincide | `docker ps`, `docker logs server-mysql --tail 100`, revisar `.env.local` / `.env.test.local` | Ajustar `DATABASE_URL` al host/puerto correctos y reiniciar servicios |
| `doctrine:migrations:migrate` falla | Base no creada, credenciales inválidas o migración conflictiva | `php bin/console doctrine:database:create --if-not-exists` y `php bin/console doctrine:migrations:status` | Crear base, corregir credenciales y volver a ejecutar migraciones |

## Comunes (afectan desarrollo diario)

| Síntoma | Causa probable | Verificar | Resolver |
|---|---|---|---|
| `composer install` falla dentro del contenedor | Cache corrupta o red inestable | Mensaje de Composer y estado de red | Reintentar con `composer clear-cache` y luego `composer install` |
| Fallan tests funcionales por login/permisos | Estado de DB de test inconsistente | `APP_ENV=test php bin/console doctrine:migrations:migrate --no-interaction` | Reaplicar esquema de test y reejecutar suite funcional |

## No bloqueantes (calidad/observabilidad)

| Síntoma | Causa probable | Verificar | Resolver |
|---|---|---|---|
| `composer test:coverage` falla por cobertura | Falta driver (Xdebug/PCOV/phpdbg) en el entorno | Error tipo "No code coverage driver available" | Ejecutar suites sin cobertura (`composer test`, `composer test:unit`, etc.) o instalar driver de cobertura |

## Comandos útiles

```bash
docker ps
docker logs server-php-apache --tail 100
docker logs server-mysql --tail 100
```

Comando recomendado para validar el estado de rutas dentro del contenedor:

```bash
php bin/console debug:router
```

## Recuperación de entorno

Si el estado quedó inconsistente tras varios intentos:

```bash
cd ../../../
./bajar.sh
./iniciar.sh
./consola.sh
```

## Referencias

- [README.md](../README.md)
- [TESTING.md](../TESTING.md)