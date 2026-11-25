# Shiva Computers — Technical Architecture

**SC-MASTER-001 local candidate — P01 documentation (25 November 2026).** Written against the baseline source at `develop` `f5eff3cf5fe1d7e5fe50960b99ec5b74b43033e8` and the owner-approved target. "Current" in this document means that baseline; later local packages (P03 → P02 → P04(A) → P05) implement parts of the target and their verified status is recorded in `docs/WEBSITE_ACCEPTANCE.md` (created by P05). Nothing here claims production deployment, current CI or live verification.

## Current baseline and MVC layers

Reference: `develop` `f5eff3cf5fe1d7e5fe50960b99ec5b74b43033e8`; `main` `9e18f6ac194a3e43205b2c243c821d5430cdfba0`; both tree `03a9286ccf9ca4937adfec2bd7005f9768fe33c6` (116 tracked blobs), refreshed 25 November 2026. Repository: https://github.com/ShivaComputers-nl/shivacomputers-web (ID 1444462366).

```text
Request → public/index.php → Middleware (security headers) → FastRoute Router
→ Controller → Service → Repository → data/*.php, config
← typed Models → ViewModel + View → Response
```

Each layer has a defined responsibility; dependency injection wires interfaces at `config/services.php`. Signed contact-form token validation belongs to the contact flow; no claim of generic CSRF middleware.

| Folder | Responsibility | Example |
|---|---|---|
| config/ | Settings, routes, object wiring | routes.php, services.php |
| src/Framework/ | Infrastructure | Router, View, Container |
| src/Middleware/ | Request/response middleware | SecurityHeadersMiddleware |
| src/Controllers/ | HTTP handling | PortfolioController |
| src/Services/ | Business rules | ProjectService::getFeatured() |
| src/Repositories/ | Data access | FileProjectRepository |
| src/Models/ | Typed data | Project |
| src/ViewModels/ | Presentation | PageViewModel |
| src/Enums/ | Fixed values | Language, Page |
| src/Views/ | Escaped HTML templates | Portfolio/index.php |
| translations/ | EN/NL dictionaries | en.php, nl.php |

Example `/en/portfolio`: FastRoute dispatches PortfolioController::index with English; controller asks IProjectService; ProjectService asks IProjectRepository; FileProjectRepository reads data/projects.php and returns localized Project models; Portfolio/index renders within Layouts/main and returns Response. Constructor injection, interfaces and response objects permit tests with fake repositories. Future MySQL would require separate scope and a repository implementation, not a view rewrite. Existing typed enums reject invalid values.

## Current boundaries and target extension

Keep PHP >=8.2, Composer/FastRoute, strict types, existing src/ layout, dependency injection and file-backed services. Request → public/index.php → middleware → Router → Controller → Service → Repository → typed model/view model → View → Response. Controllers own HTTP mapping, views render escaped data, repositories own data, no business operations in templates. No new DB/CMS/SPA/auth service or dependencies. Preserve current composer.lock and Docker. Reuse offerings/project repositories and existing icons. Proposed service-detail content uses explicit Page mapping and dictionaries, not a new database. Keep Page::cases/indexability and metadata coherent with new routes. Product enum currently four entries and ProductLinks constructor validates configured external origins. Keep stable enum cases, identifiers and external base validation; introduce presentation-level local introductory URLs without replacing existing configured external URL contract wholesale. New support product landing links should be distinct from operational subdomain links. No dynamic destination from request. If this cannot be done in listed paths, stop and propose scope correction.

Protected: src/Framework (including FormToken, Storage, Router, Application, escaping helpers), middleware, ContactController and all mail/contact/rate-limit services and interfaces, RemoteSupportController, existing remote support view, data/projects.php and portfolio screenshots, privacy view, config/app.php (including mail, storage, product origins and content flags), dependencies, Docker, public entrypoint and .htaccess, .github/workflows, live data/storage, other repos. All unlisted paths excluded. Existing contact POST token, trap/timing, rate limits, mail delivery/fallback, private storage and security headers intact. Preserve configured-origin validation; no open redirects. Do not weaken tests or disclose personal data/secrets. New links use local fixed routes, existing validated product configuration; no new portal configuration. No changes to public download/signing infrastructure.

Production hazard: deploy.yml runs on push to main and workflow_dispatch, tests before SFTP deployment. Non-main pushes/PRs may trigger tests.yml. Therefore even branch push, PR creation or merge can trigger CI; require authorization for each class of action. No remote writes, workflows or deployment during planning. Retain storage exclusion and host-key validation; no operational changes. Prior code reads show config marks content approved; do not treat this as approval for redesign. Local-only candidate testing using isolated storage/file mail after separate approval.

Shared navigation: see [navigation contract](../shiva-nav/README.md). Page addition guide (future only): typed Page case, explicit FastRoute path, controller and DI registration, escaped view, both translations and metadata, corresponding tests. Preserve existing helpers and fixed mappings; no user-controlled template paths. [Sitemap](WEBSITE_SITEMAP.md) governs URL transitions.

## Exact future candidate scope

Sequence **P01 → P03 → P02 → P04 → P05**, then separate production gate. Keep original identifiers; P03 route foundation precedes P02 homepage links. M means existing baseline modify-only; C means absent baseline create-only; R means modify a file created by an earlier accepted package. No deletion or rename authorized. Repeated M paths below are intentional candidate permissions. All 46 unique paths belong to original inventory; package-level repeated permissions are proposed, pending authorization. Update relevant existing tests in same package, not only at final gate. Every authorized application implementation package must pass its applicable build and PHPUnit verification gates before acceptance. Documentation-only P01 requires static document, scope and integrity verification instead. No application execution, build, Docker operation, dependency installation or CI execution is permitted under P01. If scope insufficient, stop rather than disable assertions or create unlisted paths.

### P01 — documentation foundation (4 M, 4 C)
M:
- README.md
- SPECIFICATION.md
- docs/ARCHITECTURE.md
- shiva-nav/README.md
C:
- docs/BUSINESS_ARCHITECTURE.md
- docs/WEBSITE_SITEMAP.md
- AGENTS.md
- HANDOFF.md
Document approved business direction, stable routes, current vs target, shared-nav authority caveat, deployment gates and AI rules. Do not claim features exist before accepted source. No runtime changes. Exit: docs consistent, exact scope diff, no deploy/CI unless separately permitted.

### P03 — route and division foundation (12 M, 9 C)
M:
- config/routes.php
- config/services.php
- src/Enums/Page.php
- src/Controllers/ServiceController.php
- src/Views/Services/index.php
- src/ViewModels/ProductLinks.php
- translations/en.php
- translations/nl.php
- public/assets/css/site.css
- src/Views/Layouts/main.php
- tests/Feature/ApplicationTest.php
- tests/Feature/SeoTest.php
C:
- src/Controllers/DevelopmentController.php
- src/Controllers/SupportController.php
- src/Views/Development/index.php
- src/Views/Development/service.php
- src/Views/Support/index.php
- src/Views/Support/product.php
- src/Views/Support/repair-services.php
- tests/Feature/DivisionRoutesTest.php
- tests/Feature/LegacyRouteCompatibilityTest.php
Implement all nine new logical page routes (five development, four support), using fixed handlers and Page mapping; existing /services remains canonical 200 for transition. Existing /remote-support remains unchanged. Preserve old homepage and shared menu contract. ProductLinks may add local introduction helpers without changing existing cards/URLs or unsafe-origin rejection. New pages can use existing styles or additive CSS; cache-bust if needed. Tests cover exact transitional sitemap, direct route response, language, invalid route, existing links and legacy behavior; retain existing product tests unchanged unless scope is separately reconciled. Exit: all new targets work locally, old behavior and suite remain green. Note: nine new logical routes plus seven existing = sixteen per language during transition.

### P02 — homepage and design (7 M, 3 C, 1 R)
M:
- src/Controllers/HomeController.php
- src/Views/Home/index.php
- src/Views/Layouts/main.php
- public/assets/css/site.css
- translations/en.php
- translations/nl.php
- tests/Feature/ProductsTest.php
C:
- src/Views/Partials/division-card.php
- src/Views/Partials/product-card.php
- tests/Feature/HomepageArchitectureTest.php
R:
- tests/Feature/DivisionRoutesTest.php
Build equal primary divisions linking to existing P03 routes; secondary three support cards, portfolio, truthful availability. Retain existing Product enum and safe external link contract. Replace obsolete four-card layout assertions with exact new grouping, order, status and link tests; retain unsafe URL checks and shared menu tests. Use fixed local landing links; no unapproved portal action. Exit: desktop/mobile and EN/NL visual evidence, both CTAs valid, all old safety tests green.

### P04 — shared navigation, product links and compatibility transition (18 M, 2 R)
M:
- shiva-nav/Navigation.php
- shiva-nav/menu.php
- shiva-nav/header.php
- shiva-nav/README.md
- public/assets/shiva-nav/shiva-nav.css
- src/Views/Layouts/main.php
- src/ViewModels/PageViewModelFactory.php
- src/Enums/Product.php
- src/ViewModels/ProductLinks.php
- config/services.php
- config/routes.php
- src/Enums/Page.php
- src/Controllers/ServiceController.php
- tests/Feature/ApplicationTest.php
- tests/Feature/ProductsTest.php
- tests/Feature/SeoTest.php
- tests/Feature/RemoteSupportTest.php
- tests/Unit/SharedNavigationTest.php
R:
- tests/Feature/DivisionRoutesTest.php
- tests/Feature/LegacyRouteCompatibilityTest.php
 Extend shared menu and active keys, preserve current site keys and safety validation, update version/cache. Keep remote page/footer link. Under approved option A (SC-MD03), retain /services as a canonical 200 page and keep Page::Services indexable; the same-language 301, its sitemap exclusion and the deploy.yml `/en/services` smoke-check change are deferred to a separately authorized option B outside this scope. Update existing tests for menu labels and exact sitemap; do not erase remote portal/unsafe origin checks. Keep old shared copies functional through legacy route. If transition not ready, retain 200 and record pending, no forced redirect. **Approved variant A (SC-MD03): retain 200; the 301 and deploy.yml smoke-check change are deferred and outside this scope.** Cross-repo sync separate. Exit: menu parity and active state, all links and language switch correct, legacy compatibility, suite green.

### P05 — final SEO and regression acceptance (7 M, 1 C, 3 R)
M:
- tests/Feature/ApplicationTest.php
- tests/Feature/ProductsTest.php
- tests/Feature/SeoTest.php
- tests/Feature/RemoteSupportTest.php
- tests/Unit/SharedNavigationTest.php
- tests/Unit/TranslationTest.php
- src/Controllers/SeoController.php
C:
- docs/WEBSITE_ACCEPTANCE.md
R:
- tests/Feature/HomepageArchitectureTest.php
- tests/Feature/DivisionRoutesTest.php
- tests/Feature/LegacyRouteCompatibilityTest.php
Verify exact canonical URL set and content approval, complete regression, accessibility, mobile/desktop, performance and protected-path diff. Existing SEO controller may already derive correct sitemap from Page enum; avoid unnecessary edits. Record evidence and remaining unverified items. Exit: independently accepted candidate, not production authority.

Cumulative: 29 baseline M + 17 baseline C = 46 unique paths, no additions. P03 and P04 repeated permissions deliberately moved earlier for tests and integration; P05 modifies previously created tests. P03/P04 counts and dependency checks must be revalidated before execution. All package-level scope is a candidate maximum, not requirement to touch every path.

## Verification and release

| ID | Requirement |
|---|---|
| SC-AC01 | Exactly two primary homepage divisions |
| SC-AC02 | Equal visual prominence desktop/mobile |
| SC-AC03 | Software services and portfolio accessible |
| SC-AC04 | Assist, Recovery and Remote under support |
| SC-AC05 | No public infrastructure page/item |
| SC-AC06 | Every approved EN route correct |
| SC-AC07 | Every approved NL route correct |
| SC-AC08 | Language switching retains logical page |
| SC-AC09 | Existing public routes compatible |
| SC-AC10 | Existing help shortcuts preserved |
| SC-AC11 | Remote portal link gated by existing config and release approval |
| SC-AC12 | Unsafe product URLs rejected |
| SC-AC13 | Shared menu active group correct |
| SC-AC14 | Mobile menu link parity |
| SC-AC15 | No mandatory JS dependency |
| SC-AC16 | EN/NL keys and value shapes match |
| SC-AC17 | Canonical and reciprocal hreflang correct |
| SC-AC18 | Exact indexable canonical sitemap, no redirects/placeholders |
| SC-AC19 | Invalid routes correct 404 |
| SC-AC20 | Unsupported methods preserve semantics |
| SC-AC21 | Contact validation and delivery regressions pass |
| SC-AC22 | Security headers intact |
| SC-AC23 | Private storage outside web root |
| SC-AC24 | Truthful product status |
| SC-AC25 | No fabricated claims or testimonials |
| SC-AC26 | Responsive layout and zoom usable |
| SC-AC27 | Keyboard/focus/accessibility |
| SC-AC28 | All regression tests pass |
| SC-AC29 | Bounded path scope respected |
| SC-AC30 | No unauthorized deploy or history changes |

After explicit test authorization, isolated local Docker or existing PHP 8.2 environment: composer install from lock; `docker compose up --build -d`; `docker compose exec web vendor/bin/phpunit` (existing documented flow). No real mail: isolated storage and file transport, no production secrets. Run syntax lint on changed PHP; all existing PHPUnit tests plus new tests. Test each locale and all canonical routes, root, legacy /services transition, trailing slash, invalid language/path, wrong method, contact token/rate-limit behavior, remote fallback/portal/unsafe URL cases, product URL validation, menu parity/current group, metadata and sitemap exact set. Retain old tests except explicitly replaced obsolete expectations; report replacements. Local browser screenshots of homepage and all key pages at mobile/tablet/desktop sizes in both languages; check keyboard, focus, contrast, zoom and no overflow. Inspect asset paths and CSS cache version. Performance baseline before quantitative claims; no remote tracking/extra JS. No live portal connections, mail, DNS, hosting or CI without separate authorization.

Evidence per package: starting/refreshed HEAD/tree, exact changed paths and mode, protected diff, rationale for repeated files, lint/build and tests counts/pass/fail/skip, sanitized screenshots, route matrix, translation parity, regressions, known limitations and independent disposition. No acceptance from build alone. For documentation package, evidence is document consistency and diff, not runtime pass. Never claim latest CI verified unless exact run/commit verified.

Owner approves business direction, scope, security changes, production. ChatGPT independent architecture/review; Claude/Codex implementation only under explicit authorization. Routine styling within approved package delegated; scope expansion, new dependencies, safety changes, conflicts, merges/deploys require owner. Proposed branch `feature/sc-web-two-divisions` from fresh develop; no creation now. No force push, history rewrite, branch deletion, remote writes, CI or contacting agents under this planning assignment. Existing main/develop history retained. Each product separate workstream and version. See [agent instructions](../AGENTS.md) and [continuation checkpoint](../HANDOFF.md) for the current authorized assignment, execution boundaries and project checkpoints.

P01 exception: documentation-only static evidence, no runtime commands, CI or application execution. Subsequent packages require fresh authorization. See [specification](../SPECIFICATION.md), [business architecture](BUSINESS_ARCHITECTURE.md), [handoff](../HANDOFF.md).
