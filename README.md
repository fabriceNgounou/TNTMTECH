# TNTMTECH

Plateforme Laravel 12 en français dédiée aux prestations de services informatiques
de TNTMTECH : services, devis, formations, réalisations, candidatures et contacts.

## Prérequis

- PHP 8.2 ou supérieur avec `pdo_sqlite`, `mbstring`, `openssl`, `fileinfo`
- Composer 2

Le front-end utilise des fichiers CSS et JavaScript natifs. Node.js n'est pas requis.

## Installation locale

```bash
composer install
copy .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

Compte de démonstration créé en local :

- Email : `admin@tntmtech.cm`
- Mot de passe : `Tntmtech@2026`

Ce mot de passe est public : il ne doit **jamais** être utilisé en production.
En production, définissez `ADMIN_EMAIL` et `ADMIN_PASSWORD` dans les variables
d'environnement avant de lancer le seeder.

## Configuration

Les numéros WhatsApp et l'adresse email principale sont configurables dans `.env`.
Chaque fiche de service propose une demande de devis et un accès WhatsApp avec
le nom de la prestation prérempli dans le message.

```dotenv
TNTMTECH_WHATSAPP_DOUALA=237676388135
TNTMTECH_WHATSAPP_YAOUNDE=237650600990
TNTMTECH_WHATSAPP_FRANCE=33756992282
TNTMTECH_EMAIL=contact@tntmtech.cm
```

Le projet utilise SQLite en local et MySQL en production.

---

# Déploiement sur Railway

L'application est livrée sous forme d'image Docker : Apache sert le dossier
`public/` et écoute sur le port fourni par Railway via `$PORT`.

| Fichier | Rôle |
| --- | --- |
| `Dockerfile` | Image PHP 8.4 + Apache, extensions et dépendances |
| `docker/entrypoint.sh` | Migrations, caches et permissions au démarrage |
| `docker/vhost.conf` | Hôte virtuel Apache pointant sur `public/` |
| `docker/ports.conf` | Écoute sur `$PORT` |
| `docker/php.ini` | Réglages PHP et OPcache pour la production |
| `railway.json` | Builder Docker et sonde de santé `/up` |
| `.env.railway.example` | Liste des variables à définir sur Railway |

## 1. Générer la clé applicative

À exécuter en local, une seule fois. Conservez la valeur : elle chiffre les
sessions et les données sensibles.

```bash
php artisan key:generate --show
```

La commande affiche une valeur de la forme `base64:xxxxxxxx...`. Copiez-la
intégralement, préfixe compris.

## 2. Créer le projet et la base de données

1. Sur [railway.app](https://railway.app), cliquez sur **New Project**
   puis **Deploy from GitHub repo** et sélectionnez `TNTMTECH`.
2. Dans le projet, cliquez sur **New** → **Database** → **Add MySQL**.

Railway détecte automatiquement le `Dockerfile` à la racine du dépôt.

## 3. Renseigner les variables d'environnement

Ouvrez le service applicatif, onglet **Variables**, puis **Raw Editor**.
Collez le contenu de `.env.railway.example` et complétez les valeurs vides :

- `APP_KEY` : la clé générée à l'étape 1
- `ADMIN_PASSWORD` : un mot de passe fort de votre choix
- `APP_URL` : à compléter à l'étape 5

Les variables `DB_*` utilisent des références Railway
(`${{MySQL.MYSQLHOST}}`, etc.) : saisissez-les telles quelles, accolades
comprises. Railway les résout automatiquement et les met à jour si la base
est recréée.

## 4. Premier déploiement avec les données de départ

Passez `RUN_SEEDERS` à `true` **uniquement pour ce premier déploiement**, afin
de créer le compte administrateur et les contenus initiaux (services,
formations, offre d'emploi).

Lancez le déploiement. Dans les journaux, vous devez lire successivement :

```
[TNTMTECH] Base de données joignable.
[TNTMTECH] Application des migrations...
[TNTMTECH] Insertion des données initiales...
[TNTMTECH] Application prête, passage à Apache.
```

Une fois le déploiement réussi, **repassez `RUN_SEEDERS` à `false`**. Le seeder
utilise `updateOrCreate` : le laisser actif réinitialiserait le mot de passe
administrateur à chaque redémarrage.

## 5. Exposer le service

Onglet **Settings** → **Networking** → **Generate Domain**. Railway attribue
une adresse en `.up.railway.app`.

Reportez cette adresse dans la variable `APP_URL` (avec le préfixe `https://`),
puis redéployez. Sans cela, les liens absolus et les courriels pointeront vers
une mauvaise adresse.

## Vérifications après mise en ligne

- `https://votre-service.up.railway.app/up` renvoie une réponse de santé
- La page d'accueil s'affiche avec ses styles
- Une page interne (`/services`) répond — cela valide la réécriture d'URL
- La connexion à `/connexion` fonctionne avec `ADMIN_EMAIL` / `ADMIN_PASSWORD`,
  et l'administration `/administration` est accessible ensuite

## Domaine personnalisé

Dans **Settings** → **Networking** → **Custom Domain**, ajoutez `tntmtech.cm`,
puis créez chez votre registraire l'enregistrement CNAME indiqué par Railway.
Mettez `APP_URL` à jour ensuite.

## Points d'attention

**Le disque est éphémère.** À chaque déploiement, le conteneur repart d'une
image neuve. Les sessions, le cache et les files d'attente sont donc stockés en
base (`SESSION_DRIVER=database`, `CACHE_STORE=database`,
`QUEUE_CONNECTION=database`).

**Les CV de candidature exigent un volume.** Le formulaire `/carrieres`
enregistre chaque CV sur le disque (`storage/app/private/applications`). Sans
volume, ces fichiers disparaissent au déploiement suivant : les candidatures
resteraient visibles en base, mais les CV seraient introuvables.

Créez donc un volume avant d'ouvrir le site au public :

**Settings** → **Volumes** → **New Volume**, avec pour point de montage :

```
/var/www/html/storage/app
```

L'entrypoint recrée l'arborescence attendue au premier démarrage. Un volume
Railway est attaché à un seul conteneur : gardez `numReplicas` à `1`.

Pour une montée en charge ultérieure, remplacez le volume par un stockage objet
compatible S3 et basculez le disque `local` vers `s3` dans
`config/filesystems.php`.

**Le mot de passe administrateur par défaut est public.** Il figure dans ce
dépôt. Définissez `ADMIN_PASSWORD` avant le premier seeding.

**`APP_DEBUG` doit rester à `false`.** À `true`, Laravel affiche les traces
d'erreur et les variables d'environnement aux visiteurs.

## Tester l'image en local

```bash
docker build -t tntmtech .
docker run --rm -p 8080:8080 \
  -e APP_KEY="base64:votre-cle" \
  -e APP_ENV=production \
  -e DB_CONNECTION=mysql \
  -e DB_HOST=host.docker.internal \
  -e DB_DATABASE=tntmtech \
  -e DB_USERNAME=root \
  -e DB_PASSWORD=secret \
  tntmtech
```

L'application répond alors sur `http://localhost:8080`.
