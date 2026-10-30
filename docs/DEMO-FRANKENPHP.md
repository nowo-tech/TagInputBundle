# Demo notes (FrankenPHP)

**REQ-DEMO-001:** FrankenPHP demos must install **Nowo Twig Inspector** and **Nowo Hot Reload** together (`nowo-tech/twig-inspector-bundle` + `nowo-tech/hot-reload-bundle` in `require-dev`). Caddyfile: Mercure + `hot_reload` (and `worker { file …; watch }` in worker mode). Do not enable Hot Reload in production.

This bundle includes `demo/symfony8` with a sample Symfony application.

Each demo has its own `docker-compose.yml`, `Dockerfile`, and `docker/frankenphp/` (Caddyfile variants) for local development.

The **repository root** `docker-compose.yml` is for **bundle** development (PHP, Composer, pnpm/Vite, tests). It is not the same as launching a demo as a standalone hosted app.

To run a demo, follow the README inside `demo/symfony8`.

This bundle is **100% compatible** with FrankenPHP worker mode when `FRANKENPHP_RESET_KERNEL=false` (see the main [README](../README.md) banner and the [worker audit](FRANKENPHP-WORKER-AUDIT.md)). Demos default to `FRANKENPHP_MODE=worker`.

## Files

| File | Role |
| --- | --- |
| `demo/symfony8/Dockerfile` | `dunglas/frankenphp:1-php8.5-alpine` + Composer; copies both Caddyfiles and the entrypoint |
| `demo/symfony8/docker/frankenphp/Caddyfile` | Worker mode: `:80`, `root * /app/public`, `encode zstd br gzip`, Mercure (`anonymous`), `php_server { hot_reload; worker { file /app/public/index.php; watch } }` |
| `demo/symfony8/docker/frankenphp/Caddyfile.dev` | Classic mode: same site block without `worker`, plus no-cache headers |
| `demo/symfony8/docker/entrypoint.sh` | Picks the Caddyfile from `FRANKENPHP_MODE` (`worker` default, `classic`), runs `composer install` when `vendor/` is missing, starts `frankenphp run` |
| `demo/symfony8/docker/php-dev.ini` | `opcache.revalidate_freq=0` (mounted in dev) |
| `demo/symfony8/config/packages/dev/twig.yaml` | `twig.cache: false` in dev |

The demo listens on **`PORT`** from `demo/symfony8/.env` / `.env.example` (default **8073**); Compose maps `${PORT:-8073}:80`.

## Start the demo in development

```bash
cd demo/symfony8
make up          # copies .env.example to .env on first run, composer install, links the bundle
# -> Demo started at: http://localhost:8073
make down
```

From the repository root the same is `make -C demo up-symfony8` / `make -C demo down-symfony8`.

Development defaults (`.env.example`): `APP_ENV=dev`, `APP_DEBUG=1`, `FRANKENPHP_MODE=worker`. The Web Profiler toolbar,
Nowo Twig Inspector and Nowo Hot Reload are active (`dev` only). With `watch` in the worker Caddyfile, PHP/Twig edits restart
the workers automatically; set `FRANKENPHP_MODE=classic` if you prefer one PHP process per request.

After editing the bundle's TypeScript, rebuild the assets from the repository root (`make assets`) and re-publish them in the demo
(`make -C demo/symfony8 update-bundle`, which runs `assets:install`).

## Start the demo in production mode

The demo is a showcase, not a hosted product, but it boots with production settings to check the bundle under a `prod` kernel and
FrankenPHP workers. Compose reads `APP_ENV` / `APP_DEBUG` / `APP_SECRET` from `.env` (defaults `dev` / `1`):

```bash
cd demo/symfony8
cp .env.example .env    # if not present
# In .env: APP_ENV=prod, APP_DEBUG=0, a real APP_SECRET, FRANKENPHP_MODE=worker
docker compose up -d
docker compose exec php composer install --no-interaction
docker compose exec php php bin/console cache:clear --env=prod
docker compose exec php php bin/console assets:install public --env=prod
curl -I http://localhost:8073/demo   # HTTP 200
docker compose down
```

In `prod` the Web Profiler, Twig Inspector, Debug and Hot Reload bundles are not registered (`config/bundles.php`), so no toolbar is
shown and `hot_reload` / `watch` in the Caddyfile have no effect on the application. For a real deployment build your own image
from `demo/symfony8/Dockerfile` with the application code copied in (no bind mounts), `composer install --no-dev --optimize-autoloader`,
and keep `FRANKENPHP_MODE=worker` (see the [worker audit](FRANKENPHP-WORKER-AUDIT.md) for `FRANKENPHP_RESET_KERNEL=false`).
Switch `.env` back to `APP_ENV=dev` / `APP_DEBUG=1` before using `make up` again.

## Troubleshooting

| Symptom | Cause | Fix |
| --- | --- | --- |
| `Bind for 0.0.0.0:8073 failed: port is already allocated` | Another container or service uses the port | Stop it, or set another `PORT` in `demo/symfony8/.env` and run `make up` again (the URL printed follows `PORT`) |
| `make up` prints `Demo php container failed to stay up` | Entrypoint error (bad `FRANKENPHP_MODE`, Composer failure) | `docker compose logs php`; `FRANKENPHP_MODE` must be `worker` or `classic` |
| Composer `curl error 6` / cannot resolve `repo.packagist.org` | Docker/WSL DNS | Compose already sets `dns: 8.8.8.8, 8.8.4.4`; restart Docker Desktop/WSL if it persists |
| Changes to PHP/Twig not visible | Worker kept old code, or Twig cache | Check `watch` is in the Caddyfile and the container was recreated (`docker compose up -d`); or use `FRANKENPHP_MODE=classic`; `make cache-clear` |
| Changing `.env` has no effect | `docker compose restart` does not reload env | `docker compose up -d` (recreates the container) |
| Tag field renders as a plain input (no Tagify) | Bundle assets not published in the demo | `make -C demo/symfony8 update-bundle` (runs `assets:install public`); rebuild with `make assets` at the root if you changed TypeScript |
| Web Profiler toolbar missing | Not in `dev`, or stale cache | `APP_ENV=dev`, `APP_DEBUG=1` in `.env`; `make cache-clear`; open `/_profiler` |
| `vendor/nowo-tech/tag-input-bundle` is not the local code | Symlink lost after `composer install` | `make -C demo/symfony8 link-bundle` |
| HTTP 500 after switching to `prod` | Cache built for another env, or missing `APP_SECRET` | `php bin/console cache:clear --env=prod`; set `APP_SECRET` in `.env` |

This bundle performs no blocking I/O (no HTTP clients, subprocesses or document conversion), so no timeout hierarchy
(REQ-RUNTIME-001) applies to the demo.

## Demo smoke (REQ-TEST-011)

From the repository root:

```bash
make demo-smoke
```

Boots `demo/symfony8`, asserts **HTTP 200** on `http://127.0.0.1:<PORT>/demo` (from `.env` / `.env.example`, default **8073**), then tears down. CI: `.github/workflows/demo-smoke.yml`.

Per-demo: `make -C demo/symfony8 verify`.

## Switching classic vs worker (`FRANKENPHP_MODE`)

Demos select the FrankenPHP runtime via **`FRANKENPHP_MODE`** in `.env` / `.env.example` (not a Dockerfile `ENV`):

| Value | Behaviour |
| --- | --- |
| **`worker`** (default) | Keep the worker Caddyfile (`php_server { worker ... }`) |
| **`classic`** | Entrypoint copies `Caddyfile.dev` (plain `php_server`, hot-reload friendly) |

Compose passes `FRANKENPHP_MODE=${FRANKENPHP_MODE:-worker}` into the PHP service. After changing `.env`, run `docker compose up -d` (or `make up`) so the container is **recreated** — a plain `restart` does not reload env. No image rebuild is required.
