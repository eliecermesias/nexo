# Arquitectura de Nexo

## Estilo Arquitectónico

Nexo usa una arquitectura Laravel modular inspirada en Clean Architecture, SOLID, TDD y DDD cuando aporte valor.

## Capas

### Domain

Modelo de negocio puro, value objects, enums, reglas y contratos.

Módulos sugeridos:

- `Domain/Quotes`
- `Domain/Proposals`
- `Domain/PaymentClaims`
- `Domain/Compliance`
- `Domain/Documents`
- `Domain/Finance`
- `Domain/Sequences`
- `Domain/Templates`
- `Domain/Audit`

### Application

Casos de uso, DTOs, commands, queries y orquestación.

Ejemplos:

- `Application/UseCases/CreateQuote`
- `Application/UseCases/GeneratePaymentClaim`
- `Application/UseCases/ValidateComplianceDocuments`
- `Application/UseCases/GenerateDocumentPackage`
- `Application/DTOs`
- `Application/Commands`
- `Application/Queries`

### Infrastructure

Persistencia, adaptadores Eloquent, storage, generación PDF, colas, rendering y servicios externos.

Ejemplos:

- `Infrastructure/Persistence`
- `Infrastructure/Pdf`
- `Infrastructure/Storage`
- `Infrastructure/Security`
- `Infrastructure/Queues`
- `Infrastructure/Rendering`

### Interfaces

Controladores HTTP, form requests, resources, componentes Livewire, policies y view models.

Ejemplos:

- `Interfaces/Http/Controllers`
- `Interfaces/Http/Requests`
- `Interfaces/Http/Resources`
- `Interfaces/Policies`
- `Interfaces/ViewModels`

## Servicios de Dominio Principales

- `ServiceValuationService`
- `QuoteGenerationService`
- `ProposalGenerationService`
- `PaymentClaimGenerationService`
- `ComplianceValidationService`
- `PdfGenerationService`
- `PdfMergeService`
- `DocumentPackageService`
- `SequenceGenerator`
- `CurrencyConversionService`
- `TemplateRenderingService`
- `DocumentStorageService`
- `AuditLogger`
- `AccessControlService`

## Casos de Uso Principales

- `CreateQuote`
- `UpdateQuote`
- `SendQuote`
- `AcceptQuote`
- `ConvertQuoteToPaymentClaim`
- `CreateProposal`
- `GeneratePaymentClaim`
- `RegisterExternalInvoiceNumber`
- `ConfigureClientComplianceMatrix`
- `UploadRequiredDocument`
- `ValidateComplianceDocuments`
- `GenerateDocumentPackage`
- `MergeDocumentPackage`
- `ConfigureBankAccount`
- `ConfigurePaymentMethod`
- `CreateDocumentTemplate`
- `GenerateInternalSequence`
- `DownloadSecureDocument`
- `AuditDocumentAccess`

## Estructura Laravel

```text
app/
  Domain/
  Application/
  Infrastructure/
  Interfaces/
tests/
  Unit/
  Feature/
  Architecture/
context/
  spec/
```
