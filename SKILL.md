# Skill Symfony para `src/sgt`

## Contexto

- Proyecto Symfony ubicado en `src/sgt`.
- La carpeta se monta en Docker en `/var/www/html/sgt`.
- El contenedor principal es `server-php-apache`.
- Usa `composer install --working-dir /var/www/html/sgt` y `php /var/www/html/sgt/bin/console`.

## Reglas específicas

- Identifica el entorno usando `src/sgt/.env`, `src/sgt/.env.local` y `src/sgt/.env.test`.
- Para ejecutar migraciones usa `php /var/www/html/sgt/bin/console doctrine:migrations:migrate`.
- Para pruebas usa `php /var/www/html/sgt/vendor/bin/phpunit` o `php /var/www/html/sgt/bin/phpunit` según el proyecto.

## Buenas prácticas

- No asumas que todos los comandos Symfony son iguales entre proyectos; confirma la carpeta de trabajo antes de ejecutar.
- Mantén los cambios locales en `src/sgt` y documenta en `src/sgt/README.md` si haces ajustes de entorno.
