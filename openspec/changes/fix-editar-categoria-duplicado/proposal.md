## Problem

`CategoriaManager::editarCategoria()` tiene un error de precedencia de operadores en la validación de duplicados. La condición actual es:

```php
if ($categoria->getGenero()->value !== $genero || $categoria->getNombre() !== $nombre
    && $this->categoriaRepository->findOneBy([...])
)
```

En PHP `&&` tiene mayor precedencia que `||`, por lo que esto evalúa como:
`genero_cambió || (nombre_no_cambió && hayDuplicado)`

Si solo se cambia el género (sin duplicado real), la condición es `true` y lanza `AppException('Ya existe una categoría con ese nombre y genero')` incorrectamente. Cambiar solo el género de una categoría es una operación válida que el sistema rechaza por este bug.

## Proposal

Corregir la condición agregando paréntesis para que la lógica sea:
`(genero_cambió || nombre_cambió) && hayDuplicado`

```php
if (($categoria->getGenero()->value !== $genero || $categoria->getNombre() !== $nombre)
    && $this->categoriaRepository->findOneBy([...])
)
```

## Out of Scope

- No se modifica la validación de `nombreCorto` (que ya está correcta).
- No se agrega logging adicional.
