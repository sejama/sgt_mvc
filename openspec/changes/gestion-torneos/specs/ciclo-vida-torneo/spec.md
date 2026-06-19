## ADDED Requirements

### Requirement: Estados válidos del Torneo
El sistema SHALL gestionar el `Torneo` a través de cuatro estados: BORRADOR, ACTIVO, EN_CURSO y FINALIZADO. Los valores de persistencia son los definidos en `EstadoTorneo`.

```
BORRADOR ("Borrador") ──► ACTIVO ("Inscripcion") ──► EN_CURSO ("En curso") ──► FINALIZADO ("Finalizado")
    ▲                          │                           │
    └──────────────────────────┴───────────────────────────┘
               (reset por edición de datos)
```

#### Scenario: Estado inicial al crear es siempre BORRADOR
- **GIVEN** datos válidos para un nuevo torneo
- **WHEN** se invoca `crearTorneo()`
- **THEN** el `Torneo` queda con estado `EstadoTorneo::BORRADOR`

#### Scenario: Edición de datos resetea cualquier estado a BORRADOR
- **GIVEN** un `Torneo` en cualquier estado (ACTIVO, EN_CURSO o FINALIZADO)
- **WHEN** se invocan `editarTorneo()` con datos válidos
- **THEN** el estado del `Torneo` pasa a `EstadoTorneo::BORRADOR`

### Requirement: Eliminación permanente del Torneo
El sistema SHALL eliminar el `Torneo` de forma permanente (hard delete) al invocar `eliminarTorneo()`. La operación SHALL loguear el evento antes de proceder.

#### Scenario: Torneo eliminado no existe más en el sistema
- **GIVEN** un `Torneo` existente
- **WHEN** se invoca `eliminarTorneo()`
- **THEN** el `Torneo` ya no puede ser recuperado por ninguna consulta

#### Scenario: Eliminación loguea identificadores del torneo
- **GIVEN** un `Torneo` con id, nombre y ruta
- **WHEN** se invoca `eliminarTorneo()`
- **THEN** se registra un log con el `torneo_id`, `nombre` y `ruta` del torneo eliminado

### Requirement: eliminarTorneo falla a nivel de BD si el Torneo tiene entidades relacionadas (comportamiento actual)
La entidad `Torneo` no tiene `cascade: ['remove']` en sus relaciones con `Categoria` ni con `Sede`. `eliminarTorneo()` no hace ningún pre-check de dependencias. Si el torneo tiene categorías o sedes, la eliminación falla con error de FK a nivel de base de datos.

#### Scenario: Eliminar Torneo con categorías asociadas produce error de FK
- **GIVEN** un `Torneo` que tiene al menos una `Categoria` asociada
- **WHEN** se invoca `eliminarTorneo()`
- **THEN** la operación falla con un error de integridad referencial antes de completarse
