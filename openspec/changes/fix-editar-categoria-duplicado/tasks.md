## 1. Código (specs/edicion-categoria-duplicado)

- [ ] 1.1 En `CategoriaManager::editarCategoria()` (línea 91), corregir la condición agregando paréntesis:
  ```php
  // Antes (bug):
  if ($categoria->getGenero()->value !== $genero ||  $categoria->getNombre() !== $nombre
      && $this->categoriaRepository->findOneBy([...])
  )
  // Después (correcto):
  if (($categoria->getGenero()->value !== $genero || $categoria->getNombre() !== $nombre)
      && $this->categoriaRepository->findOneBy([...])
  )
  ```

## 2. Tests

- [ ] 2.1 Test: cambiar solo el género sin duplicado no lanza excepción
- [ ] 2.2 Test: cambiar solo el nombre sin duplicado no lanza excepción
- [ ] 2.3 Test: cambiar a par (nombre, género) ya existente en el torneo lanza AppException
- [ ] 2.4 Test: conservar nombre y género sin cambio no lanza excepción
