## ADDED Requirements

### Requirement: Transición automática de Grupo a FINALIZADO
El sistema SHALL cambiar el estado del `Grupo` a `EstadoGrupo::FINALIZADO` automáticamente cuando todos sus `Partido` están en estado FINALIZADO o CANCELADO. Esta transición SHALL ocurrir durante el cálculo de posiciones.

#### Scenario: Grupo finaliza cuando todos los partidos terminaron
- **GIVEN** un `Grupo` con 3 partidos, todos en estado FINALIZADO
- **WHEN** se calculan las posiciones del `Grupo`
- **THEN** el estado del `Grupo` cambia a `EstadoGrupo::FINALIZADO`

#### Scenario: Grupo no finaliza si hay partidos pendientes
- **GIVEN** un `Grupo` con 3 partidos donde 2 están FINALIZADO y 1 está PROGRAMADO
- **WHEN** se calculan las posiciones del `Grupo`
- **THEN** el estado del `Grupo` NO cambia a FINALIZADO

#### Scenario: Partido CANCELADO cuenta como finalizado para el estado del Grupo
- **GIVEN** un `Grupo` con 3 partidos: 2 en estado FINALIZADO y 1 en estado CANCELADO
- **WHEN** se calculan las posiciones del `Grupo`
- **THEN** el estado del `Grupo` cambia a `EstadoGrupo::FINALIZADO`

#### Scenario: Grupo ya FINALIZADO no se actualiza nuevamente
- **GIVEN** un `Grupo` cuyo estado ya es `EstadoGrupo::FINALIZADO`
- **WHEN** se calculan las posiciones del `Grupo`
- **THEN** el sistema NO persiste el estado nuevamente (condición verificada antes de guardar)
