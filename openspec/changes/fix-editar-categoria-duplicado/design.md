## Context

La condición actual en `editarCategoria()` (línea 91 de `CategoriaManager.php`):

```php
if ($categoria->getGenero()->value !== $genero ||  $categoria->getNombre() !== $nombre
    && $this->categoriaRepository->findOneBy([...])
)
```

Por precedencia PHP (`&&` > `||`) esto es equivalente a:

```php
if (genero_cambió || (nombre_no_cambió && hayDuplicado))
```

Casos con el bug:
| genero_cambió | nombre_cambió | hayDuplicado | Resultado actual | Resultado esperado |
|---|---|---|---|---|
| true | false | false | throws (BUG) | ok |
| true | true | false | throws (BUG) | ok |
| true | true | true | throws (correcto por razón equivocada) | throws |
| false | true | true | ok | throws |
| false | false | — | ok | ok |

La corrección es simple: agregar paréntesis alrededor de la condición OR.

## Goals / Non-Goals

**Goals:**
- Permitir cambiar solo el género (o solo el nombre) sin duplicado real sin lanzar excepción.
- Mantener el rechazo cuando el par (genero, nombre) ya existe en otra categoría del torneo.

**Non-Goals:**
- No modificar la validación de `nombreCorto`.
- No cambiar el mensaje de la excepción.

## Decisions

### D1 — Solo agregar paréntesis, sin refactorizar

La corrección mínima es la correcta. No se refactoriza el método completo para evitar introducir regresiones en otra lógica del mismo método.

## Risks / Trade-offs

- **[Ninguno relevante]** El cambio es de una sola línea de precedencia de operadores.
