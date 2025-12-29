#!/usr/bin/env bash
set -euo pipefail

# deploy.sh - Rsync the invoice site to your NAS (hardcoded)
# Hardcoded defaults:
#   REMOTE_HOST=jego-nas
#   REMOTE_USER=jean
#   REMOTE_PATH=/docker/invoice/www
# Behavior:
#   - The script performs a REAL deploy by default. Use `--dry-run` to test without changing remote files.
#   - If `ssh_password.gpg` exists in the script directory, the script will attempt to decrypt it and pass
#     the SSH password to rsync/ssh using `sshpass`. This requires `gpg` (for decryption) and `sshpass`.
#     If decryption or `sshpass` is not available, the script falls back to normal SSH (keys or interactive login).
#   - The script will attempt to set ownership on the remote to UID:GID 33:33 (www-data) via sudo.
#   - Change the hardcoded variables below if you need a different target or behavior.

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

# If an encrypted password is available, try to use it with sshpass
SSH_PASSWORD_FILE="ssh_password.gpg"
USE_SSHPASS=0
if [ -f "$SSH_PASSWORD_FILE" ]; then
  if ! command -v gpg >/dev/null 2>&1; then
    echo "ssh_password.gpg found but 'gpg' is not installed. Install gpg or remove the file." >&2
  else
    echo "Found $SSH_PASSWORD_FILE — attempting to decrypt (you will be prompted for GPG passphrase if needed)"
    set +x
    SSH_PASSWORD=$(gpg --quiet --decrypt "$SSH_PASSWORD_FILE" 2>/dev/null || true)
    set -x
    if [ -n "${SSH_PASSWORD}" ]; then
      if command -v sshpass >/dev/null 2>&1; then
        USE_SSHPASS=1
      else
        echo "Decrypted password available but 'sshpass' is not installed; please install sshpass to use password-based SSH." >&2
      fi
    else
      echo "Could not decrypt $SSH_PASSWORD_FILE (wrong passphrase or other error)." >&2
    fi
  fi
fi

# If not a dry run, ensure the remote path exists first
if [[ $DRY_RUN -eq 0 ]]; then
  echo "Ensuring remote path ${REMOTE_PATH} exists (creating if needed)"
  if [[ $USE_SSHPASS -eq 1 ]]; then
    # rsync/ssh via sshpass will handle authentication
    sshpass -p "$SSH_PASSWORD" ssh -p "${SSH_PORT}" "${REMOTE_USER}@${REMOTE_HOST}" "mkdir -p '${REMOTE_PATH}' || true"
  else
    ssh -p "${SSH_PORT}" "${REMOTE_USER}@${REMOTE_HOST}" "mkdir -p '${REMOTE_PATH}' || true"
  fi
fi

# Build rsync command
if [[ $USE_SSHPASS -eq 1 ]]; then
  echo "Using sshpass to supply SSH password for rsync (decrypted from $SSH_PASSWORD_FILE)."
  sshpass -p "$SSH_PASSWORD" rsync -e "ssh -p ${SSH_PORT}" ${RSYNC_OPTS} ./ ${REMOTE_USER}@${REMOTE_HOST}:"${REMOTE_PATH}"
  # Unset password variable
  unset SSH_PASSWORD
else
  rsync -e "ssh -p ${SSH_PORT}" ${RSYNC_OPTS} ./ ${REMOTE_USER}@${REMOTE_HOST}:"${REMOTE_PATH}"
fi

if [[ $DRY_RUN -eq 1 ]]; then
  echo "Dry run complete. Review output above."
  exit 0
fi

echo "Adjusting ownership on remote to ${CHOWN_ON_REMOTE} (using sudo)"
ssh -p "${SSH_PORT}" "${REMOTE_USER}@${REMOTE_HOST}" \
  "sudo chown -R ${CHOWN_ON_REMOTE} '${REMOTE_PATH}' || echo 'chown failed (you may not have sudo privileges)'; sudo find '${REMOTE_PATH}' -type d -exec chmod 755 {} \; ; sudo find '${REMOTE_PATH}' -type f -exec chmod 644 {} \;"
echo "Ownership and permissions adjusted (if sudo was available)."

echo "Deploy finished."
