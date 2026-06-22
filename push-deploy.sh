#!/usr/bin/env bash
#
# Push lokal -> deploy server dalam SATU perintah (jalankan dari laptop / Git Bash).
#
# Pakai:
#   bash push-deploy.sh            # git push, lalu jalankan deploy.sh di server
#   bash push-deploy.sh --seed     # + db:seed di server (data master / awal)
#
# Konfigurasi: salin .deploy.env.example -> .deploy.env lalu isi.
#   (atau set lewat env var: SSH_HOST=ip SSH_USER=root bash push-deploy.sh)
#
#   SSH_HOST   : IP / domain server        (mis. 123.45.67.89)
#   SSH_USER   : user SSH                   (mis. root / deploy)
#   SSH_PORT   : port SSH                   (default 22)
#   REMOTE_DIR : path project di server     (default /var/www/amidigital.sistemedu.com)
#   BRANCH     : branch git                 (default master)

set -euo pipefail
cd "$(dirname "$0")"

# Muat konfigurasi lokal (tidak ikut ke git).
if [[ -f .deploy.env ]]; then
  set -a; . ./.deploy.env; set +a
fi

SSH_HOST="${SSH_HOST:-}"
SSH_USER="${SSH_USER:-}"
SSH_PORT="${SSH_PORT:-22}"
REMOTE_DIR="${REMOTE_DIR:-/var/www/amidigital.sistemedu.com}"
BRANCH="${BRANCH:-master}"

# Argumen (mis. --seed) diteruskan ke deploy.sh di server.
DEPLOY_ARGS="$*"

log() { printf '\n\033[1;36m==> %s\033[0m\n' "$*"; }
die() { printf '\n\033[1;31mERROR: %s\033[0m\n' "$*" >&2; exit 1; }

[[ -n "$SSH_HOST" ]] || die "SSH_HOST belum diisi. Salin .deploy.env.example -> .deploy.env lalu isi."
[[ -n "$SSH_USER" ]] || die "SSH_USER belum diisi (edit .deploy.env)."

log "Push commit lokal ke origin/$BRANCH"
git push origin "$BRANCH"

log "Deploy di server: $SSH_USER@$SSH_HOST:$REMOTE_DIR  (deploy.sh $DEPLOY_ARGS)"
ssh -p "$SSH_PORT" "$SSH_USER@$SSH_HOST" "cd '$REMOTE_DIR' && bash deploy.sh $DEPLOY_ARGS"

log "Selesai: push + deploy beres."
