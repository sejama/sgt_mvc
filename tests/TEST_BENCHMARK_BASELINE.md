# Baseline de Performance de Tests

Fecha: 2026-04-15
Entorno: contenedor server-php-apache (docker exec)
Metodo: una sola corrida secuencial, con medicion en ms usando date +%s%3N

## Suites

| Suite | Tiempo (ms) | Tiempo (s) |
|---|---:|---:|
| Unit | 317 | 0.317 |
| Integration | 44205 | 44.205 |
| Functional | 190099 | 190.099 |

## Archivos Functional (detalle)

| Archivo | Tiempo (ms) | Tiempo (s) |
|---|---:|---:|
| tests/Functional/AdminBusinessFlowFunctionalTest.php | 51803 | 51.803 |
| tests/Functional/AdminBusinessFlowTorneoUsuarioFunctionalTest.php | 59663 | 59.663 |
| tests/Functional/AdminBusinessFlowPartidoFunctionalTest.php | 47256 | 47.256 |
| tests/Functional/AdminBusinessFlowJugadorGrupoFunctionalTest.php | 27567 | 27.567 |
| tests/Functional/SecurityAccessFunctionalTest.php | 5550 | 5.550 |

## Lectura rapida

- El costo dominante esta en Functional.
- Los tres archivos mas caros son TorneoUsuario, AdminBase y Partido.
- Unit no es cuello de botella en este proyecto.

## Comando de referencia

```bash
cd /home/smaidana/Proyectos/entorno-php/src/sgt && docker exec server-php-apache bash -lc '
cd /var/www/html/sgt
measure(){
  label="$1"; shift
  start=$(date +%s%3N)
  "$@" >/dev/null
  end=$(date +%s%3N)
  ms=$((end-start))
  echo "$label|$ms"
}
measure UNIT php ./vendor/bin/phpunit --testsuite Unit --order-by=default
measure INTEGRATION php ./vendor/bin/phpunit --testsuite Integration --order-by=default
measure FUNCTIONAL php ./vendor/bin/phpunit --testsuite Functional --order-by=default
measure F_ADMIN_BASE php ./vendor/bin/phpunit tests/Functional/AdminBusinessFlowFunctionalTest.php --order-by=default
measure F_TORNEO_USUARIO php ./vendor/bin/phpunit tests/Functional/AdminBusinessFlowTorneoUsuarioFunctionalTest.php --order-by=default
measure F_PARTIDO php ./vendor/bin/phpunit tests/Functional/AdminBusinessFlowPartidoFunctionalTest.php --order-by=default
measure F_JUGADOR_GRUPO php ./vendor/bin/phpunit tests/Functional/AdminBusinessFlowJugadorGrupoFunctionalTest.php --order-by=default
measure F_SECURITY_ACCESS php ./vendor/bin/phpunit tests/Functional/SecurityAccessFunctionalTest.php --order-by=default
'
```
