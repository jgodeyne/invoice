#!/usr/bin/env bash
set -euo pipefail

# deploy.sh - Rsync the invoice site to your NAS
# Defaults:
#   REMOTE_HOST=jego-nas
#   REMOTE_USER=$USER
#   REMOTE_PATH=/docker/invoice/www
# Usage:
#   ./deploy.sh [--host HOST] [--user USER] [--path PATH] [--port PORT] [--no-chown] [--dry-run]
# Examples:
#   ./deploy.sh --host jego-nas --user jean --path /docker/invoice/www
#   ./deploy.sh --dry-run

REMOTE_HOST=${REMOTE_HOST:-jego-nas}
REMOTE_USER=${REMOTE_USER:-$USER}
REMOTE_PATH=${REMOTE_PATH:-/docker/invoice/www}
SSH_PORT=${SSH_PORT:-22}
RSYNC_OPTS_DEFAULT='-avz --delete --exclude=.git --exclude=node_modules --exclude=.env'
CHOWN_ON_REMOTE=${CHOWN_ON_REMOTE:-33:33}
DRY_RUN=0
DO_CHOWN=1

show_help(){
  sed -n '1,120p' "$0" | sed -n '1,40p'
}

while [[ $# -gt 0 ]]; do
  case "$1" in
    --host) REMOTE_HOST="$2"; shift 2;;
    --user) REMOTE_USER="$2"; shift 2;;
    --path) REMOTE_PATH="$2"; shift 2;;
    --port) SSH_PORT="$2"; shift 2;;
    --dry-run) DRY_RUN=1; shift 1;;
    --no-chown) DO_CHOWN=0; shift 1;;
    --chown) CHOWN_ON_REMOTE="$2"; shift 2;;
    -h|--help) show_help; exit 0;;
    *) echo "Unknown arg: $1"; show_help; exit 2;;
  esac
done

RSYNC_OPTS="$RSYNC_OPTS_DEFAULT"
if [[ $DRY_RUN -eq 1 ]]; then
  RSYNC_OPTS="$RSYNC_OPTS --dry-run"
  echo "*** DRY RUN: no files will be changed on the remote until you run without --dry-run ***"
fi

echo "Deploying to: ${REMOTE_USER}@${REMOTE_HOST}:${REMOTE_PATH}"

eval rsync -e \"ssh -p ${SSH_PORT}\" ${RSYNC_OPTS} ./ ${REMOTE_USER}@${REMOTE_HOST}:"${REMOTE_PATH}"

if [[ $DRY_RUN -eq 1 ]]; then
  echo "Dry run complete. Review output above."
  exit 0
fi

if [[ $DO_CHOWN -eq 1 ]]; then
  echo "Adjusting ownership on remote to ${CHOWN_ON_REMOTE} (using sudo)"
  ssh -p "${SSH_PORT}" "${REMOTE_USER}@${REMOTE_HOST}" \
    "sudo chown -R ${CHOWN_ON_REMOTE} '${REMOTE_PATH}' || echo 'chown failed (you may not have sudo privileges)'; sudo find '${REMOTE_PATH}' -type d -exec chmod 755 {} \; ; sudo find '${REMOTE_PATH}' -type f -exec chmod 644 {} \;"
  echo "Ownership and permissions adjusted (if sudo was available)."
fi

echo "Deploy finished."
