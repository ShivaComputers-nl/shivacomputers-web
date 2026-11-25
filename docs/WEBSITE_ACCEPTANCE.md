# Shiva Computers website — SC-MASTER-001 acceptance record

## 0. Integration and release-preparation status (SC-WEB-R02, 25 November 2026)

- Published as PR #12 (`feature/sc-web-two-divisions`, commit `03c0718`) and merged into `develop` as `4745e8041def8bb130c642e94b6704d5d1472fd1`. `main` (`9e18f6a`) and production are unchanged.
- GitHub CI, workflow "Tests", all `success`: run 45680923313 (push, feature branch, `03c0718`), run 45681007312 (pull request, `03c0718`), run 45681277051 (push, develop, `4745e80`).
- Implementation scope: 24 modified and 17 created files (41 of 46 authorized paths); all 87 program-protected baseline files independently verified unchanged.
- Independent review SC-MASTER-001-IR02 (owner-supplied disposition): 22 accepted, 8 partial, 0 failed. The IR02 artifact itself is not stored in this repository; the per-package reviewer reports from the implementation session are summarized in §1.
- SC-WEB-R02 content corrections: Shiva Remote status (`support.remote.status`) and the legacy home-card tag (`products.remote.tag`) changed to "On request" / "Op aanvraag" (owner decision, closes §6 item 1); contact page meta description generalized to software projects and computer problems. Shiva Assist and Shiva Recovery remain "In development"; repair services remain "On request".

Open documentation item: `shiva-nav/README.md` still opens with the P01 banner and calls 2.0.0 a local candidate; refresh under a later scoped grant (outside the R02 path list).

The sections below are the original P05 record (local candidate, before publication) and are kept as history.


**Local candidate, not a production release.** Recorded 25 November 2026 in an isolated, detached local workspace at `develop` `f5eff3cf5fe1d7e5fe50960b99ec5b74b43033e8` (tree `03a9286ccf9ca4937adfec2bd7005f9768fe33c6`). No commit, branch, push, pull request, CI run, deployment, hosting or DNS change was made. `git clone` created the default local `main` ref at `9e18f6a` (= origin/main, unchanged, never checked out or modified); the work itself created no branch. Publication and production need separate owner grants (APPROVAL-REGISTER G01–G06).

## 1. Package status

| Package | Result | Independent review |
|---|---|---|
| P01-R01 + P01 documentation (8 paths) | Applied; static checks only | Accepted with non-blocking findings; N1–N4 corrected, N5 open (README go-live checklist condensed) |
| P03 routes and division pages | Applied | Accepted with non-blocking findings |
| P02 homepage | Applied | Corrections required (equal CTA size) → corrected → accepted with non-blocking findings |
| P04(A) shared navigation 2.0.0 | Applied; `/services` stays 200 | Accepted with non-blocking findings |
| P05 regression and acceptance | This record | Final independent review: see evidence package |

Bounded correction disclosed in P04: `public/assets/css/site.css` (a P02/P03 path) got heading wrapping rules (`overflow-wrap`, `hyphens`) because long Dutch headings overflowed at 320px; cache version `site.css?v=8`.

Compatibility option A (SC-MD03): `/en/services` and `/nl/services` stay canonical 200 pages and remain in the sitemap. The 301 to `/software-development` and the matching `.github/workflows/deploy.yml` smoke-check change are deferred (option B, separate grant).

## 2. Verification performed

- Dependencies: `composer install` from the committed `composer.lock` only (lock unchanged). PHP 8.3.6 CLI (project requires ≥8.2); dom, mbstring, simplexml, xmlwriter present.
- Test isolation: `STORAGE_PATH` outside the repository and `MAIL_TRANSPORT=file`. No real mail, remote portal, live site or CI was contacted.
- PHPUnit: baseline 105 tests / 381 assertions OK; final candidate 208 tests / 1525 assertions OK; 0 skipped, 0 failures.
- `php -l` on every changed PHP file; `git diff --check` clean.
- Scope: only the 46 authorized paths changed (29 modify-only, 17 create-only maximum); all 87 program-protected baseline files byte-identical; no deletions, renames or mode changes.
- Browser (local Chrome via Playwright, PHP built-in server): 16 pages EN/NL at 320, 375, 768, 899, 900, 1024, 1099, 1100 and 1440 px plus a 200% zoom equivalent; no horizontal overflow, exactly one H1, no image without `alt`, no executable scripts (the JSON-LD data block is unchanged from baseline), automated text contrast ≥4.5:1 (≥3:1 large) on all checked elements, visible 3px focus outline in keyboard order.
- Performance (lab, same host; not a production claim): server time per page median 0.6–1.1 ms, p95 ≤1.3 ms (20 warm samples), no regression against baseline. Mobile LCP median 60–84 ms unthrottled, CLS 0. Trade-off: the home page and the development overview now show the selected projects higher, so one lazy-loaded portfolio image falls inside the first view (≈142 kB vs 21 kB resources on first load, 6 vs 5 requests). The images keep their dimensions and lazy loading; existing images are protected assets.

## 3. Route matrix (per language, `/en` and `/nl`)

| Suffix | Status | Indexed |
|---|---|---|
| (home), /services, /portfolio, /about, /contact, /privacy, /remote-support | 200 (unchanged) | yes |
| /software-development, /software-development/websites, /web-applications, /custom-software, /automation | 200 (new) | yes |
| /computer-support, /computer-support/assist, /recovery, /repair-services | 200 (new) | yes |

32 sitemap URLs (exact set tested). Unchanged: `/` 302 → `/en`; `/help` and `/hulp` 302 with `no-store`; trailing slash 301 to the clean path; unknown language or path 404 (noindex, no canonical); POST on a page 405 with `Allow: GET, HEAD`; contact GET+POST.

## 4. SC-AC01–30

| ID | Status | Evidence |
|---|---|---|
| SC-AC01 | Pass | HomepageArchitectureTest: exactly two division entries |
| SC-AC02 | Pass (note) | Equal cards and CTAs at all widths; NL 375px stacked support CTA wraps to two lines (known limitation) |
| SC-AC03 | Pass | Development overview, four service pages, portfolio linked |
| SC-AC04 | Pass | Support overview and home group Assist, Recovery, Remote |
| SC-AC05 | Pass | No infrastructure route, menu item or text (tested) |
| SC-AC06 / 07 | Pass | 16 routes per language, localized H1/title/description |
| SC-AC08 | Pass | Language switch keeps the page on all routes |
| SC-AC09 | Pass | Existing routes and `/services` 200 (option A) |
| SC-AC10 | Pass | `/help`, `/hulp` unchanged |
| SC-AC11 | Pass | Portal link only from validated config; null fallback unchanged |
| SC-AC12 | Pass | Unsafe product and portal URLs rejected; unsafe config fails closed |
| SC-AC13 | Pass | Active group and current child; legacy keys mark group only |
| SC-AC14 | Pass | Mobile menu has every desktop link plus language |
| SC-AC15 | Pass | No JavaScript; native details/summary |
| SC-AC16 | Pass | EN/NL keys and value shapes match (tested) |
| SC-AC17 | Pass | Canonical and reciprocal hreflang incl. x-default on every sitemap page |
| SC-AC18 | Pass | Exact transitional sitemap; no redirects or placeholders |
| SC-AC19 / 20 | Pass | 404 and 405 behaviour tested |
| SC-AC21 | Pass | Contact tests unchanged and passing (synthetic data, file transport) |
| SC-AC22 | Pass | CSP and security headers unchanged (middleware protected) |
| SC-AC23 | Pass (source) | Storage path outside `public/` unchanged; production not verified |
| SC-AC24 | Pass (source, R02) | Status labels shown as text; Remote status set to "On request" by owner decision (R02); public copy approval still open (§6) |
| SC-AC25 | Pass (source) | No testimonials, guarantees, prices or downloads; owner content review pending |
| SC-AC26 / 27 | Pass (automated lab) | Reflow, zoom, contrast, focus; manual screen-reader review not performed |
| SC-AC28 | Pass | Full suite green |
| SC-AC29 | Pass | Scope check |
| SC-AC30 | Pass | No remote or deploy operations |

HOME-01–12: all met in the candidate (shared brand, equal desktop and mobile entries, one CTA each, blue development, green support, portfolio kept, support products grouped, honest status, shared contact, no infrastructure, language parity), with HOME-09 subject to the owner content items in §6.

## 5. Not verified

Production hosting, STRATO document root, HTTPS, mail delivery, current CI, Safari and Firefox rendering, manual screen-reader output (NVDA/VoiceOver), real-device testing, legal/privacy review, image rights. Shared navigation 2.0.0 exists only in this repository; the rescue and assist sites keep their 1.0.0 copies (still compatible).

## 6. Owner decisions before publication

1. ~~Confirm the Shiva Remote status~~ — resolved in SC-WEB-R02: "On request" / "Op aanvraag".
2. Approve implementer-added copy: repair status "On request"; `cta.title`; `meta.home.title`; menu child descriptions.
3. Approve all new EN/NL copy (CONTENT-UX draft) and product statuses for public use; `content_approved=true` in config does not cover the redesign.
4. Decide on option B (301 plus deploy smoke-check change) later.
5. ~~Grant publication~~ — done (PR #12 merged into `develop`). Production deployment (merge to `main`) still needs a separate grant.

Notes: P01-AUTHORIZATION says "108 other tracked paths"; the correct figure is 112 (116 − 4). MASTER-PLAN §5 calls 1100px the existing navigation threshold; the baseline used 900px, and the candidate now switches at 1100px as planned.
