#!/usr/bin/env bash
set -euo pipefail

# encrypt_password.sh - create an encrypted file containing the SSH password
# Usage:
#   ./encrypt_password.sh        (will prompt for password and create ssh_password.gpg)
#   echo "mypassword" | ./encrypt_password.sh  (reads from stdin, creates ssh_password.gpg)

OUT_FILE=${OUT_FILE:-ssh_password.gpg}

# Require `gpg` to be available
if ! command -v gpg >/dev/null 2>&1; then
  echo "gpg is not installed. Install it first." >&2
  echo "  macOS (Homebrew): brew install gnupg" >&2
  echo "  Debian/Ubuntu: sudo apt-get update && sudo apt-get install -y gnupg" >&2
  exit 1
fi

if [ -t 0 ]; then
  # interactive
  read -s -p "Enter SSH password to encrypt: " PW_CONTENT
  echo
  if [ -z "$PW_CONTENT" ]; then
    echo "No password entered, aborting." >&2
    exit 2
  fi
  # Prompt separately for the GPG passphrase to protect the encrypted file
  read -s -p "Enter GPG passphrase to protect the encrypted file: " GPG_PW
  echo
  if [ -z "$GPG_PW" ]; then
    echo "No GPG passphrase entered, aborting." >&2
    exit 3
  fi
  # Use loopback pinentry so gpg does not require external pinentry program
  printf "%s" "$PW_CONTENT" | gpg --batch --yes --pinentry-mode loopback --passphrase "$GPG_PW" --symmetric --cipher-algo AES256 -o "$OUT_FILE"
  unset GPG_PW
  echo "Encrypted password written to $OUT_FILE"
else
  # read from stdin
  # read the content from stdin (first line or full input)
  PW_CONTENT=$(cat -)
  if [ -z "$PW_CONTENT" ]; then
    echo "No input received on stdin, aborting." >&2
    exit 2
  fi
  # Ask for GPG passphrase to protect the encrypted file
  read -s -p "Enter GPG passphrase to protect the encrypted file: " GPG_PW
  echo
  if [ -z "$GPG_PW" ]; then
    echo "No GPG passphrase entered, aborting." >&2
    exit 3
  fi
  printf "%s" "$PW_CONTENT" | gpg --batch --yes --pinentry-mode loopback --passphrase "$GPG_PW" --symmetric --cipher-algo AES256 -o "$OUT_FILE"
  unset GPG_PW
  echo "Encrypted password created at $OUT_FILE"
fi
