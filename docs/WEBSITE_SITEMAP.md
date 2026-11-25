# Shiva Computers — Website Sitemap and Route Contract

**Checkpoint SC-WEB-R02 (25 November 2026).** SC-MASTER-001 P01–P05 is implemented and integrated in `develop` (PR #12, merge commit `4745e8041def8bb130c642e94b6704d5d1472fd1`). The redesign is **not deployed**: `main` (`9e18f6ac194a3e43205b2c243c821d5430cdfba0`) and production are unchanged. Text marked *baseline* describes `develop` `f5eff3cf5fe1d7e5fe50960b99ec5b74b43033e8` before the redesign and is kept as history; the implemented state and its evidence are in [`docs/WEBSITE_ACCEPTANCE.md`](WEBSITE_ACCEPTANCE.md).

## Implemented routes (develop `4745e80`, compatibility option A)

| Suffix under `/en` and `/nl` | Status |
|---|---|
| (home), /services, /portfolio, /about, /contact (GET+POST), /privacy, /remote-support | 200, unchanged contracts |
| /software-development, /software-development/websites, /software-development/web-applications, /software-development/custom-software, /software-development/automation | 200, new |
| /computer-support, /computer-support/assist, /computer-support/recovery, /computer-support/repair-services | 200, new |

16 logical pages per language, 32 canonical URLs in `/sitemap.xml` (exact set tested in `tests/Feature/SeoTest.php`). `/`, `/help`, `/hulp`, robots, sitemap, trailing-slash 301, 404 and 405 behaviour unchanged. The final 15-per-language (30 URL) target with `/services` → 301 is deferred to option B.

## Baseline source routes (before SC-MASTER-001, history)

| Route | Contract |
|---|---|
| `/` | 302 to `/en` |
| `/en`, `/nl` | Home |
| `/<lang>/services` | Existing services page |
| `/<lang>/portfolio` | Portfolio |
| `/<lang>/about` | About |
| `/<lang>/contact` | GET and POST |
| `/<lang>/privacy` | Privacy |
| `/<lang>/remote-support` | Remote information |
| `/help`, `/hulp` | Conditional 302 to configured approved portal or language-specific information page; no-store |
| `/robots.txt`, `/sitemap.xml` | SEO endpoints |

`<lang>` means `en` or `nl`. Seven logical pages, 14 localized URLs per current source test expectation; not a runtime measurement. Retain trailing-slash normalization, 404 and method/HEAD behavior. Current menu is grouped Home / Software & websites (Services, Portfolio) / Computer help (Rescue OS, Assist, Remote help) / About / Contact / language. Current cards are four independent presentation entries.

## Approved target and transition

Retain existing language enum/default English; root 302 to /en, /en and /nl without trailing slash. Use stable English slugs and translated headings/menu labels. All routes below are relative to /{language} except root/shortcuts/SEO endpoints. Use explicit fixed FastRoute paths and typed Page enum mapping, not arbitrary template paths or user-controlled redirects.

| Canonical path suffix | English title | Dutch title |
|---|---|---|
| (empty) | Home | Home |
| /software-development | Software Design & Development | Softwareontwerp en -ontwikkeling |
| /software-development/websites | Website development | Websiteontwikkeling |
| /software-development/web-applications | Web applications | Webapplicaties |
| /software-development/custom-software | Custom software | Software op maat |
| /software-development/automation | Automation & integration | Automatisering en integratie |
| /portfolio | Portfolio | Portfolio |
| /computer-support | Computer Repair & Support | Computerreparatie en ondersteuning |
| /computer-support/assist | Shiva Assist | Shiva Assist |
| /computer-support/recovery | Shiva Recovery | Shiva Recovery |
| /remote-support | Shiva Remote | Shiva Remote |
| /computer-support/repair-services | Computer repair services | Reparatiediensten |
| /about | About | Over ons |
| /contact | Contact | Contact |
| /privacy | Privacy | Privacyverklaring |

15 canonical pages per language = 30 URLs once all are approved/indexable. No indexable placeholders or aliases. Terms/cookie information subject to legal/content approval; not included as new routes now. Existing /services remains 200 during transitional P03/P02; a fixed same-language 301 to /software-development is a later option B, not part of this redesign. Compatibility option A (SC-MD03, approved): `/services` stays a canonical 200 page in both languages for this redesign; the 301 to `/software-development` is deferred to a separately authorized option B that must also amend the `/en/services` smoke check in `.github/workflows/deploy.yml` (outside the 46-path scope). Preserve /portfolio, /about, /contact GET+POST, /privacy, /remote-support. /hulp and /help retain configured portal/fallback, correct language, 302 and existing no-store behavior. Preserve root, robots, sitemap, trailing-slash handling, 404/405/HEAD semantics as currently implemented; do not silently change method behavior. SEO canonical, hreflang reciprocal EN/NL/x-default, Open Graph, existing organization JSON-LD and content approval semantics. Redirect aliases excluded from final sitemap; derive expected URL set, not simply a count. During transition /services remains canonical if still a 200 and indexable; 16 pages per language possible temporarily. Do not advertise unapproved candidate pages publicly; local development review only. Existing global content approval=true does not approve new copy; require explicit content gate before main integration.

## Target navigation

Main navigation: Home | Software Development (overview, websites, web apps, custom software, automation, portfolio) | Computer Support (overview, Assist, Recovery, Remote, repair services) | About | Contact | language switch. Both groups equal visual prominence. Footer retains privacy, contact/company details and remote help. No infrastructure page. Shared navigation keeps native details/summary, mobile parity, active group, current page, keyboard and accessible names; no mandatory JS. Parent overview is a real dropdown child link. Use fixed route-to-key mapping and preserve old shared site identifiers main/rescue/assist; no breaking constructor changes to other sites. Version component and cache-bust changed CSS. Main repository proposed canonical shared-navigation source, pending confirmation against earlier documented external source ShivaComputers_26/shiva-nav; do not overwrite other copies or claim migration complete. Coordinate separate product-specific updates later. Old copies must continue resolving existing URLs.

Product introduction pages remain local; operational links use existing validated configured origins and actual release readiness. No public infrastructure or unapproved placeholders. P03 adds nine logical pages while retaining `/services` (16 per language, up to 32 indexable URLs); P02 uses established routes; P04 runs with option A and keeps `/services` 200 (still 16 per language, up to 32). A later option B may activate the fixed same-language 301 only with separate authorization and the matching deploy smoke-check amendment (15 per language, up to 30). Actual sitemap uses exact indexable set, not count alone. Keep existing remote and recovery links working. SEO verification: reciprocal locale alternates, x-default, canonical, Open Graph, organization JSON-LD, robots, exact sitemap, language switching, root/legacy redirects, 404/405 and safe origin handling. See [specification](../SPECIFICATION.md) and [architecture](ARCHITECTURE.md).
