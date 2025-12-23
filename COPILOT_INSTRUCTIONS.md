# COPILOT_INSTRUCTIONS.md

> Short project-specific instructions for GitHub Copilot (and human devs) to add features and maintain this invoice app. Follow these steps and conventions to keep changes safe, consistent, and reversible. ✅

---

## Project overview 🔧
- Language: PHP (procedural + simple OOP classes). Many files use short PHP tags (<?) and pre-5/legacy patterns.
- DB access: custom `Database` class in `ppa/database_class.php` (mysqli procedural). SQL queries are built by string concatenation in `ppa/entity_class.php` and many other files.
- Authentication: session-based (`common/session.php`, `login/process.php`). Passwords are currently compared with `md5()`.
- Localization: `locale/<lang>/LC_MESSAGES/*.po` and `.mo` (gettext).
- Date handling: helpers in `common/date_functions.php`, and `date_picker.js` UI.
- No Composer, no tests, no migration tooling present.

## Key files & conventions 📁
- DB conf: `ppa/database_conf.ini` (contains Host/User/Password/Schema). Keep out of VCS or restrict access for production.
- DB wrapper: `ppa/database_class.php` — uses `mysqli_*` and returns raw results or objects.
- Generic entity layer: `ppa/entity_class.php` — maps class to table via `ppa/entity_mapping.ini` and builds SQL by string concatenation.
- Domain classes: `client/`, `company/`, `invoice/`, `job/` (each has `_class.php`, `*_list.php`, `*_form.php`). Follow existing file placement for new features.
- Session & auth: `common/session.php`, `login/`.
- Templates: simple PHP pages mixing HTML and PHP. Many pages use `<? include(...) ?>` and short tags.

## Safety & security ⚠️
- **Do not** concatenate user input into SQL queries. Instead, use prepared statements (mysqli::prepare or PDO with prepared statements). Many existing functions are vulnerable to SQL injection.
- **Passwords**: migrate from `md5()` to `password_hash()` and `password_verify()` and rotate/rehash stored passwords.
- **Short PHP tags** (`<?`) are used in many files. Replace with `<?php` for portability and clarity.
- **Output escaping**: always use `htmlspecialchars($value, ENT_QUOTES, 'UTF-8')` when rendering user input or DB output to HTML.
- **Error handling**: do not show raw DB errors to end users. Use logging (error_log) and show generic messages.

## Recommended minimal modernization roadmap (low-risk, incremental) ✅
1. Add `phpcs` and adopt PSR-12 style gradually. Fix files you touch.
2. Start using prepared statements for all queries you touch; prefer PDO with exceptions if possible.
3. Replace `md5()` password usage with `password_hash()` / `password_verify()`; provide a migration path for existing passwords.
4. Replace short tags `<?` with `<?php` when editing files.
5. Add Composer (composer.json) and a small autoload setup, then gradually extract utility classes into namespaced files.
6. Add a `migrations/` folder (numbered SQL scripts) and tiny migration runner (PHP CLI) to apply changes consistently.
7. Add tests (phpunit) and a test DB config (e.g., `database_conf.test.ini`).

---

### What I implemented in this change ✅
- Added `composer.json` with `phpunit` and `phpcs` dev deps and useful scripts.
- Added `phpcs.xml` (PSR-12) ruleset to apply gradually.
- Added a simple migration runner (`bin/migrate.php`) and a baseline migration `001_create_schema_migrations.sql`.
- Updated `login/process.php` to use prepared statements and `password_verify()`; it supports legacy `md5()` passwords and upgrades them to `password_hash()` on successful login.
- Added a GitHub Actions CI workflow `.github/workflows/ci.yml` that runs PHP lint, `phpcs`, and PHPUnit on pushes and pull requests.
- Added test bootstrap (`tests/bootstrap.php`) and a minimal unit test (`tests/InvoiceExistsTest.php`) to validate the test harness.

How to use the changes:
- Run `composer install` to install dev deps.
- Use `make` or composer scripts locally to run checks quickly:
  - `make lint` (or `composer run lint`) — runs PHP syntax check across the repo
  - `make phpcs` (or `composer phpcs`) — runs PHPCS with the project's ruleset
  - `make test` (or `composer test`) — runs PHPUnit
  - `make migrate` (or `composer migrate`) — runs the migration runner
  - `make ci` — runs lint, phpcs and tests

> Note: I couldn't run `php` or `composer` in this environment; CI will run the checks and you can run them locally and report any failures so I can follow up.


## How to add a feature — step-by-step (example-first) 🛠️
Example: Add a `discount` numeric column to invoices.
1. Database migration
   - Create `migrations/001_add_invoice_discount.sql` with the `ALTER TABLE invoice ADD COLUMN discount DECIMAL(10,2) DEFAULT 0;` statement.
   - Commit the migration (and document release notes).
2. Update model/class
   - Add a private property `$discount;` to `invoice/invoice_class.php` (match DB column name exactly).
   - Add getter/setter methods (and include in `getProperties()` if necessary).
3. Update forms & lists
   - Update `invoice/invoice_form.php` to include the input (validate on server side).
   - Update `invoice/invoice_list.php` and detail views to show the value where appropriate.
4. Validation & business logic
   - Add input validation (numeric, min/max) in the form processing (`invoice_create.php` or `invoice_update.php`).
5. Tests
   - Add unit/integration tests that ensure save() persists discount and retrieval returns expected value.
6. Deploy
   - Run the SQL migration on staging first, then prod; take DB backup before applying.
7. Localization
   - If UI labels are new, add keys to `.po` files under `locale/` and generate `.mo` files.

> Note: Because `Entity::save()` builds SQL from object properties, adding a property to the class normally causes `save()` to include the new column—ensure name matches DB column and the default is safe.

## Database & migrations 🗃️
- There is no migration tooling yet. Add simple migration runner (e.g., `bin/migrate.php`) that records applied migrations in a `schema_migrations` table.
- Keep SQL changes idempotent when possible (check for column existence before adding).
- Store development/production DB configs separately and never commit production secrets.

## Localization (i18n) 🌐
- PO/MO files live in `locale/<lang>/LC_MESSAGES/invoice.po`.
- To add a language: create `locale/<code>/LC_MESSAGES/`, copy PO file, translate strings, compile `.mo` with `msgfmt`.
- When adding UI text: wrap strings with gettext `_('My text')` and add to the POT/PO extraction process.

## Dates, formats & UI 🗓️
- UI uses `date_picker.js` and helper functions in `common/date_functions.php` for Euro ↔ US conversions.
- When adding date fields: use the same conversion helpers and consistent storage (YYYY-MM-DD) in DB; display using helpers.

## Testing & CI 🔁
- Add `phpunit` and a basic test suite for model classes and DB access using a test DB.
- Add a `Makefile` or `composer` scripts to run `php -l` (syntax check), `phpcs`, and `phpunit`.
- For CI: run static checks and tests on every PR.

## Code review checklist ✅
- No direct concatenation of user input into SQL.
- Proper escaping (`htmlspecialchars`) on output.
- No short `<?` tags in modified files.
- New DB changes include a migration script and backup steps.
- Tests added/updated to cover new behavior.
- Localization keys updated where relevant.
- No secrets committed.

## Branching & commits 📌
- Feature branches: `feature/<short-description>`.
- Bugfix branches: `fix/<short-description>`.
- Commit message style: use imperative, e.g., `Add discount field to invoice and migration`.
- Open PRs and request at least one reviewer.

## Examples & transformation guidance 🔧
- Unsafe (current style):
```php
$query = "SELECT * FROM users WHERE username = '".$_POST['user']."' AND password='".md5($_POST['pass'])."'";
```
- Recommended (mysqli prepared):
```php
$stmt = $mysqli->prepare('SELECT id,userlevel,password_hash FROM users WHERE username = ?');
$stmt->bind_param('s', $_POST['user']);
$stmt->execute();
$res = $stmt->get_result();
// verify with password_verify()
```

## Maintenance notes & gotchas ⚠️
- Many files still use old PHP style and short-tags; when editing, modernize progressively but keep changes minimal and well-tested.
- `Entity::save()` and other methods expect DB column names to match property names exactly.
- `getProperties()` uses `get_object_vars($this)` which includes private properties — be careful when adding computed properties.

---

## Quick checklist for Copilot when generating changes 🤖
- Identify which files to change: model, forms, list, and DB migration.
- Propose a migration SQL script and a rollback if applicable.
- Replace unsafe SQL concatenation with prepared statements when touching query code.
- Add server-side validation and escaping for new inputs.
- Update localization strings and recompile `.mo` files.
- Add/modify tests ensuring new functionality and migration work.
- Add a short `CHANGELOG` entry describing the change and migration steps.

---

If you'd like, I can also:
- Propose a `migrations/` runner and add one sample migration.
- Create a PHPCS ruleset and apply it to a subset of files as a demonstration.

---

*Generated by GitHub Copilot — follow the checklist and test changes on staging before deploying.*
