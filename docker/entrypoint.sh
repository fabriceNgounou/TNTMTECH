#!/bin/sh
# ---------------------------------------------------------------------------
# Démarrage du conteneur TNTMTECH sur Railway.
# Prépare l'application (clé, migrations, caches) puis passe la main à Apache.
# ---------------------------------------------------------------------------
set -e

# Railway injecte $PORT ; on garde une valeur de repli pour l'exécution locale.
export PORT="${PORT:-8080}"

echo "[TNTMTECH] Démarrage — environnement : ${APP_ENV:-production}, port : ${PORT}"

# --- Vérification de la clé applicative -------------------------------------
if [ -z "${APP_KEY}" ]; then
    echo ""
    echo "[TNTMTECH] ERREUR : la variable APP_KEY est absente."
    echo "  Générez-en une en local avec :  php artisan key:generate --show"
    echo "  puis ajoutez-la aux variables du service Railway."
    echo ""
    exit 1
fi

# --- Attente de la base de données ------------------------------------------
# Le service MySQL de Railway peut ne pas être joignable dès la première seconde.
attente=0
max_attentes=15
until php artisan db:show --quiet >/dev/null 2>&1; do
    attente=$((attente + 1))
    if [ "${attente}" -ge "${max_attentes}" ]; then
        echo "[TNTMTECH] Base de données injoignable après ${max_attentes} tentatives."
        echo "  Vérifiez les variables DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD."
        exit 1
    fi
    echo "[TNTMTECH] Base de données indisponible, nouvelle tentative (${attente}/${max_attentes})..."
    sleep 3
done
echo "[TNTMTECH] Base de données joignable."

# --- Migrations --------------------------------------------------------------
echo "[TNTMTECH] Application des migrations..."
php artisan migrate --force --no-interaction

# --- Données initiales (optionnel) -------------------------------------------
# Activez RUN_SEEDERS=true sur Railway pour le tout premier déploiement,
# puis remettez la variable à false pour ne pas réinsérer les données.
if [ "${RUN_SEEDERS}" = "true" ]; then
    echo "[TNTMTECH] Insertion des données initiales..."
    php artisan db:seed --force --no-interaction
fi

# --- Stockage des fichiers ---------------------------------------------------
# Si un volume Railway est monté sur storage/app, il arrive vide : on recrée
# l'arborescence attendue par Laravel (CV de candidature, fichiers publics).
mkdir -p /var/www/html/storage/app/private/applications \
         /var/www/html/storage/app/public

if [ ! -e /var/www/html/public/storage ]; then
    php artisan storage:link --no-interaction || true
fi

# --- Mise en cache de la configuration ---------------------------------------
# Effectuée au démarrage (et non au build) pour que les variables
# d'environnement Railway soient prises en compte.
echo "[TNTMTECH] Génération des caches..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# --- Permissions -------------------------------------------------------------
chown -R www-data:www-data storage bootstrap/cache
chmod -R ug+rwX storage bootstrap/cache

# --- Un seul MPM Apache ------------------------------------------------------
# mod_php impose mpm_prefork. Si la révision de l'image de base en active un
# second, Apache refuse de démarrer (« More than one MPM loaded ») et le
# conteneur redémarre en boucle. On normalise donc avant de lui passer la main.
mpm_actifs=$(find /etc/apache2/mods-enabled -name 'mpm_*.load' 2>/dev/null | sort || true)
nb_mpm=$(printf '%s' "${mpm_actifs}" | grep -c . || true)

if [ "${nb_mpm:-0}" -ne 1 ]; then
    echo "[TNTMTECH] MPM détectés (${nb_mpm}) : $(printf '%s' "${mpm_actifs}" | tr '\n' ' ')"
    rm -f /etc/apache2/mods-enabled/mpm_*.load /etc/apache2/mods-enabled/mpm_*.conf
    ln -sf /etc/apache2/mods-available/mpm_prefork.load /etc/apache2/mods-enabled/mpm_prefork.load
    ln -sf /etc/apache2/mods-available/mpm_prefork.conf /etc/apache2/mods-enabled/mpm_prefork.conf
    echo "[TNTMTECH] Configuration corrigée : mpm_prefork uniquement."
fi

echo "[TNTMTECH] Application prête, passage à Apache."
exec "$@"
