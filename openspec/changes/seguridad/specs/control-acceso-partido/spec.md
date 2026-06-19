## ADDED Requirements

### Requirement: ROLE_ADMIN puede cargar resultado en cualquier Partido
El sistema SHALL permitir a un `Usuario` con rol `ROLE_ADMIN` cargar el resultado de cualquier `Partido`, independientemente del estado del partido.

#### Scenario: ROLE_ADMIN puede cargar resultado en Partido FINALIZADO
- **GIVEN** un `Usuario` con rol ROLE_ADMIN y un `Partido` en estado FINALIZADO
- **WHEN** se evalúa el atributo `CARGAR_RESULTADO` sobre el partido
- **THEN** el acceso es concedido

#### Scenario: ROLE_ADMIN puede cargar resultado en Partido BORRADOR
- **GIVEN** un `Usuario` con rol ROLE_ADMIN y un `Partido` en estado BORRADOR
- **WHEN** se evalúa el atributo `CARGAR_RESULTADO` sobre el partido
- **THEN** el acceso es concedido

### Requirement: ROLE_PLANILLERO solo puede cargar resultado en Partidos no FINALIZADOS
El sistema SHALL permitir a un `Usuario` con rol `ROLE_PLANILLERO` cargar el resultado únicamente si el `Partido` no está en estado FINALIZADO.

#### Scenario: ROLE_PLANILLERO puede cargar resultado en Partido PROGRAMADO
- **GIVEN** un `Usuario` con rol ROLE_PLANILLERO y un `Partido` en estado PROGRAMADO
- **WHEN** se evalúa el atributo `CARGAR_RESULTADO` sobre el partido
- **THEN** el acceso es concedido

#### Scenario: ROLE_PLANILLERO puede cargar resultado en Partido BORRADOR
- **GIVEN** un `Usuario` con rol ROLE_PLANILLERO y un `Partido` en estado BORRADOR
- **WHEN** se evalúa el atributo `CARGAR_RESULTADO` sobre el partido
- **THEN** el acceso es concedido

#### Scenario: ROLE_PLANILLERO no puede cargar resultado en Partido FINALIZADO
- **GIVEN** un `Usuario` con rol ROLE_PLANILLERO y un `Partido` en estado FINALIZADO
- **WHEN** se evalúa el atributo `CARGAR_RESULTADO` sobre el partido
- **THEN** el acceso es denegado

### Requirement: Usuario no autenticado o con otro rol no puede cargar resultado
El sistema SHALL denegar el acceso al atributo `CARGAR_RESULTADO` a cualquier usuario no autenticado o que no tenga los roles `ROLE_ADMIN` ni `ROLE_PLANILLERO`.

#### Scenario: Usuario no autenticado es denegado
- **GIVEN** ningún usuario autenticado en el token de seguridad
- **WHEN** se evalúa el atributo `CARGAR_RESULTADO` sobre cualquier `Partido`
- **THEN** el acceso es denegado

#### Scenario: Usuario sin ROLE_ADMIN ni ROLE_PLANILLERO es denegado
- **GIVEN** un `Usuario` autenticado sin ninguno de los dos roles especiales
- **WHEN** se evalúa el atributo `CARGAR_RESULTADO` sobre cualquier `Partido`
- **THEN** el acceso es denegado

### Requirement: PartidoVoter solo actúa sobre el atributo CARGAR_RESULTADO y entidad Partido
El sistema SHALL ignorar cualquier atributo distinto a `CARGAR_RESULTADO` o cualquier sujeto que no sea una instancia de `Partido`. El Voter no interfiere con otros controles de acceso del sistema.

#### Scenario: Atributo distinto a CARGAR_RESULTADO no es soportado por el Voter
- **GIVEN** un atributo de autorización diferente a CARGAR_RESULTADO
- **WHEN** se evalúa el Voter
- **THEN** el Voter declina participar (supports retorna false)
