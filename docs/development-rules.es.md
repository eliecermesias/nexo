# Nexo / Nexalvia - Reglas de desarrollo

**Código base:** NX-BASE-001  
**Versión:** 0.1  
**Estado:** Borrador inicial versionado  

---

## 1. Principios generales

El desarrollo del proyecto debe seguir un enfoque guiado por especificaciones. Ninguna funcionalidad funcional principal debe implementarse sin una historia de usuario, criterios de aceptación y reglas de seguridad mínimas.

Regla de oro:

```text
Primero especificar, luego construir.
```

---

## 2. Stack técnico

- PHP 8.3 o superior
- Laravel 13
- MySQL
- Blade
- Livewire
- Flux
- Tailwind CSS
- HeroIcons o iconografía equivalente de Flux
- Laravel Fortify
- Laravel Sail, cuando aplique
- PHPUnit
- Laravel Pint

---

## 3. Arquitectura recomendada

El proyecto puede usar componentes Livewire para interfaz dinámica, pero la lógica de negocio debe estar separada.

Flujo recomendado:

```text
Livewire Component o Controller
→ Validation / Form Request
→ Service
→ Policy
→ Model
```

Reglas:

- Los controladores o componentes Livewire coordinan la interacción.
- Los Services contienen lógica de negocio.
- Las Policies contienen autorización.
- Las validaciones deben estar separadas y ser explícitas.
- Los Models no deben acumular lógica de aplicación pesada.
- Las consultas deben respetar siempre el equipo activo.

---

## 4. Propiedad de datos

Como el proyecto usa equipos, las entidades funcionales deben manejar propiedad por equipo.

Campos obligatorios recomendados:

```text
team_id
created_by
created_at
updated_at
```

Reglas:

- Toda entidad funcional principal debe tener `team_id`.
- Toda entidad funcional principal debe tener `created_by`.
- Las consultas deben filtrar por el equipo activo.
- Las policies deben validar que el usuario pertenezca al equipo.
- No se debe permitir acceso cruzado entre equipos.

Entidades afectadas:

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

## 5. Convenciones de nombres

### Controladores

Usar nombre singular o de recurso claro:

```text
CompanyController
CurrencyController
BankController
BankAccountController
ServiceItemController
ConsecutiveController
DocumentTemplateController
CommercialDocumentController
RequiredDocumentController
DocumentAttachmentController
```

### Services

```text
App\Services\Companies\CompanyService
App\Services\Documents\CommercialDocumentService
App\Services\Consecutives\ConsecutiveService
App\Services\Integrations\GitHubIntegrationService
App\Services\Integrations\ClickUpIntegrationService
```

### Livewire Components

```text
App\Livewire\Companies\Index
App\Livewire\Currencies\Index
App\Livewire\Banks\Index
App\Livewire\CommercialDocuments\Index
```

### Vistas

Cada funcionalidad debe tener una vista `index.blade.php` o vista equivalente usada por Livewire.

Ejemplos:

```text
resources/views/companies/index.blade.php
resources/views/currencies/index.blade.php
resources/views/banks/index.blade.php
resources/views/commercial-documents/index.blade.php
```

---

## 6. Rutas

Las rutas funcionales deben estar dentro del contexto de equipo.

Ejemplo:

```php
Route::prefix('{current_team}')
    ->middleware(['auth', 'verified', EnsureTeamMembership::class])
    ->group(function () {
        Route::resource('companies', CompanyController::class);
        Route::resource('currencies', CurrencyController::class);
    });
```

Rutas sugeridas:

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

## 7. Reglas de interfaz

Cada funcionalidad debe cumplir:

- Tener listado principal en tabla.
- Soportar búsqueda o filtrado dinámico.
- Usar paginación.
- Tener acciones por fila.
- Usar iconos HeroIcons o Flux.
- Mostrar tooltip por acción.
- Abrir modales para ver, editar y eliminar.
- Refrescar la tabla después de crear, editar, eliminar o cambiar estado.
- Mantener el esquema de colores del proyecto.
- Ser responsiva.

Acciones mínimas:

```text
Ver
Editar
Eliminar
Cambiar estado
Descargar PDF, si aplica
Adjuntar documento, si aplica
```

---

## 8. Reglas para CRUD

Cada CRUD debe incluir:

- Migration
- Model
- Controller o Livewire Component
- Service
- Policy
- Validación
- Vista principal
- Pruebas básicas

No se debe crear un controlador gigante para funcionalidades no relacionadas.

Correcto:

```text
CompanyController
CurrencyController
BankController
```

Incorrecto:

```text
ConfigurationController con toda la lógica de empresas, bancos, monedas y consecutivos.
```

---

## 9. Validaciones mínimas

Las validaciones deben estar centralizadas mediante Form Requests o reglas Livewire explícitas.

Ejemplo para empresas:

```text
name: required|string|max:255
tax_id: nullable|string|max:50
email: nullable|email|max:255
status: required|in:active,inactive
```

Ejemplo para consecutivos:

```text
document_type: required|string
prefix: required|string|max:20
year: required|integer
number_length: required|integer|min:1|max:20
initial_number: required|integer|min:1
final_number: required|integer|gte:initial_number
current_number: required|integer|gte:initial_number|lte:final_number
status: required|in:active,inactive
```

---

## 10. Policies y autorización

Toda entidad funcional debe tener una Policy cuando soporte consulta, edición o eliminación.

Reglas mínimas:

- `viewAny`: usuario pertenece al equipo activo.
- `view`: registro pertenece al equipo activo.
- `create`: usuario pertenece al equipo activo.
- `update`: registro pertenece al equipo activo.
- `delete`: registro pertenece al equipo activo.

Ejemplo conceptual:

```php
return $user->belongsToTeam($team) && $model->team_id === $team->id;
```

---

## 11. Consecutivos

La generación de consecutivos debe implementarse en un Service.

Servicio sugerido:

```text
App\Services\Consecutives\ConsecutiveService
```

Responsabilidades:

- Buscar consecutivo activo por equipo y tipo de documento.
- Validar disponibilidad.
- Generar número final con ceros.
- Incrementar número actual.
- Evitar duplicados.
- Ejecutar operación en transacción.

Regla crítica:

```text
Nunca generar consecutivos desde la vista ni directamente desde el controlador.
```

---

## 12. Documentos comerciales

La lógica de documentos comerciales debe manejarse mediante Services.

Servicio sugerido:

```text
App\Services\Documents\CommercialDocumentService
```

Responsabilidades:

- Crear documento.
- Asociar empresa.
- Asociar ítems.
- Calcular subtotal y total.
- Asociar plantilla.
- Asignar consecutivo.
- Cambiar estado.
- Generar PDF.
- Asociar adjuntos.

---

## 13. Estados

Los estados deben manejarse preferiblemente mediante enums.

Enums sugeridos:

```text
App\Enums\DocumentType
App\Enums\DocumentStatus
App\Enums\RecordStatus
```

Estados mínimos de documento:

```text
Borrador
Generado
Enviado
Aprobado
Rechazado
Pagado
Anulado
```

---

## 14. Archivos adjuntos

Reglas:

- Usar Storage de Laravel.
- Validar tipo de archivo.
- Validar tamaño.
- Asociar archivo con `team_id` y `created_by`.
- No exponer rutas internas directamente.
- Descargar archivos mediante controlador o endpoint autorizado.

Tipos permitidos iniciales:

```text
pdf
jpg
jpeg
png
doc
docx
xls
xlsx
```

---

## 15. Integraciones

Tokens y secretos deben almacenarse cifrados.

Reglas:

- No guardar tokens en texto plano.
- No subir `.env` al repositorio.
- Usar variables de entorno para credenciales.
- Registrar trazabilidad en `work_item_traces`.

Integraciones previstas:

- GitHub
- ClickUp

---

## 16. Git y ramas

Rama principal:

```text
main
```

Rama de desarrollo recomendada:

```text
develop
```

Formato de ramas:

```text
feature/NX-CONF-001-company-crud
feature/NX-COM-001-quotation-crud
fix/NX-CONF-002-consecutive-validation
docs/NX-BASE-001-specifications
```

---

## 17. Commits

Usar mensajes claros:

```text
docs: add initial project specification
feat(companies): add company CRUD
fix(consecutives): prevent duplicated numbers
test(companies): add company policy tests
refactor(documents): move PDF logic to service
```

---

## 18. Definition of Ready

Una historia está lista para desarrollo cuando tiene:

- Código de historia.
- Título claro.
- Módulo asociado.
- Descripción en formato Como / Quiero / Para.
- Criterios de aceptación.
- Reglas de seguridad.
- Entidades afectadas.
- Rutas o componentes esperados.
- Dependencias identificadas.

---

## 19. Definition of Done

Una historia se considera terminada cuando:

- El CRUD o funcionalidad cumple criterios de aceptación.
- La tabla dinámica funciona.
- Los modales funcionan.
- Los tooltips están implementados.
- La validación está implementada.
- La Policy está implementada.
- Las consultas respetan `team_id`.
- Las pruebas básicas pasan.
- El código fue formateado con Pint.
- El pull request fue revisado.
- La historia fue actualizada en GitHub y ClickUp, si aplica.

---

## 20. Reglas para Codex

Cuando Codex genere código debe cumplir:

1. Leer primero `docs/spec.es.md`.
2. Leer luego `docs/user-stories.es.md`.
3. Leer luego `docs/development-rules.es.md`.
4. No crear funcionalidades sin historia asociada.
5. No crear controladores multipropósito para funcionalidades principales.
6. No mezclar lógica de negocio compleja en controladores o componentes Livewire.
7. Usar `team_id` como filtro de propiedad funcional.
8. Crear Services para reglas de negocio.
9. Crear Policies para autorización.
10. Crear pruebas básicas por módulo.
11. Mantener nombres consistentes con la historia de usuario.
12. Respetar las convenciones de ramas y commits.
