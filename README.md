# Visa Dossier

Application de gestion de dossier visa : upload, listing et suppression de fichiers par catégorie.

---

## Backend

### Prérequis

- Docker + Docker Compose

### Installation

```bash
cd backend
composer install
./vendor/bin/sail up -d
./vendor/bin/sail artisan migrate
```

### Lancer les tests

```bash
./vendor/bin/sail artisan test
```

### API

| Méthode | Route | Description |
|---------|-------|-------------|
| GET | `/api/visa-files?type={type}&page={n}&per_page={n}` | Liste paginée |
| POST | `/api/visa-files/{type}` | Upload d'un fichier |
| DELETE | `/api/visa-files/{id}` | Suppression par UUID |

Types acceptés : `passport`, `photo`, `form`

---

## Frontend

### Prérequis

- Node.js 20+

### Installation

```bash
cd frontend
npm install
```

### Lancer

```bash
npm run dev
```

L'application est disponible sur [http://localhost:5173](http://localhost:5173).
