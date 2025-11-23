<!-- Copilot/AI agent instructions for SWPSC-SL-PPP2 -->
# Quick orientation for AI coding agents

This repository is a Laravel 10 application (PHP ^8.1) with a Filament v3 admin panel. The goal of this file is to orient an AI agent so it can be immediately productive and follow project conventions.

- **Big picture**: backend-first Laravel app with a Filament admin UI. Eloquent models live in `app/Models`, Filament resources in `app/Filament/Resources`, and custom providers in `app/Providers`.
- **Runtime & infra**: Dockerfile uses `webdevops/php-nginx:8.2-alpine`. Local start script `start.sh` runs migrations and `php artisan serve`. `docker-compose.yml` defines a `marmotteio` app service and a MariaDB service.

- **Key packages to be aware of**: see `composer.json` — notable packages include `filament/filament`, `bezhansalleh/filament-shield`, `spatie/laravel-permission`, `vyuldashev/laravel-openapi`, `maatwebsite/excel`, `sentry/sentry-laravel`.

- **Locale / timezone**: default locale is Spanish and timezone is `America/Lima` (`config/app.php`). Keep UI strings and notifications typically in Spanish.

- **Filament patterns (what to look for and edit)**:
  - Filament Resources: `app/Filament/Resources/<Resource>/*` — resources are split into `Pages`, `RelationManagers`, and a main `Resource` class (e.g., `app/Filament/Resources/HardwareResource.php`).
  - Shared components: `app/Filament/Resources/Shared` (examples: `ClsmComponent`, `ImagesAndNoteComponent`) — reuse these for consistent forms.
  - Relation managers live in `RelationManagers` subfolders and are returned by `getRelations()`.
  - Common pattern: resource `form()` and `table()` methods build schemas with `Section`, `Grid`, `KeyValue`, custom formatting via `formatStateUsing` (see `specifications` handling in `HardwareResource`).

- **Data storage & conventions**:
  - Some fields store JSON strings (example: `specifications` on `Hardware` stored/displayed via `json_decode` + `formatStateUsing`). Handle both string and array states when reading.
  - Global search attributes on resources may include relationship paths like `hardware_model.name` (see `getGloballySearchableAttributes`).

- **Dev / build / test workflows** (commands you can run):
  - PHP deps: `composer install` (or in container). On Windows PowerShell: `composer install`.
  - Copy env (if missing): PowerShell: `copy .env.example .env`; *nix: `cp .env.example .env`.
  - Generate key: `php artisan key:generate`.
  - Migrate: `php artisan migrate` (production uses `--force`). `start.sh` calls `php artisan migrate --force` and `php artisan app:create-default-tenant-command`.
  - Serve locally: `php artisan serve --host=0.0.0.0 --port=8000` (or use `docker-compose up` with the included compose file).
  - Frontend: `npm install` then `npm run dev` (Vite) or `npm run build` for production assets.
  - Tests: `./vendor/bin/phpunit` or `vendor/bin/phpunit` — `phpunit.xml` sets test env defaults (DB_DATABASE=`marmotte`).

- **Where to look first when implementing a change**:
  - Business rules / data shape: `app/Models` and related migrations in `database/migrations`.
  - Admin UI: `app/Filament/Resources` and `app/Filament/Resources/Shared` for form components and layout conventions.
  - Config / environment: `config/*.php` (notably `config/app.php`) and `docker-compose.yml` / `Dockerfile` for runtime details.
  - Routes & auth hooks: `routes/web.php` and `app/Providers` (Filament admin provider lives in `App\\Providers\\Filament\\AdminPanelProvider`).

- **Common code patterns to match**:
  - Use existing shared Filament components; prefer `ClsmComponent::render()` or `ImagesAndNoteComponent::render()` rather than creating new ad-hoc widgets.
  - Use `->preload()` and `->searchable()` on `BelongsToSelect` for relationship selects (see `HardwareResource`).
  - Keep table column `formatStateUsing` logic close to model presentation—decode JSON safely and return readable strings.
  - Keep language in Spanish for user-facing labels and notifications.

- **Integration points and gotchas**:
  - DB assumptions: tests and docker use different DB names (`marmotte` vs `marmotteio`). Verify which DB a change targets before running migrations/tests.
  - `start.sh` runs an app-specific command `php artisan app:create-default-tenant-command` — ensure migrations and seeding expectations are satisfied when starting containers.
  - OpenAPI: provider `vyuldashev/laravel-openapi` is registered; API schema may be under `openapi.json`.

- **If you change structure or add a package**:
  - Update `composer.json` and run `composer install` (or run inside the container). Follow `scripts` hooks—some packages trigger `artisan vendor:publish` or `filament:upgrade` in `composer.json` scripts.

If anything here is unclear or you want the doc expanded with examples (e.g., common PR checklist, where UI copy lives, or detailed Filament component patterns), tell me which area to expand and I'll iterate.
