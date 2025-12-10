# ===============================
# Variables générales
# ===============================
PROJECT_NAME := E-RADIOLOGIE
DC           := docker compose
PHP_SERVICE  := php
BACK_DIR     := backend
FRONT_DIR    := frontend

# ===============================
# Cibles principales
# ===============================

.PHONY: help up down restart ps logs build destroy \
        bash sh phpunit composer console \
        db-create db-drop migrate migrate-diff fixtures \
        front-install front-dev front-build front-test front-lint

help:
	@echo "---- $(PROJECT_NAME) : commandes utiles ----"
	@echo ""
	@echo " Docker :"
	@echo "   make up            -> lancer tous les conteneurs"
	@echo "   make down          -> arrêter les conteneurs"
	@echo "   make restart       -> redémarrer les conteneurs"
	@echo "   make build         -> rebuild les images"
	@echo "   make logs          -> suivre les logs"
	@echo "   make ps            -> lister les services"
	@echo "   make destroy       -> tout arrêter + supprimer volumes"
	@echo ""
	@echo " Backend Symfony :"
	@echo "   make composer      -> composer install dans le conteneur"
	@echo "   make console cmd='about'  -> exécuter une commande Symfony"
	@echo "   make db-create     -> créer la base (si nécessaire)"
	@echo "   make db-drop       -> drop la base"
	@echo "   make migrate       -> exécuter les migrations"
	@echo "   make migrate-diff  -> générer une migration à partir des entités"
	@echo "   make fixtures      -> charger les fixtures (si tu en as)"
	@echo "   make bash          -> shell dans le conteneur PHP"
	@echo ""
	@echo " Frontend Vue :"
	@echo "   make front-install -> npm install"
	@echo "   make front-dev     -> lancer Vite en mode dev"
	@echo "   make front-build   -> build de prod"
	@echo "   make front-test    -> tests (vitest si config)"
	@echo "   make front-lint    -> eslint"
	@echo ""

# ===============================
# Docker
# ===============================

up:
	$(DC) up -d

down:
	$(DC) down

restart:
	$(DC) down
	$(DC) up -d

build:
	$(DC) build

logs:
	$(DC) logs -f

ps:
	$(DC) ps

destroy:
	$(DC) down -v

bash:
	$(DC) exec -it $(PHP_SERVICE) bash

sh:
	$(DC) exec -it $(PHP_SERVICE) sh

# ===============================
# Backend Symfony / PHP
# ===============================

composer:
	$(DC) exec $(PHP_SERVICE) composer install

# usage : make console cmd='about'  OU  make console cmd='cache:clear'
console:
	$(DC) exec $(PHP_SERVICE) php bin/console $(cmd)

db-create:
	$(DC) exec $(PHP_SERVICE) php bin/console doctrine:database:create --if-not-exists

db-drop:
	$(DC) exec $(PHP_SERVICE) php bin/console doctrine:database:drop --force --if-exists

migrate:
	$(DC) exec $(PHP_SERVICE) php bin/console doctrine:migrations:migrate --no-interaction

migrate-diff:
	$(DC) exec $(PHP_SERVICE) php bin/console doctrine:migrations:diff

update-structure:
	docker compose exec php php bin/console make:migration

fixtures:
	$(DC) exec $(PHP_SERVICE) php bin/console doctrine:fixtures:load --no-interaction

# Si tu ajoutes plus tard phpunit :
phpunit:
	$(DC) exec $(PHP_SERVICE) ./vendor/bin/phpunit

# ===============================
# Frontend Vue / Vite
# ===============================

front-install:
	cd $(FRONT_DIR) && npm install

front-dev:
	cd $(FRONT_DIR) && npm run dev

front-build:
	cd $(FRONT_DIR) && npm run build

front-test:
	cd $(FRONT_DIR) && npm run test || true

front-lint:
	cd $(FRONT_DIR) && npm run lint || true
