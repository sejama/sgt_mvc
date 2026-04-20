# PHPUnit: inyección de argumentos por saltos de línea en valores de PHP INI

## Resumen

Esta nota documenta una vulnerabilidad de alta severidad en PHPUnit donde ciertos valores de PHP INI que se transmiten a procesos hijos pueden contener saltos de línea. Eso permite inyectar argumentos adicionales en la invocación del proceso hijo.

## Impacto

- Un valor manipulado puede alterar la línea de comando usada por PHPUnit.
- El comportamiento del proceso hijo puede cambiar de forma no prevista.
- En entornos de CI o testing, esto puede exponer ejecuciones a manipulación de parámetros.

## Qué revisar

- Flujos donde PHPUnit lanza procesos hijos.
- Valores de PHP INI que provienen de entorno, configuración externa o entradas no confiables.
- Cualquier script de pruebas que construya comandos usando valores heredados sin validación.

## Mitigación recomendada

- Actualizar PHPUnit a una versión que incluya la corrección.
- Evitar pasar valores de PHP INI no confiables a procesos hijos.
- Normalizar o validar los valores antes de construir la invocación.
- Revisar pipelines de pruebas para detectar configuraciones que puedan introducir saltos de línea.

## Nota operativa

Si este proyecto usa PHPUnit para correr pruebas dentro de contenedores o scripts de soporte, conviene revisar primero el punto donde se arma la llamada al proceso hijo y luego validar el origen de las variables de entorno y de PHP INI.