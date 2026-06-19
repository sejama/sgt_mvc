## 1. Código (specs/cache-invalidacion)

- [ ] 1.1 Inyectar `TablaManager` en `PartidoManager` (agregar al constructor)
- [ ] 1.2 En `cargarResultado()`, antes del flush, agregar: `if ($partido->getGrupo() !== null) { $this->tablaManager->clearCache($partido->getGrupo()); }`

## 2. Tests

- [ ] 2.1 Test: cargar resultado de partido con grupo invalida cache (clearCache es llamado)
- [ ] 2.2 Test: cargar resultado de partido sin grupo no llama clearCache
