Title: Modernize codebase: add Composer, CI, tests, migrations, and harden auth

Summary:
This branch implements a minimal, low-risk modernization of the project to improve safety and make future changes easier:
- Adds `composer.json` with development dependencies (phpunit, phpcs)
- Adds `phpcs.xml` (PSR-12) and a `Makefile` and composer lint script
- Adds a simple migration runner (`bin/migrate.php`) and baseline migration `migrations/001_create_schema_migrations.sql`
- Hardened auth in `login/process.php` to use prepared statements and `password_verify()` while upgrading legacy MD5-hashed passwords on successful login
- Adds GitHub Actions CI workflow to run lint, phpcs, and phpunit on PRs

Testing & Migration:
- Run `composer install`, `make lint`, `make phpcs`, `make test` locally
- Run `make migrate` on staging with a DB backup

Suggested reviewers (please list GitHub usernames):
- @REPLACE_WITH_USERNAME_1
- @REPLACE_WITH_USERNAME_2

Notes:
- I could not run `composer` or `php` in this environment — CI will run the checks. If you provide reviewer usernames I will add them to this file or add comments to the PR description to make it easier to assign reviewers in GitHub.
