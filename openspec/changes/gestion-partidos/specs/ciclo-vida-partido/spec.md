## ADDED Requirements

### Requirement: Estados válidos del Partido y sus transiciones
El sistema SHALL gestionar el ciclo de vida de un `Partido` a través de los siguientes estados: BORRADOR, PROGRAMADO, FINALIZADO y CANCELADO. Las transiciones válidas son:

```
BORRADOR ──► PROGRAMADO ──► FINALIZADO
                        ──► CANCELADO
```

Un `Partido` recién creado (automáticamente o manual) SHALL tener estado BORRADOR. La transición a PROGRAMADO ocurre al asignar cancha y horario. La transición a FINALIZADO ocurre al cargar el resultado.

#### Scenario: Partido creado en estado BORRADOR
- **GIVEN** una solicitud de generación o creación manual de partido
- **WHEN** el `Partido` es creado
- **THEN** tiene estado `EstadoPartido::BORRADOR`, sin cancha y sin horario

#### Scenario: Partido pasa a PROGRAMADO al asignar cancha y horario
- **GIVEN** un `Partido` en estado BORRADOR
- **WHEN** se asigna una `Cancha` y un horario válidos
- **THEN** el estado del `Partido` cambia a `EstadoPartido::PROGRAMADO`

#### Scenario: Partido pasa a FINALIZADO al cargar resultado
- **GIVEN** un `Partido` en cualquier estado
- **WHEN** se carga un resultado con sets válidos
- **THEN** el estado del `Partido` cambia a `EstadoPartido::FINALIZADO`

### Requirement: Identificación única de Partido dentro de un Torneo
El sistema SHALL asignar un número único y secuencial a cada `Partido` dentro del `Torneo`. El número SHALL ser global por torneo (no reinicia por grupo o categoría). Cualquier `Partido` SHALL ser identificable por la combinación `ruta del torneo + número de partido`.

#### Scenario: Numeración secuencial global no se repite en el mismo torneo
- **GIVEN** un torneo con partidos numerados del 1 al 10
- **WHEN** se generan 3 partidos nuevos para cualquier grupo o categoría del mismo torneo
- **THEN** los nuevos partidos reciben números 11, 12 y 13

#### Scenario: Partido recuperable por ruta y número
- **GIVEN** un `Partido` con número 7 en el torneo de ruta "torneo-verano-2026"
- **WHEN** se busca el partido por `obtenerPartido("torneo-verano-2026", 7)`
- **THEN** se retorna ese `Partido` específico

### Requirement: Tipos de Partido y su semántica
El sistema SHALL soportar dos tipos de `Partido`: CLASIFICATORIO (pertenece a un `Grupo`, determina posición en la fase regular) y ELIMINATORIO (pertenece al bracket de playoff, puede no tener equipos asignados al momento de creación).

#### Scenario: Partido CLASIFICATORIO pertenece a un Grupo
- **GIVEN** un `Partido` de tipo `TipoPartido::CLASIFICATORIO`
- **WHEN** se accede a su `Grupo`
- **THEN** el `Grupo` no es nulo

#### Scenario: Partido ELIMINATORIO puede tener equipos nulos
- **GIVEN** un `Partido` de tipo `TipoPartido::ELIMINATORIO` recién creado por el bracket
- **WHEN** se accede a `equipoLocal` o `equipoVisitante`
- **THEN** pueden ser nulos (los equipos se asignarán cuando se propaguen los resultados del bracket)
