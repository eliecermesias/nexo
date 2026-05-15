# Nexo Security Specification

## OWASP Scope

Nexo must address OWASP Top 10 and OWASP ASVS-inspired controls for authentication, session management, access control, input validation, output encoding, file upload security, file storage security, API security, logging, error handling, business logic validation, data protection, and secure configuration.

## Core Security Requirements

1. Enforce strict ownership validation for every record.
2. Use Laravel Policies for authorization.
3. Protect against IDOR by checking owner scope before reads, writes, downloads, and PDF generation.
4. Use CSRF protection for web forms.
5. Use Eloquent and Query Builder safely to prevent SQL injection.
6. Validate and sanitize all user input.
7. Store uploaded documents outside the public path.
8. Use private disks and temporary signed URLs for downloads.
9. Log document upload, download, generation, merge, and deletion events.
10. Run dependency checks such as `composer audit`.

## File Upload Security

- Validate extension.
- Validate real MIME type.
- Validate file size.
- Rename files.
- Prevent execution of uploaded files.
- Store files outside public path.
- Use private storage disks.
- Associate files with owner and business context.
- Authorize downloads.
- Register upload and download events.
- Prevent path traversal.
- Prevent overwrites.
- Avoid exposing internal paths.

## PDF Security

- Sanitize template data before rendering.
- Prevent HTML injection in generated PDFs.
- Validate uploaded PDFs.
- Protect merged PDFs with authorization.
- Store hash for generated package integrity.
- Record generation user and timestamp.
- Version generated packages.

## Abuse Cases

- A user attempts to download another user's document.
- A user modifies a URL ID to access another payment claim.
- A malicious file is uploaded as documentary evidence.
- A template contains unsafe HTML.
- A stale required document is reused after expiration.

## Security Test Cases

- Unauthorized users cannot access another owner scope.
- Invalid MIME files are rejected.
- Oversized files are rejected.
- Private files cannot be downloaded without authorization.
- Missing compliance documents block submission when configured as blocking.
