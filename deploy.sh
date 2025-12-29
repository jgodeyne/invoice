#!/usr/bin/env bash
set -euo pipefail

# deploy.sh - Rsync the invoice site to your NAS
# Defaults:
#   REMOTE_HOST=jego-nas
#   REMOTE_USER=jean
#   REMOTE_PATH=/docker/invoice/www
# Usage:
#   ./deploy.sh [--host HOST] [--user USER] [--path PATH] [--port PORT] [--no-chown] [--dry-run]
# Examples:
#   ./deploy.sh --host jego-nas --user jean --path /docker/invoice/www
#   ./deploy.sh --dry-run

# Hardcoded deployment config (no CLI parameters)
REMOTE_HOST="jego-nas"
REMOTE_USER="jean"
REMOTE_PATH="/docker/invoice/www"
SSH_PORT=22
RSYNC_OPTS='-avz --delete --exclude=.git --exclude=node_modules --exclude=.env'
CHOWN_ON_REMOTE='33:33'
# By default run a dry-run. Set DRY_RUN=0 in this script to perform a real deploy.
DRY_RUN=1
DO_CHOWN=1

# No argument parsing — all values are hardcoded above.
if [[ $DRY_RUN -eq 1 ]]; then
  RSYNC_OPTS="$RSYNC_OPTS --dry-run"
  echo "*** DRY RUN: no files will be changed on the remote until you set DRY_RUN=0 in the script ***"
fi

echo "Deploying to: ${REMOTE_USER}@${REMOTE_HOST}:${REMOTE_PATH}"

# If not a dry run, ensure the remote path exists first
if [[ $DRY_RUN -eq 0 ]]; then
  echo "Ensuring remote path ${REMOTE_PATH} exists (creating if needed)"
  ssh -p "${SSH_PORT}" "${REMOTE_USER}@${REMOTE_HOST}" "mkdir -p '${REMOTE_PATH}' || true"
fi

rsync -e "ssh -p ${SSH_PORT}" ${RSYNC_OPTS} ./ ${REMOTE_USER}@${REMOTE_HOST}:"${REMOTE_PATH}"

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
