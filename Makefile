# Makefile for local development tasks
.PHONY: help install lint phpcs test migrate ci

help:
	@echo "Usage: make [target]"
	@echo "Targets: install, lint, phpcs, test, migrate, ci"

install:
	@if command -v composer >/dev/null 2>&1 ; then \
		composer install; \
	elif [ -f composer.phar ] ; then \
		php composer.phar install; \
	else \
		echo "Composer not found. Install with 'brew install composer' or see https://getcomposer.org/download/"; \
		exit 1; \
	fi

lint:
	# Syntax check PHP files
	find . -name '*.php' -not -path './vendor/*' -not -path './.github/*' -not -path './node_modules/*' -exec php -l {} \\;

phpcs:
	composer phpcs

test:
	composer test

migrate:
	composer migrate

ci: lint phpcs test
	@echo "CI checks complete."