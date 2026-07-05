# Demo notes

This bundle includes `demo/symfony7` and `demo/symfony8` with sample Symfony applications.

Each demo has its own `docker-compose.yml`, `Dockerfile`, and `docker/frankenphp/` (Caddyfile variants) for local development.

The **repository root** `docker-compose.yml` is for **bundle** development (PHP, Composer, pnpm/Vite, tests). It is not the same as launching a demo as a standalone hosted app.

To run a demo, follow the README inside `demo/symfony7` or `demo/symfony8`.

FrankenPHP worker mode is not declared as supported for this bundle at the moment; see the main [README](../README.md).
