# Tag Input Bundle — Demo (Symfony 7)

This demo runs with **FrankenPHP** (Caddy, HTTP on port 80). In **dev** (`APP_ENV=dev`), worker mode is disabled so each request runs in a new PHP process and **code/template changes are visible on refresh** without restarting the container.

## Quick start

```bash
make up
# Open http://localhost:8010 (or the PORT set in .env)
```

## Demo URLs

- `/` — Redirects to `/demo`
- `/demo` — List of TagType examples
- `/demo/tags/basic` — Free-form tags (array model value)
- `/demo/tags/whitelist` — Whitelist with Tagify dropdown
- `/demo/tags/comma-string` — Comma-separated string model value

## Web Profiler toolbar

The demo has **Web Profiler** and **Nowo Twig Inspector** enabled in `dev`. The toolbar is shown at the bottom of the page when:

- `APP_ENV=dev` and `APP_DEBUG=1` (default in `.env`)
- You have run `make up` (Composer install runs automatically)

If the toolbar does not appear, clear the cache inside the container:

```bash
docker-compose exec php php bin/console cache:clear --env=dev
```

Then reload the page. You can also open `/_profiler` to see the latest requests.

## Commands

- `make up` — Build and start the container (FrankenPHP). After changing Dockerfile or Caddyfile, run `make build` or `docker-compose build` then `make up`.
- `make down` — Stop the container
- `make install` — Composer install (and cache:clear)
- `make shell` — Open a shell in the container
- `make update-bundle` — Sync the mounted bundle source into the container
- `make release-check` — Run demo smoke tests

See also [docs/DEMO-FRANKENPHP.md](../../docs/DEMO-FRANKENPHP.md) in the bundle root.
