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
- a TypeScript behavior that syncs visible Tag inputs with one hidden form value

There are no HTTP controllers, no API endpoints, no persistence layer, and no cryptographic operations in this bundle.

## Threat model and mitigations

- **Input normalization**
  - Tag values are normalized server-side through `TagsToValueTransformer`.
  - Non-allowed characters are removed (`numeric_only` or alphanumeric mode).
  - Value length is bounded by configuration (`length`, min 3, max 12).
  - Optional uppercase normalization is applied.
- **Frontend constraints**
  - The browser-side script sanitizes per-character input and pasted values.
  - Hidden field value is derived from sanitized visible inputs.
  - Frontend checks improve UX only; server-side normalization remains the trust boundary.
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
| **Input / output** | Tag input normalization and allowed-character rules are preserved; outputs escaped in Twig/templates where user-controlled. |
| **Dependencies** | `composer audit` run; issues triaged. |
| **Logging** | Logs do not print secrets, tokens, or session identifiers unnecessarily. |
| **Cryptography** | If used: keys from secure config; never hardcoded. |
| **Permissions / exposure** | Routes and admin features documented; roles configured for production. |
| **Limits / DoS** | Timeouts, size limits, rate limits where applicable. |

Record confirmation in the release PR or tag notes.

