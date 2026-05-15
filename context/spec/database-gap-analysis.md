# Nexo Database Gap Analysis

Date: 2026-05-15

## Executive Summary

The current database already covers a useful commercial foundation: authentication, teams, menus, document catalogs, enterprises, parties, contacts, services, plans, taxes, quotations, proposals, collection accounts, invoices, payments, bank accounts, payment destinations, document templates, template versions, and basic attachments.

However, it does not yet comply with the product requirements in `context/spec`. The main gaps are structural:

1. Business tables do not have an explicit owner scope (`team_id`, `user_id`, or equivalent), which blocks reliable SaaS isolation and IDOR prevention.
2. The schema mixes Laravel conventions (`id`, `*_id`) with legacy names (`Id`, `*_Id`), increasing Eloquent complexity and migration risk.
3. The Compliance Matrix module does not exist.
4. Required document tracking does not exist.
5. Uploaded document records are too limited for secure document management.
6. PDF packages, generated documents, merge order, hashes, and package history do not exist.
7. Internal sequences and sequence history do not exist.
8. Audit trail tables do not exist.
9. External invoice number tracking is incomplete.
10. Financial requirements such as retentions, service rates, and richer valuation metadata are missing.

## Current Schema Strengths

- Core Laravel tables exist: `users`, `sessions`, `cache`, `jobs`, `failed_jobs`.
- Team base exists: `teams`, `team_members`, `team_invitations`.
- Commercial flow exists: `quotations`, `proposals`, `collection_accounts`, `invoices`, `payments`.
- Catalogs exist: `document_types`, `document_statuses`, `currencies`, `services`, `plans`, `taxes`, `payment_methods`, `banks`.
- Payment integrity has a useful check constraint: a `payments` row references either `collection_accounts` or `invoices`, but not both.
- Document templates and versions exist.
- Collection account and invoice attachments exist at a basic level.

## Critical Gaps

### 1. Ownership and Data Isolation

Current business tables do not include `team_id`, `user_id`, `owner_id`, `created_by`, or `uploaded_by`. Only session and team membership tables reference users/teams.

Affected tables include:

- `enterprises`
- `parties`
- `contacts`
- `services`
- `plans`
- `quotations`
- `proposals`
- `collection_accounts`
- `invoices`
- `payments`
- `bank_accounts`
- `payment_destinations`
- `document_templates`
- `collection_account_attachments`
- `invoice_attachments`

Required change:

- Add `team_id` as the main ownership boundary for business data.
- Add `created_by` where traceability matters.
- Add `uploaded_by` to document upload tables.
- Index all owner-scoped queries.

Recommended pattern:

```text
team_id BIGINT UNSIGNED NOT NULL
created_by BIGINT UNSIGNED NULL
updated_by BIGINT UNSIGNED NULL
```

### 2. Laravel Naming Conventions

Many current tables use uppercase primary keys and foreign keys:

- `Id`
- `document_types_Id`
- `enterprises_Id`
- `collection_accounts_Id`
- `payment_methods_Id`

This conflicts with the target Laravel convention documented in `context/spec/database.md` and forces every model to override `$primaryKey` and relationship keys.

Required change:

- Standardize new work on `id` and `*_id`.
- Decide whether to migrate existing tables now or introduce compatibility migrations later.

Recommendation:

- Because the project is still early, prefer normalizing existing business tables before building more modules.
- Keep Laravel/framework tables unchanged.

### 3. Missing Compliance Matrix

The requirements need configurable documentary rules per client company. No matching tables exist.

Required tables:

- `compliance_matrices`
- `compliance_requirements`
- `compliance_requirement_conditions`
- `compliance_validation_results`
- `compliance_validation_items`

Minimum fields:

- `client_enterprise_id`
- `document_type`
- `name`
- `description`
- `is_mandatory`
- `is_blocking`
- `requires_expiration_date`
- `requires_approval`
- `requires_file_upload`
- `can_be_generated_by_system`
- `must_be_attached_separately`
- `must_be_merged_into_final_pdf`
- `merge_order`
- `renewal_frequency`
- `condition_type`
- `condition_payload`
- `is_active`

Important condition examples:

- Always required.
- Required when total is greater than one legal minimum wage.
- Required by service.
- Required by plan.
- Required by document status.

### 4. Required and Uploaded Documents

Existing tables `collection_account_attachments` and `invoice_attachments` only store file metadata. They do not say which requirement the file satisfies, who uploaded it, whether it expires, whether it was approved, or whether it is safe to merge.

Required change:

- Add a generic `uploaded_documents` table.
- Link uploads to requirements and business documents.
- Either replace current attachment tables or keep them as compatibility views/legacy tables.

Recommended table:

- `uploaded_documents`

Important fields:

- `team_id`
- `uploaded_by`
- `client_enterprise_id`
- `compliance_requirement_id`
- `documentable_type`
- `documentable_id`
- `original_name`
- `stored_name`
- `disk`
- `path`
- `mime_type`
- `extension`
- `size`
- `hash_sha256`
- `expires_at`
- `approved_at`
- `approved_by`
- `rejected_at`
- `rejected_by`
- `status`
- `metadata`

### 5. PDF Packages and Generated Documents

The requirements need generated documents, merged packages, merge order, hash integrity, regeneration history, and package versioning. These tables do not exist.

Required tables:

- `generated_documents`
- `document_packages`
- `document_package_items`

Important fields:

- `payment_claim_id` or `collection_account_id`
- `template_version_id`
- `version`
- `disk`
- `path`
- `hash_sha256`
- `generated_by`
- `generated_at`
- `merge_order`
- `source_type`
- `source_id`
- `status`

### 6. Sequence Management

Document numbers are currently stored in each document table, with unique indexes such as enterprise plus number. There is no sequence configuration, no counter, and no history.

Required tables:

- `internal_sequences`
- `sequence_counters`
- `sequence_histories`
- `external_invoice_numbers`

Required capabilities:

- Prefix and suffix.
- Padding.
- Sequence by document type.
- Sequence by year.
- Optional sequence by client company.
- Duplicate prevention.
- Full sequence history.

### 7. Audit Trail

No audit tables currently exist. This blocks traceability, ISO 9001 evidence, and security monitoring.

Required tables:

- `activity_logs`
- `audit_logs`

Minimum events:

- Document created.
- Document updated.
- Status changed.
- File uploaded.
- File downloaded.
- Compliance validated.
- PDF generated.
- PDF merged.
- Payment claim submitted.
- Payment registered.
- Sequence generated.
- Security-related access denied.

### 8. Invoice Requirements

The current `invoices` table has `number` and `authorization_number`, but the requirements call for external invoice number capture, duplicate prevention, and association with payment claims or document packages.

Required change:

- Add `external_invoice_numbers`.
- Add invoice type/source fields.
- Link external invoice PDFs through `uploaded_documents`.

### 9. Finance and Valuation

The current schema has taxes and totals, but it lacks richer valuation concepts required by the specification.

Recommended additions:

- `service_rates`
- `retention_rates`
- item-level retention fields
- pricing type fields on `services`
- currency conversion support if multi-currency is in scope for v1.2

## Recommended Migration Plan

### Phase 1: Stabilize Conventions and Ownership

1. Choose final ownership boundary: recommended `team_id`.
2. Add `team_id` to all business tables.
3. Add `created_by`, `updated_by`, and upload user fields where needed.
4. Normalize table and column names to Laravel convention if the project can still absorb the change.
5. Update Eloquent models and tests.

### Phase 2: Compliance Matrix

1. Create `compliance_matrices`.
2. Create `compliance_requirements`.
3. Create `compliance_requirement_conditions`.
4. Create `compliance_validation_results`.
5. Create `compliance_validation_items`.

### Phase 3: Secure Documents

1. Create `uploaded_documents`.
2. Migrate or bridge `collection_account_attachments` and `invoice_attachments`.
3. Add file hash, disk, path, owner, status, expiration, and approval metadata.
4. Enforce private storage usage in application code.

### Phase 4: Document Packages and PDFs

1. Create `generated_documents`.
2. Create `document_packages`.
3. Create `document_package_items`.
4. Add package versioning and hash integrity.

### Phase 5: Sequences and External Invoices

1. Create `internal_sequences`.
2. Create `sequence_counters`.
3. Create `sequence_histories`.
4. Create `external_invoice_numbers`.
5. Replace manual document number generation with `SequenceGenerator`.

### Phase 6: Audit and ISO 9001 Evidence

1. Create `activity_logs`.
2. Create `audit_logs`.
3. Record critical business, document, and security events.

## Suggested Target Tables

| Requirement Area | Current Support | Required Change |
| --- | --- | --- |
| SaaS isolation | Partial teams only | Add `team_id` to business tables |
| Client requirements | Missing | Add Compliance Matrix tables |
| Required documents | Missing | Add requirements and validation result tables |
| Uploaded files | Basic attachments | Add generic secure upload model |
| PDF packages | Missing | Add generated document and package tables |
| Sequences | Missing | Add sequence tables |
| Audit trail | Missing | Add activity/audit logs |
| External invoices | Partial | Add external invoice number registry |
| Laravel conventions | Inconsistent | Normalize `Id`/`*_Id` to `id`/`*_id` |

## Implementation Notes

- New migrations should be created with Artisan and should use Laravel conventions.
- Use `foreignId()->constrained()` where table names follow conventions.
- Add explicit index names only where needed.
- Use JSON columns for flexible rule payloads, package metadata, and validation details.
- Add tests before each migration group.
- Do not build Compliance or PDF workflows on top of the current attachment tables without redesigning the document model first.

## Highest Priority Decision

Before implementing the next business module, decide whether Nexo is team-scoped or user-scoped.

Recommended decision:

```text
Nexo business data should be team-scoped using team_id.
```

Reason:

- The app already has teams.
- It supports future assistants, auditors, and multi-user workflows.
- It gives a stable authorization boundary for policies.
