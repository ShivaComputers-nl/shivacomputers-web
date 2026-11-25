<?php

declare(strict_types=1);

/*
 * The Shiva Computers main menu, shared by shivacomputers.nl, rescue.shivacomputers.nl and assist.shivacomputers.nl.
 *
 * Version 2.0.0 (SC-MASTER-001 P04): two equal groups, Software Development and Computer Support.
 * Historical source of truth: ShivaComputers_26/shiva-nav/. Which copy is canonical is not yet verified, and the
 * other sites' copies are NOT updated by this change; they keep working because every old address still answers.
 * Copying this folder to another site needs its own approval (see README.md in this folder).
 *
 * Each link names the site it lives on ('main', 'rescue', 'assist') and its path after /{language}.
 * 'legacy' lists older page keys (from older menu copies or product sites) that mark a group active
 * without marking any link in it as the current page.
 */

return [
    'labels' => [
        'navigation' => ['en' => 'Main menu', 'nl' => 'Hoofdmenu'],
        'menu' => ['en' => 'Menu', 'nl' => 'Menu'],
        'home' => ['en' => 'Shiva Computers home page', 'nl' => 'Startpagina Shiva Computers'],
        'languages' => ['en' => 'Language', 'nl' => 'Taal'],
    ],
    'items' => [
        [
            'key' => 'home',
            'site' => 'main',
            'path' => '',
            'label' => ['en' => 'Home', 'nl' => 'Home'],
        ],
        [
            'key' => 'software-development',
            'label' => ['en' => 'Software Development', 'nl' => 'Softwareontwikkeling'],
            'legacy' => ['services'],
            'children' => [
                [
                    'key' => 'development',
                    'site' => 'main',
                    'path' => '/software-development',
                    'label' => ['en' => 'Overview', 'nl' => 'Overzicht'],
                    'description' => ['en' => 'Software Design & Development', 'nl' => 'Softwareontwerp en -ontwikkeling'],
                ],
                [
                    'key' => 'websites',
                    'site' => 'main',
                    'path' => '/software-development/websites',
                    'label' => ['en' => 'Websites', 'nl' => 'Websites'],
                    'description' => ['en' => 'Clear, responsive websites', 'nl' => 'Duidelijke, responsieve websites'],
                ],
                [
                    'key' => 'web-applications',
                    'site' => 'main',
                    'path' => '/software-development/web-applications',
                    'label' => ['en' => 'Web applications', 'nl' => 'Webapplicaties'],
                    'description' => ['en' => 'Software in the browser', 'nl' => 'Software in de browser'],
                ],
                [
                    'key' => 'custom-software',
                    'site' => 'main',
                    'path' => '/software-development/custom-software',
                    'label' => ['en' => 'Custom software', 'nl' => 'Software op maat'],
                    'description' => ['en' => 'Tailored to your processes', 'nl' => 'Afgestemd op uw processen'],
                ],
                [
                    'key' => 'automation',
                    'site' => 'main',
                    'path' => '/software-development/automation',
                    'label' => ['en' => 'Automation & integration', 'nl' => 'Automatisering en integratie'],
                    'description' => ['en' => 'Less repetitive work', 'nl' => 'Minder repeterend werk'],
                ],
                [
                    'key' => 'portfolio',
                    'site' => 'main',
                    'path' => '/portfolio',
                    'label' => ['en' => 'Portfolio', 'nl' => 'Portfolio'],
                    'description' => ['en' => 'Selected projects, with the source code', 'nl' => 'Geselecteerde projecten, met de broncode'],
                ],
            ],
        ],
        [
            'key' => 'computer-support',
            'label' => ['en' => 'Computer Support', 'nl' => 'Computerondersteuning'],
            'legacy' => ['rescue', 'assist'],
            'children' => [
                [
                    'key' => 'support',
                    'site' => 'main',
                    'path' => '/computer-support',
                    'label' => ['en' => 'Overview', 'nl' => 'Overzicht'],
                    'description' => ['en' => 'Computer Repair & Support', 'nl' => 'Computerreparatie en ondersteuning'],
                ],
                [
                    'key' => 'assist-intro',
                    'site' => 'main',
                    'path' => '/computer-support/assist',
                    'label' => ['en' => 'Shiva Assist', 'nl' => 'Shiva Assist'],
                    'description' => ['en' => 'Windows diagnostics, in development', 'nl' => 'Windows-diagnose, in ontwikkeling'],
                ],
                [
                    'key' => 'recovery-intro',
                    'site' => 'main',
                    'path' => '/computer-support/recovery',
                    'label' => ['en' => 'Shiva Recovery', 'nl' => 'Shiva Recovery'],
                    'description' => ['en' => 'Including Shiva Rescue OS, in development', 'nl' => 'Met Shiva Rescue OS, in ontwikkeling'],
                ],
                [
                    'key' => 'remote',
                    'site' => 'main',
                    'path' => '/remote-support',
                    'label' => ['en' => 'Shiva Remote', 'nl' => 'Shiva Remote'],
                    'description' => ['en' => 'Remote help with your consent', 'nl' => 'Hulp op afstand met uw toestemming'],
                ],
                [
                    'key' => 'repair-services',
                    'site' => 'main',
                    'path' => '/computer-support/repair-services',
                    'label' => ['en' => 'Repair services', 'nl' => 'Reparatiediensten'],
                    'description' => ['en' => 'Diagnosis, maintenance and next steps', 'nl' => 'Diagnose, onderhoud en vervolgstappen'],
                ],
            ],
        ],
        [
            'key' => 'about',
            'site' => 'main',
            'path' => '/about',
            'label' => ['en' => 'About', 'nl' => 'Over ons'],
        ],
        [
            'key' => 'contact',
            'site' => 'main',
            'path' => '/contact',
            'label' => ['en' => 'Contact', 'nl' => 'Contact'],
        ],
    ],
];
