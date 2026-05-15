# Nexo Project Instructions

You are working on "Nexo", a Laravel 13 and PHP 8.4 SaaS application for managing quotes, proposals, invoices, payment claims, compliance documents, PDF generation, PDF merging, financial configuration, bank accounts, currencies, payment methods, document templates, internal sequences, external invoice numbers, audit trail, ISO 9001-inspired quality controls, and OWASP-aligned security.

## Mandatory Language Rules

All generated documentation must be bilingual.

For every technical document in English, create a Spanish equivalent using the `.es.md` suffix.

Examples:

- `spec.md`
- `spec.es.md`
- `architecture.md`
- `architecture.es.md`
- `requirements.md`
- `requirements.es.md`
- `database.md`
- `database.es.md`
- `security.md`
- `security.es.md`
- `testing.md`
- `testing.es.md`
- `roadmap.md`
- `roadmap.es.md`

English is the primary language for code and technical implementation.

Spanish is required for functional, academic, managerial, and review documentation.

All code-related names must be in English:

- Tables.
- Columns.
- Models.
- Entities.
- Value Objects.
- Services.
- Interfaces.
- Methods.
- Variables.
- Enums.
- Jobs.
- Events.
- Listeners.
- Policies.
- Form Requests.
- DTOs.
- Tests.
- Seeders.
- Factories.
- Folders.
- Namespaces.

## Technical Stack

Use:

- Laravel 13.
- PHP 8.4.
- MySQL or MariaDB.
- Clean Architecture.
- SOLID.
- TDD.
- DDD when valuable.
- Tailwind CSS.
- Livewire or Inertia according to architectural fit.
- Secure private storage for documents.
- Queues for heavy PDF processing.
- OWASP-aligned security controls.

## Architecture Rules

Use a layered architecture:

- `app/Domain`
- `app/Application`
- `app/Infrastructure`
- `app/Interfaces`

Core domains:

- `Users`
- `ClientCompanies`
- `Services`
- `Quotes`
- `Proposals`
- `Invoices`
- `PaymentClaims`
- `Compliance`
- `Documents`
- `DocumentPackages`
- `Templates`
- `Finance`
- `Sequences`
- `Audit`
- `Security`

## Security Rules

The system must be designed to pass an OWASP-oriented security review.

Always consider:

- Broken Access Control.
- IDOR prevention.
- File upload security.
- Private document storage.
- Signed or temporary URLs for downloads.
- Strict ownership validation.
- Laravel Policies.
- CSRF protection.
- XSS protection.
- SQL Injection prevention.
- Secure PDF generation.
- MIME type validation.
- File size limits.
- Path traversal prevention.
- Audit logs.
- Rate limiting.
- Secure error handling.
- Dependency vulnerability checks.
- Production hardening.

Create and maintain:

- `security.md`
- `security.es.md`

## Quality Rules

Apply ISO 9001-inspired quality principles:

- Document control.
- Versioning.
- Traceability.
- Approval states.
- Evidence management.
- Audit trail.
- Change history.
- Record integrity.
- Nonconformity handling.
- Corrective action suggestions.

## Development Rules

Use TDD.

For each feature, prefer this order:

1. Write the failing test.
2. Implement the domain rule.
3. Implement the use case.
4. Add infrastructure.
5. Add interface/controller.
6. Refactor.
7. Update bilingual documentation.

Every important feature must include:

- Unit tests.
- Feature tests.
- Authorization tests.
- Validation tests.
- Security tests when applicable.

## Documentation Rules

Whenever a new module, feature, or architectural decision is created, update the corresponding bilingual documentation.

Minimum documentation structure:

```txt
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


  ## Subscription and Licensing Rules

Nexo must be designed as a SaaS product with subscription plans, licensing, feature access control, usage quotas and billing readiness.

The system must support:

- Free plan.
- Trial plan.
- Basic plan.
- Professional plan.
- Business plan.
- Enterprise plan.

Use these technical names consistently:

- `SubscriptionPlan`
- `Subscription`
- `SubscriptionFeature`
- `SubscriptionLimit`
- `License`
- `LicenseKey`
- `PlanFeature`
- `PlanLimit`
- `UsageRecord`
- `UsageQuota`
- `BillingCycle`
- `SubscriptionInvoice`
- `SubscriptionPayment`
- `PaymentGateway`

Every feature that can be limited by plan must be validated in the backend.

Do not rely only on frontend restrictions.

Examples of limitable features:

- Maximum client companies.
- Maximum quotes per month.
- Maximum payment claims per month.
- Maximum uploaded documents.
- Maximum storage size.
- PDF merge access.
- Template customization.
- Number of document templates.
- Number of bank accounts.
- Multi-currency support.
- Advanced compliance matrix.
- Advanced audit trail.
- API access.
- Team members.

Required services:

- `SubscriptionService`
- `LicenseService`
- `FeatureAccessService`
- `UsageQuotaService`
- `PlanLimitChecker`
- `BillingService`
- `TrialManagementService`

Required documentation files:

- `subscription.md`
- `subscription.es.md`
- `billing.md`
- `billing.es.md`
- `licensing.md`
- `licensing.es.md`

Required tests:

- Subscription creation.
- License activation.
- License expiration.
- Feature access validation.
- Usage quota validation.
- Plan upgrade.
- Plan downgrade.
- Trial expiration.
- Unauthorized feature access attempt.
- Audit logging for subscription changes.