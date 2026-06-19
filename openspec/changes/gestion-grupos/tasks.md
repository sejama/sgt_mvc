## 1. Tests de creacion-grupos

- [ ] 1.1 Escribir test unitario: equipos distribuidos secuencialmente (primeros N al primer grupo, siguientes M al segundo)
- [ ] 1.2 Escribir test unitario: cada grupo creado tiene estado BORRADOR
- [ ] 1.3 Escribir test unitario: suma de equipos en zonas menor al total de la Categoria lanza AppException
- [ ] 1.4 Escribir test unitario: suma de equipos en zonas mayor al total de la Categoria lanza AppException
- [ ] 1.5 Escribir test unitario: suma exacta permite la creación sin error de balance
- [ ] 1.6 Escribir test unitario: nombre de grupo vacío lanza AppException
- [ ] 1.7 Escribir test unitario: nombre de grupo mayor a 16 caracteres lanza AppException
- [ ] 1.8 Escribir test unitario: Categoria cambia a ZONAS_CREADAS al crear grupos exitosamente
- [ ] 1.9 Escribir test unitario: categoriaId inexistente en DTO lanza AppException

## 2. Tests de clasificacion-grupos

- [ ] 2.1 Escribir test unitario: clasificaOro cero o nulo lanza AppException
- [ ] 2.2 Escribir test unitario: clasificaBronce sin clasificaPlata lanza AppException
- [ ] 2.3 Escribir test unitario: clasificaBronce con clasificaPlata definida es válido
- [ ] 2.4 Escribir test unitario: acumulado de clasificaOro supera total de equipos lanza AppException
- [ ] 2.5 Escribir test unitario: acumulado incluyendo clasificaPlata supera total lanza AppException
- [ ] 2.6 Escribir test unitario: totales de clasificados exactamente iguales al total de equipos es válido

## 3. Tests de intercambio-equipos

- [ ] 3.1 Escribir test unitario: Categoria en estado distinto a ZONAS_CREADAS lanza AppException
- [ ] 3.2 Escribir test unitario: partidos existentes en la Categoria impiden el intercambio
- [ ] 3.3 Escribir test unitario: equipoOrigenId igual a cero lanza AppException
- [ ] 3.4 Escribir test unitario: ambos IDs iguales lanza AppException
- [ ] 3.5 Escribir test unitario: equipo que no pertenece a la Categoria lanza AppException
- [ ] 3.6 Escribir test unitario: equipo sin grupo asignado lanza AppException
- [ ] 3.7 Escribir test unitario: equipos del mismo grupo lanza AppException
- [ ] 3.8 Escribir test unitario: intercambio exitoso asigna grupos cruzados (A→GrupoY, B→GrupoX)

## 4. Tests de consulta-grupos

- [ ] 4.1 Escribir test unitario: obtenerGrupo con id existente retorna el Grupo
- [ ] 4.2 Escribir test unitario: obtenerGrupo con id inexistente lanza AppException "No se encontró el grupo"
- [ ] 4.3 Escribir test unitario: obtenerGrupos retorna grupos ordenados por nombre ASC
- [ ] 4.4 Escribir test unitario: obtenerGrupos con Categoria sin grupos retorna lista vacía
- [ ] 4.5 Escribir test unitario: obtenerEquiposDeCategoriaConGrupo excluye equipos sin grupo
- [ ] 4.6 Escribir test unitario: obtenerEquiposDeCategoriaConGrupo ordena por nombre de grupo y luego nombre de equipo
- [ ] 4.7 Escribir test unitario: obtenerEquiposDeCategoriaConGrupo retorna lista vacía si ningún equipo tiene grupo

## 5. Verificación con herramientas de calidad

- [ ] 5.1 Ejecutar PHPStan sobre los nuevos tests y confirmar nivel estricto sin errores
- [ ] 5.2 Ejecutar PHPUnit con coverage y verificar cobertura ≥ 90% en `GrupoManager`
