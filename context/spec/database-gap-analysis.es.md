# Informe de Brechas de Base de Datos de Nexo

Fecha: 2026-05-15

## Resumen Ejecutivo

La base de datos actual ya cubre una base comercial útil: autenticación, equipos, menús, catálogos documentales, empresas, terceros, contactos, servicios, planes, impuestos, cotizaciones, propuestas, cuentas de cobro, facturas, pagos, cuentas bancarias, destinos de pago, plantillas documentales, versiones de plantillas y adjuntos básicos.

Sin embargo, todavía no cumple los requerimientos definidos en `context/spec`. Las brechas principales son estructurales:

1. Las tablas de negocio no tienen un alcance propietario explícito (`team_id`, `user_id` o equivalente), lo que bloquea el aislamiento SaaS y la prevención confiable de IDOR.
2. El esquema mezcla convenciones Laravel (`id`, `*_id`) con nombres heredados (`Id`, `*_Id`), aumentando la complejidad de Eloquent y el riesgo de migraciones.
3. No existe el módulo de Compliance Matrix.
4. No existe seguimiento de documentos requeridos.
5. Los registros de documentos cargados son insuficientes para gestión documental segura.
6. No existen paquetes PDF, documentos generados, orden de combinación, hashes ni historial de paquetes.
7. No existen consecutivos internos ni historial de consecutivos.
8. No existen tablas de auditoría.
9. El manejo de números de factura externa está incompleto.
10. Faltan requerimientos financieros como retenciones, tarifas de servicios y metadata de valoración más rica.

## Fortalezas del Esquema Actual

- Existen tablas core de Laravel: `users`, `sessions`, `cache`, `jobs`, `failed_jobs`.
- Existe base de equipos: `teams`, `team_members`, `team_invitations`.
- Existe flujo comercial: `quotations`, `proposals`, `collection_accounts`, `invoices`, `payments`.
- Existen catálogos: `document_types`, `document_statuses`, `currencies`, `services`, `plans`, `taxes`, `payment_methods`, `banks`.
- `payments` tiene un check constraint útil: un pago referencia una `collection_account` o una `invoice`, pero no ambas.
- Existen `document_templates` y `document_template_versions`.
- Existen adjuntos básicos para cuentas de cobro y facturas.

## Brechas Críticas

### 1. Ownership y Aislamiento de Datos

Las tablas de negocio actuales no incluyen `team_id`, `user_id`, `owner_id`, `created_by` ni `uploaded_by`. Solo las tablas de sesión y membresía de equipos referencian usuarios/equipos.

Tablas afectadas:

- `enterprises`
- `parties`
- `contacts`
- `services`
- `plans`
- `quotations`
- `proposals`
- `collection_accounts`
- `invoices`
- `payments`
- `bank_accounts`
- `payment_destinations`
- `document_templates`
- `collection_account_attachments`
- `invoice_attachments`

Cambio requerido:

- Agregar `team_id` como frontera principal de ownership para datos de negocio.
- Agregar `created_by` donde importe trazabilidad.
- Agregar `uploaded_by` a tablas de carga documental.
- Indexar todas las consultas por owner scope.

Patrón recomendado:

```text
team_id BIGINT UNSIGNED NOT NULL
created_by BIGINT UNSIGNED NULL
updated_by BIGINT UNSIGNED NULL
```

### 2. Convenciones Laravel

Muchas tablas actuales usan primary keys y foreign keys en mayúscula:

- `Id`
- `document_types_Id`
- `enterprises_Id`
- `collection_accounts_Id`
- `payment_methods_Id`

Esto contradice la convención objetivo documentada en `context/spec/database.md` y obliga a sobrescribir `$primaryKey` y llaves de relación en cada modelo.

Cambio requerido:

- Estandarizar el trabajo nuevo en `id` y `*_id`.
- Decidir si se migran las tablas existentes ahora o si se introducen migraciones de compatibilidad más adelante.

Recomendación:

- Como el proyecto sigue en una etapa temprana, conviene normalizar las tablas de negocio existentes antes de construir más módulos.
- Mantener intactas las tablas propias de Laravel/framework.

### 3. Compliance Matrix Faltante

Los requerimientos necesitan reglas documentales configurables por empresa cliente. No existen tablas equivalentes.

Tablas requeridas:

- `compliance_matrices`
- `compliance_requirements`
- `compliance_requirement_conditions`
- `compliance_validation_results`
- `compliance_validation_items`

Campos mínimos:

- `client_enterprise_id`
- `document_type`
- `name`
- `description`
- `is_mandatory`
- `is_blocking`
- `requires_expiration_date`
- `requires_approval`
- `requires_file_upload`
- `can_be_generated_by_system`
- `must_be_attached_separately`
- `must_be_merged_into_final_pdf`
- `merge_order`
- `renewal_frequency`
- `condition_type`
- `condition_payload`
- `is_active`

Ejemplos de condiciones importantes:

- Siempre requerido.
- Requerido cuando el total supera un salario mínimo legal.
- Requerido por servicio.
- Requerido por plan.
- Requerido por estado documental.

### 4. Required Documents y Uploaded Documents

Las tablas actuales `collection_account_attachments` e `invoice_attachments` solo guardan metadata de archivo. No indican qué requisito cumple el archivo, quién lo cargó, si vence, si fue aprobado o si se puede combinar en el paquete final.

Cambio requerido:

- Agregar una tabla genérica `uploaded_documents`.
- Relacionar documentos cargados con requisitos y documentos de negocio.
- Reemplazar las tablas actuales de adjuntos o mantenerlas como compatibilidad temporal.

Tabla recomendada:

- `uploaded_documents`

Campos importantes:

- `team_id`
- `uploaded_by`
- `client_enterprise_id`
- `compliance_requirement_id`
- `documentable_type`
- `documentable_id`
- `original_name`
- `stored_name`
- `disk`
- `path`
- `mime_type`
- `extension`
- `size`
- `hash_sha256`
- `expires_at`
- `approved_at`
- `approved_by`
- `rejected_at`
- `rejected_by`
- `status`
- `metadata`

### 5. PDF Packages y Generated Documents

Los requerimientos exigen documentos generados, paquetes combinados, orden de combinación, integridad por hash, historial de regeneración y versionamiento de paquetes. Estas tablas no existen.

Tablas requeridas:

- `generated_documents`
- `document_packages`
- `document_package_items`

Campos importantes:

- `payment_claim_id` o `collection_account_id`
- `template_version_id`
- `version`
- `disk`
- `path`
- `hash_sha256`
- `generated_by`
- `generated_at`
- `merge_order`
- `source_type`
- `source_id`
- `status`

### 6. Sequence Management

Los números de documento se guardan actualmente en cada tabla documental, con índices únicos como empresa más número. No existe configuración de consecutivos, contador ni historial.

Tablas requeridas:

- `internal_sequences`
- `sequence_counters`
- `sequence_histories`
- `external_invoice_numbers`

Capacidades requeridas:

- Prefijo y sufijo.
- Padding.
- Consecutivo por tipo documental.
- Consecutivo por año.
- Consecutivo opcional por empresa cliente.
- Prevención de duplicados.
- Historial completo.

### 7. Audit Trail

No existen tablas de auditoría. Esto bloquea trazabilidad, evidencia ISO 9001 y monitoreo de seguridad.

Tablas requeridas:

- `activity_logs`
- `audit_logs`

Eventos mínimos:

- Documento creado.
- Documento actualizado.
- Estado cambiado.
- Archivo cargado.
- Archivo descargado.
- Compliance validado.
- PDF generado.
- PDF combinado.
- Cuenta de cobro radicada.
- Pago registrado.
- Consecutivo generado.
- Acceso denegado por seguridad.

### 8. Requerimientos de Facturación

La tabla actual `invoices` tiene `number` y `authorization_number`, pero los requerimientos piden captura de números externos de factura, prevención de duplicados y asociación con cuentas de cobro o paquetes documentales.

Cambio requerido:

- Agregar `external_invoice_numbers`.
- Agregar campos de tipo/origen de factura.
- Relacionar PDFs de factura externa mediante `uploaded_documents`.

### 9. Finance y Valuation

El esquema actual tiene impuestos y totales, pero le faltan conceptos de valoración más ricos exigidos por la especificación.

Adiciones recomendadas:

- `service_rates`
- `retention_rates`
- campos de retención a nivel de item
- campos de pricing type en `services`
- soporte de conversión de moneda si multi-currency entra en v1.2

## Plan Recomendado de Migraciones

### Fase 1: Estabilizar Convenciones y Ownership

1. Elegir frontera final de ownership: recomendado `team_id`.
2. Agregar `team_id` a todas las tablas de negocio.
3. Agregar `created_by`, `updated_by` y campos de usuario de carga donde aplique.
4. Normalizar nombres de tablas y columnas a convención Laravel si el proyecto todavía puede absorber el cambio.
5. Actualizar modelos Eloquent y pruebas.

### Fase 2: Compliance Matrix

1. Crear `compliance_matrices`.
2. Crear `compliance_requirements`.
3. Crear `compliance_requirement_conditions`.
4. Crear `compliance_validation_results`.
5. Crear `compliance_validation_items`.

### Fase 3: Documentos Seguros

1. Crear `uploaded_documents`.
2. Migrar o conectar `collection_account_attachments` e `invoice_attachments`.
3. Agregar hash, disk, path, owner, status, expiración y metadata de aprobación.
4. Exigir storage privado desde el código de aplicación.

### Fase 4: Document Packages y PDFs

1. Crear `generated_documents`.
2. Crear `document_packages`.
3. Crear `document_package_items`.
4. Agregar versionamiento de paquetes e integridad por hash.

### Fase 5: Consecutivos y Facturas Externas

1. Crear `internal_sequences`.
2. Crear `sequence_counters`.
3. Crear `sequence_histories`.
4. Crear `external_invoice_numbers`.
5. Reemplazar generación manual de números por `SequenceGenerator`.

### Fase 6: Auditoría y Evidencia ISO 9001

1. Crear `activity_logs`.
2. Crear `audit_logs`.
3. Registrar eventos críticos de negocio, documentos y seguridad.

## Tablas Objetivo Sugeridas

| Área de Requerimiento | Soporte Actual | Cambio Requerido |
| --- | --- | --- |
| Aislamiento SaaS | Equipos parciales | Agregar `team_id` a tablas de negocio |
| Requisitos por cliente | No existe | Agregar tablas de Compliance Matrix |
| Documentos requeridos | No existe | Agregar requisitos y resultados de validación |
| Archivos cargados | Adjuntos básicos | Agregar modelo genérico de carga segura |
| Paquetes PDF | No existe | Agregar documentos generados y paquetes |
| Consecutivos | No existe | Agregar tablas de secuencias |
| Audit trail | No existe | Agregar activity/audit logs |
| Facturas externas | Parcial | Agregar registro de números externos |
| Convenciones Laravel | Inconsistente | Normalizar `Id`/`*_Id` a `id`/`*_id` |

## Notas de Implementación

- Las nuevas migraciones deben crearse con Artisan y seguir convenciones Laravel.
- Usar `foreignId()->constrained()` cuando los nombres de tablas sigan convenciones.
- Agregar nombres explícitos de índices solo cuando sea necesario.
- Usar columnas JSON para payloads flexibles de reglas, metadata de paquetes y detalles de validación.
- Agregar pruebas antes de cada grupo de migraciones.
- No construir Compliance ni PDF Packages sobre las tablas actuales de adjuntos sin rediseñar primero el modelo documental.

## Decisión Más Urgente

Antes de implementar el siguiente módulo de negocio, hay que decidir si Nexo será team-scoped o user-scoped.

Decisión recomendada:

```text
Los datos de negocio de Nexo deben ser team-scoped usando team_id.
```

Razón:

- La aplicación ya tiene equipos.
- Permite futuros asistentes, auditores y flujos multiusuario.
- Da una frontera estable de autorización para Policies.
