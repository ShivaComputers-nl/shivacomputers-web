# Shiva Computers — Business Architecture

**SC-MASTER-001 local candidate — P01 documentation (25 November 2026).** Written against the baseline source at `develop` `f5eff3cf5fe1d7e5fe50960b99ec5b74b43033e8` and the owner-approved target. "Current" in this document means that baseline; later local packages (P03 → P02 → P04(A) → P05) implement parts of the target and their verified status is recorded in `docs/WEBSITE_ACCEPTANCE.md` (created by P05). Nothing here claims production deployment, current CI or live verification.

## Authority and approved decisions

SC-ARCH-001 revision 1.1 governs the target. Reference: `develop` `f5eff3cf5fe1d7e5fe50960b99ec5b74b43033e8`; `main` `9e18f6ac194a3e43205b2c243c821d5430cdfba0`; both tree `03a9286ccf9ca4937adfec2bd7005f9768fe33c6` (116 tracked blobs), refreshed 25 November 2026. Repository: https://github.com/ShivaComputers-nl/shivacomputers-web (ID 1444462366).

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

All are approved. SC-MASTER-001 (SC-MD01–08, 25 November 2026) adds a bounded local execution grant only; legal content, product status and releases still require separate review.

## Business and repository hierarchy

```text
Shiva Computers — shivacomputers.nl
├── Software Design & Development (blue)
│   ├── Website development
│   ├── Web applications
│   ├── Custom software / business applications
│   ├── Automation and integration
│   └── Portfolio and maintenance
└── Computer Repair & Support (green)
    ├── Shiva Assist — Windows diagnostics, verified repair
    ├── Shiva Recovery — system and data recovery
    │   └── Shiva Rescue OS — existing rescue utility
    ├── Shiva Remote — explicit-consent technical assistance
    └── Computer repair and maintenance services

Internal only: Shiva Infrastructure — hosting, networking, backups, monitoring, deployment
```

| Repository | Responsibility | Status / recommendation |
|---|---|---|
| shivacomputers-web | Central public information, contact, portfolio, product introductions | Verified public repository |
| shiva-assist | Native Windows application and product-specific releases | Verified public repository (separate earlier audit); SA-UX-001 is independent |
| shiva-recovery | Recovery family engineering, including existing Rescue OS | Target name; inventory existing source before rename/transfer |
| shiva-remote | Consent-based remote application and portal | Target name; existing docs mention shiva-remote-portal, inventory before migration |
| shiva-infrastructure | Private operational engineering | Proposed private repository; never public navigation |

Earlier exact-name lookups for recovery/remote/infrastructure and rescue-os under organization returned 404; not proof of absence. Do not create duplicates or assume transfer is needed. Full organization inventory unavailable. Client projects get separate repositories. Preserve repository IDs, Git history, branches, PRs, releases and automation when future migrations are authorized. No transfers or visibility changes here. Existing website composer metadata is proprietary; decide distribution/licensing deliberately; public source is not synonymous with open source. Use least privilege, MFA, protected production integration and secrets outside Git. No credentials or customer data in any repository.

Organization documentation standard: README, architecture/specification, AGENTS, CLAUDE where needed, HANDOFF, CHANGELOG, SECURITY, CONTRIBUTING and license policy. Only exact files in the package inventory are candidates now; other documents require separate scope. GitHub canonical record is intended, but publication not authorized and prior Assist issue publication returned 403.

## Domains and independent releases

| Origin | Role / gate |
|---|---|
| shivacomputers.nl | Main bilingual business site, existing STRATO target |
| assist.shivacomputers.nl | Product information and approved signed downloads; independent Assist release gates |
| recovery.shivacomputers.nl | Target recovery platform, not verified provisioned |
| rescue.shivacomputers.nl | Existing configured Rescue OS origin; preserve until separately authorized migration |
| remote.shivacomputers.nl | Target remote portal; only expose operational link when configured and production-approved |
| Private infrastructure | Internal server management, monitoring, deployment, backup; no public product page |

Main site product pages are informational and do not perform repairs or remote control. Never introduce shared authentication or session privileges in the website. Preserve existing remote fallback and /help and /hulp shortcuts. No automatic session initiation; explicit consent, visibility and ability to stop remain product requirements. Existing portal code/config may be separate; do not assume it is live. Recovery migration needs origin inventory, certificate/DNS/hosting verification, old links and download compatibility, rollback and owner authorization. No DNS, certificates, live network or hosting changes in this workstream. Do not expose home-server management services or private infrastructure details.

## Brand identity

Shared navy #14243A, development blue #1D4ED8, support green #167D49, canvas #F4F6F9, white #FFFFFF. Equal business prominence, not necessarily equal commercial resources. See [functional specification](../SPECIFICATION.md) for visual requirements and [sitemap](WEBSITE_SITEMAP.md) for public hierarchy. Product websites have independent deployment, testing, versioning and release gates; no shared credentials or session control.

## Status and unresolved governance

Current website source labels Assist and Rescue OS in development and remote help available by appointment; verify all before publication. No guarantees, invented testimonials or unverified outcomes. Full organization inventory, actual live domains, availability, licensing and privacy remain unresolved. Do not create duplicate repositories, rename, transfer, change visibility or expose internal infrastructure. Owner approves consequential changes and releases. [Technical architecture](ARCHITECTURE.md) and [handoff](../HANDOFF.md) define boundaries.
