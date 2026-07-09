# Security Policy

## Table of contents

- [Supported Versions](#supported-versions)
- [Reporting a Vulnerability](#reporting-a-vulnerability)
- [Scope and attack surface](#scope-and-attack-surface)
- [Threat model and mitigations](#threat-model-and-mitigations)
- [Dependencies and updates](#dependencies-and-updates)
- [Release security checklist (12.4.1)](#release-security-checklist-1241)

## Supported Versions

| Version | Supported          |
| ------- | ------------------ |
| 1.x     | :white_check_mark: |

## Reporting a Vulnerability

We take the security of `TagInputBundle` seriously.

Please report vulnerabilities privately by email: **hectorfranco@nowo.tech**.

Do not open public issues for security-sensitive reports.

## Scope and attack surface

This bundle provides:

- one Symfony form type (`TagType`)
- one data transformer (`TagsToValueTransformer`)
- Twig form theme templates
- a TypeScript behavior that syncs visible Tag inputs with one hidden form value (Tagify JSON)

There are no HTTP controllers, no API endpoints, no persistence layer, and no cryptographic operations in this bundle.

## Threat model and mitigations

- **Input normalization**
  - Tag values are normalized server-side through `TagsToValueTransformer`.
  - Optional `pattern` (regex) and `whitelist` restrict allowed values.
  - Optional `max_tags` bounds the number of tags per field.
  - `trim` removes leading/trailing whitespace (default: true).
  - `duplicates` controls whether repeated tags are kept.
- **Frontend constraints**
  - The browser-side script improves UX; server-side normalization remains the trust boundary.
- **XSS**
  - The bundle does not inject untrusted HTML.
  - Twig templates render standard form inputs and escaped attributes.
- **Authentication/authorization**
  - Not handled by this bundle (must be enforced by the host application where needed).
- **Secrets**
  - No bundle feature requires hardcoded secrets.
  - Repository policy: keep `.env` and local credentials untracked.

## Dependencies and updates

- Run `composer audit` regularly.
- Keep Symfony and PHPUnit/dev tooling updated through normal dependency maintenance.
- Review release notes before upgrading transitive frontend tooling (pnpm/Vite/Vitest).

## Release security checklist (12.4.1)

Before tagging a release, confirm:

| Item | Notes |
|------|--------|
| **SECURITY.md** | This document is current and linked from the README where applicable. |
| **`.gitignore` and `.env`** | `.env` and local env files are ignored; no committed secrets. |
| **No secrets in repo** | No API keys, passwords, or tokens in tracked files. |
| **Recipe / Flex** | Default recipe or installer templates do not ship production secrets. |
| **Input / output** | Tag `pattern`, `whitelist`, and `max_tags` behave as documented; outputs escaped in Twig/templates. |
| **Dependencies** | `composer audit` run; issues triaged. |
| **Logging** | Logs do not print secrets, tokens, or session identifiers unnecessarily. |
| **Cryptography** | N/A — no custom cryptography in this bundle. |
| **Permissions / exposure** | Form fields inherit host-app authorization; no public routes in bundle. |
| **Limits / DoS** | Use `max_tags` and application-level payload limits for large submissions. |

Record confirmation in the release PR or tag notes.
