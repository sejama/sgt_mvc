# Coverage Recovery Plan (towards blocking 90%)

## Current situation

- CI tests are green.
- Coverage gate is temporarily non-blocking at 90% and emits a warning when below threshold.
- Last GitHub run failure pattern was at step "Coverage gate (90% threshold)" after all suites passed.

## Constraint in local container

- Coverage driver is not available in current local container (no xdebug/pcov module loaded), so fine-grained clover analysis cannot be generated locally right now.
- Prioritization below is based on source-to-test mapping and previously tracked low-coverage hotspots.

## Priority 1 (quick win, high impact)

### Add missing Unit tests for Controllers

Controllers in src:
- CanchaController
- CategoriaController
- EquipoController
- ErrorController
- GrupoController
- JugadorController
- MainController
- PartidoController
- SecurityController
- SedeController
- TorneoController
- UsuarioController

Existing Unit controller tests:
- CanchaControllerTest
- CategoriaControllerTest
- MainControllerTest
- PartidoControllerTest
- SecurityControllerTest
- TorneoControllerTest
- UsuarioControllerTest

Missing Unit controller tests (target now):
- EquipoControllerTest
- ErrorControllerTest
- GrupoControllerTest
- JugadorControllerTest
- SedeControllerTest

Expected outcome:
- Improve line coverage in controller layer with low execution cost.
- Reduce reliance on large functional tests for basic branch coverage.

## Priority 2 (business-critical hotspots)

Use previous tracked hotspots as primary targets:
- PartidoController (historically low)
- UsuarioController (historically low)
- TorneoController (below objective)

Actions:
- Add functional assertions for under-covered branches (error and validation paths).
- Add focused unit tests for isolated branch logic where possible.

## Priority 3 (stability and maintainability)

- Continue splitting oversized test files where practical.
- Prefer data providers for repeated authorization/validation matrices.
- Keep security/ownership tests as non-regression guardrails.

## Exit criteria to restore blocking gate

1) At least 3 consecutive CI runs with coverage >= 90%.
2) Unit + Integration + Functional stable (no flaky failures).
3) Revert gate step in workflow to blocking mode at 90%.

## Suggested execution order

1. Implement missing Unit tests for: Equipo, Jugador, Grupo, Sede, Error.
2. Re-run CI and inspect coverage warning trend.
3. Add targeted tests for Partido/Torneo/Usuario branches.
4. Restore blocking coverage gate.
