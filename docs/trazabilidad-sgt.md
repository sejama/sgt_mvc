# Trazabilidad del sistema SGT

## Objetivo

Documentar la trazabilidad actualmente implementada en SGT (entorno-php/src/sgt), identificar brechas y proponer un plan de cierre para auditoria funcional, tecnica y de seguridad.

## Alcance relevado

- Capa web (controllers, rutas y seguridad)
- Capa de configuracion de logs
- Capa de datos (entidades con metadatos de tiempo/autor)
- Mecanismos de manejo de errores

No se relevaron integraciones externas ni mensajeria asincronica porque en el codigo inspeccionado no se detectaron componentes de ese tipo.

## Lo que hay hoy

### 1) Logging de acciones en capa Controller

- Hay uso de `LoggerInterface` en 9 controladores:
  - [src/Controller/CategoriaController.php](src/Controller/CategoriaController.php#L10)
  - [src/Controller/CanchaController.php](src/Controller/CanchaController.php#L8)
  - [src/Controller/EquipoController.php](src/Controller/EquipoController.php#L13)
  - [src/Controller/GrupoController.php](src/Controller/GrupoController.php#L11)
  - [src/Controller/JugadorController.php](src/Controller/JugadorController.php#L9)
  - [src/Controller/PartidoController.php](src/Controller/PartidoController.php#L14)
  - [src/Controller/SedeController.php](src/Controller/SedeController.php#L9)
  - [src/Controller/TorneoController.php](src/Controller/TorneoController.php#L11)
  - [src/Controller/UsuarioController.php](src/Controller/UsuarioController.php#L8)

- Se relevaron aproximadamente 32 registros de nivel info y 71 de nivel error en controladores.

- Se registran eventos de negocio como:
  - alta/edicion/baja de entidades
  - armado de playoff
  - carga de resultados
  - denegaciones de acceso en algunos flujos

Ejemplos:
- [src/Controller/GrupoController.php](src/Controller/GrupoController.php#L88)
- [src/Controller/PartidoController.php](src/Controller/PartidoController.php#L373)
- [src/Controller/UsuarioController.php](src/Controller/UsuarioController.php#L228)

### 2) Operaciones sensibles en POST con token CSRF

Las operaciones destructivas y de cambio relevante se encuentran mayormente en POST con validacion de token CSRF, por ejemplo:

- eliminar categoria: [src/Controller/CategoriaController.php](src/Controller/CategoriaController.php#L161)
- cerrar categoria: [src/Controller/CategoriaController.php](src/Controller/CategoriaController.php#L192)
- eliminar torneo: [src/Controller/TorneoController.php](src/Controller/TorneoController.php#L132)
- eliminar/bajar equipo: [src/Controller/EquipoController.php](src/Controller/EquipoController.php#L174)

### 3) Trazabilidad temporal en entidades

- Hay metadatos `createdAt` y `updatedAt` en 10 entidades principales del dominio.
- Se actualizan por callbacks de Doctrine con `PrePersist` y `PreUpdate`.

Referencia de patron:
- [src/Entity/Categoria.php](src/Entity/Categoria.php#L137)
- [src/Entity/Categoria.php](src/Entity/Categoria.php#L150)

### 4) Configuracion de log centralizada (Monolog)

- Monolog activo en el proyecto: [config/bundles.php](config/bundles.php#L14)
- Configuracion por entorno:
  - dev: archivo local de logs
  - test: `fingers_crossed`
  - prod: salida JSON a stderr

Referencia:
- [config/packages/monolog.yaml](config/packages/monolog.yaml#L1)

### 5) Control de acceso y autenticacion

- Login form con CSRF habilitado en firewall principal.
- Uso de `IsGranted` y Voter para autorizacion de acciones de partido.

Referencias:
- [config/packages/security.yaml](config/packages/security.yaml#L20)
- [src/Security/Voter/PartidoVoter.php](src/Security/Voter/PartidoVoter.php#L10)

## Lo que falta (brechas)

### A) Falta de correlacion end-to-end por request

No se detecta un identificador de correlacion transversal (por ejemplo request id/trace id) propagado entre:

- logs de controladores
- errores
- capas internas (manager/repository)

Sin ese id, reconstruir incidentes complejos o auditorias forenses es mas costoso.

### B) Logging mayormente no estructurado

La mayoria de eventos usa strings concatenadas y no contexto estructurado (campos clave como actor, recurso, accion, estado, resultado).

Impacto:
- consultas operativas mas dificiles
- menor capacidad de filtrar por entidad/accion/usuario

### C) Capa Manager sin evidencia de logging

No se detectaron logs en managers. La evidencia auditada queda concentrada en controladores.

Riesgo:
- pasos de negocio internos no quedan registrados cuando son invocados desde distintos puntos
- menor granularidad para explicar decisiones de negocio

### D) Auditoria de seguridad incompleta

- El flujo de login muestra error al usuario, pero no registra explicitamente intento fallido/exitoso con metadata de seguridad.
  - [src/Controller/SecurityController.php](src/Controller/SecurityController.php#L13)
- El `AccessDeniedHandler` redirige, pero no registra evento de denegacion.
  - [src/Security/AccessDeniedHandler.php](src/Security/AccessDeniedHandler.php#L11)

### E) Trazabilidad de actor en datos es parcial

- Existe `creador` en Torneo, pero no hay un patron transversal `createdBy/updatedBy/deletedBy` en entidades clave.
  - [src/Entity/Torneo.php](src/Entity/Torneo.php#L51)

Impacto:
- para auditoria historica depende de logs externos y no del modelo de datos

### F) Falta de politica documental de trazabilidad

No habia hasta ahora un documento consolidado de:

- eventos auditables
- esquema de campos de log
- estrategia de retencion/busqueda
- responsabilidades por capa

## Mapa de cobertura actual

| Dimension | Estado actual | Nivel |
|---|---|---|
| Trazabilidad funcional (acciones de usuario) | Presente en varios controllers | Medio |
| Trazabilidad tecnica (request->capa->respuesta) | Sin correlacion global | Bajo |
| Trazabilidad de datos (created/updated) | Presente en entidades principales | Medio |
| Trazabilidad de actor en datos | Parcial (caso Torneo) | Bajo |
| Trazabilidad de seguridad (authN/authZ) | Parcial y dispersa | Bajo-Medio |
| Logging estructurado para analitica | Mayormente no estructurado | Bajo |

## Plan recomendado (priorizado)

### Prioridad 1 - Observabilidad base

1. Definir y propagar `request_id` por cada request HTTP.
2. Estandarizar formato de logs con campos fijos:
   - event
   - action
   - actor_id
   - resource_type
   - resource_id
   - request_id
   - outcome
   - error_code
3. Registrar explicitamente login exitoso, login fallido y access denied.

### Prioridad 2 - Auditoria de negocio

1. Mover eventos criticos a un servicio de auditoria reutilizable (en vez de solo logger directo en controller).
2. Incorporar trazas en capa Manager para hitos relevantes de regla de negocio.
3. Definir catalogo de eventos auditables por modulo (torneo, categoria, equipo, partido, usuario).

### Prioridad 3 - Persistencia de auditoria

1. Evaluar tabla dedicada de auditoria para eventos criticos (ademas de logs).
2. Incorporar `createdBy/updatedBy` en entidades de mayor riesgo operativo.
3. Definir retencion y politicas de consulta (operacion, seguridad, cumplimiento).

## Checklist de revision

- [x] Existe logging de acciones principales en controllers.
- [x] Operaciones sensibles usan POST y validacion CSRF.
- [x] Entidades principales tienen createdAt/updatedAt automatico.
- [ ] Existe correlacion request end-to-end.
- [ ] Existe estandar de logs estructurados.
- [ ] Se auditan explicitamente login exitoso/fallido.
- [ ] Se auditan explicitamente access denied en handler global.
- [ ] Existe trazabilidad de actor persistida de forma transversal en datos.
- [ ] Existe politica documental formal de trazabilidad operativa y de seguridad.

---

Documento generado el 2026-04-27 sobre la base del estado actual del codigo en entorno-php/src/sgt.