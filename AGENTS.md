# AGENTS.md

Guidance for AI coding agents working in this repository. `CLAUDE.md` imports this file. Commands, setup and
deployment are in `README.md`; changes are recorded in `CHANGELOG.md`.

## Project

Talk & Play Marketplace (<https://talkandplay.scify.org/>): a Laravel 12 app where specialists and family members
create communication-card and game-card **packages** for the Talk & Play desktop app. Administrators approve packages;
the desktop app downloads approved ones through `routes/api.php`. Default branch: `main`.

## Working rules

- **Run everything in DDEV** (`ddev composer …`, `ddev artisan …`, `ddev npm …`, `ddev exec …`). The host may have a
  different PHP. `ddev start` runs `composer setup` and `php artisan migrate` on the DDEV database (post-start hook).
- **The developer's DDEV database holds real local data.** Ask before `migrate`, `db:seed` or tinker writes against
  it. For browser checks, create temporary users and data, and delete them afterwards.
- **Commit gate**: run each tool as its own command and read its exit code, then commit in a separate command:
  `ddev composer lint:check`, `ddev npm run build`, `ddev composer test`. A commit chained after a gate once went in
  with a PHPStan error.
- `phpstan-baseline.neon`: new code adds no entries. When a change removes code that an entry ignores, remove that
  entry in the same commit (PHPStan fails on unmatched entries).
- `composer require` / `composer update` without `-W`; use `-w` with the exact packages instead.
- `main` accepts only pull requests with a passing `ci / CI` check (ruleset, no bypass). Work on a branch; ask before
  every push, force push, tag and release.
- Commits are authored by Paul only, without `Co-Authored-By` lines. Pull request descriptions have no "Test plan"
  section and no mention of AI tools.
- Every user-visible change gets an entry under `## [Unreleased]` in `CHANGELOG.md`; deployment-relevant changes also
  get an upgrade note. Releases follow "Releasing" in `README.md`.

## Tests

- **Tests must run on SQLite in memory only.** DDEV sets `DB_CONNECTION=mysql` and `DB_HOST=db` as container
  variables, which `<env>` lines in `phpunit.xml` would not override; the `<server>` lines do.
  `tests/TestCase.php` throws before `RefreshDatabase` runs on any other database (the check is in `setUpTraits()`).
  Keep both.
- `TestCase` seeds through `RefreshDatabase` (`$seed = true`) and fakes the `public` disk and notifications. A test
  that checks notifications asserts on that fake.
- Pest helpers in `tests/Pest.php`: `memberUser()`, `adminUser()`, `createPackage($creator, $statusId, $typeId)`.
- Write the test first and watch it fail; for access rules, also remove the check once and watch the test fail.
- Chained Pest datasets (`->with(a)->with(b)`) form a Cartesian product, not a union: use one dataset per test, and
  list the case names with `--filter` to confirm the cases.
- `UploadedFile::fake()` derives its MIME type from the file name. To test content-based checks, build a real
  `UploadedFile` from a temporary file.
- Without a terminal, a failing `ddev composer test` prints no test output. For scripted runs use
  `ddev exec php artisan test --compact`.
- Laravel skips CSRF in tests: check CSRF-relevant changes in a browser with a real session.

## Domain and architecture

- Controllers → `app/BusinessLogicLayer/*Manager` → `app/Repository/*Repository`. `Repository::where()` returns the
  first match or `null`; controllers turn `null` into 404 (`findPackageOrFail()`, `?? abort(404)`).
- A package (`ResourcesPackage`, table `resources_package`) has a **cover card** (`card_id` → `resources.id`); its
  cards are `resources` rows with `resource_parent_id` = cover card id. `ResourcesPackageManager::getPackageOfResource()`
  finds the package of any card. The MySQL foreign key names still carry the old table name
  `communication_resources_pack`.
- Types (`ResourceTypesLkp`): 1 communication, 2-4 games. Statuses (`ResourceStatusesLkp`): 1 pending, 2 approved,
  3 rejected. Roles (`UserRolesLkp`): 1 admin, 2 content creator.
- Access: gate `manage-platform` (admin); `ResourcesPackagePolicy::manage` (creator or admin) for edit, update,
  delete, submit and adding cards; `::view` (approved, or `manage`) for download and clone. Ids come from the route
  and the session user, never from the request body.
- A member's change to an approved package sends it back to pending and mails the admins
  (`returnToPendingAfterEdit()`); a failed mail is reported, not thrown.
- The package list endpoints in `routes/api.php` run with the `web` middleware so `visibleStatusIds()` knows the
  viewer: non-approved packages only for admins and their creator. The desktop app calls them without a session.
  Package payloads show the creator by `id` and `name` only.
- Uploads: `mimes` rules on every card route (sound mp3, images jpg/png), and `ResourceFileManager` stores only
  content that matches, with the extension taken from the content.
- `TrustHosts` is on outside `local`: only the `APP_URL` host and its subdomains (others get HTTP 400).

## Frontend

- Vite entries are listed in `vite.config.js`; Blade loads them with `@vite()`. A new page asset needs an `input`
  entry. The Vite dev server runs in DDEV on port 5179.
- `#app` wraps the layout with in-DOM Vue 2.7 templates, so `vue` resolves to the full build. `@vitejs/plugin-vue2`
  supports Vite ≤ 7.
- jQuery: `resources/js/jquery-global.js` sets `window.$`; modules import `$`. Inline Blade scripts that use jQuery
  must be `type="module"` (`tests/Unit/InlineScriptsTest.php` enforces it).
- `public/vendor/scify` is published by Composer on every install (`--force`) and is not in git. Cookie dialog
  colours are CSS-variable overrides in `resources/sass/app.scss` (README "Cookie consent").
- `common/footer-scripts.blade.php` sets `window.Laravel` (`csrfToken`, `baseUrl`, route map); the frontend calls
  routes by name through `window.route()`, so keep route names stable. Use `window.Laravel.baseUrl` for URLs;
  `VITE_*` values are fixed at build time.

## Known issues

- `ResourceController::report` takes the package id from the request body (500 on an unknown id) and accepts reports
  on non-approved packages.
- Ratings are accepted for non-approved packages.
- The reported packages page fails when a reported package was deleted (`getReportedPackages`).
- The `web` middleware on the list API starts a session for every desktop-app request.
- `AdminNotice` uses the "new package submission" text also for edits.
- The response-to-reporter feature has no button in the UI.
