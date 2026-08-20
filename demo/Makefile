DEMOS := symfony8

.PHONY: help up down test test-coverage release-check verify demo-smoke $(foreach demo,$(DEMOS),up-$(demo) down-$(demo) test-$(demo) test-coverage-$(demo) verify-$(demo))

help:
	@echo "Tag Input Bundle Demo"
	@echo "make up-symfony8 | down-symfony8"
	@echo "make verify DEMO=symfony8 | demo-smoke"

up-symfony8:
	@$(MAKE) -C symfony8 up

down-symfony8:
	@$(MAKE) -C symfony8 down

test-symfony8:
	@$(MAKE) -C symfony8 test

test-coverage-symfony8:
	@$(MAKE) -C symfony8 test-coverage

verify-symfony8:
	@$(MAKE) -C symfony8 verify

up:
	@[ -n "$(DEMO)" ] || { echo "Usage: make up DEMO=symfony8"; exit 1; }
	@$(MAKE) -C $(DEMO) up

down:
	@[ -n "$(DEMO)" ] || { echo "Usage: make down DEMO=symfony8"; exit 1; }
	@$(MAKE) -C $(DEMO) down

test:
	@[ -n "$(DEMO)" ] || { echo "Usage: make test DEMO=symfony8"; exit 1; }
	@$(MAKE) -C $(DEMO) test

test-coverage:
	@[ -n "$(DEMO)" ] || { echo "Usage: make test-coverage DEMO=symfony8"; exit 1; }
	@$(MAKE) -C $(DEMO) test-coverage

verify:
	@[ -n "$(DEMO)" ] || { echo "Usage: make verify DEMO=symfony8"; exit 1; }
	@$(MAKE) verify-$(DEMO)

# REQ-TEST-011: boot primary demo + one HTTP 200
demo-smoke:
	@PORT=$$(grep "^PORT=" symfony8/.env 2>/dev/null | cut -d= -f2 | tr -d '\r'); \
	[ -z "$$PORT" ] && PORT=$$(grep "^PORT=" symfony8/.env.example 2>/dev/null | cut -d= -f2 | tr -d '\r'); \
	[ -z "$$PORT" ] && PORT=8011; \
	echo "Demo smoke: symfony8 (http://127.0.0.1:$$PORT/demo)"; \
	$(MAKE) -C symfony8 up || exit 1; \
	code=000; \
	for i in 1 2 3 4 5 6 7 8 9 10 11 12 13 14 15; do \
		code=$$(curl -s -o /dev/null -w "%{http_code}" --max-time 10 "http://127.0.0.1:$$PORT/demo" || true); \
		[ -z "$$code" ] && code=000; \
		if [ "$$code" = "200" ]; then break; fi; \
		sleep 2; \
	done; \
	$(MAKE) -C symfony8 down 2>/dev/null || true; \
	if [ "$$code" != "200" ]; then echo "symfony8 failed with HTTP $$code"; exit 1; fi; \
	echo "symfony8 OK (HTTP $$code)"

release-check:
	@$(MAKE) -C symfony8 release-check

update-bundle:
	@$(MAKE) -C symfony8 update-bundle

# REQ-MAKE-008: update-deps-all (REQ-MAKE-008)
BUNDLE_ROOT := $(abspath $(dir $(lastword $(MAKEFILE_LIST)))/..)
# Optional: monorepo helper absent on standalone GitHub Actions checkout (REQ-MAKE-009).
-include $(BUNDLE_ROOT)/../.scripts/Makefile.demo-aggregate-update-deps.mk
