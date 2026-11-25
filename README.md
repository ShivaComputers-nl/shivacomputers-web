# Shiva Computers Website

A bilingual PHP 8.2+ website for ShivaComputers.nl, built with layered MVC, Composer, FastRoute and file-backed repositories. **SC-MASTER-001 local candidate — P01 documentation (25 November 2026).** Written against the baseline source at `develop` `f5eff3cf5fe1d7e5fe50960b99ec5b74b43033e8` and the owner-approved target. "Current" in this document means that baseline; later local packages (P03 → P02 → P04(A) → P05) implement parts of the target and their verified status is recorded in `docs/WEBSITE_ACCEPTANCE.md` (created by P05). Nothing here claims production deployment, current CI or live verification.

## Business direction

Approved target: two equal divisions, Software Design & Development (blue) and Computer Repair & Support (green). Support includes Shiva Assist, Shiva Recovery (including existing Shiva Rescue OS) and Shiva Remote. Private Shiva Infrastructure is not public. Separate product release gates. The existing site still uses four homepage product cards and a grouped menu; the redesign is not implemented by P01.

## Documentation index

- [Functional specification](SPECIFICATION.md)
- [Technical architecture](docs/ARCHITECTURE.md)
- [Business architecture](docs/BUSINESS_ARCHITECTURE.md)
- [Website sitemap](docs/WEBSITE_SITEMAP.md)
- [Shared navigation](shiva-nav/README.md)
- [Agent rules](AGENTS.md)
- [Continuation checkpoint](HANDOFF.md)

## Start locally (existing instructions; separate execution authorization required)

Docker installs Composer packages inside the image, so host PHP is not necessary.

```bash
docker compose up --build -d
```

Open http://localhost:8080/en or http://localhost:8080/nl. Stop with `docker compose down`. Rebuild with `--build` after changing composer.json. Port 8080 must be free.

### Create or update composer.lock (separate dependency maintenance only)

```powershell
docker run --rm -v "${PWD}:/app" composer:2 update --no-interaction --no-scripts --ignore-platform-reqs
```

This creates vendor/ (ignored). Commit lock changes only under separate authorization. Do not update dependencies as part of P01.

### Tests (future authorized local execution)

```bash
docker compose exec web vendor/bin/phpunit
```

Without Docker, run `composer install` (committed lock only) and `vendor/bin/phpunit` with a local PHP 8.2+ that has dom, mbstring, simplexml and xmlwriter. Use isolated local storage (`STORAGE_PATH`) and file transport (`MAIL_TRANSPORT=file`); no real mail. P01 itself is documentation only and runs no tests.

## Contact form

`POST /{language}/contact` uses ContactController and ContactFormService. The form is implemented in source; production delivery is unverified. Spam protection: hidden trap, signed token rejecting submissions within 3 seconds or after 2 hours, at most five messages per visitor/hour; IPs stored as keyed hashes. Production default `MAIL_TRANSPORT=mail` uses PHP mail() on STRATO with configured sender `website@shivacomputers.nl` and visitor Reply-To; development `file` writes to storage/unsent/. Mail failure falls back to file storage (not a guarantee of delivery or persistence if storage fails). `storage/` sits outside public/, holds app.key, rate limits and unsent messages; deployment must preserve it. Do not disclose key contents or customer messages.

## Current routes, products and menu

Routes defined in `config/routes.php`. Current `/` → `/en`; `/en`, `/nl`; `/<lang>/services`, `/portfolio`, `/about`, `/contact` (GET+POST), `/privacy`, `/remote-support`; `/help`, `/hulp`; `/robots.txt`, `/sitemap.xml`. SEO uses canonical, hreflang and homepage organization JSON-LD; see [sitemap](docs/WEBSITE_SITEMAP.md).

| Current card | Link |
|---|---|
| Software & websites | `/<lang>/services` |
| Shiva Rescue OS | `https://rescue.shivacomputers.nl/<lang>` |
| Shiva Assist | `https://assist.shivacomputers.nl/<lang>` |
| Remote help | `/<lang>/remote-support` |

Product enum and ProductLinks drive cards; shiva-nav/menu.php independently drives grouped Home, Software & websites (Services, Portfolio), Computer help (Rescue, Assist, Remote), About, Contact and language. Shared nav uses details/summary and a separate mobile menu below 900px (`max-width: 899px` in shiva-nav.css). Product origins from `product_sites` in config/app.php. Development overrides: `RESCUE_SITE_URL=http://localhost:8082`, `ASSIST_SITE_URL=http://localhost:8083`; only approved HTTPS origin syntax in production, localhost HTTP in development. Increment site.css query version in Layouts/main.php when styles change. No P01 runtime changes.

## Remote support

`/<lang>/remote-support` explains remote help; sessions are in separate documented shiva-remote-portal, target remote.shivacomputers.nl (existing specification refers to *Shiva Remote Support — Architecture & Design v3*). Main website has a contact form but does not host remote sessions.

| Configuration | Information page | /help and /hulp |
|---|---|---|
| remote_support_url null (default) | Call button | EN/NL information page |
| Approved HTTPS portal origin | Enter your code | EN/NL portal |

`REMOTE_SUPPORT_URL` overrides the setting; development example http://localhost:8081. Existing validation requires origin only, rejects credentials/path/query/fragment, allows local HTTP only in development; unsafe portal disables link. Shortcuts use 302 and no-store. No automatic remote connection. Portal must pass production gate before configuration; explicit customer consent/control preserved.

## Before go-live

Review contact details, project statements/images/rights, content, privacy and availability. Existing content_approved=true does not approve redesigned copy. Keep APP_ENV production (unset default); no previews or sensitive errors. Set content approval only after separate review. Verify PHP >=8.2, rewrite, optional mod_headers, HTTPS, SSH/SFTP, document-root mapping on actual STRATO Web Power Advance plan. Do not assume package features.

## Deployment on STRATO (operational guidance only; separate authorization required)

Build production dependencies only after approval:

```powershell
docker run --rm -v "${PWD}:/app" composer:2 install --no-dev --optimize-autoloader --no-scripts --ignore-platform-reqs
```

Set domain root to public/; src/, config/, data/, translations/, vendor/, storage/ and shared PHP files must remain outside public access. If hosting cannot map public/, stop and obtain safe restructuring approval. Upload over SFTP including vendor, shared shiva-nav folder and assets, preserve public/.htaccess and rewrite. Verify HTTPS and routes before any separately approved HTTPS configuration change. No unfinished legal/contact content.

Every push to main runs deployment workflow: tests first; install production dependencies, directory 755/file 644; verify STRATO host-key fingerprint; mirror application directories (including shiva-nav) to documented production target, removing obsolete mirrored files; preserve private storage. Smoke checks include /en, /nl, /en/services, /en/remote-support and /en/contact returning 200 and /config/app.php returning 404. Future /services redirect must be compatible with existing smoke behavior or separately reconciled. Compatibility option A (SC-MD03, approved): `/services` stays a canonical 200 page in both languages for this redesign; the 301 to `/software-development` is deferred to a separately authorized option B that must also amend the `/en/services` smoke check in `.github/workflows/deploy.yml` (outside the 46-path scope). The existing secret name `STRATO_SFTP_PASSWORD` is managed in GitHub Actions (Settings → Secrets and variables → Actions); never disclose its value in source or logs. Manual workflow_dispatch also deploys; never trigger without explicit approval. Rollback by separately approved revert/fix on main triggers redeployment; verify backups and rollback beforehand. Non-main pushes and PRs may trigger CI; no push/PR without permission.

## Limitations

Existing project names/repository links were previously documented as checked; descriptions, images, rights and production suitability require review. Current source audit only, no current runtime, CI, mail delivery, accessibility or live deployment verification. Product availability, licensing, project rights, privacy and cross-site shared-navigation authority require review. See HANDOFF for next steps.
