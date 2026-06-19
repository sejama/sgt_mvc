## 1. Tests de generacion-partidos

- [ ] 1.1 Escribir test unitario: generación round-robin produce n*(n-1)/2 partidos para un grupo con n equipos
- [ ] 1.2 Escribir test unitario: grupo con 1 equipo no genera partidos
- [ ] 1.3 Escribir test unitario: partidos generados tienen tipo CLASIFICATORIO y estado BORRADOR
- [ ] 1.4 Escribir test unitario: numeración global secuencial — nuevos partidos continúan desde el último número del torneo
- [ ] 1.5 Escribir test unitario: generación de categoría con playoff crea partidos eliminatorios + PartidoConfig origen por posición de grupo
- [ ] 1.6 Escribir test unitario: generación de categoría con playoff crea PartidoConfig origen por ganador de partido previo
- [ ] 1.7 Escribir test unitario: categoría sin playoff genera solo clasificatorios (sin PartidoConfig)
- [ ] 1.8 Escribir test unitario: equipo BORRADOR pasa a ACTIVO al generar su primer partido
- [ ] 1.9 Escribir test unitario: equipo ya ACTIVO no cambia de estado al generar partido

## 2. Tests de creacion-manual-partido

- [ ] 2.1 Escribir test unitario: partido CLASIFICATORIO creado correctamente con grupo y equipos válidos
- [ ] 2.2 Escribir test unitario: partido CLASIFICATORIO sin grupo lanza AppException
- [ ] 2.3 Escribir test unitario: equipo local igual al visitante lanza AppException
- [ ] 2.4 Escribir test unitario: partido ELIMINATORIO creado sin grupo ni equipos (ambos nulos)
- [ ] 2.5 Escribir test unitario: equipo de otra categoría rechazado con AppException
- [ ] 2.6 Escribir test unitario: creación con config válida por posición de grupo genera PartidoConfig correcto
- [ ] 2.7 Escribir test unitario: config incompleta (posición faltante) lanza AppException
- [ ] 2.8 Escribir test unitario: partido no puede depender de sí mismo en config por ganadores

## 3. Tests de programacion-partido

- [ ] 3.1 Escribir test unitario: programación exitosa asigna cancha, horario y estado PROGRAMADO
- [ ] 3.2 Escribir test unitario: cancha inexistente lanza AppException
- [ ] 3.3 Escribir test unitario: cancha de otro torneo lanza AppException
- [ ] 3.4 Escribir test unitario: conflicto de cancha y horario lanza AppException
- [ ] 3.5 Escribir test unitario: misma cancha, horario diferente se permite
- [ ] 3.6 Escribir test unitario: primer partido anterior a fechaInicioTorneo lanza AppException
- [ ] 3.7 Escribir test unitario: primer partido exactamente en fechaInicioTorneo es válido
- [ ] 3.8 Escribir test unitario: segundo partido no está sujeto a restricción de fecha de inicio
- [ ] 3.9 Escribir test unitario: equipos BORRADOR se activan al programar partido exitosamente

## 4. Tests de carga-resultado

- [ ] 4.1 Escribir test unitario: resultado en 2 sets registrado y estado cambia a FINALIZADO
- [ ] 4.2 Escribir test unitario: resultado en 3 sets registrado correctamente
- [ ] 4.3 Escribir test unitario: ganador determinado correctamente por sets mayoritarios
- [ ] 4.4 Escribir test unitario: ganador propagado como equipoLocal del siguiente partido (ganadorPartido1)
- [ ] 4.5 Escribir test unitario: ganador propagado como equipoVisitante del siguiente partido (ganadorPartido2)
- [ ] 4.6 Escribir test unitario: perdedor propagado al bracket cuando PartidoConfig lo referencia
- [ ] 4.7 Escribir test unitario: sin PartidoConfig no se modifica ningún otro partido

## 5. Tests de ciclo-vida-partido

- [ ] 5.1 Escribir test unitario: partido creado tiene estado BORRADOR sin cancha ni horario
- [ ] 5.2 Escribir test unitario: numeración secuencial global no se repite en el mismo torneo
- [ ] 5.3 Escribir test unitario: partido CLASIFICATORIO tiene Grupo no nulo
- [ ] 5.4 Escribir test unitario: partido ELIMINATORIO puede tener equipos nulos al crearse

## 6. Verificación con herramientas de calidad

- [ ] 6.1 Ejecutar PHPStan sobre los nuevos tests y confirmar nivel estricto sin errores
- [ ] 6.2 Ejecutar PHPUnit con coverage y verificar cobertura ≥ 90% en `PartidoManager`
