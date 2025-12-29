# Invoice — Deploy & Run (Docker) ✅

Short overview and steps to deploy the `invoice` site to your NAS and run it with Docker Compose.

## Requirements
- Docker & docker-compose on the NAS
- SSH access to your NAS (for `deploy.sh` / rsync)
- The MySQL container accessible as `mysql` on Docker network `dockernetwork`

## Quick start (recommended)
1. From your workstation, run a dry-run:

```bash
./deploy.sh --dry-run
```

2. Do the real deploy (default):

```bash
./deploy.sh
```

3. On the NAS, start the service (in `/docker/invoice`):

```bash
cd /docker/invoice
docker-compose up -d
```

4. Visit: http://<NAS_IP>:8080/

## Docker Compose notes
- The service uses the official `php:8.2-apache` image and expects the webroot to be mounted at `/var/www/html`.
- Environment variables used by the app (set in `docker-compose.yml`):
  - `DB_HOST` (default: `mysql`)
  - `DB_USER` (default: `invoice`)
  - `DB_PASSWORD` (default: `invoice`)
  - `DB_SCHEMA` (default: `invoice`)
- The compose file already sets a small `command:` to avoid the Apache `AH00558` ServerName warning.

## deploy.sh
- Usage: `./deploy.sh` (performs a real deploy by default). Use `--dry-run` to test without changing remote files.
- Details: host `jego-nas`, path `/docker/invoice/www`. The script excludes `.git`, `node_modules` and `.env` by default and will attempt `sudo chown -R 33:33` (www-data) on the remote.

### Encrypted SSH password (optional)
- You can store an SSH password encrypted in `ssh_password.gpg` and `deploy.sh` will use it automatically if present.
- To create the encrypted password file locally, run:

```bash
./encrypt_password.sh
# or: echo "mypassword" | gpg --symmetric --cipher-algo AES256 -o ssh_password.gpg
```

- `deploy.sh` will attempt to decrypt `ssh_password.gpg` (you will be prompted for the GPG passphrase) and, if successful, use `sshpass` to supply the SSH password to `rsync`.
- Requirements: `gpg` (for decryption) and `sshpass` (to pass the password to rsync/ssh). If `sshpass` is not installed, the script will fall back to using SSH key or interactive SSH.

**Install notes**:
- macOS (Homebrew):

```bash
brew install gnupg
# sshpass may require a tap; try:
brew install hudochenkov/sshpass/sshpass || brew install sshpass
```

- Debian/Ubuntu:

```bash
sudo apt-get update && sudo apt-get install -y gnupg sshpass
```

**Security note:** Storing passwords even encrypted requires protecting the GPG passphrase; prefer SSH keys with a passphrase + `ssh-agent` when possible.

## Permissions & ownership
- After deploy, ensure the webroot is readable/writable by `www-data` in the container (commonly UID/GID `33:33`):

```bash
sudo chown -R 33:33 /docker/invoice/www
sudo find /docker/invoice/www -type d -exec chmod 755 {} \;
sudo find /docker/invoice/www -type f -exec chmod 644 {} \;
```

## Notes & troubleshooting
- The application now reads DB credentials from environment variables (DB_HOST/DB_USER/DB_PASSWORD/DB_SCHEMA) and falls back to `ppa/database_conf.ini` or safe defaults.
- If you see DB connection issues, check that `mysql` is reachable on the `dockernetwork` and credentials match.
- If `mysqli` is not available in the official image, run a small custom image that installs `mysqli` (we removed the Dockerfile to use the official image — I can add a build if you prefer).
- Useful checks:
  - `docker-compose logs -f invoice`
  - `docker exec -it invoice php -r "echo function_exists('mysqli_connect') ? 'mysqli OK' : 'no mysqli';"`
  - `docker exec -it invoice php -r "var_dump(mysqli_connect(getenv('DB_HOST'),getenv('DB_USER'),getenv('DB_PASSWORD')));"`

## Next steps (optional)
- Add a reverse proxy (Nginx/Traefik/Caddy) with TLS on the NAS
- Add CI for automated deploys

---

If you want, I can tailor this README (add examples, screenshots, or a one-line systemd service) — tell me what you'd like to include.