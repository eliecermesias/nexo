# Nexo API Specification

## Purpose

The API will expose selected Nexo resources for future integrations, dashboards, assistants, and external workflows.

## Initial Scope

- Client companies.
- Services.
- Quotes.
- Proposals.
- Payment claims.
- Compliance requirements.
- Uploaded documents metadata.
- Document packages metadata.
- Bank accounts.
- Payment methods.
- Audit events.

## API Principles

- Use versioned routes, for example `/api/v1`.
- Use Laravel API Resources.
- Enforce authentication and authorization on every endpoint.
- Validate owner scope on every query.
- Never expose private storage paths.
- Use signed temporary URLs for authorized downloads.
- Rate limit sensitive endpoints.
- Return consistent error responses.

## Example Endpoints

| Method | Path | Purpose |
| --- | --- | --- |
| GET | `/api/v1/client-companies` | List client companies. |
| POST | `/api/v1/client-companies` | Create a client company. |
| GET | `/api/v1/payment-claims/{paymentClaim}` | Show a payment claim. |
| POST | `/api/v1/payment-claims/{paymentClaim}/documents` | Upload required document metadata and file. |
| POST | `/api/v1/payment-claims/{paymentClaim}/validate-compliance` | Validate compliance status. |
| POST | `/api/v1/payment-claims/{paymentClaim}/document-packages` | Generate a document package. |

## Security Requirements

- API authentication is required.
- Policies must authorize every resource.
- IDOR prevention is mandatory.
- File downloads must use temporary signed URLs.
- Validation errors must not leak internal implementation details.
