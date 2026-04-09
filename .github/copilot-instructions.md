# Vivanoo / Vibraplast — Shopware 6.6 Project

## Overview

Shopware 6.6.10.9 e-commerce project. PHP 8.4, MariaDB 10.11, Redis, OpenSearch 2.7. Three languages: German (de-DE, base), English (en-GB), French (fr-FR).

## Development Environment

**All commands must run inside DDEV** — use `ddev exec <cmd>` or `ddev ssh` first.

```bash
ddev start                              # Start all services
ddev composer install                   # Install PHP dependencies (needs auth.json)
ddev exec bin/console cache:clear       # Clear cache (do after config/plugin changes)
ddev exec bin/console theme:compile     # Compile theme assets
ddev exec bin/console plugin:refresh    # Detect plugin changes
```

Site: `https://vibraplast.ddev.site`

### Build

```bash
ddev exec bin/build-storefront.sh       # Build storefront JS/CSS
ddev exec bin/build-administration.sh   # Build admin JS
ddev exec bin/watch-storefront.sh       # Watch with hot reload
```

### Test

```bash
ddev exec vendor/bin/phpunit                      # All tests
ddev exec vendor/bin/phpunit --filter=TestName     # Single test
cd e2e && npm run cy:run                           # Cypress E2E headless
```

### Translation Validation

```bash
ddev exec php vendor/bin/phpunuhi validate         # Check snippet translations
ddev exec php vendor/bin/phpunuhi fix:structure     # Add missing translation keys
```

See [CLAUDE.md](../CLAUDE.md) for full command reference.

## Architecture

### Theme Inheritance

```
@Storefront → @WebwirkungVibraplastTheme → @VivanooTheme
                                         → @WebwirkungPeterHeftiTheme
```

- **WebwirkungVibraplastTheme** — Main theme with CMS blocks, 11+ JS plugins, event subscribers, service decorators, Twig extensions, migrations. The heavy lifter.
- **VivanooTheme** — Lightweight child theme. Branding overrides only (SCSS, snippets, logo). No custom PHP logic.
- **WebwirkungPeterHeftiTheme** — Another child theme (white-label, color overrides only).

### Custom Plugins (`custom/static-plugins/`)

All custom code lives in static plugins, symlinked via Composer path repositories:

| Plugin | Purpose |
|--------|---------|
| WebwirkungFaqPlugin | FAQ entities + admin CRUD + CMS blocks |
| WebwirkungGlossaryPlugin | Glossary entity + admin + storefront filtering |
| WebwirkungProductFinder | Multi-step product finder wizard (category custom fields) |
| WebwirkungMinimumOrderSurchargePlugin | Cart surcharge for low-value orders |
| WebwirkungVibraplastAbacusIntegration | ERP price calculator decorators (Abacus) |

### Infrastructure

- **Cache/Sessions:** Redis (DB 0 = cache, DB 1 = sessions)
- **Search:** OpenSearch 2.7 (`SHOPWARE_ES_ENABLED`)
- **Media:** Amazon S3 (`AWS_CDN_DOMAIN`)
- **Mail (dev):** Mailpit on port 8025
- **CI/CD:** Buddy pipelines in `.buddy/`

## Conventions

### Plugin Development

- Custom plugins go in `custom/static-plugins/<PluginName>/`
- Register new plugins as path repositories in root `composer.json`
- Follow Shopware 6 plugin structure: `src/`, `src/Resources/`, PSR-4 autoloading
- Themes implement `ThemeInterface` and configure via `src/Resources/theme.json`
- Run `ddev exec bin/console plugin:refresh && ddev exec bin/console plugin:install --activate <name>` after creating a plugin

### Translations

- Every user-visible string needs snippets in all 3 locales: `de-DE`, `en-GB`, `fr-FR`
- Snippet files: `src/Resources/snippet/storefront.{locale}.json`
- Validate with PHPUnuhi before committing

### Storefront Assets

- SCSS entry points: `base.scss` (variables, component styles), `overrides.scss` (Shopware variable overrides)
- JS plugins registered in `src/Resources/app/storefront/src/main.js`
- Always run `ddev exec bin/build-storefront.sh` or theme:compile after asset changes

### Key Config Files

| File | Purpose |
|------|---------|
| `config/packages/shopware.yaml` | Filesystem, API JWT, auto-update |
| `config/packages/redis.yaml` | Cache + session config |
| `config/packages/staging.yaml` | Staging mode |
| `phpunuhi.xml` | Translation validation sets |
| `.env` / `.env.local` | Environment variables |

## Pitfalls

- **Never run PHP/composer/bin/console outside DDEV** — services (DB, Redis, OpenSearch) are containerized
- **`auth.json` contains Shopware Store credentials** — required for `composer install`, do not commit secrets
- **Redis config** is currently at `redis.yaml.bak` — Redis may be disabled locally; check before relying on cache
- **Theme compile needed after SCSS/Twig changes** — storefront won't reflect changes without `theme:compile` or `build-storefront.sh`
- **Plugin symlinks** — if `vendor/<name>` doesn't resolve, run `ddev composer install` to recreate symlinks
