# shiva-nav — Shared Shiva Computers navigation

**SC-MASTER-001 local candidate — P01 documentation (25 November 2026).** Written against the baseline source at `develop` `f5eff3cf5fe1d7e5fe50960b99ec5b74b43033e8` and the owner-approved target. "Current" in this document means that baseline; later local packages (P03 → P02 → P04(A) → P05) implement parts of the target and their verified status is recorded in `docs/WEBSITE_ACCEPTANCE.md` (created by P05). Nothing here claims production deployment, current CI or live verification.

## Local candidate 2.0.0 (SC-MASTER-001 P04, main website only)

This repository's copy is now version 2.0.0 as a local candidate. The other sites' copies are not changed.

```text
Home | Software Development ▾        | Computer Support ▾      | About | Contact | language
       ├ Overview (/software-development) ├ Overview (/computer-support)
       ├ Websites                         ├ Shiva Assist (/computer-support/assist)
       ├ Web applications                 ├ Shiva Recovery (/computer-support/recovery)
       ├ Custom software                  ├ Shiva Remote (/remote-support)
       ├ Automation & integration         └ Repair services
       └ Portfolio
```

- Every 2.0.0 link is on the main site; product origins (rescue, assist) are linked from the local introduction pages, still validated by `ProductLinks`.
- New page keys: development, websites, web-applications, custom-software, automation, support, assist-intro, recovery-intro, repair-services; unchanged: home, portfolio, remote, about, contact.
- Compatibility: a group's `legacy` list marks the group active for older keys without marking any link current. `services` (the `/services` page, kept as 200 under option A) marks Software Development; `rescue` and `assist` (sent by the product sites' own copies) mark Computer Support. No `aria-current` is placed on a link to another origin.
- Constructor, `Navigation::SITES` (main, rescue, assist), origin validation and the header markup are unchanged. The mobile menu now applies below 1100px (`max-width: 1099px`) so the wider labels fit; very narrow screens (<360px) use tighter spacing. Stylesheet cache version `shiva-nav.css?v=2`.
- Older 1.0.0 copies on other sites keep working: every address they link to (`/services`, `/portfolio`, `/remote-support`, product origins) still answers.

## Previous contract (version 1.0.0, baseline)

Three documented sites, not independently verified live:

| Site identifier | Origin | Documented STRATO folder | Current page keys |
|---|---|---|---|
| main | shivacomputers.nl | shivacomputers-live | home, services, portfolio, remote, about, contact |
| rescue | rescue.shivacomputers.nl | rescue-site | rescue |
| assist | assist.shivacomputers.nl | assist-site | assist |

```text
Home | Software & websites ▾ | Computer help ▾ | About | Contact | language
         ├ Services             ├ Shiva Rescue OS
         └ Portfolio            ├ Shiva Assist
                                └ Remote help
```

| Shared file | Responsibility |
|---|---|
| shiva-nav/menu.php | Groups, EN/NL labels, descriptions and routes |
| shiva-nav/Navigation.php | Relative own-site and absolute cross-site links, active keys |
| shiva-nav/header.php | Escaped markup, native details/summary, desktop/mobile |
| public/assets/shiva-nav/shiva-nav.css | snav-prefixed styles |
| public/assets/shiva-nav/logo.svg | Shared logo |

Historical docs and code designate ShivaComputers_26/shiva-nav as source of truth and describe identical copies. Actual cross-repository equality and authority are unresolved; main website repository is proposed canonical source only after verification and separate approval. Do not copy or replace other sites now.

## Existing interface (example, not execution instruction)

```php
require_once $root . '/shiva-nav/Navigation.php';
$navigation = new ShivaNav\Navigation(
    ['main' => 'https://shivacomputers.nl', 'rescue' => 'https://rescue.shivacomputers.nl', 'assist' => 'https://assist.shivacomputers.nl'],
    'assist',
    $isDevelopment,
);
$shivaNav = $navigation->build($language, 'assist', $languageLinks);
require $root . '/shiva-nav/header.php';
```

Language links example: `[['code' => 'nl', 'name' => 'Nederlands', 'title' => 'Bekijk deze pagina in het Nederlands', 'url' => '/nl/pricing']]`. One language gives a link; multiple languages use dropdown. Preserve constructor, stable site keys, active page/group, escaped labels, HTTPS origin-only validation (no credentials/query/fragment/path except root), development-only localhost HTTP. Native details/summary requires no JS; keep keyboard and mobile parity.

## Approved target

Home | Software Development (overview, websites, web applications, custom software, automation, portfolio) | Computer Support (overview, Assist, Recovery, Remote, repair services) | About | Contact | language. Equal hierarchy. Recovery includes existing Rescue OS, retain rescue origin and help links. No infrastructure item. See [sitemap](../docs/WEBSITE_SITEMAP.md).

## Coordinated rollout (P04 local candidate under SC-MASTER-001; cross-repository rollout not authorized)

Verify authority and inventory each site copy and version; separately approve scope for each repository. Establish all target local routes before menu links; preserve legacy keys/origins and `/services` compatibility until authorized transition. Update Navigation::VERSION and stylesheet cache query, keep data-snav-version. Test all sites independently, safe origins, active page/group, EN/NL labels, desktop/mobile parity, keyboard, valid URLs, and old-copy compatibility. No automatic copying, deployment, DNS or release; cross-repo changes require explicit grants. P01 edits this README only. P04 may change this folder in the main repository as a local candidate; copying it to other repositories remains a separate grant.
