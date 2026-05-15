# Resumen Ejecutivo de Evaluación de Base de Datos de Nexo

Fecha: 2026-05-15

## Propósito

Este documento resume la evaluación de la base de datos actual de Nexo y propone las recomendaciones y el plan de trabajo para convertir la plataforma en una solución sólida, escalable, segura y preparada para operar como SaaS profesional.

El informe técnico completo está en:

- `context/spec/database-gap-analysis.es.md`
- `context/spec/database-gap-analysis.md`

## Conclusión General

Nexo ya tiene una base prometedora. La estructura actual cubre buena parte del flujo comercial: empresas, terceros, contactos, servicios, planes, impuestos, cotizaciones, propuestas, cuentas de cobro, facturas, pagos, cuentas bancarias, destinos de pago, plantillas y adjuntos básicos.

La oportunidad grande está en dar el siguiente salto: pasar de una base de datos funcional a una arquitectura de producto SaaS con aislamiento de datos, trazabilidad, cumplimiento documental configurable, paquetes PDF, auditoría y reglas de negocio robustas.

Para lograrlo, no conviene seguir agregando pantallas encima del esquema actual sin antes corregir los fundamentos. Si se construyen módulos críticos como Compliance, documentos requeridos o paquetes PDF sobre la estructura actual de adjuntos, después será más costoso corregir seguridad, ownership y trazabilidad.

## Estado Actual

### Lo Que Ya Está Bien Encaminado

- Existe autenticación y base de equipos con `users`, `teams`, `team_members` y `team_invitations`.
- Existe flujo comercial principal con `quotations`, `proposals`, `collection_accounts`, `invoices` y `payments`.
- Existen catálogos base como `document_types`, `document_statuses`, `currencies`, `services`, `plans`, `taxes`, `payment_methods` y `banks`.
- Existen cuentas bancarias y destinos de pago.
- Existen plantillas documentales y versiones.
- Existen adjuntos básicos para cuentas de cobro y facturas.
- `payments` ya incluye una regla importante: un pago se aplica a una cuenta de cobro o a una factura, pero no a ambas.

### Lo Que Todavía No Cumple Los Requerimientos

- No hay `team_id` o `user_id` en tablas de negocio, por lo tanto falta una frontera clara de ownership.
- El esquema mezcla nombres tipo `Id` y `*_Id` con nombres Laravel tipo `id` y `*_id`.
- No existe `Compliance Matrix`.
- No existe modelo de `Required Documents`.
- No existe tabla genérica y segura de `Uploaded Documents`.
- No existen `Document Packages` ni `Generated Documents`.
- No existen consecutivos internos configurables.
- No existe auditoría de eventos críticos.
- No existe registro formal de números externos de factura.
- Falta soporte financiero más completo para retenciones, tarifas por servicio y reglas avanzadas de valoración.

## Recomendación Principal

Antes de construir más módulos funcionales, Nexo debe estabilizar su modelo de datos.

La recomendación central es:

```text
Nexo debe ser una plataforma team-scoped, usando team_id como frontera principal de ownership para todos los datos de negocio.
```

Razones:

- La aplicación ya tiene equipos.
- Permite crecer hacia roles como administrador, asistente, auditor o colaborador.
- Facilita Policies de Laravel y previene IDOR.
- Es más flexible que un modelo limitado solo a `user_id`.
- Encaja mejor con un SaaS profesional.

## Decisiones Arquitectónicas Necesarias

### 1. Alcance de Datos

Decisión recomendada:

- Usar `team_id` en tablas de negocio.
- Usar `created_by`, `updated_by`, `uploaded_by`, `approved_by` y `generated_by` para trazabilidad.

### 2. Convenciones de Base de Datos

Decisión recomendada:

- Normalizar nuevas tablas a `id` y `*_id`.
- Si el proyecto todavía no tiene datos productivos reales, normalizar también las tablas existentes.

Esto reduce complejidad en Eloquent y evita tener que declarar `$primaryKey = 'Id'` y llaves manuales en todos los modelos.

### 3. Modelo Documental

Decisión recomendada:

- No seguir creciendo `collection_account_attachments` e `invoice_attachments` como tablas principales.
- Crear un modelo genérico `uploaded_documents`.
- Relacionar cada archivo con:
  - owner scope,
  - empresa cliente,
  - documento de negocio,
  - requisito documental,
  - estado,
  - vencimiento,
  - aprobación,
  - hash,
  - usuario que lo cargó.

### 4. Compliance Matrix

Decisión recomendada:

- Tratar la matriz documental como módulo central, no como accesorio.
- Cada empresa cliente debe poder configurar sus reglas.
- Las reglas deben admitir condiciones, por ejemplo:
  - siempre requerido,
  - requerido si el valor supera un salario mínimo,
  - requerido por servicio,
  - requerido por plan,
  - requerido por tipo de documento.

### 5. Paquetes PDF

Decisión recomendada:

- Separar documentos cargados, documentos generados y paquetes finales.
- Guardar versión, orden de combinación, hash, usuario generador, fecha y estado.

## Plan de Trabajo Sugerido

### Fase 0: Congelar Dirección Técnica

Objetivo: evitar seguir construyendo sobre una base que luego toque rehacer.

Acciones:

1. Confirmar que el modelo será `team_id` como ownership principal.
2. Confirmar si se normalizarán las tablas existentes a convención Laravel.
3. Definir nombres finales: mantener los módulos actuales (`enterprises`, `collection_accounts`) o migrar lenguaje de producto hacia `client_companies` y `payment_claims`.
4. Crear issues técnicos para cada fase de migración.

Resultado esperado:

- Decisiones documentadas.
- Backlog técnico ordenado.
- Cero ambigüedad antes de tocar migrations.

### Fase 1: Corregir Fundamentos de SaaS y Laravel

Objetivo: dejar la base lista para seguridad, multiusuario y desarrollo limpio.

Acciones:

1. Agregar `team_id` a tablas de negocio.
2. Agregar campos de trazabilidad: `created_by`, `updated_by`, `uploaded_by`, según aplique.
3. Agregar índices por `team_id` y relaciones principales.
4. Normalizar columnas a `id` y `*_id` si el proyecto aún está en etapa temprana.
5. Actualizar modelos, factories, seeders y tests.

Resultado esperado:

- Datos aislados por equipo.
- Policies más simples.
- Eloquent más limpio.
- Menor riesgo de IDOR.

### Fase 2: Construir Compliance Matrix

Objetivo: permitir requisitos documentales configurables por empresa cliente.

Tablas sugeridas:

- `compliance_matrices`
- `compliance_requirements`
- `compliance_requirement_conditions`
- `compliance_validation_results`
- `compliance_validation_items`

Acciones:

1. Crear catálogo de requisitos documentales.
2. Crear matriz por empresa cliente.
3. Permitir reglas obligatorias, opcionales y bloqueantes.
4. Implementar condiciones configurables.
5. Validar requisitos aplicables a una cuenta de cobro.

Resultado esperado:

- Cada cliente puede exigir documentos diferentes.
- El sistema sabe qué falta antes de radicar.
- Se puede bloquear una cuenta de cobro incompleta.

### Fase 3: Rediseñar Gestión Documental

Objetivo: manejar documentos de forma segura, trazable y compatible con Compliance.

Tabla principal sugerida:

- `uploaded_documents`

Acciones:

1. Crear tabla genérica de documentos cargados.
2. Relacionar documentos con requisitos, empresas y documentos de negocio.
3. Guardar `disk`, `path`, `mime_type`, `size`, `hash_sha256`, `status` y fechas de vencimiento.
4. Agregar aprobación/rechazo documental.
5. Usar storage privado y descargas autorizadas.

Resultado esperado:

- Archivos seguros.
- Documentos vinculados a requisitos concretos.
- Evidencia documental verificable.

### Fase 4: Generación y Combinación de PDFs

Objetivo: producir paquetes documentales finales listos para radicación.

Tablas sugeridas:

- `generated_documents`
- `document_packages`
- `document_package_items`

Acciones:

1. Registrar documentos generados desde plantillas.
2. Registrar paquetes finales.
3. Guardar orden de combinación.
4. Guardar hash de integridad.
5. Versionar paquetes regenerados.

Resultado esperado:

- Paquetes PDF auditables.
- Historial de generación.
- Control de integridad.
- Base lista para automatizar radicación o entrega.

### Fase 5: Consecutivos y Facturas Externas

Objetivo: formalizar numeración, trazabilidad y prevención de duplicados.

Tablas sugeridas:

- `internal_sequences`
- `sequence_counters`
- `sequence_histories`
- `external_invoice_numbers`

Acciones:

1. Configurar prefijos, sufijos, padding y año.
2. Generar números por tipo documental.
3. Registrar historial de cada número generado.
4. Controlar números externos de factura por empresa cliente.

Resultado esperado:

- Consecutivos confiables.
- Auditoría de numeración.
- Menos errores manuales.

### Fase 6: Auditoría, Seguridad e ISO 9001

Objetivo: convertir Nexo en una plataforma con evidencia, control y trazabilidad profesional.

Tablas sugeridas:

- `activity_logs`
- `audit_logs`

Acciones:

1. Registrar eventos críticos de negocio.
2. Registrar accesos y descargas documentales.
3. Registrar validaciones de Compliance.
4. Registrar generación y combinación de PDFs.
5. Registrar cambios de estado.

Resultado esperado:

- Evidencia documental.
- Trazabilidad para auditoría.
- Base para reportes ISO 9001.
- Mejor monitoreo de seguridad.

## Prioridad Recomendada

| Prioridad | Trabajo | Motivo |
| --- | --- | --- |
| 1 | Definir `team_id` como ownership | Sin esto no hay SaaS seguro |
| 2 | Normalizar convenciones Laravel | Reduce deuda técnica inmediata |
| 3 | Crear Compliance Matrix | Es diferencial central del producto |
| 4 | Crear `uploaded_documents` | Es base de seguridad documental |
| 5 | Crear PDF Packages | Permite entregar valor real al usuario |
| 6 | Crear secuencias | Evita desorden en numeración |
| 7 | Crear auditoría | Soporta seguridad, trazabilidad e ISO 9001 |

## Riesgos Si No Se Corrige Ahora

- Crecerá la deuda técnica por modelos con llaves no convencionales.
- Será difícil aplicar Policies limpias y seguras.
- Los documentos cargados no tendrán trazabilidad suficiente.
- Compliance quedará atado a adjuntos genéricos y será difícil de validar.
- La generación de PDFs no tendrá historial ni control de integridad.
- Se complicará migrar a un SaaS multiusuario real.
- Los futuros módulos dependerán de decisiones inconsistentes.

## La Ruta Para Hacer de Nexo una Plataforma Sobresaliente

Nexo no debe limitarse a ser un CRUD de cotizaciones y cuentas de cobro. Su valor diferencial debe estar en resolver un problema real: cada empresa cliente pide documentos distintos, con reglas distintas, en momentos distintos, y el usuario necesita cumplir sin improvisar.

La plataforma debe convertirse en un sistema que:

- Entiende qué exige cada cliente.
- Valida automáticamente qué documentos faltan.
- Bloquea errores antes de radicar.
- Genera documentos profesionales desde plantillas.
- Combina soportes en paquetes PDF ordenados.
- Guarda evidencia de cada acción.
- Protege documentos privados.
- Permite auditar el proceso.
- Reduce reprocesos y rechazos de cuentas de cobro.

Ese es el camino para convertir Nexo en una plataforma de alto valor: no solo generar documentos, sino garantizar cumplimiento documental, trazabilidad y control.

## Siguiente Paso Recomendado

Crear una épica técnica en GitHub para la Fase 1:

```text
[Épica Técnica] Estabilizar ownership, convenciones Laravel y base SaaS de Nexo
```

Criterios de aceptación sugeridos:

- Todas las tablas de negocio tienen `team_id`.
- Las consultas críticas filtran por equipo.
- Los modelos principales tienen relaciones de ownership.
- Existen tests de aislamiento de datos entre equipos.
- Se define una decisión final sobre normalización de `Id`/`*_Id`.
- Las nuevas migrations usan convención Laravel.

Después de esa fase, el siguiente bloque debe ser:

```text
[Épica Técnica] Implementar Compliance Matrix y documentos requeridos por empresa cliente
```

Ese orden evita construir funcionalidades visibles sobre una base insegura o difícil de mantener.
