# Priorizacion De Tests

## Objetivo
Definir que tests son imprescindibles, recomendables o prescindibles para mantener calidad sin inflar mantenimiento.

## Criterios
- Imprescindible: valida seguridad, permisos, ownership, persistencia critica o flujos core de negocio.
- Recomendable: valida UX/redirect/mensajes y ramas utiles, pero sin impacto critico directo.
- Prescindible/Revisar: fuerza errores artificiales (ej. Throwable por payload mal formado), duplica cobertura o es fragil ante cambios menores de implementacion.

## Imprescindibles (mantener siempre)
- Matriz de acceso por rol y anonimo en rutas admin de torneos/usuarios.
  - tests/Functional/SecurityAccessFunctionalTest.php
- Ownership de torneo (admin no creador no puede editar torneo/reglamento).
  - tests/Functional/AdminBusinessFlowFunctionalTest.php
  - testAdminNoCreadorNoAccedeAEditarTorneoYRedirigeALogin
  - testAdminNoCreadorNoAccedeAEditarReglamentoYRedirigeALogin
- Flujos de persistencia con efecto de negocio:
  - crear/editar/eliminar categoria, sede, equipo, jugador
  - cargar resultado de partido y cambios de estado
  - baja de equipo y cancelacion de partidos
- Flujo inicial de seguridad:
  - login redirige a alta de primer usuario si no hay usuarios
  - alta del primer admin
- Unitarios de SecurityController para login/logout base:
  - tests/Unit/Controller/SecurityControllerTest.php

## Recomendables (mantener si no generan friccion)
- GET de formularios admin (crear/editar) para evitar regresiones de routing/template.
- Validaciones funcionales de mensajes de negocio (duplicados, formato invalido, campos obligatorios).
- Cobertura de vistas publicas (home, torneo, categoria) con asserts de contenido estable.

## Prescindibles O A Revisar Primero Si Hay Que Recortar
- Tests que fuerzan catch(Throwable) con payload no realista de usuario:
  - tests/Functional/SecurityAccessFunctionalTest.php
- Tests que repiten la misma regla de autorizacion sin agregar nueva rama observable.
- Asserts de textos frágiles de plantilla (si cambian seguido por copy/UI).

## Plan De Limpieza Seguro
1. Mantener intactos todos los tests de seguridad/roles/ownership y persistencia critica.
2. Marcar como "candidatos" los tests Throwable artificiales y duplicados de redireccion equivalente.
3. Remover candidatos de a uno, ejecutando:
   - tests/Functional/SecurityAccessFunctionalTest.php
   - tests/Functional/AdminBusinessFlowFunctionalTest.php
   - composer test:coverage
4. Aceptar remocion solo si no cae cobertura en controllers criticos ni se pierde una rama funcional real.

## Estado Actual (ultima medicion)
- Cobertura global lineas: 90.02%
- UsuarioController lineas: 81.54%
- PartidoController lineas: 75.78%
- TorneoController lineas: 88.76%

## Limpieza Aplicada
- Se eliminaron los dos tests artificiales de Throwable en SecurityAccessFunctionalTest.
- Se conservó la cobertura de alto valor en access control, ownership y flujos core.
- Se agregó un test estable de edición de partido con programación válida.
- Se agregó un test estable de carga de resultado por GET con equipos asignados.
- Se agregó un test estable de creación de torneo con categoría y sede en un solo POST.
- Se agregaron tests unitarios de manejo de excepciones en UsuarioController y CategoriaController para cubrir degradación controlada (flash + redirect/render).

## Nota De Practica
Preferir pocos tests de alto valor por comportamiento observable sobre muchos tests que cubren lineas por excepciones artificiales.
