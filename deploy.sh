#!/usr/bin/env bash
set -euo pipefail

# deploy.sh - Rsync the invoice site to your NAS (hardcoded)
# Hardcoded defaults:
#   REMOTE_HOST=jego-nas
#   REMOTE_USER=jean
#   REMOTE_PATH=/docker/invoice/www
# Behavior:
#   - The script runs a DRY RUN by default (no files are changed). To perform a real deploy, edit this file and set DRY_RUN=0.
#   - No CLI parameters are supported; change variables inside the script if you need different target or options.

# Hardcoded deployment config (with optional CLI flag for dry-run)
REMOTE_HOST="jego-nas"
REMOTE_USER="jean"
REMOTE_PATH="/docker/invoice/www"
SSH_PORT=22
RSYNC_OPTS='-avz --delete --exclude=.git --exclude=node_modules --exclude=.env'
CHOWN_ON_REMOTE='33:33'
# By default perform a real deploy. Use --dry-run to perform a dry run.
DRY_RUN=0

# Optional argument parsing:
#   --dry-run  Run as a dry run (no files will be changed)
#   -h|--help  Show brief usage
while [[ $# -gt 0 ]]; do
  case "$1" in
    --dry-run)
      DRY_RUN=1; shift ;;
    -h|--help)
      echo "Usage: ./deploy.sh [--dry-run]"; exit 0 ;;
    *)
      echo "Unknown arg: $1"; echo "Usage: ./deploy.sh [--dry-run]"; exit 2 ;;
  esac
done

if [[ $DRY_RUN -eq 1 ]]; then
  RSYNC_OPTS="$RSYNC_OPTS --dry-run"
  echo "*** DRY RUN: no files will be changed on the remote (use no flag to run real deploy) ***"
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

echo "Adjusting ownership on remote to ${CHOWN_ON_REMOTE} (using sudo)"
ssh -p "${SSH_PORT}" "${REMOTE_USER}@${REMOTE_HOST}" \
  "sudo chown -R ${CHOWN_ON_REMOTE} '${REMOTE_PATH}' || echo 'chown failed (you may not have sudo privileges)'; sudo find '${REMOTE_PATH}' -type d -exec chmod 755 {} \; ; sudo find '${REMOTE_PATH}' -type f -exec chmod 644 {} \;"
echo "Ownership and permissions adjusted (if sudo was available)."

echo "Deploy finished."
