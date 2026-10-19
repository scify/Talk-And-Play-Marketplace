# Talk & Play Marketplace

[![CI](https://github.com/scify/Talk-And-Play-Marketplace/actions/workflows/ci.yml/badge.svg?branch=main)](https://github.com/scify/Talk-And-Play-Marketplace/actions/workflows/ci.yml)
[![Security](https://github.com/scify/Talk-And-Play-Marketplace/actions/workflows/security.yml/badge.svg)](https://github.com/scify/Talk-And-Play-Marketplace/actions/workflows/security.yml)
[![License](https://img.shields.io/badge/License-Apache%202.0-blue.svg)](LICENCE.md)
[![GitHub Issues](https://img.shields.io/github/issues/scify/Talk-And-Play-Marketplace)](https://github.com/scify/Talk-And-Play-Marketplace/issues)

A marketplace web application where specialists and family members create and share communication cards and game
cards for the Talk & Play desktop app. Administrators review each package; approved packages are public and the
desktop app can download them.

[Project URL](https://talkandplay.scify.org/)

## Contents

- [Tech stack](#tech-stack)
- [Requirements](#requirements)
- [Getting started](#getting-started)
  - [Everyday commands](#everyday-commands)
  - [npm supply-chain settings](#npm-supply-chain-settings)
- [Tests](#tests)
- [Code quality](#code-quality)
- [Continuous integration](#continuous-integration)
- [Releasing](#releasing)
- [Deployment](#deployment)
- [Sitemap](#sitemap)
- [Debugging](#debugging)
- [License](#license)

## Tech stack

- **Backend:** Laravel 12, PHP 8.2 or newer (DDEV runs PHP 8.2, CI runs PHP 8.4), MySQL 8.0
- **Frontend:** Vue 2.7, Vite 7, Bootstrap 5, Sass, jQuery
- **Tooling:** Pest (PHPUnit), Laravel Pint, Larastan (PHPStan), ESLint
- **Local environment:** [DDEV](https://ddev.com/)

## Requirements

- [DDEV](https://ddev.readthedocs.io/en/stable/users/install/ddev-installation/) and Docker
- Git

DDEV runs PHP, Composer, MySQL and Node.js in containers, so you do not need them on your machine. The Node.js version
comes from `.nvmrc` (**24.21.0**); a DDEV start hook keeps `nodejs_version` in `.ddev/config.yaml` in sync with it.

## Getting started

1. Clone the repository and start DDEV:

   ```bash
   git clone https://github.com/scify/Talk-And-Play-Marketplace.git
   cd Talk-And-Play-Marketplace
   ddev start
   ```

   Every `ddev start` runs `composer setup` and then the database migrations. `composer setup` creates `.env` from
   `.env.example` (only if `.env` is missing), installs the PHP dependencies and, when `package-lock.json` changed, the
   npm dependencies (`npm ci`), generates `APP_KEY` (only if it is empty), links `public/storage` to `storage/app/public` and builds the frontend. It is safe to run again at any
   time, also by hand: `ddev composer setup`. DDEV writes the database, mail and `APP_URL` settings into `.env`.

2. Edit `.env`:
   - `DEFAULT_ADMIN_USER_PASSWORD_FOR_SEED`: the password of the seeded administrator
   - `MAIL_FROM_ADDRESS`: any sender address, for example `noreply@example.com`. Without it, the app cannot send its
     notification emails. DDEV catches all mail in [Mailpit](https://mailpit.axllent.org/) (`ddev mailpit`).

3. Create the starter data:

   ```bash
   ddev artisan db:seed
   ```

   The seeders create the lookup data and the administrator `admin-tnp@scify.org`.

4. For frontend work, start the Vite dev server (hot reload through port 5179):

   ```bash
   ddev npm run dev
   ```

5. Open <https://talkandplay-marketplace.ddev.site:8443>.

### Everyday commands

| Task | Command |
|---|---|
| Start or stop the environment | `ddev start`, `ddev stop` |
| Run Artisan, Composer or npm in the container | `ddev artisan ...`, `ddev composer ...`, `ddev npm ...` |
| Open a MySQL shell | `ddev mysql` |
| Open Mailpit (all outgoing mail) | `ddev mailpit` |
| Show URLs and services | `ddev describe` |

### npm supply-chain settings

`.npmrc` hardens npm for this project. CI checks these settings:

- `ignore-scripts=true`: npm does not run install scripts of dependencies
- `min-release-age=7`: npm installs only package versions that are at least 7 days old
- `engine-strict=true`: npm refuses to install with a Node or npm version outside `engines` in `package.json`

Use `npm ci` to install exactly what `package-lock.json` specifies (`composer setup` does this).

## Tests

```bash
ddev composer test            # run the test suite
ddev composer test:coverage   # the same, with a coverage summary and an HTML report in coverage/
```

The tests run on SQLite in memory (see the `<server>` values in `phpunit.xml`). They never use the DDEV MySQL database:
`<server>` values override the database variables that DDEV sets in the container, `tests/TestCase.php` refuses to
reset any database except SQLite in memory, and `composer test` clears a cached configuration first.
`composer test:coverage` needs a coverage driver, for example Xdebug (`ddev xdebug on`).

## Code quality

The project uses three tools:

- [Laravel Pint](https://laravel.com/docs/pint) for the PHP code style (rules in `pint.json`)
- [Larastan](https://github.com/larastan/larastan) (PHPStan) for static analysis of `app/` (settings in `phpstan.neon`;
  known errors are in `phpstan-baseline.neon`, and new code must not add entries)
- [ESLint](https://eslint.org/) for `resources/js` (rules in `eslint.config.js`)

```bash
ddev composer lint:check   # run all three tools; report problems, change nothing
ddev composer lint:fix     # fix the PHP style and the JavaScript, then report what PHPStan still finds
```

`lint:check` stops at the first tool that reports a problem. PHPStan cannot fix code, so after `lint:fix` you fix the
remaining PHPStan errors by hand. CI runs the same checks.

## Continuous integration

The repository uses the shared [SciFY workflows](https://github.com/scify/.github):

- `ci.yml` (`laravel-ci.yml`) runs on pull requests and on pushes to `main`: Pint, PHPStan, ESLint, the frontend
  build and the tests. The required check is **`ci / CI`**.
- `security.yml` runs on pull requests and every Monday: secret scanning, npm supply-chain checks, `composer audit`
  and `npm audit`.

Dependabot opens pull requests for security updates only (`.github/dependabot.yml`). `dependabot-auto-merge.yml`
enables GitHub auto-merge for every update except a major version; it needs "Allow auto-merge" in the repository settings and the
required check `ci / CI` in the branch protection of `main`.

## Releasing

Releases follow [Semantic Versioning](https://semver.org/): increase the **major** version for changes that break
users or deployments without a migration path, the **minor** version for new features and compatible changes, and the
**patch** version for bug fixes only. The version is defined only in `package.json`; the app shows it in the footer.
Releases are tagged `vX.Y.Z`. All changes are recorded in [CHANGELOG.md](CHANGELOG.md).

To release version `X.Y.Z` from an up-to-date `main` with green CI:

1. In `CHANGELOG.md`, rename `## [Unreleased]` to `## [X.Y.Z] - YYYY-MM-DD`, add a new empty `## [Unreleased]` above
   it, and update the compare links at the end of the file.
2. Set the version (this updates `package.json` and `package-lock.json`, without a commit or tag):

   ```bash
   ddev npm version X.Y.Z --no-git-tag-version
   ```

3. Commit, tag and push:

   ```bash
   git add CHANGELOG.md package.json package-lock.json
   git commit -m "chore: release vX.Y.Z"
   git tag -a vX.Y.Z -m "vX.Y.Z"
   git push origin main --follow-tags
   ```

4. Create the GitHub release, with the changelog section as the release notes:

   ```bash
   awk '/^## \[X.Y.Z\]/{found=1; next} /^## \[/{found=0} found' CHANGELOG.md > release-notes.md
   gh release create vX.Y.Z --title "vX.Y.Z" --notes-file release-notes.md --verify-tag
   rm release-notes.md
   ```

5. Deploy the tag, not the branch (see below).

## Deployment

Deploy a release tag (`git checkout vX.Y.Z`), not `main`. Read the upgrade notes of every release in
[CHANGELOG.md](CHANGELOG.md) since the deployed version. A deployment needs these steps:

1. Node.js 24.21.0 on the build machine; build the frontend with `npm ci && npm run build`. The build writes to
   `public/build`.
2. `composer install --no-dev --optimize-autoloader`
3. `.env` for production:
   - `APP_ENV=production`, `APP_DEBUG=false`
   - `APP_URL` must match the host name that users open. Outside the `local` environment, `TrustHosts` accepts only
     the `APP_URL` host and its subdomains, and answers other hosts with HTTP 400. If a reverse proxy is in front of
     the app, make sure that it forwards the original `Host` header.
   - `MAIL_*`, including `MAIL_FROM_ADDRESS`: the app sends notifications (submissions, approvals, rejections,
     reports) by email.
   - `SENTRY_LARAVEL_DSN` for backend error reporting.
   - `VITE_SENTRY_DSN_PUBLIC` for frontend error reporting. Vite reads `VITE_*` values at build time, so set them
     before `npm run build`.
4. `php artisan migrate --force`, `php artisan storage:link`, then `php artisan config:cache`, `route:cache` and
   `view:cache`.
5. The web server's user must be able to write to `storage/` and `bootstrap/cache/`.
   `sudo ./set-file-permissions.sh www-data <deploy-user> .` sets the owner group, the permissions and the setgid bit
   on the storage folders, so that folders created by the deploy user keep the web server group.
6. The web server's document root is `public/`.
7. The notifications are queued. With `QUEUE_CONNECTION=sync` they are sent during the request; with any other queue
   connection, run a queue worker (`php artisan queue:work`) under a process manager such as Supervisor.

### Upgrading from the Laravel Mix version

The first deployment after the move from Laravel Mix to Vite needs these extra steps:

- Install Node.js 24.21.0 and build with `npm ci && npm run build` (not `npm run prod`).
- In `.env`, rename `MIX_PUSHER_*` to `VITE_PUSHER_*`, remove `MIX_APP_URL`, and set `VITE_SENTRY_DSN_PUBLIC`
  instead of `MIX_SENTRY_DSN_PUBLIC`.
- Delete the old build output: `public/dist`, `public/mix-manifest.json` and `public/fonts`.
- Run `set-file-permissions.sh` again (see step 5), so that the existing storage folders get the setgid bit.
- Check `storage/app/public/resources` for uploaded files that are not `.mp3`, `.png`, `.jpg` or `.jpeg`. Earlier versions
  did not check the file type of uploads.

## Sitemap

[Spatie Laravel Sitemap](https://github.com/spatie/laravel-sitemap) creates `public/sitemap.xml` (not in git):

```bash
ddev artisan sitemap:generate
```

## Debugging

Turn on Xdebug in the container with `ddev xdebug on` (and off again with `ddev xdebug off`), then start listening for
debug connections in your IDE (for example PhpStorm). With `APP_DEBUG=true`, the
[Laravel Debugbar](https://github.com/barryvdh/laravel-debugbar) shows queries and timings at the bottom of each page.

## License

Apache License 2.0. See [LICENCE.md](LICENCE.md).
