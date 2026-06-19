## 1. Modelo y migración (specs/config-maxsets)

- [ ] 1.1 Agregar campo `maxSets int DEFAULT 3` a la entidad `Categoria`
- [ ] 1.2 Agregar getter/setter `getMaxSets()` / `setMaxSets(int)` en `Categoria`
- [ ] 1.3 Generar migración Doctrine con `DEFAULT 3` para categorías existentes
- [ ] 1.4 Agregar validación en `ValidadorManager::validarCategoria()`: solo acepta 3 o 5
- [ ] 1.5 Actualizar `CategoriaManager::crearCategoria()` para aceptar y persistir `maxSets`
- [ ] 1.6 Actualizar `CategoriaManager::editarCategoria()` para aceptar `maxSets` y rechazar cambio si estado > ACTIVA

## 2. Carga de resultado con formato dinámico (specs/carga-resultado-sets)

- [ ] 2.1 Agregar helper privado en `PartidoManager` para resolver `maxSets` desde el partido (vía grupo → categoria o directamente categoria, fallback 3)
- [ ] 2.2 Actualizar `cargarResultado()` para persistir sets 4 y 5 cuando `maxSets = 5`
- [ ] 2.3 Agregar validación del ganador en `cargarResultado()`: ganador debe alcanzar `ceil(maxSets / 2)` sets
- [ ] 2.4 Ignorar sets 4 y 5 si `maxSets = 3` (no persistir, no lanzar excepción)

## 3. Validación de puntajes por set (specs/validacion-puntos-sets)

- [ ] 3.1 Agregar campos `setRegularDif2: bool DEFAULT true` y `setDecisivoDif2: bool DEFAULT true` a entidad `Categoria`
- [ ] 3.2 Agregar getters/setters correspondientes en `Categoria`
- [ ] 3.3 Generar migración Doctrine con DEFAULT true para categorías existentes
- [ ] 3.4 Actualizar `CategoriaManager::crearCategoria()` y `editarCategoria()` para aceptar y persistir `setRegularDif2` y `setDecisivoDif2`
- [ ] 3.5 Agregar método privado `validarPuntajesSet(int $local, int $visitante, bool $esDif2, int $tope): void` en `PartidoManager`
  - Verifica: `max(local, visitante) >= tope && diff >= ($esDif2 ? 2 : 1)`
  - Lanza `AppException` indicando el número del set que no cumple la regla
- [ ] 3.6 Invocar `validarPuntajesSet()` en `cargarResultado()` para cada set no nulo, antes de persistir
  - Sets regulares: `tope=25`, `esDif2=$categoria->isSetRegularDif2()`
  - Set decisivo (set `maxSets`): `tope=15`, `esDif2=$categoria->isSetDecisivoDif2()`

## 4. Tests

- [ ] 4.1 Test: crear categoría sin maxSets usa 3 por defecto
- [ ] 4.2 Test: crear categoría con maxSets = 5
- [ ] 4.3 Test: maxSets con valor 4 lanza AppException
- [ ] 4.4 Test: cambiar maxSets en categoría BORRADOR es permitido
- [ ] 4.5 Test: cambiar maxSets en categoría CERRADA lanza AppException
- [ ] 4.6 Test: torneo con zona maxSets=3 y playoff maxSets=5 coexisten
- [ ] 4.7 Test: cargarResultado en formato 3 — sets 4/5 quedan null
- [ ] 4.8 Test: cargarResultado en formato 5 — los 5 sets persisten
- [ ] 4.9 Test: cargarResultado formato 3 — local gana 2-0 (resultado válido)
- [ ] 4.10 Test: cargarResultado formato 3 — ninguno llega a 2 sets (lanza excepción)
- [ ] 4.11 Test: cargarResultado formato 5 — visitante gana 3-2 (resultado válido)
- [ ] 4.12 Test: cargarResultado formato 5 — nadie llega a 3 sets (lanza excepción)
- [ ] 4.13 Test: cargarResultado resuelve maxSets vía grupo → categoria
- [ ] 4.14 Test: setRegularDif2=true — 25-23 → válido
- [ ] 4.15 Test: setRegularDif2=true — 26-24 (extendido) → válido
- [ ] 4.16 Test: setRegularDif2=true — 25-24 (dif 1) → AppException
- [ ] 4.17 Test: setRegularDif2=true — 24-22 (ganador < 25) → AppException
- [ ] 4.18 Test: setRegularDif2=false — 25-24 → válido
- [ ] 4.19 Test: setRegularDif2=false — 24-23 (nadie llegó a 25) → AppException
- [ ] 4.20 Test: setDecisivoDif2=true — 15-13 → válido
- [ ] 4.21 Test: setDecisivoDif2=true — 16-14 (extendido) → válido
- [ ] 4.22 Test: setDecisivoDif2=true — 15-14 (dif 1) → AppException
- [ ] 4.23 Test: setDecisivoDif2=false — 15-14 → válido
- [ ] 4.24 Test: setDecisivoDif2=false — 14-13 (nadie llegó a 15) → AppException
- [ ] 4.25 Test: combinación mixta — setRegularDif2=true, setDecisivoDif2=false → set3=15-14 válido
- [ ] 4.26 Test: combinación mixta — setRegularDif2=false, setDecisivoDif2=true → set3=15-14 inválido
- [ ] 4.27 Test: resultado con set 2 inválido rechazado — Partido no cambia estado
- [ ] 4.28 Test: resultado completo válido (set1=25-18, set2=25-20, set3=15-10) → persiste y FINALIZADO
