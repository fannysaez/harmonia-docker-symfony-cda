# Harmonia - App Web Streaming Musical

**Harmonia** est une application web de streaming musical développée avec **Symfony 7**, dans le cadre d'un projet de formation. Elle permet aux utilisateurs de découvrir des albums, des artistes et des genres musicaux, de gérer leurs favoris et leurs playlists, et aux administrateurs de gérer l'ensemble du contenu via un tableau de bord dédié.

---

## Captures d'écran

| Page d'accueil | Liste des albums | Dashboard admin |
|---|---|---|
| ![Accueil](screenshots/home.png) | ![Albums](screenshots/albums.png) | ![Admin](screenshots/admin.png) |

---

## Technologies utilisées

- **PHP 8.2** / **Symfony 7**
- **Twig** — moteur de templates
- **Doctrine ORM** — gestion de la base de données
- **MariaDB** (via **Docker**) — base de données relationnelle
- **Bootstrap 5** + **Bootstrap Icons** — interface utilisateur
- **Extension Twig personnalisée** — injection des genres dans la navbar
- **Docker** — conteneurisation de la base de données (MariaDB, port 3506)
- **Serveur Ubuntu** — environnement de déploiement

---

## Fonctionnalités

### Côté utilisateur
- Parcourir les albums, artistes et genres musicaux
- Écouter et comptabiliser les écoutes par morceau
- Ajouter/retirer des morceaux en favoris
- Créer et gérer ses playlists
- Ajouter des morceaux à une playlist depuis la page d'un album
- Consulter son profil (favoris, historique d'écoute, playlists)

### Côté administrateur
- Tableau de bord avec statistiques (artistes, albums, morceaux, playlists)
- Gestion complète (CRUD) : Artistes, Albums, Genres, Tracks, Playlists, Utilisateurs
- Pagination sur les listes longues (albums, tracks)
- Sidebar de navigation dédiée

---

## Structure du projet

```
src/
├── Controller/
│   ├── AdminController.php
│   ├── AlbumController.php
│   ├── ArtistController.php
│   ├── FavoriteController.php
│   ├── GenreController.php
│   ├── HomeController.php
│   ├── PlaylistController.php
│   ├── TrackController.php
│   └── UserController.php
├── Entity/
│   ├── Album.php
│   ├── Artist.php
│   ├── Favorite.php
│   ├── Genre.php
│   ├── Playlist.php
│   ├── Track.php
│   └── User.php
├── Form/
│   ├── AlbumType.php
│   ├── ArtistType.php
│   ├── GenreType.php
│   ├── PlaylistType.php
│   └── TrackType.php
├── Repository/
└── Twig/
    └── AppExtension.php
templates/
├── admin/
│   ├── _sidebar.html.twig
│   ├── index.html.twig
│   ├── tracks.html.twig
│   ├── users.html.twig
│   ├── albums/index.html.twig
│   ├── artists/index.html.twig
│   ├── genres/index.html.twig
│   └── playlists/index.html.twig
├── album/
├── artist/
├── component/
├── genre/
├── playlist/
└── base.html.twig
```

---

## Rôles utilisateur

| Rôle | Accès |
|---|---|
| Visiteur | Accueil, Albums, Artistes, Genres |
| `ROLE_USER` | Favoris, Playlists, Profil, Écoutes |
| `ROLE_ADMIN` | Dashboard admin + tout le CRUD |

---

## Principales routes

| Route | URL | Description |
|---|---|---|
| `app_home` | `/` | Page d'accueil |
| `app_albums` | `/albums` | Liste des albums |
| `app_album_show` | `/album/{id}` | Détail d'un album |
| `app_artist_list` | `/artists` | Liste des artistes |
| `app_genre_list` | `/admin/genres` | Liste des genres |
| `app_profil` | `/profil` | Profil utilisateur |
| `app_handle_favorite` | `/handle-favorite/{id}` | Ajouter/retirer un favori |
| `app_playlist_add_track` | `/playlist/{id}/add-track/{track_id}` | Ajouter un morceau à une playlist |
| `app_admin_dashboard` | `/admin` | Tableau de bord admin |
| `app_admin_artists` | `/admin/artists` | Gestion artistes |
| `app_admin_albums` | `/admin/albums` | Gestion albums |
| `app_admin_genres` | `/admin/genres` | Gestion genres |
| `app_admin_tracks` | `/admin/tracks` | Gestion tracks |
| `app_admin_playlists` | `/admin/playlists` | Gestion playlists |
| `app_admin_users` | `/admin/users` | Gestion utilisateurs |

---

## Prérequis

- Docker et Docker Compose

## Installation

```bash
# 1. Construire et démarrer les conteneurs
docker compose up -d --build

# 2. Installer les dépendances PHP
docker compose exec php composer install

# 3. Créer le fichier .env.local avec l'accès à la base
echo 'DATABASE_URL="mysql://root:root@db:3306/harmonia?serverVersion=10.6.25-MariaDB&charset=utf8mb4"' > .env.local

# 4. Créer la base et jouer les migrations
docker compose exec php php bin/console doctrine:database:create --if-not-exists
docker compose exec php php bin/console doctrine:migrations:migrate --no-interaction

# 5. Charger les fixtures (données de test)
docker compose exec php php bin/console doctrine:fixtures:load --no-interaction
```

## Accès

| Service    | URL                   |
|------------|-----------------------|
| Site       | http://localhost:8000 |
| phpMyAdmin | http://localhost:9988 |

Base de données depuis l'hôte (client SQL) : `127.0.0.1:3506`, utilisateur `root`, mot de passe `root`.

> ⚠️ Dans `.env.local`, l'hôte est `db:3306` (nom du service Docker) et non `127.0.0.1:3506` :
> Symfony tourne dans le conteneur `php`, où `127.0.0.1` désigne le conteneur lui-même.

## Commandes utiles

```bash
# Démarrer / arrêter
docker compose up -d
docker compose down

# Voir les logs
docker compose logs -f php
docker compose logs -f db

# Ouvrir un terminal dans le conteneur PHP
docker compose exec php bash

# Console Symfony
docker compose exec php php bin/console <commande>

# Composer
docker compose exec php composer require <paquet>

# Créer une migration après modification d'une entité, puis l'appliquer
docker compose exec php php bin/console make:migration
docker compose exec php php bin/console doctrine:migrations:migrate --no-interaction

# Recharger les fixtures (⚠️ vide la base)
docker compose exec php php bin/console doctrine:fixtures:load --no-interaction

# Vider le cache
docker compose exec php php bin/console cache:clear

# Lancer les tests
docker compose exec php php bin/phpunit
```

## Réinitialiser complètement la base

```bash
docker compose exec php php bin/console doctrine:database:drop --force
docker compose exec php php bin/console doctrine:database:create
docker compose exec php php bin/console doctrine:migrations:migrate --no-interaction
docker compose exec php php bin/console doctrine:fixtures:load --no-interaction
```

## Charger le jeu de données SQL (`docker/harmonia_cda.sql`)

Le dump ne contient que des `INSERT` : le schéma doit exister (migrations jouées) et les tables doivent être vides.

- Base de données locale
```bash
docker compose exec -T db mariadb -uroot -proot harmonia < docker/harmonia_cda.sql
```

- Base de données test
```bash
docker compose exec php php bin/console doctrine:database:create --if-not-exists --env=test
docker compose exec php php bin/console doctrine:migrations:migrate --no-interaction --env=test
docker compose exec -T db mariadb -uroot -proot harmonia_cda_test < docker/harmonia_cda.sql
```

---

## Auteur

Développé par **Fanny** — Formation Conceptrice Développeuse Application 2026

---

## Licence

Projet réalisé dans le cadre d'une formation — usage éducatif uniquement.
