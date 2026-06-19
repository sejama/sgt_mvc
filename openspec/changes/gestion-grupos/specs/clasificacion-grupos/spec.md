## ADDED Requirements

### Requirement: clasificaOro obligatorio en cada Grupo
El sistema SHALL requerir que `clasificaOro` sea un valor positivo (no cero ni nulo) en cada `CreateGrupoDTO`. Un grupo sin clasificados a oro SHALL ser rechazado con `AppException`.

#### Scenario: clasificaOro ausente o cero lanza excepción
- **GIVEN** un `CreateGrupoDTO` con `clasificaOro = 0` o `clasificaOro = null`
- **WHEN** se invoca `crearGrupos()`
- **THEN** se lanza `AppException` indicando que no se puede crear un grupo sin equipos que clasifiquen a oro

### Requirement: clasificaBronce requiere clasificaPlata
El sistema SHALL rechazar un `CreateGrupoDTO` que defina `clasificaBronce` sin `clasificaPlata`. La jerarquía de clasificación es obligatoria: bronce solo puede existir si plata también está definida.

#### Scenario: clasificaBronce sin clasificaPlata lanza excepción
- **GIVEN** un `CreateGrupoDTO` con `clasificaBronce = 2` y `clasificaPlata = null`
- **WHEN** se invoca `crearGrupos()`
- **THEN** se lanza `AppException` indicando que no se puede clasificar equipos de bronce sin clasificar equipos de plata

#### Scenario: clasificaBronce con clasificaPlata definida es válido
- **GIVEN** un `CreateGrupoDTO` con `clasificaPlata = 2` y `clasificaBronce = 2`
- **WHEN** se invoca `crearGrupos()` con totales válidos
- **THEN** el grupo se crea sin error de jerarquía de clasificación

### Requirement: Total acumulado de clasificados no puede superar el total de equipos de la Categoria
El sistema SHALL llevar un contador acumulado del total de clasificados (oro + plata + bronce) a lo largo de todos los grupos del lote. Si en cualquier punto ese acumulado supera el total de equipos de la `Categoria`, SHALL lanzar `AppException` para ese grupo.

#### Scenario: Suma acumulada de clasificaOro supera total de equipos lanza excepción
- **GIVEN** una `Categoria` con 4 equipos y dos grupos cada uno con `clasificaOro = 3` (acumulado = 6 > 4)
- **WHEN** se procesa el segundo grupo en `crearGrupos()`
- **THEN** se lanza `AppException` indicando que no se puede clasificar más equipos de los que hay en la categoría

#### Scenario: Suma acumulada incluyendo clasificaPlata supera total lanza excepción
- **GIVEN** una `Categoria` con 4 equipos, un grupo con `clasificaOro = 2, clasificaPlata = 3` (acumulado = 5 > 4)
- **WHEN** se procesa ese grupo en `crearGrupos()`
- **THEN** se lanza `AppException` al sumar plata porque supera el total

#### Scenario: Totales de clasificados exactamente iguales al total de equipos es válido
- **GIVEN** una `Categoria` con 6 equipos y grupos cuyo total de clasificados suma 6
- **WHEN** se invoca `crearGrupos()`
- **THEN** los grupos se crean sin error de límite de clasificados
