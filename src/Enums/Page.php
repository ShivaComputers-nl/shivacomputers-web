<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Every public page. The value is the fixed path after /{language} (no leading slash) and the
 * middle part of its metadata keys: meta.<value>.title and meta.<value>.description.
 */
enum Page: string
{
    case Home = 'home';
    case Services = 'services';
    case Portfolio = 'portfolio';
    case About = 'about';
    case Contact = 'contact';
    case Privacy = 'privacy';
    case RemoteSupport = 'remote-support';

    // Software Design & Development division
    case Development = 'software-development';
    case Websites = 'software-development/websites';
    case WebApplications = 'software-development/web-applications';
    case CustomSoftware = 'software-development/custom-software';
    case Automation = 'software-development/automation';

    // Computer Repair & Support division
    case Support = 'computer-support';
    case Assist = 'computer-support/assist';
    case Recovery = 'computer-support/recovery';
    case RepairServices = 'computer-support/repair-services';

    case NotFound = 'not-found';

    /** Key of this page in the shared Shiva Computers menu (shiva-nav/menu.php), or null when it is not in the menu. */
    public function navigationKey(): ?string
    {
        return match ($this) {
            self::Home => 'home',
            self::Services => 'services',
            self::Portfolio => 'portfolio',
            self::About => 'about',
            self::Contact => 'contact',
            self::RemoteSupport => 'remote',
            self::Development => 'development',
            self::Websites => 'websites',
            self::WebApplications => 'web-applications',
            self::CustomSoftware => 'custom-software',
            self::Automation => 'automation',
            self::Support => 'support',
            // Distinct from the product sites' own keys 'assist' and 'rescue', which stay valid for them.
            self::Assist => 'assist-intro',
            self::Recovery => 'recovery-intro',
            self::RepairServices => 'repair-services',
            self::Privacy, self::NotFound => null,
        };
    }

    public function path(Language $language): string
    {
        return '/' . $language->value . match ($this) {
            self::Home, self::NotFound => '',
            default => '/' . $this->value,
        };
    }

    public function template(): string
    {
        return match ($this) {
            self::Home => 'Home/index',
            self::Services => 'Services/index',
            self::Portfolio => 'Portfolio/index',
            self::About => 'About/index',
            self::Contact => 'Contact/index',
            self::Privacy => 'Privacy/index',
            self::RemoteSupport => 'RemoteSupport/index',
            self::Development => 'Development/index',
            self::Websites, self::WebApplications, self::CustomSoftware, self::Automation => 'Development/service',
            self::Support => 'Support/index',
            self::Assist, self::Recovery => 'Support/product',
            self::RepairServices => 'Support/repair-services',
            self::NotFound => 'Errors/not-found',
        };
    }

    public function isIndexable(): bool
    {
        return $this !== self::NotFound;
    }
}
