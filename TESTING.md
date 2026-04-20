# Estrategia de Testing (objetivo >90% confianza)

Este documento define como alcanzar y sostener una confianza alta en cambios de codigo usando tres niveles de pruebas y una metrica objetiva de cobertura.

## Meta de confianza

No existe garantia matematica total de ausencia de bugs, pero si una garantia operativa fuerte con estas reglas:

- Cobertura de lineas global >= 90% en CI.
- Ramas criticas (auth, validaciones de negocio, persistencia) con pruebas de exito y error.
- Separacion por suite para detectar rapido donde falla: Unit, Integration, Functional.

## Piramide de pruebas

- Unit (70% del esfuerzo): reglas de negocio puras, Managers, validadores, utils.
- Integration (20% del esfuerzo): repositorios, Doctrine, mapeos, consultas, servicios con BD real de test.
- Functional (10% del esfuerzo): flujos HTTP reales (login, permisos, redirects, formularios, mensajes).

## Comandos

- Suite completa: composer test
- Analisis estatico: composer phpstan
- Estandar de codigo: composer phpcs
- Calidad (ambos): composer quality
- Unit: composer test:unit
- Integration: composer test:integration
- Functional: composer test:functional
- Cobertura: composer test:coverage
- Validacion umbral 90%: composer test:coverage:check
- Flujo CI recomendado: composer test:ci

## CI

- Workflow: .github/workflows/tests.yml
- Ejecuta base de datos MySQL para entorno test, migraciones y luego composer test:ci.
- Ejecuta tambien chequeos de calidad estatica (composer phpstan y composer phpcs) en modo no bloqueante, emitiendo warning si fallan.
- Cobertura en CI: se prioriza PCOV (mas rapido) y se usa Xdebug como fallback.
- En PCOV se limita el escaneo a src para reducir overhead sin cambiar el umbral ni el alcance de tests.
- Estado actual:
	- El gate de cobertura se evalua contra 90%.
	- Si queda por debajo, el job falla (bloqueante).

## Convenciones por tipo de prueba

### Unit

- No tocar base de datos ni framework si no es necesario.
- Usar mocks solo para colaboraciones externas (repositorios, servicios IO).
- Cubrir caminos positivos, validaciones y excepciones.

### Integration

- Probar repositorios reales y consultas personalizadas.
- Validar relaciones Doctrine y constraints.
- Limpiar estado entre pruebas para evitar dependencia de orden.

### Functional

- Probar endpoints clave por rol/permisos.
- Verificar codigos HTTP, redirects, contenido clave en respuesta.
- Cubrir login/logout, acceso anonimo y acceso autenticado.
- Usar data providers para una matriz de rutas admin por rol (anonimo, ROLE_USER, ROLE_ADMIN).
- Incluir casos positivos de ROLE_ADMIN en rutas base para validar acceso permitido.

## Checklist minimo por feature

- Caso feliz.
- Errores de validacion.
- Seguridad/autorizacion.
- Persistencia correcta o rollback esperado.
- Caso borde relevante del negocio.

## Criterios de merge

- Todas las suites en verde.
- Cobertura global ideal >= 90%.
- El gate de cobertura es bloqueante en 90% y debe pasar para mergear.
- Sin deprecations nuevas en test.
- Prueba funcional para cambios en controllers o seguridad.
- Prueba de integracion para cambios en repositorios/consultas.
- Revisar tendencia de tiempos contra tests/TEST_BENCHMARK_BASELINE.md cuando se toquen suites grandes.

## Siguientes prioridades en este proyecto

1. Agregar pruebas de integracion para repositorios en src/Repository.
2. Ampliar pruebas funcionales para permisos por rol en rutas sensibles.
3. Parametrizar pruebas unitarias con data providers para reducir duplicacion.
4. Medir cobertura por modulo y fijar objetivos minimos para src/Manager y src/Security.
5. Incorporar mutation testing en modulos de reglas de negocio criticas.
