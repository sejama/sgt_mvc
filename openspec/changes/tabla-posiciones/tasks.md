## 1. Tests de calculo-posiciones

- [ ] 1.1 Escribir test unitario: equipo ganador recibe 2 pts, perdedor 1 pt (spec `calculo-posiciones` — Puntuación por partido)
- [ ] 1.2 Escribir test unitario: partido CANCELADO no suma puntos a ningún equipo
- [ ] 1.3 Escribir test unitario: determinación correcta del ganador por sets (2-0, 2-1, formato hasta 5 sets)
- [ ] 1.4 Escribir test unitario: acumulación de sets a favor/en contra y diferencia de sets
- [ ] 1.5 Escribir test unitario: acumulación de puntos a favor/en contra y diferencia de puntos
- [ ] 1.6 Escribir test unitario: ordenamiento por puntos (caso sin empate)
- [ ] 1.7 Escribir test unitario: desempate por diferencia de sets cuando hay empate en puntos
- [ ] 1.8 Escribir test unitario: desempate por diferencia de puntos cuando hay empate en sets también
- [ ] 1.9 Escribir test unitario: equipo sin partidos aparece en tabla con todos los contadores en 0

## 2. Tests de estado-grupo

- [ ] 2.1 Escribir test unitario: grupo cambia a FINALIZADO cuando todos los partidos son FINALIZADO
- [ ] 2.2 Escribir test unitario: grupo NO cambia a FINALIZADO si hay un partido PROGRAMADO
- [ ] 2.3 Escribir test unitario: partido CANCELADO cuenta para determinar si el grupo finalizó
- [ ] 2.4 Escribir test unitario: grupo ya FINALIZADO no genera escritura innecesaria al repositorio

## 3. Tests de cache-posiciones

- [ ] 3.1 Escribir test unitario: segunda llamada a `calcularPosiciones()` retorna resultado cacheado (mock de CacheInterface)
- [ ] 3.2 Escribir test unitario: `clearCache()` invoca `CacheInterface::delete()` con la clave correcta
- [ ] 3.3 Escribir test unitario: `clearCache()` con grupoId sin cache no lanza excepción

## 4. Verificación con herramientas de calidad

- [ ] 4.1 Ejecutar PHPStan sobre los nuevos tests y confirmar nivel estricto sin errores
- [ ] 4.2 Ejecutar PHPUnit con coverage y verificar cobertura ≥ 90% en `TablaManager`
