# Nexo Testing Strategy

## Testing Principles

- Use TDD for domain rules and critical workflows.
- Feature tests must cover user-facing workflows.
- Unit tests must cover pure domain calculations and validation services.
- Security and authorization tests are mandatory for document access.
- Every change must be programmatically tested.

## Test Types

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

## Priority Test Scenarios

### Compliance

Given a client company requires RUT, social security certificate, and purchase order,
When a payment claim is validated without one mandatory document,
Then the compliance status is `MissingRequiredDocuments`.

### Payment Claim Submission

Given a payment claim has blocking missing requirements,
When the user attempts to submit it,
Then the system blocks submission and shows blocking reasons.

### Secure Downloads

Given a document belongs to another user,
When the authenticated user requests its download,
Then the system denies access.

### Financial Calculation

Given service items with taxes, discounts, and retentions,
When totals are calculated,
Then subtotal, tax total, discount total, retention total, and final total are correct.

### Sequence Generation

Given a sequence counter exists for a document type and year,
When a new document number is generated,
Then the next value is unique and recorded in history.
