## ADDED Requirements

### Requirement: Puntuación por partido jugado
El sistema SHALL asignar 2 puntos al equipo ganador y 1 punto al equipo perdedor de cada `Partido` en estado FINALIZADO. Los partidos en estado BORRADOR, PROGRAMADO o CANCELADO NO SHALL contribuir a los puntos de ningún equipo.

#### Scenario: Equipo gana un partido
- **GIVEN** un `Grupo` con dos equipos A y B
- **WHEN** el `Partido` entre A y B finaliza con A ganando
- **THEN** el equipo A acumula 2 puntos y el equipo B acumula 1 punto

#### Scenario: Partido cancelado no suma puntos
- **GIVEN** un `Grupo` con un `Partido` en estado CANCELADO
- **WHEN** se calculan las posiciones del `Grupo`
- **THEN** ningún equipo recibe puntos por ese partido

### Requirement: Determinación del ganador por sets
El sistema SHALL determinar el ganador de un `Partido` comparando los sets ganados por cada equipo. Un equipo gana un set si su puntaje es mayor al del equipo contrario en ese set. El equipo con más sets ganados es el ganador del partido.

#### Scenario: Ganador claro por sets (2-0)
- **GIVEN** un `Partido` con resultado: Local set1=25, set2=25 / Visitante set1=15, set2=18
- **WHEN** se determina el ganador
- **THEN** el equipo local gana con 2 sets vs 0 sets del visitante

#### Scenario: Partido en 3 sets (2-1)
- **GIVEN** un `Partido` con set3 no nulo y resultado: Local sets ganados=2, Visitante sets ganados=1
- **WHEN** se determina el ganador
- **THEN** el equipo local es el ganador del partido

#### Scenario: Partido hasta 5 sets
- **GIVEN** un `Partido` con set4 y set5 no nulos
- **WHEN** se determina el ganador
- **THEN** se cuentan todos los sets del 1 al 5 para determinar quién tiene más sets ganados

### Requirement: Acumulación de estadísticas de sets y puntos
El sistema SHALL acumular para cada equipo: sets a favor, sets en contra, diferencia de sets, puntos a favor, puntos en contra y diferencia de puntos, considerando todos los `Partido` finalizados del `Grupo`.

#### Scenario: Sets acumulados correctamente
- **GIVEN** un equipo que jugó 2 partidos ganando 2 sets en cada uno
- **WHEN** se calculan las posiciones
- **THEN** el equipo tiene `setsFavor = 4`

#### Scenario: Puntos acumulados de todos los sets
- **GIVEN** un `Partido` finalizado con Local: set1=25, set2=20 / Visitante: set1=18, set2=22
- **WHEN** se acumulan los puntos del equipo local
- **THEN** `puntosFavor` del local suma 45 y `puntosContra` suma 40

### Requirement: Ordenamiento con desempate en cascada
El sistema SHALL ordenar las posiciones usando los siguientes criterios en cascada:
1. Puntos (mayor primero)
2. Diferencia de sets (mayor primero) — aplicado solo si hay empate en puntos
3. Diferencia de puntos (mayor primero) — aplicado solo si hay empate en diferencia de sets

#### Scenario: Ordenamiento por puntos simple
- **GIVEN** tres equipos con puntos: A=6, B=5, C=3
- **WHEN** se calculan las posiciones
- **THEN** el orden es A, B, C

#### Scenario: Desempate por diferencia de sets
- **GIVEN** dos equipos con los mismos puntos, pero equipo A con `setsDiferencia=+3` y equipo B con `setsDiferencia=+1`
- **WHEN** se calculan las posiciones
- **THEN** el equipo A queda por encima del equipo B

#### Scenario: Desempate por diferencia de puntos
- **GIVEN** dos equipos con los mismos puntos y la misma diferencia de sets, pero equipo A con `puntosDiferencia=+15` y equipo B con `puntosDiferencia=+8`
- **WHEN** se calculan las posiciones
- **THEN** el equipo A queda por encima del equipo B

### Requirement: Estadísticas inicializadas en cero para todos los equipos del Grupo
El sistema SHALL incluir a todos los equipos del `Grupo` en el resultado de posiciones, incluso si no jugaron ningún partido, con todas las estadísticas en cero.

#### Scenario: Equipo sin partidos aparece en la tabla
- **GIVEN** un `Grupo` con equipo A (con partidos jugados) y equipo B (sin partidos jugados)
- **WHEN** se calculan las posiciones
- **THEN** el resultado incluye ambos equipos, con equipo B con todos los contadores en 0

### Requirement: Partido FINALIZADO con equipo nulo es ignorado silenciosamente
El sistema SHALL omitir del cálculo cualquier `Partido` en estado FINALIZADO cuyo `equipoLocal` o `equipoVisitante` sea nulo, sin lanzar excepción.

#### Scenario: Partido FINALIZADO sin equipo asignado no rompe el cálculo
- **GIVEN** un `Grupo` con un `Partido` en estado FINALIZADO donde `equipoLocal` es nulo
- **WHEN** se calculan las posiciones del grupo
- **THEN** ese partido es ignorado y el cálculo continúa sin error
