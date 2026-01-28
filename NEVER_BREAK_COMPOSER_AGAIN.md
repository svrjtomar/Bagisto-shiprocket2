
# Never Break Composer Again 🚨
## Production Safety Rulebook for Bagisto & Laravel

This document explains exact rules to safely use Composer on a live Bagisto/Laravel website.

---

## RULE 0 — Golden Rule
Composer is not a toy on production.

---

## RULE 1 — NEVER edit composer.lock
Do not manually edit composer.lock.

---

## RULE 2 — NEVER run composer update on production
Use only:
composer install --no-dev --optimize-autoloader

---

## RULE 3 — Always backup before Composer
Backup:
.env
composer.json
composer.lock
packages/

---

## RULE 4 — Remove packages in one command
Use one atomic remove command.

---

## RULE 5 — Clear cache before & after Composer
php artisan optimize:clear

---

## RULE 6 — If Artisan fails, STOP
Fix bootstrap first.

---

## RULE 7 — Recovery Mode
rm -rf vendor
composer install --no-dev

---

## RULE 8 — Remove dev packages from production
phpunit, pest, mockery must be removed.

---

## RULE 9 — Composer law
composer.lock must match vendor.

---

## RULE 10 — Environment usage
Local: update
Production: install --no-dev

---

## RULE 11 — Custom packages
No dev deps, ServiceProvider only.

---

## RULE 12 — Fix error
Call to make() on null → delete vendor

---

## RULE 13 — Token handling
Cache tokens, auto-refresh

---

## RULE 14 — Maintain changelog

---

## RULE 15 — When unsure, STOP

---

TL;DR
NEVER edit lock
NEVER update prod
DELETE vendor if broken
