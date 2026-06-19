## ADDED Requirements

### Requirement: Edición del campo disputa como operación independiente
El sistema SHALL permitir editar el campo `disputa` de una `Categoria` de forma aislada, sin afectar el estado ni los demás campos. El contenido SHALL ser sanitizado por `RichTextSanitizer::sanitize()` antes de persistirse.

#### Scenario: Edición de disputa no cambia el estado de la Categoria
- **GIVEN** una `Categoria` en estado `EstadoCategoria::ZONAS_CREADAS`
- **WHEN** se invoca `editarDisputa()` con contenido HTML
- **THEN** el campo `disputa` queda actualizado y el estado de la `Categoria` permanece `EstadoCategoria::ZONAS_CREADAS`

#### Scenario: Contenido HTML sanitizado antes de persistir
- **GIVEN** un texto de disputa con etiquetas HTML potencialmente peligrosas (ej. `<script>`)
- **WHEN** se invoca `editarDisputa()`
- **THEN** el campo `disputa` almacenado contiene el HTML sanitizado, sin las etiquetas peligrosas

#### Scenario: Disputa puede establecerse con cadena vacía
- **GIVEN** una `Categoria` con disputa existente
- **WHEN** se invoca `editarDisputa()` con cadena vacía
- **THEN** el campo `disputa` queda actualizado con el resultado de sanitizar la cadena vacía, sin error
