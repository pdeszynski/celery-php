SHELL := /bin/bash
COMPOSER := composer.phar
PHPUNIT=$(CURDIR)/vendor/bin/phpunit
PHP := $(shell which php)

#COLORS
GREEN  := $(shell tput -Txterm setaf 2)
WHITE  := $(shell tput -Txterm setaf 7)
YELLOW := $(shell tput -Txterm setaf 3)
RED	   := $(shell tput -Txterm setaf 1)
RESET  := $(shell tput -Txterm sgr0)

composer = \
	@echo -e "${GREEN}Installing/updating composer and libs${RESET}"; \
	if [ ! -f ./composer.phar ]; then \
		curl -sS https://getcomposer.org/installer | $(PHP); \
	fi; \
	$(PHP) $(COMPOSER) self-update; \
	$(PHP) $(COMPOSER) $(1)

phpunit = \
	@echo -e "${GREEN}Running phpunit test${RESET}"; \
	cd tests; \
	CMD="$(PHPUNIT)"; \
	if [ "x$(1)" != "x" ] ; then \
		CMD="$$CMD --filter $(1)"; \
	fi;	\
	if [ "x$(2)" != "x" ] ; then \
		CMD="$$CMD $(2)"; \
	fi; \
	if [ "x$(3)" != "x" ] ; then \
		CMD="$$CMD --debug"; \
	fi; \
	$$CMD

help:
	@echo -e "*****************************************\n"
	@echo -e "${GREEN}Celery tasks${RESET}\n"
	@echo -e "Please use one of the specified targets"
	@echo -e "${YELLOW}init:${RESET}\n Initializes environment with all necessary libs"
	@echo -e "${YELLOW}composer:${RESET}\n Installs composer required libs"
	@echo -e "${YELLOW}phpunit:${RESET}\n Run all phpunit tests suports optional params: [filter=<test_name> [class=<class_path> [debug=true]]]"
	@echo -e "${YELLOW}clean:${RESET}\n Cleans environment"

init: composer

phpunit:
	$(call phpunit,$(filter),$(class),$(debug))

clean:
	rm -rf vendor

composer:
	$(call composer,install)

.PHONY: help init clean composer