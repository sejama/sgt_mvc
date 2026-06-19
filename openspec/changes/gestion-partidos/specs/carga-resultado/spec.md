## ADDED Requirements

### Requirement: Registro de resultado por sets
El sistema SHALL registrar el resultado de un `Partido` aceptando hasta 5 sets para cada equipo (sets 1 y 2 obligatorios, sets 3 a 5 opcionales). Al cargar el resultado, el estado del `Partido` SHALL cambiar a FINALIZADO.

#### Scenario: Resultado en 2 sets registrado correctamente
- **GIVEN** un `Partido` con equipos asignados
- **WHEN** se carga resultado con set1 y set2 para local y visitante (set3 nulo)
- **THEN** el `Partido` almacena los valores de set1 y set2, set3 queda nulo y el estado cambia a FINALIZADO

#### Scenario: Resultado en 3 sets registrado correctamente
- **GIVEN** un `Partido` con equipos asignados
- **WHEN** se carga resultado con set1, set2 y set3 no nulos para ambos equipos
- **THEN** el `Partido` almacena los tres sets y el estado cambia a FINALIZADO

### Requirement: Determinación del ganador por sets
El sistema SHALL determinar el ganador comparando la cantidad de sets ganados por cada equipo. El equipo con más sets ganados es el ganador. Un equipo gana un set si su puntaje es mayor al del equipo contrario en ese set.

#### Scenario: Ganador por sets mayoritarios
- **GIVEN** un `Partido` con resultado: Local set1=25, set2=25, set3=null / Visitante set1=18, set2=20, set3=null — sets ganados 2-0 (el tercer set no se disputó)
- **WHEN** se carga el resultado
- **THEN** el equipo local es determinado como ganador

#### Scenario: Ganador con tercer set decisivo
- **GIVEN** un `Partido` con resultado Local: (25,18,25) / Visitante: (18,25,20) — sets ganados 2-1
- **WHEN** se carga el resultado
- **THEN** el equipo local es determinado como ganador

### Requirement: Propagación del ganador al bracket via PartidoConfig
El sistema SHALL, al finalizar un `Partido` eliminatorio, buscar el `PartidoConfig` que lo referencia como `ganadorPartido1` o `ganadorPartido2`, y asignar automáticamente el equipo ganador como equipo local o visitante del siguiente `Partido` del bracket. Si ningún `PartidoConfig` referencia el partido, no SHALL ocurrir ninguna propagación.

#### Scenario: Ganador propagado como equipo local del siguiente partido
- **GIVEN** un `PartidoConfig` donde `ganadorPartido1` apunta al `Partido` que acaba de finalizar
- **WHEN** se carga el resultado del partido
- **THEN** el `Partido` siguiente del bracket recibe al equipo ganador como `equipoLocal`

#### Scenario: Ganador propagado como equipo visitante del siguiente partido
- **GIVEN** un `PartidoConfig` donde `ganadorPartido2` apunta al `Partido` que acaba de finalizar
- **WHEN** se carga el resultado del partido
- **THEN** el `Partido` siguiente del bracket recibe al equipo ganador como `equipoVisitante`

#### Scenario: Perdedor propagado al bracket cuando corresponde
- **GIVEN** un `PartidoConfig` donde `perdedorPartido1` apunta al `Partido` que acaba de finalizar
- **WHEN** se carga el resultado del partido
- **THEN** el `Partido` siguiente del bracket recibe al equipo perdedor como `equipoLocal`

#### Scenario: Sin PartidoConfig no hay propagación
- **GIVEN** un `Partido` finalizado sin ningún `PartidoConfig` que lo referencie
- **WHEN** se carga el resultado
- **THEN** ningún otro `Partido` es modificado
