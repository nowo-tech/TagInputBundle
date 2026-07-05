DEMOS := symfony7 symfony8

.PHONY: help up down test test-coverage release-check $(foreach demo,$(DEMOS),up-$(demo) down-$(demo) test-$(demo) test-coverage-$(demo))

help:
	@echo "Tag Input Bundle Demo"
	@echo "make up-symfony7 | up-symfony8 | down-symfony7 | down-symfony8"

up-symfony7:
	@$(MAKE) -C symfony7 up

up-symfony8:
	@$(MAKE) -C symfony8 up

down-symfony7:
	@$(MAKE) -C symfony7 down

down-symfony8:
	@$(MAKE) -C symfony8 down

test-symfony7:
	@$(MAKE) -C symfony7 test

test-symfony8:
	@$(MAKE) -C symfony8 test

test-coverage-symfony7:
	@$(MAKE) -C symfony7 test-coverage

test-coverage-symfony8:
	@$(MAKE) -C symfony8 test-coverage

up:
	@[ -n "$(DEMO)" ] || { echo "Usage: make up DEMO=symfony7|symfony8"; exit 1; }
	@$(MAKE) -C $(DEMO) up

down:
	@[ -n "$(DEMO)" ] || { echo "Usage: make down DEMO=symfony7|symfony8"; exit 1; }
	@$(MAKE) -C $(DEMO) down

test:
	@[ -n "$(DEMO)" ] || { echo "Usage: make test DEMO=symfony7|symfony8"; exit 1; }
	@$(MAKE) -C $(DEMO) test

test-coverage:
	@[ -n "$(DEMO)" ] || { echo "Usage: make test-coverage DEMO=symfony7|symfony8"; exit 1; }
	@$(MAKE) -C $(DEMO) test-coverage

release-check:
	@$(MAKE) -C symfony7 release-check
	@$(MAKE) -C symfony8 release-check


# REQ-MAKE-008: update-deps-all (REQ-MAKE-008)
BUNDLE_ROOT := $(abspath $(dir $(lastword $(MAKEFILE_LIST)))/..)
include $(BUNDLE_ROOT)/../.scripts/Makefile.demo-aggregate-update-deps.mk
