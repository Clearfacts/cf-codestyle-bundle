# cf-air makefile

.DEFAULT_GOAL := list

.PHONY: list
list:
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

init: ## Setup this project.
	@make composer
	@make setup

# Composer commands
composer: ## Do a composer install for the php project.
	@composer install

# Linting and testing
setup: ## Setup git-hooks
	@composer run set-up

copy-phpcs-config: ## Setup phpcs config
	@composer run copy-phpcs-config

options?=
files?=src/
phpcs: ## Check phpcs.
	@bin/php-cs-fixer fix --dry-run --diff --using-cache=no --allow-risky=yes --ansi $(options) $(files)

phpcs-fix: ## Check phpcs and try to automatically fix issues.
	@bin/php-cs-fixer fix --diff --using-cache=no --allow-risky=yes --ansi $(options) $(files)

args?="tests"
test: ## Run tests.
	@bin/phpunit $(args)