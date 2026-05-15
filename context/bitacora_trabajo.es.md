# Bitácora de Trabajo de Nexo

## Información del Repositorio

- Repositorio GitHub: `eliecermesias/nexo`
- URL: `https://github.com/eliecermesias/nexo`
- Rama principal: `main`
- Rama de trabajo actual: `devops`
- Último commit revisado: `20227252c87d3a9d5f198a60c3008ed3b304224c`
- Commit en GitHub: `https://github.com/eliecermesias/nexo/commit/20227252c87d3a9d5f198a60c3008ed3b304224c`
- Estado de PRs en GitHub: sin pull requests abiertos.
- Estado de issues en GitHub: sin issues abiertos.
- Estado local al cierre del 2026-05-10: commits `ae9a449` y `2022725` publicados en `origin/devops`. Queda pendiente commitear esta actualización de bitácora.

## Resumen Ejecutivo

El trabajo reciente dejó preparado a Nexo para iniciar una etapa de desarrollo más estructurada. Se revisó el prototipo inicial de base de datos, se contrastó con las migrations, seeders y factories ya existentes, y se produjo una propuesta definitiva de esquema ajustada a Laravel 13.

Además, se generó documentación funcional y técnica para orientar a los desarrolladores en la construcción de módulos: diccionario de datos, diagrama entidad relación, propuesta de menú, árbol de navegación y versiones en español de los documentos principales.

## Línea de Tiempo del Repositorio

| Commit | Fecha | Descripción |
| --- | --- | --- |
| `df926af` | Sin fecha local visible | Commit inicial para sincronizar. |
| `40dfe92` | Sin fecha local visible | Sincronización Nexo. |
| `fca9736` | 2026-04-30 | Cambio del front. Esta es la punta actual de `main`. |
| `b383f80` | 2026-05-02 | Aplicación de template de autenticación. |
| `93462f9` | 2026-05-02 | Ajustes de menú. |
| `9548bf6` | 2026-05-05 | Punto de control antes de modificar el diseño de `sidebar.blade.php`. |
| `5eba663` | 2026-05-08 | Revisión de base de datos para empezar a programar en serio. |
| `f2feebf` | 2026-05-09 | Ajuste en modelos y migraciones para el sistema Nexo. |
| `ae9a449` | 2026-05-10 | Ajuste visual de la funcionalidad probada con enterprises. |
| `2022725` | 2026-05-10 | Segunda parte: módulo enterprises, ubicaciones, marca visual y pruebas. |

## Trabajo Publicado en `devops`

La rama `devops` contiene el trabajo más reciente para preparar la arquitectura funcional de Nexo.

### Commit `5eba663`

Mensaje:

```text
20260508 - revisión de base de datos para empezar a programar en serio
```

Archivos principales agregados o modificados:

- `context/inicial.sql`
- `context/nexo_commercial_database.sql`
- `context/nexo_laravel13_definitive_schema.sql`
- `context/nexo_database_dictionary.md`
- `context/nexo_database_dictionary.es.md`
- `context/nexo_menu_proposal.md`
- `context/nexo_menu_proposal.es.md`
- `database/migrations/2026_05_05_154807_create_document_types_table.php`
- `database/migrations/2026_05_05_155215_create_people_table.php`
- `database/migrations/2026_05_05_161240_create_document_classes_table.php`
- `database/seeders/DocumentTypesSeeder.php`
- `database/seeders/PeopleSeeder.php`
- `database/seeders/DocumentClassesSeeder.php`
- `app/Models/document_types.php`
- `app/Models/people.php`
- `app/Models/document_classes.php`
- Ajustes visuales en `dashboard.blade.php`, `app.blade.php`, `sidebar.blade.php` y `desktop-user-menu.blade.php`.

Estadística del commit:

- 22 archivos modificados o agregados.
- 3944 líneas agregadas.
- 35 líneas eliminadas.

## Trabajo de Base de Datos

### 1. Revisión del prototipo inicial

Se revisó el archivo:

- `context/inicial.sql`

Hallazgos principales:

- El prototipo era muy inicial.
- Mezclaba nombres singulares y plurales.
- Mezclaba `id`, `Id`, `Services_Id` y convenciones no consistentes.
- Usaba llaves compuestas en `people`.
- No cubría todavía módulos clave como `quotations`, `proposals`, `collection_accounts`, `invoices`, `payments`, `bank_accounts`, `document_templates` ni attachments.

### 2. Primera propuesta comercial

Se generó:

- `context/nexo_commercial_database.sql`

Este archivo planteó una estructura comercial amplia para:

- `quotations`
- `proposals`
- `collection_accounts`
- `invoices`
- `plans`
- `services`
- `payment_methods`
- `banks`
- `bank_accounts`
- `payment_destinations`
- `payments`
- `document_templates`
- `document_template_versions`
- `collection_account_attachments`
- `invoice_attachments`

Esta primera versión respetaba la regla solicitada inicialmente de usar `Id` como llave primaria y `parent_tables_Id` como llave foránea.

### 3. Ajuste definitivo a Laravel 13

Luego se decidió ajustar el esquema a normas Laravel 13 para facilitar Eloquent, migrations, seeders, factories y relaciones.

Se generó:

- `context/nexo_laravel13_definitive_schema.sql`

Decisiones aplicadas:

- Usar `id` como llave primaria.
- Usar `*_id` como llave foránea.
- Usar tablas plurales en `snake_case`.
- Usar constraints con nombres tipo Laravel, por ejemplo `quotations_currency_id_foreign`.
- Evitar llaves primarias compuestas.
- Conservar las tablas base de Laravel y del proyecto actual.
- Integrar el avance existente en migrations y seeders.

El SQL definitivo contiene 40 tablas y fue validado con una carga de prueba en MySQL local usando un schema temporal llamado `nexo_schema_test`. La carga creó correctamente las 40 tablas y luego el schema temporal fue eliminado.

## Tablas Incluidas en el Esquema Definitivo

### Laravel y plataforma

- `users`
- `password_reset_tokens`
- `sessions`
- `cache`
- `cache_locks`
- `jobs`
- `job_batches`
- `failed_jobs`

### Teams y navegación

- `teams`
- `team_members`
- `team_invitations`
- `menus`

### Configuración base

- `document_types`
- `document_classes`
- `document_statuses`
- `currencies`
- `taxes`

### Clientes, empresas y contactos

- `enterprises`
- `people`
- `contacts`

### Catálogo comercial

- `services`
- `plans`
- `plan_items`

### Flujo comercial

- `quotations`
- `quotation_items`
- `proposals`
- `proposal_items`
- `collection_accounts`
- `collection_account_items`
- `invoices`
- `invoice_items`

### Pagos

- `payment_methods`
- `banks`
- `bank_accounts`
- `payment_destinations`
- `payments`

### Documentos y adjuntos

- `document_templates`
- `document_template_versions`
- `collection_account_attachments`
- `invoice_attachments`

## Documentación Generada

### Diccionario de datos

Archivos:

- `context/nexo_database_dictionary.md`
- `context/nexo_database_dictionary.es.md`

Contenido:

- Alcance del esquema.
- Convenciones Laravel 13.
- Módulos principales.
- Diagrama entidad relación en Mermaid.
- Flujo de negocio.
- Reglas principales.
- Diccionario de tablas y campos.
- Orden recomendado de desarrollo.
- Notas de implementación.

### Propuesta de menú

Archivos:

- `context/nexo_menu_proposal.md`
- `context/nexo_menu_proposal.es.md`

Contenido:

- Objetivo de navegación.
- Principios de navegación.
- Árbol de menú en texto.
- Tabla de menú recomendada.
- Fase inicial de implementación.
- Rutas sugeridas.
- Permisos sugeridos.
- Estructura sugerida para `MenuSeeder`.
- Reglas de activación del menú.
- Notas para desarrolladores.

## Árbol de Menú Propuesto

```text
Nexo
├── Dashboard
├── Commercial
│   ├── Quotations
│   ├── Proposals
│   ├── Collection Accounts
│   ├── Invoices
│   └── Payments
├── Customers
│   ├── Enterprises
│   ├── People
│   └── Contacts
├── Catalog
│   ├── Services
│   ├── Plans
│   └── Taxes
├── Payments Setup
│   ├── Payment Methods
│   ├── Banks
│   ├── Bank Accounts
│   └── Payment Destinations
├── Documents
│   ├── Templates
│   ├── Template Versions
│   ├── Collection Account Attachments
│   └── Invoice Attachments
└── Settings
    ├── Document Types
    ├── Document Classes
    ├── Document Statuses
    ├── Currencies
    ├── Teams
    └── Menus
```

## Decisiones Técnicas Tomadas

### Convención de base de datos

Se descartó la convención inicial `Id` y `parent_tables_Id` para el esquema definitivo porque no es la convención natural de Laravel. La decisión final fue usar `id` y `*_id`.

Impacto:

- Menos configuración manual en Eloquent.
- Relaciones más simples.
- Factories más limpias.
- Migrations más idiomáticas.
- Mejor compatibilidad con route model binding, policies y resources.

### Representación de clientes

Se decidió aprovechar `enterprises`, `people` y `contacts` en vez de introducir `parties` como una segunda forma de representar clientes.

Impacto:

- Se conserva lo ya avanzado en migrations y seeders.
- `enterprises` puede representar emisores, clientes y proveedores mediante flags.
- `people` queda disponible para personas naturales.
- `contacts` conecta empresas con personas o datos de contacto directos.

### Documentos comerciales

Se definió un flujo claro:

```text
Quotation -> Proposal -> Collection Account -> Invoice -> Payment
```

También se permite:

```text
Proposal -> Invoice
Collection Account -> Payment
Invoice -> Payment
```

### Pagos

Se definieron:

- `payment_methods`
- `payment_destinations`
- `banks`
- `bank_accounts`
- `payments`

Regla clave:

- Un `Payment` se aplica a una `Collection Account` o a una `Invoice`, pero no a ambas.

### Plantillas y adjuntos

Se definieron:

- `document_templates`
- `document_template_versions`
- `collection_account_attachments`
- `invoice_attachments`

Esto permite construir modelos de documentos y gestionar archivos adjuntos en cuentas de cobro y facturas.

## Trabajo de Frontend y Menú Detectado en el Historial

En commits previos de `devops` se trabajó en:

- Aplicación de template de autenticación.
- Ajustes del menú.
- Diseño del `sidebar`.
- Estilos en `dashboard.blade.php`.
- Ajustes en `desktop-user-menu.blade.php`.
- Integración de `MenuSeeder` y `Menu` model.

Estos cambios preparan la interfaz para usar navegación dinámica desde la tabla `menus`.

## Estado Actual del Proyecto

El proyecto tiene una base inicial funcional con:

- Laravel 13.
- Fortify.
- Livewire 4.
- Flux UI 2.
- Teams.
- Menú dinámico inicial.
- Módulo inicial de `enterprises`.
- Migrations iniciales para `document_types`, `people`, `document_classes`.
- Seeders iniciales.
- Documentación de arquitectura de base de datos y menú.

El esquema comercial definitivo todavía está en SQL documental. El siguiente paso es convertirlo en migrations, seeders y factories reales.

## Pendientes Recomendados

### Base de datos

1. Convertir `context/nexo_laravel13_definitive_schema.sql` en migrations Laravel.
2. Decidir si las migrations existentes se reemplazan o se generan migrations incrementales.
3. Normalizar las migrations actuales para que coincidan con el esquema definitivo.
4. Evitar `cascadeOnDelete` en dominio comercial cuando deba conservarse histórico.
5. Crear seeders definitivos para catálogos base:
   - `document_types`
   - `document_classes`
   - `document_statuses`
   - `currencies`
   - `payment_methods`
   - `taxes`

### Modelos

1. Renombrar modelos actuales a convención PSR/Laravel:
   - `document_types` -> `DocumentType`
   - `document_classes` -> `DocumentClass`
   - `people` -> `Person`
   - `Enterprises` -> `Enterprise`
2. Agregar `$fillable` o `$guarded`.
3. Definir relaciones Eloquent con tipos de retorno.
4. Definir casts para booleanos, fechas y decimales.

### Factories

Crear factories para:

- `Enterprise`
- `Person`
- `Contact`
- `Service`
- `Plan`
- `Quotation`
- `Proposal`
- `CollectionAccount`
- `Invoice`
- `Payment`

### Módulos funcionales

Prioridad recomendada:

1. `Enterprises`
2. `People`
3. `Contacts`
4. `Services`
5. `Plans`
6. `Taxes`
7. `Quotations`
8. `Proposals`
9. `Collection Accounts`
10. `Invoices`
11. `Payments`

### Testing

Crear pruebas para:

- Migrations del esquema definitivo.
- Seeders base.
- Relaciones Eloquent.
- Conversión de `Quotation` a `Proposal`.
- Conversión de `Proposal` a `Collection Account`.
- Conversión de `Collection Account` a `Invoice`.
- Registro de `Payment`.
- Restricción de pago único entre `collection_account_id` e `invoice_id`.

## Observaciones

- El repositorio no tiene PRs ni issues abiertos, por lo que la bitácora se basa en commits, estado de ramas, archivos versionados y revisión directa del historial.
- `devops` es la rama que contiene el trabajo activo.
- `main` todavía no contiene el último trabajo de base de datos y documentación.
- El commit `5eba663` es el punto de referencia para iniciar la implementación formal de módulos.

## Entrada de Trabajo - 2026-05-08

Se retomó la parte del menú dinámico y se ajustó `MenuSeeder` para alinearlo con la propuesta de menú de Nexo.

Cambios realizados:

- `database/seeders/MenuSeeder.php`
  - Se reemplazó el seeder inicial de dos opciones por el árbol completo propuesto:
    - `Dashboard`
    - `Commercial`
    - `Customers`
    - `Catalog`
    - `Payments Setup`
    - `Documents`
    - `Settings`
  - Se agregaron prioridades para ordenar padres e hijos.
  - Se usaron iconos Heroicons disponibles en Flux UI.
  - Se dejó el seeder idempotente para actualizar registros existentes en vez de duplicarlos.
  - Se corrigió que todas las URLs queden en `#` porque la mayoría de rutas todavía no existen. Esto evita errores `RouteNotFoundException`.
  - Se conservó el campo `current` como referencia futura para activación del menú cuando las rutas sean implementadas.

- `app/Models/Menu.php`
  - Se agregó `priority` a `$fillable`.
  - Se agregó accessor para que `name` se traduzca al leerlo con `__($value)`.
  - Se ordenaron los hijos por `priority`.
  - Se agregaron tipos de retorno en relaciones Eloquent.

- `lang/es.json`
  - Se agregaron traducciones en español para las opciones del nuevo menú.
  - La decisión fue guardar las claves en inglés en base de datos y traducirlas al renderizar.

- `tests/Feature/MenuSeederTest.php`
  - Se agregó prueba para validar:
    - Estructura del menú propuesto.
    - Cantidad esperada de entradas.
    - Iconos disponibles en Flux/Heroicons.
    - URLs en `#`.
    - Traducción del campo `name` al leer el modelo.

- `resources/views/layouts/app/sidebar.blade.php`
  - Se retiraron las directivas `@role('admin')` y `@endrole` del menú dinámico, porque estaban condicionando la visualización de grupos.
  - Se ajustaron clases Tailwind para que el menú tenga estilos explícitos en modo claro y modo oscuro.

- `resources/views/components/desktop-user-menu.blade.php`
  - Se ajustaron colores del menú de usuario en el footer del sidebar para que no use textos demasiado claros en modo claro.

Verificaciones ejecutadas:

- `vendor/bin/pint --dirty --format agent`
- `php artisan test --compact tests/Feature/MenuSeederTest.php`
- `php artisan view:cache`
- `php artisan view:clear`

Resultado:

- Pint pasó.
- La prueba específica del menú pasó.
- La compilación de vistas Blade pasó y luego se limpió la caché.

Nota pendiente:

- Todavía no estoy convencido con el modo claro del menú/sidebar. Queda como punto de revisión visual para retomar mañana, especialmente contraste, sensación general del sidebar y coherencia con el diseño oscuro actual.

## Entrada de Trabajo - 2026-05-09

Se avanzó en la conversión del esquema comercial de Nexo desde documentación SQL hacia estructura real de Laravel, con énfasis en models, seeders, migrations y pruebas.

### Modelos y convención Laravel

Se inició la normalización de modelos hacia nombres PSR/Laravel:

- Se agregaron modelos para el dominio comercial, entre ellos `Enterprise`, `DocumentType`, `Party`, `Contact`, `Currency`, `DocumentStatus`, `Service`, `Plan`, `PlanItem`, `Tax`, `Quotation`, `Proposal`, `CollectionAccount`, `Invoice`, `Payment`, `Bank`, `BankAccount`, `PaymentMethod`, `PaymentDestination`, `DocumentTemplate` y modelos relacionados de items, versiones y adjuntos.
- Se reemplazó progresivamente el uso de modelos con nombres no convencionales como `document_types`, `document_classes` y `people`.
- Se mantuvo compatibilidad con el avance existente sin revertir cambios de trabajo en curso.

### Seeders y datos base

Se avanzó en seeders de catálogos necesarios para que el sistema pueda migrar y probar el flujo comercial:

- `CurrenciesSeeder`
- `DocumentStatusesSeeder`
- `PaymentMethodsSeeder`
- `BanksSeeder`
- `TaxesSeeder`
- `PartiesSeeder`

También se ajustaron seeders existentes como `DatabaseSeeder`, `DocumentTypesSeeder`, `EnterprisesSeeder` y `MenuSeeder`.

### Migraciones comerciales separadas por tabla

Se separó la migración monolítica `2026_05_10_030000_create_nexo_commercial_database_tables.php` en migraciones individuales por cada tabla, dejando fuera las tablas por defecto del framework.

Cambios principales:

- Se eliminó la migración agrupada que creaba muchas tablas y hacía `dropIfExists` masivo.
- Se creó una migración por tabla para el dominio comercial:
  - `parties`
  - `contacts`
  - `currencies`
  - `document_statuses`
  - `services`
  - `plans`
  - `plan_items`
  - `taxes`
  - `quotations`
  - `quotation_items`
  - `proposals`
  - `proposal_items`
  - `collection_accounts`
  - `collection_account_items`
  - `invoices`
  - `invoice_items`
  - `payment_methods`
  - `banks`
  - `bank_accounts`
  - `payment_destinations`
  - `payments`
  - `document_templates`
  - `document_template_versions`
  - `collection_account_attachments`
  - `invoice_attachments`
- Se ajustó `document_types` a una migración con timestamp anterior para que `enterprises` pueda crear correctamente su llave foránea.
- Se actualizó `enterprises` al esquema comercial.
- Se ajustó la FK de `people` para apuntar a `document_types.Id`.
- Se respetaron las migraciones base del framework: `users`, `cache`, `jobs`, entre otras.

Nota técnica:

- Las migraciones comerciales actuales usan la convención `Id` y `*_Id`, alineada con el esquema comercial que se estaba implementando en este tramo. Queda pendiente decidir si se conserva esta convención o si se migra todo definitivamente a `id` y `*_id` para una integración más idiomática con Laravel.

### Pruebas y verificación

Se agregaron o ajustaron pruebas para validar el avance:

- `tests/Feature/NexoCommercialDatabaseSchemaTest.php`
  - Verifica que las tablas comerciales existan.
  - Verifica columnas `Id`.
  - Verifica seeders base de bancos, monedas, estados y métodos de pago.
  - Verifica algunas reglas de FK con `RESTRICT`.
- `tests/Unit/EnterprisesMigrationTest.php`
  - Verifica que `enterprises` tenga las columnas comerciales esperadas.

Verificaciones ejecutadas:

- `vendor/bin/pint --dirty --format agent`
- `php artisan test --compact tests/Feature/NexoCommercialDatabaseSchemaTest.php tests/Unit/EnterprisesMigrationTest.php`

Resultado:

- Pint pasó.
- Los tests relevantes pasaron contra una base temporal MySQL `nexo_test_codex`: 4 tests, 83 assertions.
- La base temporal `nexo_test_codex` fue eliminada al terminar.

Nota del entorno:

- La ejecución inicial con SQLite en memoria falló porque el PHP CLI no tiene disponible el driver `pdo_sqlite`.
- Se usó MySQL temporal para evitar correr `RefreshDatabase` sobre la base local `nexoDB`.

### Estado de cierre

Rama actual:

- `devops`

Último commit local de referencia:

- `5eba663`

Estado:

- Quedan cambios locales pendientes de revisión.
- No se hizo commit ni push desde esta sesión.
- El push será realizado manualmente.

Comandos sugeridos para cierre manual:

```bash
git status
git add .
git commit -m "20260509 - separar migraciones comerciales por tabla"
git push origin devops
```

Pendientes para la próxima sesión:

1. Revisar si se mantiene la convención `Id` / `*_Id` o si se normaliza a `id` / `*_id`.
2. Revisar visualmente el sidebar en modo claro.
3. Continuar con factories y relaciones Eloquent para los nuevos modelos comerciales.
4. Ejecutar una suite más amplia cuando el entorno de testing quede estable.

## Entrada de Trabajo - 2026-05-10

Se continuó con el paso de la estructura comercial hacia funcionalidad visible, concentrando el trabajo en el módulo de empresas, ubicaciones Colombia/DIVIPOLA, ajustes visuales de marca y pruebas feature.

Commits de referencia:

- `ae9a449` - `20260510 - ajuste visual de la funcionalidad probada con enterprises,`
- `2022725` - `20260510 - segunda parte`

### Módulo de empresas

Se completó una primera versión funcional del CRUD de `enterprises`:

- `app/Http/Controllers/EnterpriseController.php`
  - Se reemplazó el uso del modelo antiguo `Enterprises` por `Enterprise`.
  - Se agregó listado paginado con `paginate(15)`.
  - Se implementaron las acciones `create`, `store`, `show`, `edit` y `update`.
  - Se agregaron validaciones para tipo de documento, número, razón social, contacto, régimen tributario y ubicación.
  - Se validó consistencia jerárquica entre país, departamento/estado y ciudad antes de guardar.
  - Se persisten tanto las referencias `countries_Id`, `states_Id`, `cities_Id` como los textos `country`, `state`, `city` para conservar compatibilidad con el esquema actual.

- `routes/enterprise.php` y `routes/web.php`
  - Se corrigió la definición del resource para que las rutas queden como `enterprises.*`.
  - Se ajustó el route model binding con parámetro `enterprise`.
  - Se retiró el prefijo duplicado que generaba rutas anidadas incorrectas.

- Vistas de `resources/views/enterprises/`
  - Se agregó `create.blade.php`.
  - Se agregó `show.blade.php`.
  - Se ajustó `edit.blade.php`.
  - Se rediseñó `index.blade.php` para directorio paginado, acciones de consulta/edición, estado vacío y mejor presentación visual.

### Ubicaciones y DIVIPOLA

Se incorporó una base inicial de ubicaciones para Colombia:

- Nuevos modelos:
  - `Country`
  - `State`
  - `City`

- Nuevas migraciones:
  - `2026_05_10_200000_create_locations_tables.php`
  - `2026_05_10_200001_add_location_references_to_enterprises_table.php`
  - `2026_05_10_200002_add_divipola_fields_to_locations_tables.php`

- Nuevos factories:
  - `CountryFactory`
  - `StateFactory`
  - `CityFactory`
  - `EnterpriseFactory`

- Nuevo seeder:
  - `LocationsSeeder`

- Nuevo soporte de lectura:
  - `app/Support/DivipolaMunicipalities.php`
  - Lee `context/DIVIPOLA_Municipios.ods`.
  - Extrae departamentos, municipios, tipo, longitud, latitud y marca capitales.
  - Ordena municipios priorizando capitales dentro de cada departamento.

También se agregó `LocationsSeeder` a `DatabaseSeeder` y se ajustó `EnterprisesSeeder` para trabajar con los nuevos datos de ubicación.

### Marca, favicon y ajustes visuales

Se trabajó en la identidad visual y en la coherencia de modo claro/oscuro:

- Se agregó imagen de referencia de marca en `context/nexalvia.png`.
- Se agregaron capturas de revisión visual:
  - `context/modo_claro.png`
  - `context/modo_oscuro.png`
- Se actualizaron favicon y Apple touch icon:
  - `public/favicon.ico`
  - `public/favicon.svg`
  - `public/apple-touch-icon.png`
- Se ajustaron componentes de logo:
  - `resources/views/components/app-logo.blade.php`
  - `resources/views/components/app-logo-icon.blade.php`
- Se ampliaron estilos globales en `resources/css/app.css`.
- Se ajustaron layouts de autenticación y vistas públicas:
  - `resources/views/layouts/auth/card.blade.php`
  - `resources/views/layouts/auth/simple.blade.php`
  - `resources/views/layouts/auth/split.blade.php`
  - `resources/views/pages/front.blade.php`
  - `resources/views/welcome.blade.php`
  - `resources/views/partials/head.blade.php`

### Sidebar, sesión y menú

Se continuó refinando la navegación:

- `resources/views/layouts/app/sidebar.blade.php`
  - Ajustes de estilos para mejorar lectura en modo claro y oscuro.
  - Correcciones de estructura visual del sidebar.

- `resources/views/layouts/app/sesion.blade.php`
  - Ajustes de presentación de sesión.

- `database/seeders/MenuSeeder.php`
  - Ajustes menores de rutas/entradas para conectar con el módulo `enterprises`.

### Limpieza y revisión de estructura

Se agregó:

- `context/unused_directories_review.csv`

Este archivo documenta una revisión de directorios o rutas no usadas para facilitar limpieza posterior sin eliminar archivos a ciegas.

### Pruebas agregadas

Se agregaron pruebas feature para el módulo de empresas:

- `tests/Feature/Enterprises/EnterpriseIndexTest.php`
  - Verifica que un usuario autenticado pueda ver el directorio de empresas.
  - Verifica enlaces a crear, ver y editar.
  - Verifica estado vacío.
  - Verifica paginación.

- `tests/Feature/Enterprises/EnterpriseCreateTest.php`
  - Verifica que un usuario autenticado pueda ver el formulario de creación.
  - Verifica creación de empresa con documento, contacto, ubicación y régimen tributario.
  - Verifica redirección al detalle de la empresa creada.

Verificaciones asociadas al cierre:

- Commits publicados en `origin/devops`.
- Las pruebas relevantes quedan registradas en los commits, pero en esta actualización de bitácora no se reejecutó la suite porque el cambio actual es documental.

### Estado de cierre del 2026-05-10

Rama actual:

- `devops`

Último commit publicado:

- `2022725`

Estado:

- El módulo `enterprises` ya tiene navegación, listado, creación, edición y detalle iniciales.
- El proyecto ya cuenta con tablas y seeders base para ubicaciones Colombia/DIVIPOLA.
- La identidad visual empezó a consolidarse con favicon, logo y ajustes de layout.
- La bitácora fue actualizada posteriormente para reflejar este trabajo.

Pendientes recomendados:

1. Reejecutar las pruebas de enterprises y esquema comercial antes del próximo commit funcional.
2. Revisar en navegador el formulario de creación/edición de empresas con datos reales del seeder DIVIPOLA.
3. Corregir textos sin tilde pendientes en mensajes de validación si se decide normalizar todo el copy en español.
4. Revisar si `resources/views/layouts/app/sidebar.blade_ .php` debe conservarse, renombrarse o eliminarse con una decisión explícita.
5. Continuar con el siguiente módulo comercial después de estabilizar `enterprises`.

## Entrada de Trabajo - 2026-05-11

Se continuó con el ajuste visual de marca usando los assets definitivos de Nexalvia dejados en `context/nexalvia-assets-web/`.

### Assets de marca en `public`

Se copiaron y normalizaron nombres de imágenes para uso público:

- `public/logo-nexalvia-horizontal.png`
- `public/logo-nexalvia-horizontal.webp`
- `public/logo-nexalvia-principal.png`
- `public/logo-nexalvia-principal.webp`
- `public/logo-nexalvia-symbol.png`
- `public/logo-nexalvia-symbol.webp`
- `public/logo-nexalvia-icon-512.png`
- `public/favicon-32x32.png`
- `public/favicon-64x64.png`
- `public/apple-touch-icon.png`

También se actualizaron referencias de favicon en:

- `resources/views/partials/head.blade.php`
- `resources/views/welcome.blade.php`

### Logo del sidebar

Se ajustó `resources/views/layouts/app/sidebar.blade.php`:

- Se reemplazó el logo pequeño anterior por el logo horizontal.
- Se centró el logo dentro del panel lateral.
- Se agregó recuadro blanco `#FFFFFF`.
- Se incrementó el `border-radius` a `rounded-3xl`.
- Se reforzó la sombra interna para dar más profundidad.
- Se mantuvo `backdrop-blur-[5px]` para lograr el efecto de sidebar sobrepuesto al logo.
- Se usó WebP con fallback PNG.

### Componente de símbolo

Se actualizó `resources/views/components/app-logo-icon.blade.php`:

- Se reemplazó el SVG inline por la imagen pública `logo-nexalvia-symbol.png`.
- El componente conserva la posibilidad de recibir clases desde sus consumidores.

### Pantalla de login y layout de sesión

Se ajustó `resources/views/layouts/app/sesion.blade.php` para mejorar el comportamiento vertical:

- Se eliminó `overflow-hidden`, que impedía scroll en pantallas pequeñas.
- Se agregó `overflow-y-auto`.
- Se usó `min-h-dvh` para ajustarse mejor al alto real de la ventana.
- Se redujeron paddings en móvil.
- Se dejó el contenido alineado arriba en pantallas pequeñas y centrado desde `sm`.
- Se redujo el tamaño del logo en móvil.
- Se mantuvieron fondos fijos decorativos para que el scroll afecte sólo el contenido.

Se limpió `resources/views/pages/auth/login.blade.php`:

- Se normalizó indentación.
- Se compactaron márgenes verticales en móvil.
- Se mantuvo el formulario Fortify apuntando a `route('login.store')`.

### Verificaciones ejecutadas

- `vendor/bin/pint --dirty --format agent`
- `php artisan view:cache`
- `php artisan view:clear`
- `DB_CONNECTION=mysql DB_HOST=localhost DB_PORT=3306 DB_DATABASE=nexo_test_codex DB_USERNAME=admin DB_PASSWORD=1234 php artisan test --compact tests/Feature/Auth/AuthenticationTest.php --filter=test_login_screen_can_be_rendered`
- `DB_CONNECTION=mysql DB_HOST=localhost DB_PORT=3306 DB_DATABASE=nexo_test_codex DB_USERNAME=admin DB_PASSWORD=1234 php artisan test --compact tests/Feature/DashboardTest.php`

Resultado:

- Pint pasó.
- La compilación de vistas Blade pasó.
- La prueba de render del login pasó.
- La prueba del dashboard pasó: 2 tests, 3 assertions.

Nota del entorno:

- Las pruebas con SQLite no se usaron porque el PHP CLI no tiene disponible `pdo_sqlite`.
- Para las pruebas relacionadas se usó MySQL local temporal `nexo_test_codex`.

## Entrada de Trabajo - 2026-05-15

Se inició una etapa de organización del desarrollo guiado por requerimientos. El objetivo fue convertir la visión de Nexo en issues, especificaciones y documentos de decisión que sirvan como guía antes de continuar con nuevos módulos funcionales.

### Descripción actual de la plataforma

Nexo es una plataforma web SaaS para profesionales independientes, consultores, docentes, pequeñas empresas y proveedores de servicios que necesitan gestionar documentos comerciales y de cobro de forma ordenada, segura y trazable.

La plataforma permite administrar clientes, servicios, cotizaciones, propuestas, cuentas de cobro, facturas, pagos, cuentas bancarias, plantillas documentales y soportes requeridos por cada empresa cliente.

Su valor diferencial está en la gestión documental configurable: cada cliente puede exigir documentos distintos para recibir o pagar una cuenta de cobro, como RUT, cédula, planilla de seguridad social, orden de compra, orden de trabajo, factura externa o soportes operativos. Nexo permitirá configurar esos requisitos, validar si están completos, bloquear radicaciones incompletas y generar paquetes documentales listos para entregar.

En términos de producto, Nexo no debe limitarse a generar cuentas de cobro. Debe convertirse en una plataforma para preparar, validar, organizar, generar y entregar documentos comerciales con cumplimiento, trazabilidad y control.

### Issues creados en GitHub

Se crearon issues iniciales en `eliecermesias/nexo` para ordenar el trabajo:

1. `#1` - `[Proceso] Definir flujo de desarrollo guiado por requerimientos`
2. `#2` - `[Épica] Completar base de navegación, dashboard y estructura multi-equipo`
3. `#3` - `[Épica] Gestionar clientes: Enterprises, People y Contacts`
4. `#4` - `[Épica] Gestionar catálogo comercial: Services, Plans y Taxes`
5. `#5` - `[Épica] Implementar cotizaciones comerciales`
6. `#6` - `[Épica] Implementar propuestas comerciales`
7. `#7` - `[Épica] Implementar cuentas de cobro`
8. `#8` - `[Épica] Implementar facturación`
9. `#9` - `[Épica] Implementar pagos y configuración bancaria`
10. `#10` - `[Épica] Implementar plantillas, versiones y adjuntos de documentos`
11. `#11` - `[Épica] Configurar requisitos documentales por empresa cliente`

El issue `#11` captura una condición crítica del negocio: cada empresa cliente puede exigir documentos propios antes de recibir, aprobar o pagar una cuenta de cobro. Este módulo debe permitir reglas configurables, por ejemplo documentos siempre requeridos o requeridos cuando el valor supera un salario mínimo.

### Especificaciones creadas

Se creó el directorio:

- `context/spec/`

Allí se generó una primera base de especificaciones bilingües derivada de `context/genera_especificacion.md`:

- `spec.md` / `spec.es.md`
- `architecture.md` / `architecture.es.md`
- `requirements.md` / `requirements.es.md`
- `database.md` / `database.es.md`
- `security.md` / `security.es.md`
- `testing.md` / `testing.es.md`
- `roadmap.md` / `roadmap.es.md`
- `backlog.md` / `backlog.es.md`
- `api.md` / `api.es.md`

Estos documentos definen visión de producto, alcance funcional, arquitectura, requerimientos, modelo de datos objetivo, seguridad OWASP, estrategia de pruebas, roadmap, backlog y alcance inicial de API.

### Evaluación de base de datos

Se evaluó el esquema real de MySQL usando Laravel Boost y se comparó contra las especificaciones en `context/spec`.

Documentos generados:

- `context/spec/database-gap-analysis.md`
- `context/spec/database-gap-analysis.es.md`
- `context/spec/resumen-ejecutivo-evaluacion-bd.es.md`

Conclusiones principales:

- La base actual tiene un avance comercial importante.
- Ya existen tablas para usuarios, equipos, menús, catálogos, empresas, contactos, cotizaciones, propuestas, cuentas de cobro, facturas, pagos, cuentas bancarias, destinos de pago, plantillas y adjuntos básicos.
- La base todavía no cumple los requerimientos completos de SaaS, Compliance Matrix, gestión documental segura, paquetes PDF, consecutivos y auditoría.
- Las tablas de negocio no tienen `team_id`, `user_id` ni un ownership explícito.
- El esquema mezcla convenciones Laravel (`id`, `*_id`) con nombres heredados (`Id`, `*_Id`).
- No existen tablas para `compliance_matrices`, `compliance_requirements`, `uploaded_documents`, `document_packages`, `generated_documents`, `internal_sequences`, `sequence_histories`, `activity_logs` ni `audit_logs`.

### Recomendación técnica central

Antes de continuar construyendo módulos visibles, se recomienda estabilizar el modelo de datos.

Decisión recomendada:

```text
Nexo debe ser una plataforma team-scoped, usando team_id como frontera principal de ownership para todos los datos de negocio.
```

Razones:

- La aplicación ya tiene equipos.
- Permite crecer hacia roles como administrador, asistente, auditor o colaborador.
- Facilita Policies de Laravel.
- Ayuda a prevenir IDOR.
- Es más flexible para una plataforma SaaS que depender sólo de `user_id`.

### Plan de trabajo sugerido

Orden recomendado:

1. Congelar decisiones técnicas: ownership, convenciones Laravel y nombres finales de módulos.
2. Agregar `team_id` a tablas de negocio.
3. Agregar campos de trazabilidad como `created_by`, `updated_by`, `uploaded_by`, `approved_by` y `generated_by`.
4. Normalizar convenciones de columnas a `id` y `*_id` si el proyecto todavía puede absorber el cambio.
5. Crear Compliance Matrix:
   - `compliance_matrices`
   - `compliance_requirements`
   - `compliance_requirement_conditions`
   - `compliance_validation_results`
   - `compliance_validation_items`
6. Crear gestión documental segura:
   - `uploaded_documents`
   - relación con requisitos, empresas, cuentas de cobro y facturas.
7. Crear generación y combinación de documentos:
   - `generated_documents`
   - `document_packages`
   - `document_package_items`
8. Crear consecutivos:
   - `internal_sequences`
   - `sequence_counters`
   - `sequence_histories`
   - `external_invoice_numbers`
9. Crear auditoría:
   - `activity_logs`
   - `audit_logs`
10. Implementar pruebas de aislamiento, Compliance, documentos, paquetes PDF, consecutivos y auditoría.

### Siguiente paso recomendado

Crear una épica técnica en GitHub:

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

Después de esa fase, el siguiente bloque recomendado es:

```text
[Épica Técnica] Implementar Compliance Matrix y documentos requeridos por empresa cliente
```

### Verificaciones ejecutadas

En esta sesión no se ejecutaron pruebas automatizadas porque los cambios fueron documentales y de planificación. Sí se verificó:

- Existencia de archivos creados en `context/spec`.
- Tamaño de los documentos generados.
- Estado Git de los archivos documentales.
