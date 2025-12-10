# E-RADIOLOGIE

Application **full-stack** basée sur :
- **Backend** : Symfony 8 + API Platform (API REST / JSON)
- **Frontend** : Vue 3 + Vite (TypeScript)
- **Base de données** : MySQL 8
- **Infrastructure** : Docker & Docker Compose

---

## 1. Objectif du projet

E-RADIOLOGIE est une application orientée API destinée à gérer des données liées à la radiologie (patients, examens, radiologues, etc.).

L’architecture sépare clairement :
- un **backend API** (Symfony / API Platform)
- un **frontend SPA** (Vue.js)

---

## 2. Prérequis

Assure-toi d’avoir installé :

- Docker & Docker Compose
- Node.js **20.19+ ou 22.x LTS**
- npm
- (Optionnel) Make

Vérification rapide :

```bash
node -v
docker -v
docker compose version
```

---

## 3. Architecture du projet

```text
E-RADIOLOGIE/
├── backend/           # Symfony 8 + API Platform
├── frontend/          # Vue 3 + Vite
├── docker-compose.yml # Orchestration Docker
└── Makefile           # Commandes utilitaires
```

### 3.1 Backend (Symfony)

- PHP 8.4 (PHP-FPM)
- API Platform
- Nginx en reverse proxy
- MySQL via Docker

Dossier clé :
```
backend/
├── src/        # Entités, contrôleurs, services
├── public/     # index.php
├── migrations/ # Migrations Doctrine
├── nginx/      # Config Nginx
└── Dockerfile  # Image PHP
```

### 3.2 Frontend (Vue)

- Vue 3
- Vite
- TypeScript
- ESLint / Prettier

```
frontend/
├── src/
├── index.html
├── vite.config.ts
└── package.json
```

---

## 4. Services & Ports

| Service       | URL / Port |
|--------------|------------|
| API Symfony  | http://localhost:8090/api |
| Frontend Vue | http://localhost:5173 |
| phpMyAdmin   | http://localhost:8081 |
| MySQL (host) | localhost:3307 |

---

## 5. Lancer le projet

### 5.1 Backend (Docker)

À la racine du projet :

```bash
make up
```

API disponible sur :
```text
http://localhost:8090/api
```

### 5.2 Frontend

```bash
make front-install
make front-dev
```

Frontend disponible sur :
```text
http://localhost:5173
```

---

## 6. Base de données

### Accès phpMyAdmin

```
http://localhost:8081
```

Identifiants par défaut :
- User : `root`
- Password : `root`
- DB : `eradiologie`

### Commandes Doctrine

```bash
make db-create
make migrate
make migrate-diff
```

---

## 7. Makefile – commandes utiles

Quelques commandes importantes :

```bash
make help          # liste des commandes
make up            # lancer les containers
make down          # arrêter les containers
make bash          # entrer dans le conteneur PHP
make migrate       # migrations Doctrine
make update-structure # créer une migration à partir des entités
make front-install # installer les dépendances frontend
make front-dev     # frontend Vue
```

---

## 8. Communication Front / Back

Le frontend appelle l’API via :

```text
http://localhost:8090/api
```

Exemple côté Vue :

```ts
axios.get('/patients')
```

---

## 9. Bonnes pratiques

- Toujours lancer Docker **avant** le frontend
- Utiliser le `Makefile` pour éviter les commandes longues
- Ne jamais utiliser `localhost` côté Symfony pour la DB (utiliser `db`)
- Versionner uniquement le code (pas `node_modules`, pas `vendor`)

---

## 10. Prochaines évolutions possibles

- Authentification JWT
- Gestion des rôles (admin, radiologue)
- Upload de fichiers médicaux
- Dockerisation du frontend
- CI/CD

---

## Auteur

Projet **E-RADIOLOGIE** – environnement de développement Dockerisé
