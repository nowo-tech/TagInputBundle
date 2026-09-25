# FrankenPHP worker mode audit (kernel not reset between requests)

| Field | Value |
|-------|-------|
| Package | `nowo-tech/tag-input-bundle` (`symfony-bundle`) |
| Audited revision | `v1.2.1` / `HEAD` at audit time |
| Audit date | 2026-09-25 |
| Target runtime | FrankenPHP **worker** with **`FRANKENPHP_RESET_KERNEL=false`** (sticky Kernel / “Friendly Worker”) |
| Method | Manual review of every file under `src/` (form type, data transformer, DI extension, configuration, compiler pass, `services.yaml`, Twig themes, frontend assets) + PHPStan classic + worker-strict + hardening |
| **Verdict** | ✅ **100% compatible** under Scenario B (`reset_kernel: false`) — no remediations required for sticky Kernel |

## Execution model assumed

FrankenPHP worker mode boots the Symfony kernel once per worker and serves many requests with the same container. This audit assumes the **strict** host contract used by Nowo “Friendly Worker” / kernel-isolation E2E:

| Host flag | Meaning |
|-----------|---------|
| `FRANKENPHP_MODE=worker` | Worker keeps the app in memory |
| **`FRANKENPHP_RESET_KERNEL=false`** | Kernel is **not** rebooted; Scenario **B** below |
| `FRANKENPHP_WORKER_NUM=1` | Single worker (isolation tests) |

Two scenarios are evaluated:

- **A — kernel not rebooted, `services_resetter` still runs:** services tagged `kernel.reset` (or implementing `ResetInterface`) are reset between requests. Typical default when `FRANKENPHP_RESET_KERNEL` is truthy / Runtime `worker=2`-style reset.
- **B — no reset at all (`FRANKENPHP_RESET_KERNEL=false`):** nothing is reset; any per-request state kept in a shared service leaks into the next request.

A bundle that is safe under **B** is safe under **A** and under classic mode / PHP-FPM.

## Summary

| Area | Status | Notes |
|------|--------|-------|
| Mutable state in shared services | ✅ | `TagType` only has `readonly` constructor defaults from container parameters |
| Static properties / `static` locals | ✅ | None; only `static fn` / static closures for OptionsResolver |
| `ResetInterface` / `kernel.reset` coverage | ✅ N/A | Nothing to reset |
| Request / user / locale captured in services | ✅ | Form data and options are method arguments; view vars are per request |
| Superglobals, `$_ENV`, `putenv`, `ini_set`, `setlocale`, timezone | ✅ | None used; config is compiled into container parameters |
| Doctrine / EntityManager | ✅ N/A | No persistence |
| Output, headers, `exit`, shutdown functions | ✅ | None |
| Resources (files, sockets, cURL) held open | ✅ | None |
| Memory growth across requests | ✅ | No caches or accumulating arrays |
| Blocking I/O and timeouts | ✅ N/A | No I/O at runtime; Tagify UI runs in the browser |
| Third-party static state | ✅ | Only Symfony Form/DI/Config; Twig form themes registered at compile time |
| PHPStan FrankenPHP rulesets | ✅ | `ruleset-classic.neon` + `ruleset-worker-strict.neon` + `ruleset-hardening.neon` in `phpstan.neon.dist` |

Worker demo: `demo/symfony8/docker/frankenphp/Caddyfile` has a `worker` block, and `FRANKENPHP_MODE=worker` is the default in `demo/symfony8/.env.example` / Compose.

## Services reviewed

| Service | Shared | Mutable state | Scenario A | Scenario B |
|---------|--------|---------------|------------|------------|
| `Nowo\TagInputBundle\Form\TagType` (`form.type`) | yes | none (`readonly` defaults, `src/Form/TagType.php`) | ✅ | ✅ |

`TagsToValueTransformer` is not a service: `TagType::buildForm()` creates a new `final readonly` instance for each form build with that field’s options. `ValueFormat` is a backed enum. `Configuration`, `NowoTagInputExtension`, `TwigPathsPass` and `NowoTagInputBundle` only run while the container is compiled. Twig themes only read `FormView` vars (per request). Frontend (`tag-input.ts`, `nowo-tag-input-element.ts`, `tag-input-lib.ts`) runs in the browser and does not share PHP worker memory.

## Findings

No open findings.

`TagType::buildView()` writes Tagify/`data-nowo-tag-input-*` attributes into the `FormView` of the current request only, and `configureOptions()` reads the `readonly` defaults. Under Scenario B the same `TagType` instance is reused; consecutive form builds with different options cannot poison later requests (covered by unit regression `testSharedInstanceDoesNotLeakOptionsAcrossConsecutiveBuilds`).

Submitted tags are parsed and validated per `reverseTransform()` call; whitelist / pattern / `max_tags` come from immutable options on the per-build transformer, not from earlier requests.

## Usage recommendations in worker mode

- No special configuration or reset hook is needed for this bundle when `FRANKENPHP_RESET_KERNEL=false`.
- Do not pass request- or user-dependent values (for example a whitelist loaded for the current user) as **bundle config defaults**; pass them as form options when the form is built (already per request).
- Do not keep `FormInterface`, `FormView` or submitted tag payloads in a service property — they would stay visible to later requests on the same worker.
- Types that extend or wrap `TagType` must stay stateless (or implement `ResetInterface`) to keep this verdict.

## Re-audit triggers

Re-run this audit when a change adds: mutable properties to `TagType` or a shared transformer service, a remote/autocomplete source (HTTP or Doctrine), a result cache, an event listener or Twig extension, or any use of `$_SERVER` / `$_ENV` / session at runtime.
