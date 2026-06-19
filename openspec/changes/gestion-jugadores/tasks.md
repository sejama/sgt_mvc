## 1. Tests de creacion-jugador

- [ ] 1.1 Escribir test unitario: documento duplicado en el mismo Equipo lanza AppException
- [ ] 1.2 Escribir test unitario: mismo documento en distinto Equipo se permite
- [ ] 1.3 Escribir test unitario: nombre menor a 3 caracteres lanza AppException
- [ ] 1.4 Escribir test unitario: numeroDocumento menor a 5 caracteres lanza AppException
- [ ] 1.5 Escribir test unitario: fechaNacimiento nula crea jugador con nacimiento nulo
- [ ] 1.6 Escribir test unitario: fechaNacimiento con formato inválido lanza AppException
- [ ] 1.7 Escribir test unitario: jugador creado con responsable=true persiste correctamente

## 2. Tests de edicion-jugador (incluyendo comportamientos divergentes documentados)

- [ ] 2.1 Escribir test unitario: cambiar documento a uno de jugador de otro equipo lanza AppException (unicidad global)
- [ ] 2.2 Escribir test unitario: conservar el mismo numeroDocumento no lanza excepción
- [ ] 2.3 Escribir test unitario: apellido no cambia tras la edición (setApellido nunca se llama)
- [ ] 2.4 Escribir test unitario: fechaNacimiento no nula actualiza el campo nacimiento correctamente
- [ ] 2.5 Escribir test unitario: nombre demasiado corto en edición lanza AppException (ValidadorManager invocado)

## 3. Tests de ciclo-vida-jugador

- [ ] 3.1 Escribir test unitario: eliminarJugador realiza hard delete (jugador no recuperable)
- [ ] 3.2 Escribir test unitario: log de eliminación incluye jugador_id, nombre completo y equipo_id
- [ ] 3.3 Escribir test unitario: obtenerJugador con id existente retorna el Jugador
- [ ] 3.4 Escribir test unitario: obtenerJugador con id inexistente lanza AppException "No se encontró el jugador"
- [ ] 3.5 Escribir test unitario: obtenerJugadores retorna todos sin filtro
- [ ] 3.6 Escribir test unitario: obtenerJugadoresPorEquipo retorna solo los del Equipo indicado

## 4. Verificación con herramientas de calidad

- [ ] 4.1 Ejecutar PHPStan sobre los nuevos tests y confirmar nivel estricto sin errores
- [ ] 4.2 Ejecutar PHPUnit con coverage y verificar cobertura ≥ 90% en `JugadorManager`
