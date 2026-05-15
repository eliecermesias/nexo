# Nexo Architecture

## Architectural Style

Nexo uses a modular Laravel architecture inspired by Clean Architecture, SOLID, TDD, and DDD where useful.

## Layers

### Domain

Pure business model, value objects, enums, rules, and contracts.

Suggested modules:

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

Use cases, DTOs, commands, queries, and orchestration.

Examples:

- `Application/UseCases/CreateQuote`
- `Application/UseCases/GeneratePaymentClaim`
- `Application/UseCases/ValidateComplianceDocuments`
- `Application/UseCases/GenerateDocumentPackage`
- `Application/DTOs`
- `Application/Commands`
- `Application/Queries`

### Infrastructure

Persistence, Eloquent adapters, storage, PDF generation, queues, rendering, and external services.

Examples:

- `Infrastructure/Persistence`
- `Infrastructure/Pdf`
- `Infrastructure/Storage`
- `Infrastructure/Security`
- `Infrastructure/Queues`
- `Infrastructure/Rendering`

### Interfaces

HTTP controllers, form requests, resources, Livewire components, policies, and view models.

Examples:

- `Interfaces/Http/Controllers`
- `Interfaces/Http/Requests`
- `Interfaces/Http/Resources`
- `Interfaces/Policies`
- `Interfaces/ViewModels`

## Main Domain Services

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

## Main Use Cases

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

## Laravel Folder Structure

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
