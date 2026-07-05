.PHONY: help up down build install shell logs test test-coverage cache-clear link-bundle update-bundle ensure-up restart release-check

help:
	@echo "Symfony 8 Demo - Tag Input Bundle"

up:
	@if [ ! -f .env ]; then cp .env.example .env 2>/dev/null || true; fi
	docker-compose up -d
	@echo "Waiting for container to be ready..."
	@sleep 5
	@echo "Installing dependencies..."
	@docker-compose exec -T -e COMPOSER_MEMORY_LIMIT=-1 php composer install --no-interaction
	@$(MAKE) link-bundle
	@PORT=$$(grep "^PORT=" .env 2>/dev/null | cut -d= -f2 | tr -d '\r'); \
	[ -z "$$PORT" ] && PORT=$$(grep "^PORT=" .env.example 2>/dev/null | cut -d= -f2 | tr -d '\r'); \
	echo "Demo started at: http://localhost:$$PORT"

down:
	docker-compose down

restart: ensure-up
	docker-compose restart

build:
	docker-compose build --no-cache

ensure-up:
	@if ! docker-compose exec -T php true 2>/dev/null; then docker-compose up -d; sleep 3; fi

install: ensure-up
	docker-compose exec -T -e COMPOSER_MEMORY_LIMIT=-1 php composer install --no-interaction

shell: ensure-up
	docker-compose exec php sh

logs:
	docker-compose logs -f

test: ensure-up
	docker-compose exec -e APP_ENV=test php composer test

test-coverage: ensure-up
	docker-compose exec php composer test-coverage

cache-clear: ensure-up
	docker-compose exec php php bin/console cache:clear

link-bundle: ensure-up
	docker-compose exec php sh -c "rm -rf vendor/nowo-tech/tag-input-bundle && ln -s /var/tag-input-bundle vendor/nowo-tech/tag-input-bundle && php bin/console cache:clear && php bin/console assets:install public"

update-bundle: ensure-up
	docker-compose exec php composer update nowo-tech/tag-input-bundle --no-interaction
	docker-compose exec php sh -c "php bin/console cache:clear --env=dev && php bin/console assets:install public"

release-check: ensure-up update-bundle test test-coverage

# REQ-MAKE-008: update-deps (REQ-MAKE-008)
COMPOSE := docker-compose
SERVICE_PHP := php
include $(abspath $(dir $(lastword $(MAKEFILE_LIST)))/../../../.scripts/Makefile.demo-update-deps.mk)
