# TNTMTECH

Plateforme Laravel 11 en français dédiée aux prestations de services informatiques
de TNTMTECH : services, devis, formations, réalisations, candidatures et contacts.

## Pré requis

- PHP 8.2 ou supérieur avec `pdo_sqlite`, `mbstring`, `openssl`, `fileinfo`
- Composer 2

Le front-end utilise des fichiers CSS et JavaScript natifs. Node.js n'est pas requis.

## Installation

```bash
composer install
copy .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

Compte de démonstration :

- Email : `admin@tntmtech.cm`
- Mot de passe : `Tntmtech@2026`

Changez ce mot de passe avant toute mise en ligne.

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

Le projet utilise SQLite par défaut. Pour MySQL, configurez les variables `DB_*`
adaptées à votre environnement.
