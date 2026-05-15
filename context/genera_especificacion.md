Actúa como un equipo senior compuesto por:

1. Senior Software Architect.
2. Product Owner.
3. Tech Lead Laravel.
4. Backend Engineer experto en Laravel 13 y PHP 8.4.
5. QA Engineer experto en TDD.
6. Security Engineer experto en OWASP.
7. Especialista en Clean Architecture, SOLID y DDD.
8. Consultor en sistemas financieros, gestión documental, cuentas de cobro, facturación e ISO 9001.

Mi proyecto se llama "Nexo".

Nexo será un sistema web SaaS modular para gestionar cotizaciones, propuestas comerciales, facturas, cuentas de cobro, documentos requeridos por empresa, validación documental, generación de PDFs, combinación de documentos, control de consecutivos, cuentas bancarias, divisas, modos de pago, trazabilidad y cumplimiento documental.

==================================================
OBJETIVO PRINCIPAL
==================================================

Necesito que construyas una especificación funcional, técnica, arquitectónica y de seguridad completa para desarrollar Nexo usando Laravel 13, PHP 8.4, Clean Architecture, TDD, SOLID, principios DDD cuando aplique, enfoque de calidad ISO 9001 y controles de seguridad alineados con OWASP.

La especificación debe quedar lista para que Codex pueda usarla posteriormente como base de desarrollo del sistema.

El resultado debe poder convertirse en:

- Épicas.
- Historias de usuario.
- Criterios de aceptación.
- Casos de uso.
- Reglas de negocio.
- Modelo de datos.
- ERD Mermaid.
- Migraciones Laravel.
- Modelos Eloquent.
- Servicios de dominio.
- Interfaces.
- DTOs.
- Form Requests.
- Policies.
- Jobs.
- Events.
- Listeners.
- Tests TDD.
- Roadmap de implementación.
- Documentación técnica bilingüe.
- Checklist de seguridad OWASP.
- Checklist de calidad ISO 9001.

==================================================
REGLAS MANDATORIAS DE IDIOMA Y DOCUMENTACIÓN
==================================================

1. Toda especificación debe generarse en dos versiones:

   - Inglés.
   - Español.

2. Todo documento técnico debe tener su par bilingüe.

   Ejemplos obligatorios:

   - spec.md
   - spec.es.md

   - architecture.md
   - architecture.es.md

   - requirements.md
   - requirements.es.md

   - backlog.md
   - backlog.es.md

   - database.md
   - database.es.md

   - security.md
   - security.es.md

   - testing.md
   - testing.es.md

   - api.md
   - api.es.md

   - roadmap.md
   - roadmap.es.md

3. El archivo en inglés debe ser la versión principal para desarrollo técnico.

4. El archivo en español debe ser la versión equivalente para revisión funcional, académica, gerencial o documental.

5. Los nombres técnicos siempre deben mantenerse en inglés, incluso dentro de la documentación en español.

   Ejemplos:

   - PaymentClaim
   - Quote
   - Proposal
   - ClientCompany
   - ComplianceMatrix
   - DocumentRequirement
   - RequiredDocument
   - UploadedDocument
   - DocumentPackage
   - PdfMergeService
   - ServiceValuation
   - BankAccount
   - PaymentMethod
   - Currency
   - InternalSequence
   - ExternalInvoiceNumber
   - TemplateVersion
   - ComplianceValidationService

6. Todo código, estructura de carpetas, tablas, columnas, clases, métodos, variables, enums, interfaces, tests, seeders, factories, DTOs, jobs, events y policies debe estar en inglés.

7. La explicación funcional puede estar en español, pero el diseño técnico debe usar nombres en inglés.

8. Cuando propongas documentación, debes entregar siempre una estructura tipo:

   docs/
     spec.md
     spec.es.md
     architecture.md
     architecture.es.md
     requirements.md
     requirements.es.md
     database.md
     database.es.md
     security.md
     security.es.md
     testing.md
     testing.es.md
     roadmap.md
     roadmap.es.md

==================================================
STACK TECNOLÓGICO
==================================================

El sistema debe diseñarse usando:

- Laravel 13.
- PHP 8.4.
- MySQL o MariaDB.
- Tailwind CSS.
- Livewire o Inertia, según recomendación arquitectónica.
- Clean Architecture.
- SOLID.
- TDD.
- DDD cuando aporte valor.
- Arquitectura modular.
- Queues para procesos pesados.
- Storage seguro para documentos.
- Generación de PDFs.
- Combinación de PDFs.
- Auditoría de eventos.
- Control de permisos con Policies.
- Seguridad alineada con OWASP.

==================================================
CONTEXTO FUNCIONAL DE NEXO
==================================================

Nexo será un sistema para gestionar el ciclo completo de documentos comerciales, financieros y de cobro de un usuario.

El sistema debe servir para freelancers, consultores, auditores, docentes, instructores, profesionales independientes, pequeñas empresas y proveedores de servicios que necesitan generar documentos de negocio y cumplir con requisitos documentales de diferentes empresas clientes.

El usuario debe poder:

- Configurar sus datos personales, profesionales, fiscales y comerciales.
- Registrar empresas clientes.
- Registrar servicios ofrecidos.
- Definir tarifas.
- Crear cotizaciones.
- Crear propuestas comerciales.
- Generar facturas o registrar facturas externas.
- Generar cuentas de cobro.
- Gestionar documentos requeridos por empresa.
- Cargar documentos propios.
- Validar documentos antes del cobro.
- Generar paquetes documentales.
- Combinar documentos en PDFs finales.
- Configurar cuentas bancarias.
- Configurar divisas.
- Configurar métodos de pago.
- Personalizar plantillas.
- Versionar documentos.
- Gestionar consecutivos.
- Mantener trazabilidad.
- Cumplir buenas prácticas de calidad documental basadas en ISO 9001.
- Cumplir controles de seguridad basados en OWASP.

==================================================
CAPACIDADES CORE DEL SISTEMA
==================================================

1. User Management

El sistema debe permitir que cada usuario autogestione su información.

Debe poder configurar:

- Personal information.
- Professional profile.
- Tax information.
- Business information.
- Client companies.
- Services catalog.
- Service rates.
- Bank accounts.
- Payment methods.
- Currencies.
- Required documents.
- Uploaded documents.
- Document templates.
- Document versions.
- Internal sequences.
- External invoice numbers.
- Visual preferences.
- Security preferences.

Cada usuario debe operar de forma aislada. Ningún usuario debe poder acceder a información de otro usuario.

2. Client Companies

El sistema debe permitir registrar empresas clientes.

Cada ClientCompany debe tener:

- Name.
- Tax identification.
- Contact information.
- Billing information.
- Payment conditions.
- Compliance configuration.
- Required documents.
- Preferred currency.
- Preferred payment method.
- Document delivery rules.
- PDF package rules.
- Active/inactive status.

Cada empresa cliente podrá tener su propia ComplianceMatrix.

3. Services Catalog

El sistema debe permitir registrar servicios ofrecidos por el usuario.

Cada Service debe tener:

- Name.
- Description.
- Category.
- Pricing type.
- Base price.
- Currency.
- Tax rules.
- Active status.

El sistema debe soportar cálculos por:

- Fixed price.
- Hourly rate.
- Daily rate.
- Unit price.
- Quantity.
- Percentage.
- Package price.
- Manual adjustment.
- Discount.
- Tax.
- Retention.
- Currency.

4. Service Valuation

El sistema debe permitir calcular dinámicamente el valor de los servicios.

La lógica debe aplicar tanto para:

- Quotes.
- Proposals.
- Invoices.
- PaymentClaims.
- Final charges.

El sistema debe diferenciar entre:

- Estimated amount.
- Approved amount.
- Final billed amount.
- Paid amount.
- Pending amount.

Debe existir un ServiceValuationService encargado de calcular subtotales, impuestos, descuentos, retenciones y totales.

5. Quotes

El sistema debe permitir crear cotizaciones.

Una Quote debe incluir:

- ClientCompany.
- QuoteNumber.
- Service items.
- Quantities.
- Unit prices.
- Discounts.
- Taxes.
- Retentions.
- Subtotal.
- Total amount.
- Currency.
- Valid until date.
- Status.
- Template used.
- Version.
- Terms and conditions.
- Notes.

Estados sugeridos:

- Draft.
- Sent.
- Accepted.
- Rejected.
- Expired.
- Converted.
- Cancelled.

6. Proposals

El sistema debe permitir crear propuestas comerciales.

Una Proposal debe incluir:

- ClientCompany.
- ProposalNumber.
- Scope.
- Objectives.
- Services.
- Deliverables.
- Timeline.
- Commercial conditions.
- Financial values.
- Currency.
- Template.
- Version.
- Status.

Debe ser posible versionar propuestas cuando cambien servicios, valores, alcance o condiciones.

7. Invoices

El sistema debe contemplar facturas internas o externas.

Para documentos internos:

- El sistema puede generar un documento de soporte o factura no legal, si aplica al contexto del usuario.

Para facturas legales externas:

- El sistema debe permitir capturar manualmente el número de factura.
- El sistema debe permitir cargar el PDF de la factura generada en otro sistema.
- El sistema debe validar que el número externo no esté duplicado para el usuario y la empresa cliente.
- El sistema debe asociar la factura externa a una PaymentClaim o DocumentPackage.

Entidades sugeridas:

- Invoice.
- ExternalInvoiceNumber.
- InvoiceAttachment.
- InvoiceStatus.

8. Payment Claims

El sistema debe permitir generar cuentas de cobro.

Una PaymentClaim puede generarse desde:

- Quote accepted.
- Proposal accepted.
- Manual charge.
- External invoice.
- Service execution record.

Una PaymentClaim debe incluir:

- ClientCompany.
- PaymentClaimNumber.
- Services charged.
- Total amount.
- Currency.
- BankAccount.
- PaymentMethod.
- Required documents.
- Compliance validation result.
- PDF package.
- Status.
- Issue date.
- Due date.
- Notes.

Estados sugeridos:

- Draft.
- PendingValidation.
- ReadyForSubmission.
- Submitted.
- Paid.
- Rejected.
- Cancelled.
- Archived.

9. Sequence Management

El sistema debe gestionar consecutivos automáticos y manuales.

Debe soportar:

- Internal sequences.
- Prefixes.
- Suffixes.
- Number padding.
- Sequence by document type.
- Sequence by user.
- Sequence by year.
- Optional sequence by client company.
- Manual external invoice number.
- Duplicate prevention.
- Sequence history.
- Audit trail.

Entidades sugeridas:

- InternalSequence.
- SequenceCounter.
- SequenceGenerator.
- SequenceHistory.
- ExternalInvoiceNumber.

10. Compliance Matrix

Este es un módulo crítico.

Cada ClientCompany debe poder tener una ComplianceMatrix donde se definan los documentos requeridos para procesar una PaymentClaim.

La matriz debe permitir configurar:

- Document type.
- Requirement name.
- Mandatory or optional.
- Requires expiration date.
- Requires approval.
- Requires file upload.
- Can be generated by system.
- Must be attached separately.
- Must be merged into final PDF.
- Merge order.
- Renewal frequency.
- Blocking rule.
- Validation rule.
- Active/inactive status.

Ejemplo:

Para una empresa cliente, la PaymentClaim puede requerir:

1. PaymentClaim generated by Nexo.
2. RUT uploaded by user.
3. Bank certificate uploaded by user.
4. Social security certificate valid for current period.
5. Activity report.
6. External invoice PDF.
7. Purchase order.
8. Additional support documents.

El sistema debe validar que todos los documentos requeridos existan, estén cargados, estén vigentes, estén aprobados y cumplan las reglas antes de permitir generar el paquete final de cobro.

Entidades sugeridas:

- ComplianceMatrix.
- ComplianceRequirement.
- DocumentRequirement.
- RequiredDocument.
- UploadedDocument.
- ComplianceRule.
- ComplianceValidationResult.

11. Compliance Validation Engine

El sistema debe tener un motor de validación documental.

Debe validar:

- Required document exists.
- Document uploaded.
- Document generated.
- Document approved.
- Document rejected.
- Document expired.
- Document pending approval.
- Document optional.
- Document mandatory.
- Document ready for merge.
- Document blocking payment claim.
- Document version valid.
- Document belongs to authenticated user.
- Document belongs to selected client company.

Resultados posibles:

- ReadyForSubmission.
- MissingRequiredDocuments.
- ExpiredDocuments.
- PendingApprovalDocuments.
- RejectedDocuments.
- InvalidDocumentPackage.
- ValidationFailed.

El motor debe entregar:

- Global status.
- List of valid documents.
- List of missing documents.
- List of expired documents.
- List of rejected documents.
- List of pending approval documents.
- Blocking reasons.
- Suggested actions.

Servicio sugerido:

- ComplianceValidationService.

12. PDF Generation and Merge Engine

El sistema debe generar PDFs desde plantillas y combinar documentos en paquetes finales.

Debe soportar:

- PDF generation from templates.
- PDF merging.
- Merge order.
- Cover page.
- Optional table of contents.
- Generated documents.
- Uploaded documents.
- External invoice PDFs.
- Separate attachments.
- Final PDF package.
- Versioning.
- Regeneration history.
- Audit trail.
- Secure file storage.

Reglas por empresa:

Cada ClientCompany debe poder definir si los documentos se entregan:

- As individual attachments.
- As a single merged PDF.
- In a specific order.
- With cover page.
- With table of contents.
- With excluded documents.
- With separated support documents.

Servicios sugeridos:

- PdfGenerationService.
- PdfMergeService.
- DocumentPackageService.
- TemplateRenderingService.
- DocumentStorageService.

Entidades sugeridas:

- DocumentPackage.
- DocumentPackageItem.
- GeneratedDocument.
- UploadedDocument.
- DocumentTemplate.
- TemplateVersion.

13. Editable Templates

Los documentos generados deben usar plantillas editables y versionables.

El usuario debe poder configurar:

- Logo.
- Fonts.
- Colors.
- Header.
- Footer.
- Signature.
- Contact information.
- Tax information.
- Bank information.
- Template variables.
- Document type.
- Template version.
- Active template.

Variables dinámicas sugeridas:

- {{ user_name }}
- {{ client_name }}
- {{ document_number }}
- {{ issue_date }}
- {{ due_date }}
- {{ total_amount }}
- {{ currency }}
- {{ bank_account }}
- {{ payment_method }}
- {{ service_items }}
- {{ tax_information }}
- {{ signature }}

14. Finance Module

El sistema debe permitir gestionar información financiera.

Debe incluir:

- Payment methods.
- Currencies.
- Bank accounts.
- Tax data.
- Discounts.
- Taxes.
- Retentions.
- Payment status.
- Deposit information.

Cada BankAccount debe tener:

- Bank name.
- Account type.
- Account number.
- Account holder.
- Identification type.
- Identification number.
- Currency.
- Status.
- Notes.

Entidades sugeridas:

- BankAccount.
- PaymentMethod.
- Currency.
- Money.
- TaxRate.
- RetentionRate.
- FinancialSummary.

15. Audit Trail and Traceability

El sistema debe mantener trazabilidad de eventos relevantes.

Debe registrar:

- Document created.
- Document updated.
- Document deleted.
- Status changed.
- PDF generated.
- PDF merged.
- Compliance validated.
- Missing document detected.
- Expired document detected.
- Compliance matrix updated.
- Template updated.
- Sequence generated.
- External invoice number registered.
- Payment claim submitted.
- Payment claim paid.
- File uploaded.
- File downloaded.
- Security-related events.

Entidades/eventos sugeridos:

- ActivityLog.
- AuditLog.
- DocumentStatusChanged.
- PaymentClaimValidated.
- DocumentPackageGenerated.
- ComplianceMatrixUpdated.
- TemplateVersionCreated.
- SequenceGenerated.
- ExternalInvoiceRegistered.

16. ISO 9001 Quality Perspective

El diseño debe incorporar enfoque de calidad basado en ISO 9001.

No se requiere certificar el software, pero sí aplicar buenas prácticas relacionadas con:

- Control documental.
- Versionamiento.
- Trazabilidad.
- Gestión de registros.
- Evidencia documental.
- Control de cambios.
- Estados de aprobación.
- Documentos vigentes.
- Historial de modificaciones.
- Integridad de registros.
- Auditoría interna del proceso.
- Gestión de no conformidades documentales.
- Acciones correctivas sugeridas cuando falten documentos o estén vencidos.

Debes explicar cómo el sistema ayuda a conservar evidencia y control sobre los procesos documentales.

==================================================
REQUISITOS DE SEGURIDAD OWASP
==================================================

El sistema debe diseñarse para aprobar una revisión de seguridad basada en OWASP.

Incluye controles asociados a:

1. OWASP Top 10 Web Application Security Risks

Debes considerar como mínimo:

- Broken Access Control.
- Cryptographic Failures.
- Injection.
- Insecure Design.
- Security Misconfiguration.
- Vulnerable and Outdated Components.
- Identification and Authentication Failures.
- Software and Data Integrity Failures.
- Security Logging and Monitoring Failures.
- Server-Side Request Forgery.

2. OWASP ASVS

Propón controles inspirados en OWASP ASVS para:

- Authentication.
- Session management.
- Access control.
- Input validation.
- Output encoding.
- File upload security.
- File storage security.
- API security.
- Logging.
- Error handling.
- Business logic validation.
- Data protection.
- Secure configuration.

3. Requisitos específicos de seguridad para Nexo

El sistema debe contemplar:

- Aislamiento de datos por usuario.
- Validación estricta de ownership.
- Laravel Policies para autorización.
- Middleware de seguridad.
- Protección CSRF.
- Protección XSS.
- Protección contra SQL Injection usando Eloquent y Query Builder correctamente.
- Validación de archivos cargados.
- Restricción de tipos MIME.
- Restricción de tamaño de archivos.
- Escaneo o validación de archivos antes de almacenarlos.
- Almacenamiento privado de documentos.
- URLs firmadas o temporales para descarga de documentos.
- Control de acceso a PDFs generados.
- Logs de acceso a documentos.
- Protección contra IDOR.
- Rate limiting.
- Password hashing seguro.
- MFA preparado para versión futura.
- Sanitización de entradas.
- Manejo seguro de errores.
- No exponer stack traces en producción.
- Protección de variables de entorno.
- Cifrado de datos sensibles cuando aplique.
- Auditoría de cambios críticos.
- Detección de acciones sospechosas.
- Control de dependencias vulnerables.
- Uso de herramientas como composer audit.

4. File Upload Security

El sistema gestiona documentos sensibles, por tanto debe incluir controles para:

- Validar extensión.
- Validar MIME type real.
- Validar tamaño.
- Renombrar archivos.
- Evitar ejecución de archivos cargados.
- Almacenar fuera del public path.
- Usar discos privados.
- Asociar archivo al usuario propietario.
- Verificar autorización antes de descarga.
- Registrar cargas y descargas.
- Evitar path traversal.
- Evitar sobrescritura de archivos.
- Evitar exposición directa de rutas internas.

5. PDF Security

El sistema debe contemplar:

- Sanitización de datos antes de renderizar plantillas.
- Protección contra HTML injection en generación PDF.
- Validación de PDFs cargados.
- Control de acceso a PDFs combinados.
- Historial de generación.
- Hash del archivo generado para integridad.
- Registro de quién generó el PDF.
- Registro de cuándo se generó.
- Versionamiento de paquetes documentales.

6. Security Deliverables

Debes generar una sección de seguridad con:

- Security requirements.
- OWASP checklist.
- Threat model básico.
- Riesgos principales.
- Controles propuestos.
- Casos de abuso.
- Pruebas de seguridad.
- Recomendaciones Laravel.
- Reglas para proteger documentos.
- Reglas para evitar IDOR.
- Reglas para validación de archivos.

Archivos esperados:

- security.md
- security.es.md

==================================================
ARQUITECTURA ESPERADA
==================================================

Propón una arquitectura basada en Clean Architecture, organizada por capas:

1. Domain

Debe contener entidades, value objects, enums, reglas de negocio puras y contratos de dominio.

Ejemplos:

- Domain/Quotes
- Domain/Proposals
- Domain/PaymentClaims
- Domain/Compliance
- Domain/Documents
- Domain/Finance
- Domain/Sequences
- Domain/Templates
- Domain/Audit

2. Application

Debe contener casos de uso, DTOs, comandos, queries, servicios de aplicación y orquestación.

Ejemplos:

- Application/UseCases/CreateQuote
- Application/UseCases/GeneratePaymentClaim
- Application/UseCases/ValidateComplianceDocuments
- Application/UseCases/GenerateDocumentPackage
- Application/DTOs
- Application/Commands
- Application/Queries

3. Infrastructure

Debe contener persistencia, Eloquent, servicios externos, storage, PDF, colas y adaptadores.

Ejemplos:

- Infrastructure/Persistence
- Infrastructure/Pdf
- Infrastructure/Storage
- Infrastructure/Security
- Infrastructure/Queues
- Infrastructure/Rendering

4. Interfaces

Debe contener controladores, requests, resources, Livewire/Inertia components, policies y presenters.

Ejemplos:

- Interfaces/Http/Controllers
- Interfaces/Http/Requests
- Interfaces/Http/Resources
- Interfaces/Policies
- Interfaces/ViewModels

==================================================
ENTREGABLES ESPERADOS
==================================================

Genera una especificación completa con esta estructura:

1. Executive Summary / Resumen Ejecutivo

Debe explicar qué es Nexo, para quién sirve y qué problema resuelve.

Debe generarse en:

- spec.md
- spec.es.md

2. Product Vision / Visión del Producto

Incluye:

- Product purpose.
- Target users.
- Value proposition.
- Main pain points.
- Expected benefits.

3. Functional Scope / Alcance Funcional

Describe todos los módulos principales.

Módulos mínimos:

- User Management.
- Client Companies.
- Services Catalog.
- Service Valuation.
- Quotes.
- Proposals.
- Invoices.
- Payment Claims.
- Compliance.
- Required Documents.
- Uploaded Documents.
- PDF Packages.
- Templates.
- Finance.
- Sequences.
- Audit Trail.
- Security.

4. Product Epics / Épicas del Producto

Para cada épica incluye:

- Epic name.
- Goal.
- Description.
- User value.
- Related modules.
- Business priority.
- Technical complexity.
- Dependencies.
- Security considerations.

5. User Stories / Historias de Usuario

Para cada historia usa el formato:

Como [tipo de usuario],
quiero [acción],
para [beneficio].

Cada historia debe incluir:

- Description.
- Acceptance criteria using Given / When / Then.
- Priority.
- Estimated complexity.
- Dependencies.
- Business rules.
- Security rules.
- Test scenarios.

6. Business Rules / Reglas de Negocio

Organiza reglas por dominio:

- Users.
- Client Companies.
- Services.
- Quotes.
- Proposals.
- Invoices.
- Payment Claims.
- Compliance.
- Documents.
- PDF Packages.
- Templates.
- Finance.
- Sequences.
- Audit.
- Security.

7. Compliance Matrix Specification

Explica detalladamente cómo funcionará la matriz documental por empresa.

Incluye:

- Entities.
- Fields.
- States.
- Validation rules.
- Blocking rules.
- Merge rules.
- Example scenario.
- Edge cases.
- Error messages.
- Expected outputs.

8. Compliance Validation Engine

Incluye:

- Inputs.
- Process.
- Outputs.
- Validation statuses.
- Exceptions.
- Edge cases.
- Pseudocode.
- TDD scenarios.

9. PDF Generation and Merge Engine

Incluye:

- PDF generation flow.
- Template rendering.
- External documents.
- Uploaded documents.
- Generated documents.
- Merge order.
- Final package.
- Versioning.
- Hash integrity.
- Secure storage.
- Audit trail.
- Failure handling.
- TDD scenarios.

10. Data Model

Propón un modelo de datos profesional.

Incluye:

- Entities.
- Tables.
- Fields.
- Relationships.
- Cardinalities.
- Indexes.
- Constraints.
- Unique keys.
- Foreign keys.
- Soft deletes where useful.
- Audit fields.
- Security fields.
- Ownership fields.

Todo debe estar en inglés.

11. ERD Mermaid

Genera un ERD usando Mermaid.

Todo el diagrama debe estar en inglés.

12. Domain Services and Contracts

Define servicios e interfaces principales.

Mínimos esperados:

- ServiceValuationService.
- QuoteGenerationService.
- ProposalGenerationService.
- PaymentClaimGenerationService.
- ComplianceValidationService.
- PdfGenerationService.
- PdfMergeService.
- DocumentPackageService.
- SequenceGenerator.
- CurrencyConversionService.
- TemplateRenderingService.
- DocumentStorageService.
- AuditLogger.
- AccessControlService.

Para cada uno indica:

- Responsibility.
- Main methods.
- Inputs.
- Outputs.
- Business rules.
- Security rules.
- Exceptions.
- Test cases.

13. Value Objects and Enums

Propón Value Objects y Enums.

Ejemplos mínimos:

- Money.
- CurrencyCode.
- DocumentNumber.
- SequenceNumber.
- TaxIdentification.
- EmailAddress.
- FileHash.
- DocumentStatus.
- QuoteStatus.
- ProposalStatus.
- PaymentClaimStatus.
- InvoiceStatus.
- ComplianceStatus.
- RequirementType.
- MergeStrategy.
- PaymentMethodType.
- BankAccountType.
- SequenceType.
- TemplateStatus.

14. Application Use Cases

Define casos de uso principales.

Mínimos esperados:

- CreateQuote.
- UpdateQuote.
- SendQuote.
- AcceptQuote.
- ConvertQuoteToPaymentClaim.
- CreateProposal.
- GeneratePaymentClaim.
- RegisterExternalInvoiceNumber.
- ConfigureClientComplianceMatrix.
- UploadRequiredDocument.
- ValidateComplianceDocuments.
- GenerateDocumentPackage.
- MergeDocumentPackage.
- ConfigureBankAccount.
- ConfigurePaymentMethod.
- CreateDocumentTemplate.
- GenerateInternalSequence.
- DownloadSecureDocument.
- AuditDocumentAccess.

15. Laravel 13 Folder Structure

Propón estructura profesional de carpetas.

Debe incluir:

- app/Domain
- app/Application
- app/Infrastructure
- app/Interfaces
- tests/Unit
- tests/Feature
- tests/Architecture
- docs

16. Laravel Implementation Recommendations

Incluye recomendaciones para:

- Eloquent Models.
- Migrations.
- Form Requests.
- Policies.
- Gates.
- Middleware.
- Services.
- Repositories, si aplican.
- DTOs.
- Jobs.
- Events.
- Listeners.
- Queues.
- Storage.
- PDF generation.
- File uploads.
- Testing.
- Seeders.
- Factories.
- Validation.
- Authorization.
- Logging.
- Error handling.

17. TDD Strategy

Define estrategia de pruebas.

Debe incluir:

- Unit tests.
- Feature tests.
- Integration tests.
- Architecture tests.
- Domain tests.
- Application use case tests.
- Security tests.
- File upload tests.
- PDF generation tests.
- PDF merge tests.
- Compliance validation tests.
- Sequence generation tests.
- Financial calculation tests.
- Authorization tests.
- Regression tests.

18. OWASP Security Specification

Incluye:

- OWASP Top 10 mapping.
- OWASP ASVS-inspired checklist.
- Threat model.
- Abuse cases.
- Security requirements.
- Security test cases.
- Laravel security recommendations.
- File upload security.
- PDF security.
- Access control security.
- IDOR prevention.
- Logging and monitoring.
- Dependency security.
- Production hardening checklist.

19. ISO 9001 Quality Specification

Incluye:

- Document control.
- Record control.
- Versioning.
- Traceability.
- Approval states.
- Evidence management.
- Audit trail.
- Change history.
- Nonconformity handling.
- Corrective action suggestions.
- Quality-focused workflows.

20. Roadmap

Divide el desarrollo en fases:

MVP:

- User Management.
- Client Companies.
- Services Catalog.
- Quotes.
- Payment Claims.
- Basic Compliance Matrix.
- Basic PDF generation.
- Bank Accounts.
- Internal Sequences.
- Secure file upload.
- Basic audit trail.

Version 1.1:

- Proposal management.
- PDF merge engine.
- Template versioning.
- External invoice registration.
- Advanced compliance validation.
- Document package history.

Version 1.2:

- Advanced financial calculations.
- Multi-currency support.
- Advanced taxes and retentions.
- Approval workflows.
- Notifications.
- Enhanced audit trail.

Version 2.0:

- Multi-user teams.
- Roles and permissions.
- Assistant role.
- Auditor role.
- Advanced dashboard.
- API.
- Integrations.
- MFA.
- Advanced OWASP hardening.
- ISO 9001 evidence reports.

21. Technical Risks and Architectural Decisions

Incluye:

- Risks.
- Trade-offs.
- Architectural decisions.
- Recommended libraries.
- Security risks.
- PDF processing risks.
- File storage risks.
- Data isolation risks.
- Scalability concerns.
- Testing complexity.
- Future extensibility.

22. Documentation File Plan

Genera una propuesta de documentación bilingüe.

Debe incluir como mínimo:

docs/
  spec.md
  spec.es.md
  architecture.md
  architecture.es.md
  requirements.md
  requirements.es.md
  database.md
  database.es.md
  security.md
  security.es.md
  testing.md
  testing.es.md
  roadmap.md
  roadmap.es.md
  backlog.md
  backlog.es.md
  api.md
  api.es.md

Para cada archivo indica:

- Purpose.
- Main sections.
- Source language.
- Spanish equivalent.
- When it should be updated.

==================================================
FORMATO DE RESPUESTA
==================================================

Entrega una respuesta estructurada, profesional y accionable.

Usa:

- Tablas cuando ayuden a comparar o resumir.
- Mermaid para ERD.
- Listas numeradas para reglas de negocio.
- Given / When / Then para criterios de aceptación.
- Pseudocódigo solo cuando ayude a explicar lógica crítica.
- Nombres técnicos en inglés.
- Explicaciones funcionales en español.
- Sección bilingüe cuando se trate de documentación generada.

No generes código completo todavía, salvo ejemplos mínimos de pseudocódigo, interfaces o estructuras cuando sean necesarios para explicar arquitectura.

Primero quiero una especificación clara, profunda y ordenada del producto, el dominio, las reglas de negocio, la arquitectura, la seguridad OWASP, el enfoque ISO 9001 y la documentación bilingüe.

==================================================
INSTRUCCIÓN FINAL
==================================================

Antes de responder, organiza mentalmente el sistema como si fueras a desarrollarlo profesionalmente durante varios meses.

No quiero una respuesta superficial.

Quiero una especificación que pueda convertirse en el documento maestro de desarrollo de Nexo y que Codex pueda utilizar después para implementar el sistema módulo por módulo en Laravel 13 con Clean Architecture, TDD, seguridad OWASP y documentación bilingüe.