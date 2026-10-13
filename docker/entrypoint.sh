#!/bin/sh
# ---------------------------------------------------------------------------
# Démarrage du conteneur de production.
#
# 1. Vérifie la configuration indispensable (APP_KEY)
# 2. Génère la configuration Nginx avec le port imposé par l'hébergeur
# 3. Prépare les dossiers inscriptibles
# 4. Met en cache la configuration, les routes et les vues
# 5. Applique les migrations en attendant que la base réponde
# 6. Lance Nginx + PHP-FPM via Supervisor
# ---------------------------------------------------------------------------
set -e

APP_ROOT=/var/www/html
cd "$APP_ROOT"

log() {
    printf '[entrypoint] %s\n' "$1"
}

# --- 1. Configuration indispensable -----------------------------------------
if [ -z "${APP_KEY:-}" ]; then
    log "ERREUR : la variable APP_KEY est vide."
    log "Générez-en une en local avec « php artisan key:generate --show »"
    log "puis renseignez-la dans les variables d'environnement du service."
    exit 1
fi

if [ "${APP_DEBUG:-false}" = "true" ] && [ "${APP_ENV:-production}" = "production" ]; then
    log "ATTENTION : APP_DEBUG=true en production expose les traces d'erreur."
fi

# --- 2. Configuration Nginx --------------------------------------------------
# Render (comme Heroku ou Fly) impose le port d'écoute via PORT.
PORT="${PORT:-10000}"
export PORT
envsubst '${PORT}' < /etc/nginx/nginx.conf.template > /etc/nginx/nginx.conf
log "Nginx écoutera sur le port ${PORT}."

# --- 3. Dossiers inscriptibles ----------------------------------------------
# Le système de fichiers d'un conteneur Render est éphémère : ces dossiers sont
# recréés à chaque démarrage, aucune donnée ne doit y être conservée.
mkdir -p \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# --- 4. Caches Laravel -------------------------------------------------------
# Générés maintenant et pas pendant le build de l'image : les variables
# d'environnement du service ne sont disponibles qu'à l'exécution.
log "Mise en cache de la configuration, des routes et des vues…"
php artisan optimize:clear --no-interaction >/dev/null 2>&1 || true
php artisan optimize --no-interaction

# --- 5. Migrations -----------------------------------------------------------
if [ "${SKIP_MIGRATIONS:-false}" = "true" ]; then
    log "SKIP_MIGRATIONS=true : migrations ignorées."
else
    log "Application des migrations…"
    attempt=1
    until php artisan migrate --force --no-interaction; do
        if [ "$attempt" -ge 10 ]; then
            log "ERREUR : la base de données n'a pas répondu après 10 tentatives."
            exit 1
        fi
        log "Base de données indisponible (tentative ${attempt}/10), nouvelle tentative dans 3 s…"
        attempt=$((attempt + 1))
        sleep 3
    done
    log "Migrations à jour."
fi

# Les caches applicatifs vivent en base : on vide celui de la version
# précédente pour ne pas servir de vues ou de traductions périmées.
php artisan cache:clear --no-interaction >/dev/null 2>&1 || true

# --- 6. Serveur --------------------------------------------------------------
log "Démarrage de Nginx et PHP-FPM."
exec /usr/bin/supervisord -c /etc/supervisord.conf
