# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Vibraplast is a **Shopware 6.6** (v6.6.10.9) e-commerce project built by Webwirkung. It runs on PHP 8.4, uses MariaDB 10.11, Redis for caching/sessions, and OpenSearch for product search. The shop supports three languages: German (de-DE, base), English (en-GB), and French (fr-FR).

## Development Environment

**DDEV is the local development environment.** All commands (composer, bin/console, PHP, etc.) must be run inside the DDEV container, either via `ddev exec` or after `ddev ssh`.

```bash
ddev start                  # Start all services (DB, Redis, OpenSearch, web server)
ddev ssh                    # Shell into web container
```
Site available at `https://vibraplast.ddev.site`.

### Common Commands
```bash
ddev composer install                              # Install PHP dependencies (requires auth.json with Shopware store token)
ddev exec bin/console system:install --basic-setup # Initial setup (or import a DB dump instead)
ddev exec bin/console cache:clear                  # Clear Shopware cache
ddev exec bin/console theme:compile                # Compile theme assets
ddev exec bin/console plugin:refresh               # Detect plugin changes
ddev exec bin/console plugin:install --activate <name>  # Install and activate a plugin

ddev exec bin/build-js.sh             # Build all JS (admin + storefront)
ddev exec bin/build-storefront.sh     # Build storefront JS/CSS only
ddev exec bin/build-administration.sh # Build administration JS only
ddev exec bin/watch-storefront.sh     # Watch storefront with hot reload
ddev exec bin/watch-administration.sh # Watch administration with hot reload
```

### Tests
```bash
# PHPUnit (config: phpunit.xml.dist, bootstrap: tests/bootstrap.php)
ddev exec vendor/bin/phpunit
ddev exec vendor/bin/phpunit --filter=TestClassName

# Cypress E2E (from e2e/ directory)
cd e2e && npm install
npm run cy:open    # Interactive mode
npm run cy:run     # Headless mode
```

### Translation Validation (PHPUnuhi)
```bash
ddev exec php vendor/bin/phpunuhi validate                                    # Check file-based snippet translations
ddev exec php vendor/bin/phpunuhi validate --configuration=./phpunuhi_database.xml  # Check DB translations
ddev exec php vendor/bin/phpunuhi validate:coverage                           # Translation coverage report
ddev exec php vendor/bin/phpunuhi scan:usage --dir=./custom --scanner=twig    # Find unused snippets
ddev exec php vendor/bin/phpunuhi fix:structure                               # Add missing translation keys
```

## Architecture

### Custom Plugins (in `custom/static-plugins/`)

All custom code lives in static plugins referenced via Composer path repositories and symlinked into `vendor/`:

- **WebwirkungVibraplastTheme** — Main storefront theme. Extends `@Storefront`. Contains custom CMS blocks (image-caption, icon-teaser, label-text-button, etc.), 11 storefront JS plugins (Navigation, MultiStepForm, VariantsBuyTable, VariantConfigurator, GallerySlider, etc.), event subscribers, service decorators, custom Twig extensions, and database migrations.
- **WebwirkungPeterHeftiTheme** — Child theme inheriting from VibraplastTheme. Overrides colors and minimal styling only (white-label variant).
- **WebwirkungFaqPlugin** — FAQ system with custom entities (`ww_faq`, `ww_faq_category`), admin module for CRUD, and CMS blocks for storefront display.
- **WebwirkungGlossaryPlugin** — Glossary/dictionary with custom entity (`ww_glossary`), admin module, and storefront JS plugins for alphabetical filtering.
- **WebwirkungProductFinder** — Multi-step product finder wizard. Uses category custom fields (`ww_product_finder_step_X_*`) to configure steps and property groups per category. Has a storefront controller and CMS elements.
- **WebwirkungMinimumOrderSurchargePlugin** — Adds surcharge fees to low-value orders via cart/order subscribers and customer custom fields.
- **WebwirkungVibraplastAbacusIntegration** — Backend-only ERP pricing integration. Decorates Shopware's price calculators (piece, linear meter, square meter) to pull prices from Abacus ERP.

### Theme Inheritance Chain
```
@Storefront → @WebwirkungVibraplastTheme → @WebwirkungPeterHeftiTheme
```

### External Composer Plugins (via Shopware Store or VCS)
- `webwirkung/abacus-integration` — Base Abacus ERP connector
- `store.shopware.com/swagcommercial` — Shopware Commercial (B2B, rules, etc.)
- `store.shopware.com/sasblogmodule` — Blog module (extended by theme)
- `store.shopware.com/swagcustomizedproducts` — Customized products
- `store.shopware.com/swaglanguagepack` — Language pack

### Infrastructure
- **Filesystem:** Public media on Amazon S3 (`AWS_CDN_DOMAIN`); theme/assets/sitemap served locally
- **Cache & Sessions:** Redis (DB 0 for app cache, DB 1 for sessions) — config in `config/packages/redis.yaml`
- **Search:** OpenSearch 2.7 — toggled via `SHOPWARE_ES_ENABLED` env var
- **Mail:** Mailpit in dev (port 8025 for UI)
- **CI/CD:** Buddy pipelines (`.buddy/` directory) for staging/production deploys, DB backups, and E2E tests
- **JWT:** Keys stored as base64 in env vars `JWT_PRIVATE_KEY` / `JWT_PUBLIC_KEY`

### Key Config Files
- `config/packages/shopware.yaml` — Filesystem config, API JWT, auto-update disabled
- `config/packages/redis.yaml` — Redis cache and session config
- `config/packages/staging.yaml` — Staging mode configuration
- `phpunuhi.xml` — File-based translation validation sets
- `.env` — Default env vars; `.env.local` has local overrides (gitignored)
