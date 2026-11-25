# Shiva Computers — Website Specification

SC-ARCH-001 revision 1.1 / SC-WEB-P01 candidate. **Checkpoint SC-WEB-R02 (25 November 2026).** SC-MASTER-001 P01–P05 is implemented and integrated in `develop` (PR #12, merge commit `4745e8041def8bb130c642e94b6704d5d1472fd1`). The redesign is **not deployed**: `main` (`9e18f6ac194a3e43205b2c243c821d5430cdfba0`) and production are unchanged. Text marked *baseline* describes `develop` `f5eff3cf5fe1d7e5fe50960b99ec5b74b43033e8` before the redesign and is kept as history; the implemented state and its evidence are in [`docs/WEBSITE_ACCEPTANCE.md`](docs/WEBSITE_ACCEPTANCE.md).

## Authority and business decisions

| ID | Decision |
|---|---|
| SC-D01 | Two equal business divisions, equal public prominence, not necessarily equal revenue or staffing. |
| SC-D02 | Independent product repositories, no product branches as substitute for separate codebases. |
| SC-D03 | Private internal infrastructure; no public page, marketing card or sitemap entry. |
| SC-D04 | Shared corporate identity, blue for development and green for support. |
| SC-D05 | English and Dutch public website. |
| SC-D06 | Product-specific subdomains when separately release-approved. |
| SC-D07 | Independent deployment, testing, versions and release lifecycle. |
| SC-D08 | Retain native PHP MVC website architecture. |
| SC-R01 | Stable English route slugs beneath /en and /nl; translated visible content. |
| SC-R02 | Shiva Recovery is a product family containing Shiva Rescue OS; preserve existing product identity and URLs. |
| SC-R03 | Retain and extend shared shiva-nav. |
| SC-R04 | Separate owner approval for production integration and deployment. |

All are approved. On 25 November 2026 the owner also approved SC-MASTER-001 revision 1.0 and SC-MD01–08 for bounded local execution only (no remote writes, CI, deployment, hosting or DNS). Legal content, product status and release still require separate review.

See [business architecture](docs/BUSINESS_ARCHITECTURE.md) for product and repository hierarchy. The earlier primary/secondary positioning is superseded by equal Software Design & Development and Computer Repair & Support. Audiences: businesses, entrepreneurs, prospective collaborators, individuals and businesses needing computer assistance. Journeys: discover software services → portfolio → contact; discover support → product information → contact or approved operational platform. No public infrastructure advertising.

## Current implementation versus approved target

Implemented in `develop` (SC-MASTER-001, PR #12):

- Two equal business divisions on the home page: Software Design & Development (blue) and Computer Repair & Support (green), followed by selected projects and the three support products.
- Nine new logical pages in both languages: `/software-development` (+ `/websites`, `/web-applications`, `/custom-software`, `/automation`) and `/computer-support` (+ `/assist`, `/recovery`, `/repair-services`).
- 16 logical pages per language, 32 canonical sitemap URLs under compatibility option A: `/services` stays HTTP 200 and indexed; its 301 to `/software-development` is deferred (option B, separate grant with a `deploy.yml` smoke-check change).
- Shared navigation 2.0.0 (`shiva-nav/`): Home | Software Development | Computer Support | About | Contact | language; desktop menu from 1100px. The rescue and assist sites keep their 1.0.0 copies, which still resolve.
- Product statuses: Shiva Assist and Shiva Recovery (incl. Rescue OS) *In development*; Shiva Remote and repair services *On request*. Product origins are linked only from the local introduction pages and remain validated by `ProductLinks`.
- Unchanged: contact form and its protections, remote support page and `/help` `/hulp` shortcuts, security headers, configuration, framework, dependencies and deployment workflows.

Acceptance status per SC-AC and HOME criterion: [acceptance record](docs/WEBSITE_ACCEPTANCE.md). Remaining before production: owner content approval, manual browser/screen-reader checks, hosting/backup verification and a separate production grant.

Baseline (before SC-MASTER-001, history): PHP 8.2+, Composer/FastRoute, file-based MVC, seven existing logical pages, four homepage cards and separate grouped shared menu. Contact handler with spam protections, portfolio, SEO metadata/sitemap/robots, tests and CI/deployment workflows exist in source. Production delivery, legal content, current CI and live deployment remain unverified. CMS, database, tracking and new shared authentication are excluded. [Sitemap](docs/WEBSITE_SITEMAP.md) defines current, transitional and target route contracts. [Technical architecture](docs/ARCHITECTURE.md) defines exact future candidate scope.

## UX, content, visual design and localization

Main navigation: Home | Software Development (overview, websites, web apps, custom software, automation, portfolio) | Computer Support (overview, Assist, Recovery, Remote, repair services) | About | Contact | language switch. Both groups equal visual prominence. Footer retains privacy, contact/company details and remote help. No infrastructure page. Shared navigation keeps native details/summary, mobile parity, active group, current page, keyboard and accessible names; no mandatory JS. Parent overview is a real dropdown child link. Use fixed route-to-key mapping and preserve old shared site identifiers main/rescue/assist; no breaking constructor changes to other sites. Version component and cache-bust changed CSS. Main repository proposed canonical shared-navigation source, pending confirmation against earlier documented external source ShivaComputers_26/shiva-nav; do not overwrite other copies or claim migration complete. Coordinate separate product-specific updates later. Old copies must continue resolving existing URLs.

Homepage composition: shared header; neutral corporate hero; two equal-size primary cards, blue and green; desktop two columns, narrow/mobile stacked with equal hierarchy and no hidden division; one main CTA each; selected software portfolio and three support products as secondary sections of comparable prominence; shared trust/contact section and footer. Use real portfolio content and screenshots already in source; verify ownership/claims, no fabricated customers, testimonials or results. Use local SVG/icons/imagery, no new remote dependencies. No download/portal claim until product gate; Assist and Rescue OS are labelled in development; Remote is labelled on request (owner decision, SC-WEB-R02; baseline said by appointment). Avoid pricing, guarantee or recovery-success claims without approval. Keep direct-owner communication accurate; no implied staffing. Each service detail uses explicit localized copy and a contact CTA. Recovery page introduces family and existing Rescue OS information link; Assist page introduces diagnostics and verification without suggesting guaranteed repair; Remote page remains existing consent/information flow.

Suggested copy EN: “Building software. Solving computer problems.” / “Professional software development and reliable computer support — under one roof.” Development: “Turn your ideas into reliable, secure and user-friendly software.” Support: “Technical support for your computer, from diagnosis and maintenance to recovery and remote assistance.” CTA: Explore Software Development / Explore Computer Support / Contact us.

Suggested copy NL (formal u): “Wij ontwikkelen software. Wij lossen computerproblemen op.” / “Professionele softwareontwikkeling en betrouwbare computerondersteuning — onder één dak.” Development: “Van uw ideeën naar betrouwbare, veilige en gebruiksvriendelijke software.” Support: “Technische ondersteuning voor uw computer, van diagnose en onderhoud tot herstel en hulp op afstand.” CTA: Ontdek onze softwarediensten / Ontdek onze computerondersteuning / Neem contact op. Draft copy, owner content review required.

Use existing translations/en.php and nl.php, PageTranslator and Translator, shared menu bilingual labels. Match key sets and value shapes; all user-facing strings, metadata, status and accessible labels localized. Preserve language switch to same logical page and existing x-default. No silent fallback masking missing keys. Product names consistent across all surfaces.

Design tokens (reference): corporate navy #14243A, development blue #1D4ED8, support green #167D49, canvas #F4F6F9, white #FFFFFF; lighter division backgrounds #EFF5FF/#EDF7F0. Existing system sans typography; consistent spacing, restrained cards, no layout shifts from assets, semantic HTML landmarks, one H1 per page, logical headings, visible focus, accessible contrast target 4.5:1 for normal text; test 320/375/768/1024/1440 CSS px, zoom 200%, EN/NL text wrapping, reduced motion. These are proposed acceptance targets, not measured compliance.

- HOME-01: Shared corporate brand
- HOME-02: Equal desktop entry cards
- HOME-03: Equal mobile importance
- HOME-04: One primary CTA per division
- HOME-05: Blue development accent
- HOME-06: Green support accent
- HOME-07: Preserve portfolio
- HOME-08: Group support products
- HOME-09: Honest availability
- HOME-10: Shared contact information
- HOME-11: No infrastructure advertising
- HOME-12: English/Dutch language parity

## Technical and security requirements

Keep PHP >=8.2, Composer/FastRoute, strict types, existing src/ layout, dependency injection and file-backed services. Request → public/index.php → middleware → Router → Controller → Service → Repository → typed model/view model → View → Response. Controllers own HTTP mapping, views render escaped data, repositories own data, no business operations in templates. No new DB/CMS/SPA/auth service or dependencies. Preserve current composer.lock and Docker. Reuse offerings/project repositories and existing icons. Proposed service-detail content uses explicit Page mapping and dictionaries, not a new database. Keep Page::cases/indexability and metadata coherent with new routes. Product enum currently four entries and ProductLinks constructor validates configured external origins. Keep stable enum cases, identifiers and external base validation; introduce presentation-level local introductory URLs without replacing existing configured external URL contract wholesale. New support product landing links should be distinct from operational subdomain links. No dynamic destination from request. If this cannot be done in listed paths, stop and propose scope correction.

Protected: src/Framework (including FormToken, Storage, Router, Application, escaping helpers), middleware, ContactController and all mail/contact/rate-limit services and interfaces, RemoteSupportController, existing remote support view, data/projects.php and portfolio screenshots, privacy view, config/app.php (including mail, storage, product origins and content flags), dependencies, Docker, public entrypoint and .htaccess, .github/workflows, live data/storage, other repos. All unlisted paths excluded. Existing contact POST token, trap/timing, rate limits, mail delivery/fallback, private storage and security headers intact. Preserve configured-origin validation; no open redirects. Do not weaken tests or disclose personal data/secrets. New links use local fixed routes, existing validated product configuration; no new portal configuration. No changes to public download/signing infrastructure.

Production hazard: deploy.yml runs on push to main and workflow_dispatch, tests before SFTP deployment. Non-main pushes/PRs may trigger tests.yml. Therefore even branch push, PR creation or merge can trigger CI; require authorization for each class of action. No remote writes, workflows or deployment during planning. Retain storage exclusion and host-key validation; no operational changes. Prior code reads show config marks content approved; do not treat this as approval for redesign. Local-only candidate testing using isolated storage/file mail after separate approval.

## Future acceptance requirements (not executed)

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

## Sequence and release gates

P01 → P03 → P02 → P04(A) → P05 → separate production approval. One batch local grant (SC-MD04/MD07) covers the sequence; each package needs independent reviewer acceptance before dependent work. P01 only documentation. Compatibility option A (SC-MD03, approved): `/services` stays a canonical 200 page in both languages for this redesign; the 301 to `/software-development` is deferred to a separately authorized option B that must also amend the `/en/services` smoke check in `.github/workflows/deploy.yml` (outside the 46-path scope). Exact candidate scopes in [technical architecture](docs/ARCHITECTURE.md).

| Risk | Severity | Mitigation |
|---|---|---|
| Accidental production deployment | Critical | No main push/merge or workflow_dispatch without separate approval |
| Broken existing URLs | High | Fixed language-aware legacy routes and tests |
| Cross-site menu inconsistency | High | Version shared component; preserve old keys and coordinate separately |
| Product naming inconsistency | Medium | Recovery family, preserve Rescue OS |
| Unapproved availability claims | High | Explicit status and content review |
| Contact form regression | High | Protected implementation and full tests |
| Remote security regression | Critical | No session privilege, existing portal gating and consent |
| Translation gaps | Medium | Key/value parity and locale route tests |
| SEO regression | High | Exact canonical set and hreflang |
| Infrastructure exposure | High | Private repo and no public route |
| Concurrent changes | High | Fresh HEAD, clean worktree, stop on conflict |
| Scope expansion | Medium | Exact package paths and separate scope approval |

Release gate: all packages independently reviewed, content and legal/privacy review, product availability verified, main integration explicitly authorized as production action, hosting backup/rollback and private storage protections independently confirmed, exact deployment workflow and credentials managed outside source, HTTPS and route verification in separately approved environment. Do not trigger live deployment or DNS changes now. No new product release, signing or remote portal activation through website acceptance. If current shared-nav source authority cannot be established, document and defer cross-repo rollout.

## Outstanding decisions

No live domain, STRATO hosting, current CI, legal compliance or other product repository audit established. Shared navigation authority across existing copies unresolved. Product availability, legal copy, licensing, release, actual visual evidence pending. The original 46-path inventory is mechanically verified at the reference tree; revised package repetitions are planning proposals and require execution-baseline verification. No application, GitHub, hosting or DNS changes made. This local Markdown specification is not a published GitHub record.

See [handoff](HANDOFF.md) and [agent rules](AGENTS.md).
