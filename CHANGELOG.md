# Changelog

All notable changes to this project are documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/), and this project adheres to
[Semantic Versioning](https://semver.org/spec/v2.0.0.html). The version is defined in `package.json`; see
"Releasing" in `README.md`.

## [Unreleased]

### Added

- `AGENTS.md` with guidance for AI coding agents; `CLAUDE.md` imports it.

### Changed

- The cookie consent assets (`public/vendor/scify`) are no longer tracked in git; Composer publishes them on every
  install. Override their colours in `resources/sass/app.scss` (README "Cookie consent").

## [1.1.0] - 2026-10-19

### Upgrade notes

This release changes how the application is built, configured and checked. Before you deploy it, in this order:

1. In `.env`: set `VITE_SENTRY_DSN_PUBLIC` instead of `MIX_SENTRY_DSN_PUBLIC`; delete `MIX_APP_URL`,
   `MIX_PUSHER_*` (no code uses them) and `APP_VERSION`; check that `MAIL_FROM_ADDRESS` is set. Vite reads `VITE_*`
   values at build time, so change `.env` before the build.
2. Make sure that `APP_URL` matches the host name that users open: requests for other hosts now get HTTP 400.
3. Install Node.js 24.21.0 on the build machine and build with `npm ci && npm run build` (Vite writes to
   `public/build`; the `npm run prod` script no longer exists). Delete the old build output: `public/dist`,
   `public/mix-manifest.json` and `public/fonts`.
4. Back up the database. Then check that this query returns no rows; otherwise fix those packages first:

   ```sql
   SELECT DISTINCT type_id FROM resources_package
   WHERE type_id IS NOT NULL AND type_id NOT IN (SELECT id FROM resource_types_lkp);
   ```

   The new migration replaces the foreign key on `resources_package.type_id`, and it stops if such rows exist.
5. Run `php artisan migrate --force` and `php artisan cache:clear` (the file cache permissions changed).
6. Run `set-file-permissions.sh` again (see "Deployment" in `README.md`), so that the storage folders get the
   setgid bit.
7. After the deployment:
   - Check the `user_roles` table for unexpected administrators (role 1). Earlier versions let any member grant
     themselves the admin role.
   - Check `storage/app/public/resources` for uploaded files that are not `.mp3`, `.png`, `.jpg` or `.jpeg`.
     Earlier versions did not check the type of uploaded files.

### Added

- Composer scripts: `setup`, `test`, `test:coverage`, `lint:check` and `lint:fix`. In DDEV, `ddev start` runs
  `composer setup` and the migrations, and `ddev coverage` runs the tests with a coverage report.
- Feature tests for pages, access control, profile updates, packages, uploads, ratings, reports, trusted hosts and
  the file cache. They run on SQLite in memory, with a guard that refuses to reset any other database.
- CI and security checks through the shared SciFY workflows (`ci.yml`, `security.yml`).
- Larastan (PHPStan) static analysis with a baseline, and an ESLint flat configuration with Vue 2 rules.
- npm supply-chain settings in `.npmrc` (`ignore-scripts`, `min-release-age=7`, `engine-strict`).
- A current password field and a password confirmation field in the profile form.
- An email to the administrators when a member's change sends an approved package back to pending.
- This changelog and a documented release process.

### Changed

- The frontend is built with Vite 7 instead of Laravel Mix and webpack.
- Node.js 24.21.0 is required (`.nvmrc`, `engines` in `package.json`, `nodejs_version` in DDEV).
- The application version comes from `package.json` instead of the `APP_VERSION` environment variable. The footer
  and the analytics events show it in SemVer form (for example `1.1.0` instead of `vBeta1`).
- A member's change to an approved package sends it back to pending approval; changes by administrators keep the
  status.
- Changing the email or the password in the profile needs the current password. A new password now takes effect;
  the old form ignored it.
- Deleting a package is a `DELETE` request.
- The package lists show pending and rejected packages only to administrators and to their creator. The desktop app
  and other visitors get approved packages, with the creator's id and name only.
- The moderation pages, the user reports and the response to a reporter are for administrators only. The user
  reports moved from `GET /api/resources/user-reports` to `GET /resources/user-reports`, and the response to a
  reporter from `POST /api/resources/respond` to `POST /resources/respond/{report_id}`.
- The README is rewritten around DDEV and the Composer scripts, with a deployment checklist.
- The PHP code is formatted with Pint.

### Removed

- Laravel Mix, webpack and their loaders.
- The debug routes `/test-sentry`, `/test-email` and `administration/test-email`.
- The API routes `GET /api/resources/user-reports` and `POST /api/resources/respond` (see "Changed").
- Routes to controller methods that did not exist, and the unused Fortify profile-information and two-factor
  features.
- The custom DDEV `composer` and `artisan` commands (DDEV provides both).

### Fixed

- The DDEV start no longer replaces `APP_KEY` on every start.
- No deprecation of `PDO::MYSQL_ATTR_SSL_CA` on PHP 8.5.
- The cookie consent script no longer throws a JSON error on every page; its assets are republished after every
  `composer install`.
- Creating a package no longer fails when no resource has the id of the package type (wrong foreign key on
  `resources_package.type_id`).
- The file cache no longer creates folders that it cannot open again.
- `/js/translations.js` returns a response instead of calling `exit()`, and no longer clears the cache on every page.
- An administrator no longer loses the admin role when saving their own profile.
- Admin edits no longer make the administrator the creator of the card.

### Security

- Users can update only their own profile, without role changes.
- Only the creator or an administrator can change, delete, submit or edit a package and its cards.
- Only administrators can approve or reject packages, read the user reports or respond to them.
- Uploads accept only mp3 sounds and jpg or png images, and stored files take their extension from their content.
- Public package payloads no longer contain email addresses, hashed emails, tokens or roles.
- Download and clone are limited to approved packages, unless the user can manage the package.
- Ratings always belong to the logged-in user.
- `TrustHosts` accepts only the `APP_URL` host and its subdomains.
- The login rate limit is no longer reset by every page view.
- Updated packages with security advisories (Composer and npm).

## [1.0.0]

The state of the application before release tags were introduced (commit `889272c`). It is not tagged.

[Unreleased]: https://github.com/scify/Talk-And-Play-Marketplace/compare/v1.1.0...HEAD
[1.1.0]: https://github.com/scify/Talk-And-Play-Marketplace/compare/889272c...v1.1.0
