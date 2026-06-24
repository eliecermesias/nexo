# Nexo / Nexalvia - Especificación funcional y técnica

**Código base:** NX-BASE-001  
**Versión:** 0.1  
**Estado:** Borrador inicial versionado  
**Repositorio:** eliecermesias/nexo  
**Framework:** Laravel 13  
**Base de datos:** MySQL  
**Frontend:** Blade, Livewire, Flux, Tailwind CSS  

---

## 1. Propósito del sistema

Nexo / Nexalvia es una aplicación web para gestionar documentos comerciales generados por una persona o equipo hacia empresas cliente. El sistema debe permitir administrar cotizaciones, propuestas, cuentas de cobro y facturas, incluyendo empresas, monedas, servicios, plantillas, consecutivos, cuentas bancarias, adjuntos, documentos requeridos y estados de gestión.

El sistema debe mantener trazabilidad entre especificaciones, historias de usuario, tareas de ClickUp, issues de GitHub, ramas de desarrollo y pull requests.

---

## 2. Alcance funcional inicial

El sistema debe permitir:

1. Gestionar empresas cliente.
2. Gestionar monedas y tipos de moneda.
3. Gestionar bancos.
4. Gestionar cuentas bancarias.
5. Gestionar destinos de pago.
6. Gestionar servicios o ítems comerciales.
7. Gestionar plantillas de documentos.
8. Configurar consecutivos por tipo de documento.
9. Generar cotizaciones.
10. Generar propuestas.
11. Generar cuentas de cobro.
12. Generar facturas.
13. Adjuntar documentos requeridos a documentos generados.
14. Gestionar documentos requeridos por empresa.
15. Generar documentos PDF.
16. Controlar estados de documentos.
17. Restringir el acceso a datos por equipo y usuario.
18. Integrar el proceso de desarrollo con GitHub y ClickUp.

---

## 3. Contexto de seguridad y propiedad de datos

El proyecto actual usa un starter kit con equipos. Por tanto, la propiedad funcional de los registros debe manejarse preferiblemente mediante `team_id`, registrando adicionalmente el usuario creador mediante `created_by`.

Regla base:

```text
Cada registro funcional pertenece a un equipo mediante team_id.
Cada registro debe registrar el usuario que lo creó mediante created_by.
Un usuario solo puede consultar información de los equipos a los que pertenece.
Las acciones deben estar protegidas mediante middleware, policies y validaciones de propiedad.
```

Campos recomendados para entidades principales:

```text
id
team_id
created_by
...
created_at
updated_at
```

---

## 4. Módulos del sistema

### 4.1 Comercial

Incluye:

- Cotizaciones
- Propuestas
- Cuentas de cobro
- Facturas
- Servicios o ítems
- Estados comerciales
- Generación de PDF
- Documentos entregados
- Adjuntos por documento generado

### 4.2 Personal

Incluye:

- Datos personales
- Perfil
- Documentos adjuntos personales
- Información tributaria
- Firma o imagen de firma
- Logo personal o empresarial

### 4.3 Documentos

Incluye:

- Plantillas
- Repositorio de documentos
- Tipos de documento
- Documentos requeridos por empresa
- Adjuntos
- Historial de documentos generados

### 4.4 Configuración

Incluye:

- Empresas
- Monedas
- Bancos
- Cuentas bancarias
- Destinos de pago
- Consecutivos
- Estados
- Parámetros generales

### 4.5 Integraciones

Incluye:

- GitHub
- ClickUp
- Relación entre historia de usuario, tarea, issue, rama y pull request

---

## 5. Tipos de documentos comerciales

El usuario debe poder generar:

- Cotizaciones
- Propuestas
- Cuentas de cobro
- Facturas

Cada documento debe tener:

- Empresa asociada
- Tipo de documento
- Consecutivo propio
- Fecha de emisión
- Fecha de vencimiento, si aplica
- Moneda
- Ítems
- Subtotal
- Descuentos, si aplica
- Impuestos, si aplica
- Total
- Estado
- Plantilla usada
- PDF generado
- Adjuntos asociados
- Cuenta bancaria o destino de pago, cuando aplique

---

## 6. Estados de documentos

Estados mínimos:

- Borrador
- Generado
- Enviado
- Aprobado
- Rechazado
- Pagado
- Anulado

### Cotizaciones

Estados permitidos:

- Borrador
- Generado
- Enviado
- Aprobado
- Rechazado
- Anulado

### Propuestas

Estados permitidos:

- Borrador
- Generado
- Enviado
- Aprobado
- Rechazado
- Anulado

### Cuentas de cobro

Estados permitidos:

- Borrador
- Generado
- Enviado
- Pagado
- Anulado

### Facturas

Estados permitidos:

- Borrador
- Generado
- Enviado
- Pagado
- Anulado

---

## 7. Consecutivos

Cada tipo de documento debe manejar su propio consecutivo.

Tipos mínimos:

- Cotización
- Propuesta
- Cuenta de cobro
- Factura

El consecutivo debe permitir configurar:

- Tipo de documento
- Prefijo
- Año
- Longitud del número
- Número inicial
- Número final
- Número actual
- Estado
- Formato final

Formato sugerido:

```text
{PREFIJO}-{AÑO}-{NUMERO_CON_CEROS}
```

Ejemplo:

```text
COT-2026-000001
```

Reglas:

- No se puede generar un documento sin consecutivo activo.
- El consecutivo debe incrementar automáticamente al generar un documento.
- No se debe repetir consecutivo por equipo y tipo de documento.
- El número actual no puede superar el número final.
- El consecutivo no debe reutilizarse cuando un documento sea anulado.
- El sistema debe conservar trazabilidad del consecutivo usado.

---

## 8. Empresas

El usuario debe poder administrar empresas cliente.

Campos sugeridos:

- id
- team_id
- created_by
- name
- tax_id
- email
- phone
- address
- city
- country
- contact_name
- contact_email
- status
- created_at
- updated_at

Reglas:

- Una empresa pertenece a un equipo.
- Una empresa puede tener documentos requeridos.
- Una empresa puede estar activa o inactiva.
- Solo empresas activas deben aparecer al generar documentos.

---

## 9. Monedas

Campos sugeridos:

- id
- team_id
- created_by
- code
- name
- symbol
- status
- created_at
- updated_at

Reglas:

- Los servicios deben asociarse a una moneda.
- Los documentos comerciales deben conservar la moneda seleccionada.
- Solo monedas activas deben aparecer en formularios de documentos.

---

## 10. Bancos y cuentas bancarias

### Bancos

Campos sugeridos:

- id
- team_id
- created_by
- name
- code
- status
- created_at
- updated_at

### Cuentas bancarias

Campos sugeridos:

- id
- team_id
- created_by
- bank_id
- currency_id
- account_type
- account_number
- holder_name
- holder_identification
- is_default
- status
- created_at
- updated_at

Reglas:

- Una cuenta bancaria pertenece a un banco.
- Solo debe existir una cuenta bancaria predeterminada por equipo.
- Las cuentas bancarias pueden asociarse a documentos como destino de pago.

---

## 11. Servicios o ítems

Campos sugeridos:

- id
- team_id
- created_by
- currency_id
- name
- description
- unit_value
- status
- created_at
- updated_at

Reglas:

- Solo servicios activos deben estar disponibles para documentos.
- El valor del servicio puede copiarse al documento.
- El valor copiado al documento debe poder ajustarse sin modificar el servicio base.

---

## 12. Plantillas de documentos

Campos sugeridos:

- id
- team_id
- created_by
- document_type
- name
- content
- header_content
- footer_content
- is_default
- status
- created_at
- updated_at

Reglas:

- Cada plantilla pertenece a un tipo de documento.
- Solo puede existir una plantilla predeterminada activa por equipo y tipo de documento.
- Las plantillas deben permitir variables dinámicas.

Variables sugeridas:

```text
{{nombre_usuario}}
{{documento_usuario}}
{{empresa_nombre}}
{{empresa_nit}}
{{fecha_documento}}
{{consecutivo}}
{{total}}
{{moneda}}
{{banco}}
{{numero_cuenta}}
{{items}}
```

---

## 13. Documentos requeridos y adjuntos

Cada empresa puede tener configurados documentos requeridos para entregar con cada documento generado.

Campos sugeridos para documentos requeridos:

- id
- team_id
- created_by
- company_id
- document_type
- name
- description
- is_required
- validity_days
- status
- created_at
- updated_at

Campos sugeridos para adjuntos:

- id
- team_id
- created_by
- attachable_type
- attachable_id
- required_document_id
- name
- file_path
- file_type
- file_size
- expiration_date
- status
- created_at
- updated_at

Reglas:

- Los adjuntos deben pertenecer al equipo.
- Se debe validar tipo y tamaño de archivo.
- Un usuario no debe acceder a adjuntos de equipos a los que no pertenece.
- El sistema debe mostrar documentos pendientes, entregados, vencidos o aprobados.

---

## 14. Entidades principales

Entidades iniciales:

- User
- Team
- UserProfile
- Company
- Currency
- Bank
- BankAccount
- PaymentDestination
- ServiceItem
- Consecutive
- DocumentTemplate
- CommercialDocument
- CommercialDocumentItem
- RequiredDocument
- DocumentAttachment
- IntegrationAccount
- WorkItemTrace

---

## 15. Reglas de interfaz

Cada funcionalidad debe cumplir:

- Tener una vista `index.blade.php` o componente Livewire equivalente.
- Mostrar listado en formato tabla dinámica.
- Usar controles visuales estilo HeroIcons o Flux Icons.
- Mostrar tooltips descriptivos.
- Permitir acciones de ver, editar y eliminar mediante modal.
- Refrescar la tabla después de crear, editar, eliminar o cambiar estado.
- Mantener el esquema de colores definido para el proyecto.
- Ser responsiva.

Acciones mínimas por fila:

- Ver
- Editar
- Eliminar
- Cambiar estado
- Descargar PDF, cuando aplique
- Adjuntar documento, cuando aplique

---

## 16. Arquitectura de desarrollo

Regla base:

```text
Livewire Component o Controller
→ Validation / Form Request
→ Service
→ Policy
→ Model
```

Los componentes Livewire o controladores no deben contener lógica de negocio compleja.

Cada funcionalidad debe incluir, según aplique:

- Migration
- Model
- Controller o Livewire Component
- Form Request o validación separada
- Policy
- Service
- Factory
- Seeder
- Vista `index.blade.php`
- Rutas
- Pruebas básicas

---

## 17. Rutas sugeridas

Bajo contexto de equipo:

```text
/{current_team}/companies
/{current_team}/currencies
/{current_team}/banks
/{current_team}/bank-accounts
/{current_team}/payment-destinations
/{current_team}/service-items
/{current_team}/consecutives
/{current_team}/document-templates
/{current_team}/commercial-documents
/{current_team}/required-documents
/{current_team}/document-attachments
/{current_team}/integrations/github
/{current_team}/integrations/clickup
```

---

## 18. Integración GitHub y ClickUp

El sistema debe permitir trazabilidad entre:

- Historia de usuario
- Tarea ClickUp
- Issue GitHub
- Rama Git
- Pull Request
- Estado funcional

Formato sugerido de códigos:

```text
NX-{MODULO}-{NUMERO}
```

Ejemplos:

```text
NX-CONF-001
NX-COM-001
NX-DOC-001
NX-INT-001
```

Formato sugerido de ramas:

```text
feature/NX-CONF-001-company-crud
feature/NX-COM-001-quotation-crud
fix/NX-CONF-002-consecutive-validation
```

---

## 19. Requerimientos no funcionales

### Seguridad

- Autenticación obligatoria.
- Middleware de pertenencia a equipo.
- Policies por entidad.
- Validación de entradas.
- Protección CSRF.
- Cifrado de tokens de integración.
- Validación de archivos adjuntos.
- Restricción de acceso por `team_id`.
- Buenas prácticas OWASP.

### Usabilidad

- Interfaz clara y modular.
- Tablas dinámicas.
- Modales para acciones CRUD.
- Tooltips.
- Badges de estado.
- Mensajes de confirmación.
- Diseño responsivo.

### Mantenibilidad

- Separación de responsabilidades.
- Services para lógica de negocio.
- Policies para autorización.
- Componentes reutilizables.
- Convenciones claras.
- Pruebas automatizadas.

---

## 20. Orden recomendado de desarrollo

1. Documentación base.
2. README del proyecto.
3. Estrategia de propiedad por equipo.
4. Layout funcional del dashboard.
5. Perfil del usuario.
6. Empresas.
7. Monedas.
8. Bancos.
9. Cuentas bancarias.
10. Servicios o ítems.
11. Consecutivos.
12. Plantillas.
13. Documentos requeridos.
14. Cotizaciones.
15. Propuestas.
16. Cuentas de cobro.
17. Facturas.
18. Adjuntos.
19. Generación PDF.
20. Integración ClickUp.
21. Integración GitHub.
