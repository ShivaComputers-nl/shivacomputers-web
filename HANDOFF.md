# Shiva Computers — Continuation checkpoint

**Checkpoint SC-WEB-R02 (25 November 2026).** SC-MASTER-001 P01–P05 is implemented and integrated in `develop` (PR #12, merge commit `4745e8041def8bb130c642e94b6704d5d1472fd1`). The redesign is **not deployed**: `main` (`9e18f6ac194a3e43205b2c243c821d5430cdfba0`) and production are unchanged. Text marked *baseline* describes `develop` `f5eff3cf5fe1d7e5fe50960b99ec5b74b43033e8` before the redesign and is kept as history; the implemented state and its evidence are in [`docs/WEBSITE_ACCEPTANCE.md`](docs/WEBSITE_ACCEPTANCE.md).

## Current checkpoint (SC-WEB-R02)

| Item | State |
|---|---|
| `develop` | `4745e8041def8bb130c642e94b6704d5d1472fd1` — SC-MASTER-001 merged via PR #12 |
| `main` / production | `9e18f6ac194a3e43205b2c243c821d5430cdfba0` — unchanged, redesign not deployed |
| Packages | P01, P03, P02, P04(A), P05 complete; each independently accepted (findings recorded in the acceptance record) |
| Implementation scope | 24 modified + 17 created files; all 87 program-protected baseline files verified unchanged |
| Local tests | 208 tests / 1,525 assertions OK (original implementation record) |
| GitHub CI (workflow "Tests") | 45680923313 (push, feature branch, `03c0718`), 45681007312 (pull request, `03c0718`), 45681277051 (push, develop, `4745e80`) — all success |
| Independent review SC-MASTER-001-IR02 | 22 accepted, 8 partial, 0 failed (owner-supplied disposition; the review artifact is not stored in this repository) |
| Release preparation | SC-WEB-R02: Shiva Remote status and `products.remote.tag` set to "On request" / "Op aanvraag"; contact metadata generalized; documentation reconciled |

**Do not repeat completed packages.** Next actions are owner and production gates only:

1. Owner approval of public EN/NL copy and product statuses (including implementer-added labels listed in the acceptance record).
2. Manual Safari/Firefox, phone and screen-reader (NVDA/VoiceOver) checks.
3. STRATO verification: PHP ≥ 8.2, document root `public/`, HTTPS, storage outside web root, backup and rollback rehearsal, one owner-approved contact-form test message.
4. Separate owner grant to merge `develop` into `main` (this triggers the STRATO deployment workflow); post-deploy smoke check.
5. Refresh the `shiva-nav/README.md` opening banner (still the P01 wording) under a later scoped documentation grant; it was outside the R02 path list.
6. Later and separately: option B (`/services` 301 plus `deploy.yml` smoke-check change); shared navigation 2.0.0 for the rescue and assist sites; recovery domain migration.

Historical P01 sections below are kept unchanged as provenance.

## Provenance and authority

Reference: `develop` `f5eff3cf5fe1d7e5fe50960b99ec5b74b43033e8`; `main` `9e18f6ac194a3e43205b2c243c821d5430cdfba0`; both tree `03a9286ccf9ca4937adfec2bd7005f9768fe33c6` (116 tracked blobs), refreshed 25 November 2026. Repository: https://github.com/ShivaComputers-nl/shivacomputers-web (ID 1444462366).

SC-D01–08 / SC-R01–04 approved. On 25 November 2026 the owner approved SC-MASTER-001 revision 1.0, SC-MD01–08 and compatibility option A, and authorized the bounded local program P01-R01/P01 → P03 → P02 → P04(A) → P05 in an isolated detached workspace. No GitHub write, branch, commit, CI or deployment is authorized; no commit or candidate Git tree exists. Parent future scope 29 baseline M + 17 C = 46 unique paths. The exact parent package inventory is in [architecture](docs/ARCHITECTURE.md).

## Read first

[README](README.md), [specification](SPECIFICATION.md), [business architecture](docs/BUSINESS_ARCHITECTURE.md), [sitemap](docs/WEBSITE_SITEMAP.md), [technical architecture](docs/ARCHITECTURE.md), [shared navigation](shiva-nav/README.md), [agent instructions](AGENTS.md). Current source prevails for current behavior. Refresh refs and inspect local work before future execution.

## Current source versus target (history, superseded by Current checkpoint)

*Superseded: P01–P05 are complete and merged; see Current checkpoint above.*


Existing PHP 8.2+/Composer/FastRoute layered MVC, seven pages per language, four cards, grouped menu, file repositories, contact validation/mail fallback, private storage, SEO, tests and CI/deployment workflow. New target: equal development and support divisions, local product introductions, Recovery family/Rescue compatibility, shared nav retention, independent product releases and no public infrastructure. Stable English slugs under EN/NL. Source does not yet implement target. No runtime tests, current CI or live checks performed.

## Package sequence and next assignment (history, superseded by Current checkpoint)

*Superseded: do not execute the P03 assignment below; it was completed in PR #12.*


P01 documentation → independent review → P03 route foundation → P02 homepage → P04(A) shared nav (keeps /services 200) → P05 final acceptance → separate publication and production approval. Each package proceeds only after independent acceptance of the previous one. Implementation status is recorded in `docs/WEBSITE_ACCEPTANCE.md` (P05). P03 assignment: refresh baseline, verify exact paths, Page/DI and fixed routes, preserve seven current pages and /services 200, add nine logical routes in both languages, exact transitional sitemap and tests, preserve old cards/menu and security. Report necessary scope changes before editing. No automatic execution.

## Outstanding limitations and stop conditions

No live domain, STRATO hosting, current CI, legal compliance or other product repository audit established. Shared navigation authority across existing copies unresolved. Product availability, legal copy, licensing, release, actual visual evidence pending. The original 46-path inventory is mechanically verified at the reference tree; revised package repetitions are planning proposals and require execution-baseline verification. No application, GitHub, hosting or DNS changes made. This local checkpoint is not a published GitHub record.

Prior Assist issue publication returned 403; website write permissions not tested here. Full organization inventory and shared navigation authority unresolved; no renames or DNS migration. Product status, licensing, privacy, accessibility and deployment verification pending. Main push or workflow_dispatch can deploy STRATO; PR/non-main pushes can trigger CI. Stop on source drift, local conflicts, scope/security changes or unsafe operations; separate approval for publication and production. No fabricated commit, CI run or issue number. P01 acceptance remains pending independent review; attached evidence is candidate self-check, not independent acceptance.
