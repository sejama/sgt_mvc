# Observabilidad en Producción

El sistema usa dos herramientas complementarias para monitorear el estado de la aplicación en producción:

- **Sentry** — captura errores y excepciones no controladas
- **BetterStack (Logtail)** — recibe los logs de auditoría estructurados del canal `sgt`

Ambas funcionan vía HTTP, sin necesidad de instalar agentes en el servidor (compatible con Hostinger Shared Hosting).

---

## Arquitectura de logging

```
Managers (TorneoManager, PartidoManager, etc.)
    │
    ▼
Canal Monolog: sgt
    │
    ├── [dev]  → var/log/dev.log  (stream, todos los niveles)
    │
    └── [prod] → BetterStack Logtail  (HTTP, nivel info+)
                 + Sentry captura excepciones por separado
```

El `UserContextProcessor` agrega automáticamente el usuario autenticado a cada entrada del canal `sgt`.

---

## Variables de entorno requeridas en producción

Definir en `.env.local` en el servidor (nunca en `.env` commiteado):

```dotenv
SENTRY_DSN=https://<key>@o<org>.ingest.sentry.io/<project>
LOGTAIL_SOURCE_TOKEN=<token>
```

---

## Configuración inicial — Sentry

### 1. Crear cuenta y proyecto

1. Ir a [sentry.io](https://sentry.io) → crear cuenta gratuita
2. **Create Project** → seleccionar **PHP** → nombre: `sgt`
3. El plan gratuito incluye 5.000 errores/mes

### 2. Obtener el DSN

`Settings` → `Projects` → `sgt` → `Client Keys (DSN)` → copiar el valor de **DSN**

Formato: `https://abc123def456@o123456.ingest.sentry.io/7890123`

### 3. Configurar en el servidor

En Hostinger hPanel → `Hosting` → tu dominio → `File Manager` → editar `.env.local`:

```dotenv
SENTRY_DSN=https://abc123def456@o123456.ingest.sentry.io/7890123
```

### 4. Verificar

Luego de hacer deploy, Sentry debería mostrar el proyecto como **conectado**. Para forzar un evento de prueba:

```bash
php bin/console sentry:test --env=prod
```

### Qué captura Sentry

- Excepciones PHP no controladas con stack trace completo
- URL, método HTTP, IP del cliente
- Usuario autenticado (username de Symfony)
- Variables de entorno seguras (sin datos sensibles, `send_default_pii: false`)
- Alertas por email configurables (primer ocurrencia, regresión, etc.)

---

## Configuración inicial — BetterStack Logtail

### 1. Crear cuenta y fuente

1. Ir a [logs.betterstack.com](https://logs.betterstack.com) → crear cuenta gratuita
2. **Sources** → **Create source** → tipo: **HTTP** → nombre: `sgt-prod`
3. El plan gratuito incluye 1 GB/mes con 3 días de retención

### 2. Obtener el Source token

En la fuente creada → copiar el **Source token**

Formato: `AbCdEfGhIjKlMnOpQrSt`

### 3. Configurar en el servidor

En `.env.local`:

```dotenv
LOGTAIL_SOURCE_TOKEN=AbCdEfGhIjKlMnOpQrSt
```

### 4. Verificar

Ejecutar cualquier operación en la app (crear un torneo, cargar un resultado) y buscar en el dashboard de Logtail. Los logs llegan en segundos.

### Qué registra Logtail (canal `sgt`)

Todas las operaciones exitosas de los managers, con contexto estructurado:

| Evento | Manager | Nivel |
|---|---|---|
| Torneo creado / editado / eliminado | `TorneoManager` | `info` |
| Reglamento editado | `TorneoManager` | `info` |
| Categoría creada / editada / eliminada | `CategoriaManager` | `info` |
| Playoff armado / categoría cerrada | `CategoriaManager` | `info` |
| Equipo creado / editado / eliminado | `EquipoManager` | `info` |
| Equipo dado de baja (partidos cancelados) | `EquipoManager` | `warning` |
| Jugador creado / editado / eliminado | `JugadorManager` | `info` |
| Partidos generados por grupo / categoría | `PartidoManager` | `info` |
| Partido programado (cancha + horario) | `PartidoManager` | `info` |
| Partido creado / editado manualmente | `PartidoManager` | `info` |
| Resultado cargado | `PartidoManager` | `info` |

Cada entrada incluye automáticamente el **usuario autenticado** que realizó la operación.

Ejemplo de entrada en Logtail:

```json
{
  "level": "info",
  "message": "Resultado cargado",
  "channel": "sgt",
  "context": {
    "partido_id": 42,
    "numero": 7,
    "local": "Club Atlético Norte",
    "visitante": "Deportivo Sur",
    "sets_local": [25, 22, 15],
    "sets_visitante": [18, 25, 10],
    "torneo": "torneo-2026"
  },
  "extra": {
    "user": "admin@torneo.ar"
  }
}
```

---

## Deploy en Hostinger

Después de subir los archivos al servidor:

```bash
# Instalar dependencias sin paquetes de desarrollo
composer install --no-dev --optimize-autoloader

# Limpiar y regenerar caché en modo producción
php bin/console cache:clear --env=prod
php bin/console cache:warmup --env=prod
```

> Las variables `SENTRY_DSN` y `LOGTAIL_SOURCE_TOKEN` deben estar definidas **antes** de ejecutar `cache:warmup`, ya que el contenedor se compila en ese paso.

---

## Archivos de configuración relevantes

| Archivo | Propósito |
|---|---|
| `config/bundles.php` | Registra `SentryBundle` solo en `prod` |
| `config/packages/sentry.yaml` | Configuración de Sentry (solo `when@prod`) |
| `config/packages/monolog.yaml` | Canal `sgt` + handler Logtail en `prod` |
| `config/services.yaml` | Servicio `SynchronousLogtailHandler` en `prod` |
| `src/Logger/UserContextProcessor.php` | Processor que inyecta el usuario en cada log |
