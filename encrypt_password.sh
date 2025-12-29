#!/usr/bin/env bash
set -euo pipefail

# encrypt_password.sh - create an encrypted file containing the SSH password
# Usage:
#   ./encrypt_password.sh        (will prompt for password and create ssh_password.gpg)
#   echo "mypassword" | ./encrypt_password.sh  (reads from stdin, creates ssh_password.gpg)

OUT_FILE=${OUT_FILE:-ssh_password.gpg}

if [ -t 0 ]; then
  # interactive
  read -s -p "Enter SSH password to encrypt: " PW
  echo
  if [ -z "$PW" ]; then
    echo "No password entered, aborting." >&2
    exit 2
  fi
  printf "%s" "$PW" | gpg --symmetric --cipher-algo AES256 -o "$OUT_FILE"
  echo "Encrypted password written to $OUT_FILE"
else
  # read from stdin
  gpg --symmetric --cipher-algo AES256 -o "$OUT_FILE"
  echo "Encrypted password created at $OUT_FILE"
fi
