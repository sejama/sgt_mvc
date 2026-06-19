## ADDED Requirements

### Requirement: Sets regulares con regla dif2 (setRegularDif2 = true)
Cuando `setRegularDif2 = true`, un set regular se cierra cuando un equipo llega a 25 puntos CON diferencia mínima de 2. Si están empatados en 24-24 el juego continúa hasta que alguien tenga 2 de diferencia (26-24, 27-25, etc.). Sin techo.

#### Scenario: 25-23 válido (dif2 activado)
- **GIVEN** set regular con `setRegularDif2 = true`, resultado local=25, visitante=23
- **WHEN** se valida el puntaje
- **THEN** el set es válido, gana el equipo local

#### Scenario: 26-24 válido — set extendido (dif2 activado)
- **GIVEN** set regular con `setRegularDif2 = true`, resultado local=26, visitante=24
- **WHEN** se valida el puntaje
- **THEN** el set es válido, gana el equipo local

#### Scenario: 25-24 inválido (dif2 activado — diferencia de 1)
- **GIVEN** set regular con `setRegularDif2 = true`, resultado local=25, visitante=24
- **WHEN** se valida el puntaje
- **THEN** el set es inválido — ningún equipo alcanzó 2 de diferencia

#### Scenario: 24-22 inválido (dif2 activado — ganador no llegó a 25)
- **GIVEN** set regular con `setRegularDif2 = true`, resultado local=24, visitante=22
- **WHEN** se valida el puntaje
- **THEN** el set es inválido — el ganador no alcanzó 25 puntos

### Requirement: Sets regulares con regla primero al tope (setRegularDif2 = false)
Cuando `setRegularDif2 = false`, el set regular termina en cuanto un equipo llega a 25 puntos, independientemente de la diferencia. Un marcador de 25-24 es un resultado válido.

#### Scenario: 25-24 válido (setRegularDif2 = false)
- **GIVEN** set regular con `setRegularDif2 = false`, resultado local=25, visitante=24
- **WHEN** se valida el puntaje
- **THEN** el set es válido, gana el equipo local

#### Scenario: 25-0 válido (setRegularDif2 = false)
- **GIVEN** set regular con `setRegularDif2 = false`, resultado local=25, visitante=0
- **WHEN** se valida el puntaje
- **THEN** el set es válido, gana el equipo local

#### Scenario: 24-23 inválido (setRegularDif2 = false — nadie llegó a 25)
- **GIVEN** set regular con `setRegularDif2 = false`, resultado local=24, visitante=23
- **WHEN** se valida el puntaje
- **THEN** el set es inválido — ningún equipo llegó a 25

### Requirement: Set decisivo con regla dif2 (setDecisivoDif2 = true)
Cuando `setDecisivoDif2 = true`, el set decisivo (set 3 en formato 3, set 5 en formato 5) se cierra cuando un equipo llega a 15 puntos CON diferencia mínima de 2. Sin techo: 16-14, 17-15, etc. son válidos.

#### Scenario: 15-13 válido — set decisivo (dif2 activado)
- **GIVEN** set decisivo con `setDecisivoDif2 = true`, resultado local=15, visitante=13
- **WHEN** se valida el puntaje
- **THEN** el set es válido, gana el equipo local

#### Scenario: 16-14 válido — set decisivo extendido (dif2 activado)
- **GIVEN** set decisivo con `setDecisivoDif2 = true`, resultado local=16, visitante=14
- **WHEN** se valida el puntaje
- **THEN** el set es válido, gana el equipo local

#### Scenario: 15-14 inválido — set decisivo (dif2 activado — diferencia de 1)
- **GIVEN** set decisivo con `setDecisivoDif2 = true`, resultado local=15, visitante=14
- **WHEN** se valida el puntaje
- **THEN** el set es inválido — ningún equipo tiene 2 de diferencia

#### Scenario: 14-12 inválido — set decisivo (dif2 activado — ganador no llegó a 15)
- **GIVEN** set decisivo con `setDecisivoDif2 = true`, resultado local=14, visitante=12
- **WHEN** se valida el puntaje
- **THEN** el set es inválido — el ganador no alcanzó 15 puntos

### Requirement: Set decisivo con regla primero al tope (setDecisivoDif2 = false)
Cuando `setDecisivoDif2 = false`, el set decisivo termina en cuanto un equipo llega a 15 puntos, independientemente de la diferencia. Un marcador de 15-14 es válido.

#### Scenario: 15-14 válido — set decisivo (setDecisivoDif2 = false)
- **GIVEN** set decisivo con `setDecisivoDif2 = false`, resultado local=15, visitante=14
- **WHEN** se valida el puntaje
- **THEN** el set es válido, gana el equipo local

#### Scenario: 14-13 inválido — set decisivo (setDecisivoDif2 = false — nadie llegó a 15)
- **GIVEN** set decisivo con `setDecisivoDif2 = false`, resultado local=14, visitante=13
- **WHEN** se valida el puntaje
- **THEN** el set es inválido — ningún equipo llegó a 15

### Requirement: cargarResultado aplica la regla correcta según configuración de la Categoria
El sistema SHALL usar `setRegularDif2` para validar sets regulares y `setDecisivoDif2` para el set decisivo. Si cualquier set jugado no cumple la regla que le corresponde, SHALL lanzar `AppException` sin cambiar el estado del `Partido`.

#### Scenario: Combinación mixta — set regular con dif2, set decisivo sin dif2
- **GIVEN** una categoría con `setRegularDif2 = true` y `setDecisivoDif2 = false`, partido formato 3 sets
- **WHEN** se carga resultado: set1=25-20 (válido dif2), set2=25-23 (válido dif2), set3=15-14 (válido porque setDecisivoDif2=false)
- **THEN** el resultado se persiste y el Partido pasa a FINALIZADO

#### Scenario: Combinación mixta — set regular sin dif2, set decisivo con dif2
- **GIVEN** una categoría con `setRegularDif2 = false` y `setDecisivoDif2 = true`, partido formato 3 sets
- **WHEN** se carga resultado: set1=25-24 (válido porque setRegularDif2=false), set2=25-24 (válido), set3=15-14 (inválido — setDecisivoDif2=true exige dif2)
- **THEN** se lanza `AppException` indicando que el set 3 no cumple la regla de puntaje

#### Scenario: Set regular 25-24 rechazado si setRegularDif2 = true
- **GIVEN** una categoría con `setRegularDif2 = true`
- **WHEN** se carga resultado con set1=25-24
- **THEN** se lanza `AppException` y el `Partido` no cambia de estado
