## 1. Tests de creacion-categoria

- [ ] 1.1 Escribir test unitario: categoría creada con estado BORRADOR y asociada al Torneo
- [ ] 1.2 Escribir test unitario: combinación torneo+genero+nombre duplicada lanza AppException
- [ ] 1.3 Escribir test unitario: mismo nombre con distinto genero se permite en el mismo torneo
- [ ] 1.4 Escribir test unitario: nombreCorto duplicado en el mismo torneo lanza AppException
- [ ] 1.5 Escribir test unitario: nombre menor a 3 caracteres lanza AppException
- [ ] 1.6 Escribir test unitario: genero inválido en creación lanza AppException via ValidadorManager

## 2. Tests de edicion-categoria

- [ ] 2.1 Escribir test unitario: edición exitosa no altera el estado de la Categoria
- [ ] 2.2 Escribir test unitario: cambiar nombre a combinación ya existente en otra Categoria lanza AppException
- [ ] 2.3 Escribir test unitario: conservar el mismo nombre y genero no lanza excepción
- [ ] 2.4 Escribir test unitario: cambiar nombreCorto a uno usado por otra Categoria lanza AppException
- [ ] 2.5 Escribir test unitario: conservar el mismo nombreCorto no lanza excepción
- [ ] 2.6 Escribir test unitario: genero inválido en edición lanza ValueError (no AppException)

## 3. Tests de disputa-categoria

- [ ] 3.1 Escribir test unitario: edición de disputa no cambia el estado de la Categoria
- [ ] 3.2 Escribir test unitario: contenido HTML es sanitizado por RichTextSanitizer antes de persistir
- [ ] 3.3 Escribir test unitario: disputa puede establecerse con cadena vacía sin error

## 4. Tests de armar-playoff

- [ ] 4.1 Escribir test unitario: un grupo no finalizado lanza AppException (no modifica nada)
- [ ] 4.2 Escribir test unitario: todos los grupos finalizados permite iniciar el armado
- [ ] 4.3 Escribir test unitario: equipo en posición 1 del grupo resuelto en índice 0 del array de posiciones
- [ ] 4.4 Escribir test unitario: equipo en posición 2 del grupo resuelto en índice 1 del array de posiciones
- [ ] 4.5 Escribir test unitario: partidos con equipos ya asignados no son modificados
- [ ] 4.6 Escribir test unitario: partidos con config por ganador no reciben equipos en esta etapa
- [ ] 4.7 Escribir test unitario: estado de Categoria cambia a ZONAS_CERRADAS al completar exitosamente

## 5. Tests de ciclo-vida-categoria

- [ ] 5.1 Escribir test unitario: cerrarCategoria cambia estado a CERRADA sin validaciones
- [ ] 5.2 Escribir test unitario: eliminarCategoria por id elimina la entidad y loguea id y nombre
- [ ] 5.3 Escribir test unitario: eliminarCategoria con id inexistente lanza AppException
- [ ] 5.4 Escribir test unitario: eliminarCategoriaEntidad elimina directamente sin búsqueda previa
- [ ] 5.5 Escribir test unitario: obtenerCategorias retorna todas sin filtro
- [ ] 5.6 Escribir test unitario: obtenerCategoriasPorTorneo retorna solo las del torneo indicado
- [ ] 5.7 Escribir test unitario: obtenerCategoria retorna null si el id no existe (sin excepción)

## 6. Verificación con herramientas de calidad

- [ ] 6.1 Ejecutar PHPStan sobre los nuevos tests y confirmar nivel estricto sin errores
- [ ] 6.2 Ejecutar PHPUnit con coverage y verificar cobertura ≥ 90% en `CategoriaManager`
