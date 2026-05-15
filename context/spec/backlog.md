# Nexo Backlog

## Epics

| Epic | Goal | Priority | Complexity |
| --- | --- | --- | --- |
| User Management | Configure owner data and preferences. | High | Medium |
| Client Companies | Manage client companies and compliance configuration. | High | Medium |
| Services Catalog | Manage services, rates, taxes, and retentions. | High | Medium |
| Quotes | Create and manage quotes. | Medium | Medium |
| Proposals | Create and version commercial proposals. | Medium | Medium |
| Payment Claims | Generate and manage payment claims. | High | High |
| Compliance Matrix | Configure client-specific required documents. | High | High |
| Uploaded Documents | Upload, validate, approve, and trace documents. | High | High |
| PDF Packages | Generate and merge final document packages. | High | High |
| Finance | Manage bank accounts, payment methods, currencies, and balances. | High | Medium |
| Sequences | Generate internal document numbers and prevent duplicates. | High | Medium |
| Audit Trail | Track critical business and security events. | High | Medium |
| API | Expose selected resources securely. | Low | High |

## Initial User Stories

### Configure Compliance Requirements

As a user,
I want to configure required documents per client company,
so that each payment claim follows the client's submission rules.

Acceptance criteria:

- Given a client company exists, when I add a required document rule, then it is available for future payment claims.
- Given a requirement is mandatory and blocking, when it is missing, then submission is blocked.

### Upload Required Document

As a user,
I want to upload evidence for a required document,
so that I can complete payment claim compliance.

Acceptance criteria:

- Given a payment claim has pending requirements, when I upload a valid file, then the requirement becomes fulfilled.
- Given a file has invalid MIME type, when I upload it, then the system rejects it.

### Generate Document Package

As a user,
I want to generate a final PDF package,
so that I can submit all required documents to the client company.

Acceptance criteria:

- Given all blocking requirements are fulfilled, when I generate the package, then a versioned PDF package is created.
- Given a required document is missing, when I generate the package, then generation is rejected.
