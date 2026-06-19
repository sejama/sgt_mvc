## ADDED Requirements

### Requirement: Edición del reglamento como operación independiente
El sistema SHALL permitir editar el reglamento del `Torneo` de forma separada a los demás campos. La edición del reglamento NO SHALL modificar el estado del torneo ni revalidar el resto de los campos.

#### Scenario: Reglamento editado sin afectar estado del Torneo
- **GIVEN** un `Torneo` en estado `EstadoTorneo::ACTIVO`
- **WHEN** se invoca `editarReglamento()` con contenido HTML válido
- **THEN** el reglamento queda actualizado y el estado del torneo permanece `EstadoTorneo::ACTIVO`

#### Scenario: Reglamento puede ser nulo o vacío
- **GIVEN** un `Torneo` con reglamento existente
- **WHEN** se invoca `editarReglamento()` con cadena vacía
- **THEN** el reglamento queda actualizado con el resultado de sanitizar la cadena vacía

### Requirement: Sanitización del contenido HTML del reglamento
El sistema SHALL pasar el contenido del reglamento por `RichTextSanitizer::sanitize()` antes de persistirlo. El contenido resultante SHALL ser el valor sanitizado, no el original.

#### Scenario: HTML potencialmente peligroso es sanitizado antes de persistir
- **GIVEN** un reglamento con contenido HTML que incluye etiquetas no permitidas (ej. `<script>`)
- **WHEN** se invoca `editarReglamento()`
- **THEN** el reglamento almacenado contiene el HTML sanitizado sin las etiquetas peligrosas
