#!/usr/bin/env bash
#
# Deploy AMI Digital ke VPS Debian 13 (Nginx + PHP-FPM + MySQL/MariaDB).
#
# Pakai:
#   bash deploy.sh            # deploy normal (pull, composer, migrate, cache)
#   bash deploy.sh --seed     # + jalankan db:seed (HATI-HATI: hanya untuk awal / data master)
#
# Env opsional:
#   DEPLOY_BRANCH=master  PHP_BIN=php8.4  COMPOSER_BIN=composer  FPM_SERVICE=php8.4-fpm
#
set -euo pipefail
cd "$(dirname "$0")"

BRANCH="${DEPLOY_BRANCH:-master}"
PHP_BIN="${PHP_BIN:-php}"
COMPOSER_BIN="${COMPOSER_BIN:-composer}"
FPM_SERVICE="${FPM_SERVICE:-}"
RUN_SEED=0
[[ "${1:-}" == "--seed" ]] && RUN_SEED=1

log() { printf '\n\033[1;32m==> %s\033[0m\n' "$*"; }

log "Maintenance mode ON"
$PHP_BIN artisan down --retry=15 || true

# Pastikan kode persis sama dengan remote (server tidak boleh punya perubahan lokal).
log "Ambil kode terbaru ($BRANCH)"
git fetch --all --prune
git reset --hard "origin/${BRANCH}"

log "Composer install (mode produksi)"
$COMPOSER_BIN install --no-dev --optimize-autoloader --no-interaction --prefer-dist

log "Migrasi database"
$PHP_BIN artisan migrate --force

if [[ "$RUN_SEED" == "1" ]]; then
  log "Seeding database (--seed)"
  $PHP_BIN artisan db:seed --force
fi

log "Bangun ulang cache (config/view/event)"
$PHP_BIN artisan optimize:clear
$PHP_BIN artisan config:cache
$PHP_BIN artisan event:cache
$PHP_BIN artisan view:cache
# CATATAN: 'route:cache' SENGAJA tidak dijalankan — ada banyak nama route duplikat
# antar-role (dashboard.*, statistik-*.*, dll), sehingga route:cache akan gagal.
# Lihat DEPLOY.md bila ingin mengaktifkannya (perlu merapikan nama route dulu).

log "Symlink storage"
$PHP_BIN artisan storage:link 2>/dev/null || true

# Pastikan folder yang ditulis runtime bisa ditulis web server.
log "Set izin storage & bootstrap/cache"
chmod -R ug+rwX storage bootstrap/cache || true

# Reload PHP-FPM agar OPcache ke-reset (set FPM_SERVICE=php8.4-fpm bila perlu & punya sudo).
if [[ -n "$FPM_SERVICE" ]]; then
  log "Reload $FPM_SERVICE"
  sudo systemctl reload "$FPM_SERVICE" || true
fi

log "Maintenance mode OFF"
$PHP_BIN artisan up

log "Deploy selesai."
