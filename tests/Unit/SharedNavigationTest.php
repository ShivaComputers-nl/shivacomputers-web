<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use ShivaNav\Navigation;

/** The shared menu kit in shiva-nav/ (identical in all three sites). */
final class SharedNavigationTest extends TestCase
{
    private const SITES = ['main' => 'https://shivacomputers.nl', 'rescue' => 'https://rescue.shivacomputers.nl', 'assist' => 'https://assist.shivacomputers.nl'];

    public static function setUpBeforeClass(): void
    {
        require_once __DIR__ . '/../../shiva-nav/Navigation.php';
    }

    protected function setUp(): void
    {
        require_once __DIR__ . '/../../shiva-nav/Navigation.php';
    }

    /** @return array<string, array<string, mixed>> key => link, for every link in the menu */
    private static function links(array $built): array
    {
        $links = [];
        foreach ($built['items'] as $item) {
            foreach ($item['children'] === [] ? [$item] : $item['children'] as $link) {
                $links[$link['key']] = $link;
            }
        }

        return $links;
    }

    public function testLinksToTheOwnSiteStayRelativeAndOthersBecomeAbsolute(): void
    {
        // As rendered by the Assist site: every 2.0.0 link lives on the main site, so all become absolute.
        $links = self::links((new Navigation(self::SITES, 'assist'))->build('nl', 'assist', []));
        self::assertSame('https://shivacomputers.nl/nl/computer-support/assist', $links['assist-intro']['url']);
        self::assertSame('https://shivacomputers.nl/nl/software-development', $links['development']['url']);
        self::assertTrue($links['assist-intro']['external']);

        // As rendered by the main site: the same links stay relative.
        $main = self::links((new Navigation(self::SITES, 'main'))->build('nl', null, []));
        self::assertSame('/nl/computer-support/assist', $main['assist-intro']['url']);
        self::assertFalse($main['assist-intro']['external']);
        self::assertSame('https://rescue.shivacomputers.nl/nl', (new Navigation(self::SITES, 'main'))->url('rescue', '', 'nl'));
    }

    public function testEveryLinkHasTextInEnglishAndDutch(): void
    {
        $menu = require __DIR__ . '/../../shiva-nav/menu.php';
        $check = function (array $texts): void {
            self::assertArrayHasKey('en', $texts);
            self::assertArrayHasKey('nl', $texts);
        };
        foreach ($menu['labels'] as $label) {
            $check($label);
        }
        foreach ($menu['items'] as $item) {
            $check($item['label']);
            foreach ($item['children'] ?? [] as $child) {
                $check($child['label']);
                $check($child['description']);
                self::assertTrue(in_array($child['site'], Navigation::SITES, true));
            }
        }
    }

    public function testCurrentPageMarksItsGroup(): void
    {
        $built = (new Navigation(self::SITES, 'main'))->build('en', 'remote', []);
        $groups = array_column($built['items'], 'active', 'key');

        self::assertTrue($groups['computer-support']);
        self::assertFalse($groups['software-development']);
        self::assertTrue(self::links($built)['remote']['current']);
    }

    public function testUnknownLanguageFallsBackToEnglish(): void
    {
        self::assertSame('Computer Support', (new Navigation(self::SITES, 'main'))->build('de', null, [])['items'][2]['label']);
    }

    public static function legacyKeys(): array
    {
        return [
            'old services page' => ['services', 'software-development'],
            'rescue product site' => ['rescue', 'computer-support'],
            'assist product site' => ['assist', 'computer-support'],
        ];
    }

    /** Older menu keys still work: they mark their group, but never mark a link as the current page. */
    #[DataProvider('legacyKeys')]
    public function testOlderPageKeysMarkTheirGroupOnly(string $key, string $group): void
    {
        foreach (['main', 'rescue', 'assist'] as $site) {
            $built = (new Navigation(self::SITES, $site))->build('en', $key, []);
            $groups = array_column($built['items'], 'active', 'key');

            self::assertTrue($groups[$group], $site . ' ' . $key);
            self::assertNotContains(true, array_column(self::links($built), 'current'), $site . ' ' . $key);
        }
    }

    public function testHeaderMarksTheCurrentPageOncePerMenuVariant(): void
    {
        $shivaNav = (new Navigation(self::SITES, 'main'))->build('en', 'recovery-intro', []);
        ob_start();
        require __DIR__ . '/../../shiva-nav/header.php';
        $html = (string) ob_get_clean();
        $mobileStart = (int) strpos($html, '<details class="snav-mobile">');

        self::assertSame(1, substr_count(substr($html, 0, $mobileStart), 'aria-current="page"'), 'desktop');
        self::assertSame(1, substr_count(substr($html, $mobileStart), 'aria-current="page"'), 'mobile');
    }

    public function testSiteIdentifiersAndLinkKeysAreStable(): void
    {
        self::assertSame(['main', 'rescue', 'assist'], Navigation::SITES);
        $links = self::links((new Navigation(self::SITES, 'main'))->build('en', null, []));

        self::assertSame(
            ['home', 'development', 'websites', 'web-applications', 'custom-software', 'automation', 'portfolio',
                'support', 'assist-intro', 'recovery-intro', 'remote', 'repair-services', 'about', 'contact'],
            array_keys($links),
        );
        self::assertStringNotContainsString('infrastructu', strtolower(json_encode($links, JSON_THROW_ON_ERROR)));
    }

    public static function unsafe(): array
    {
        return [
            'http' => ['http://shivacomputers.nl'],
            'path' => ['https://shivacomputers.nl/x'],
            'query' => ['https://shivacomputers.nl?a=1'],
            'credentials' => ['https://u:p@shivacomputers.nl'],
            'javascript' => ['javascript:alert(1)'],
            'empty' => [''],
        ];
    }

    #[DataProvider('unsafe')]
    public function testUnsafeSiteAddressesAreRefused(string $url): void
    {
        $refused = false;
        try {
            new Navigation(['main' => $url] + self::SITES, 'main');
        } catch (\InvalidArgumentException) {
            $refused = true;
        }
        self::assertTrue($refused);
    }

    public function testLocalDockerAddressesOnlyInDevelopment(): void
    {
        self::assertFalse(Navigation::isSafeBaseUrl('http://localhost:8083'));
        self::assertTrue(Navigation::isSafeBaseUrl('http://localhost:8083', true));
    }

    public function testHeaderTemplateNeverOverwritesThePagesVariables(): void
    {
        $current = 'page value';
        $h = 'page value';
        $languages = 'page value';
        $shivaNav = (new Navigation(self::SITES, 'main'))->build('en', 'home', []);

        ob_start();
        require __DIR__ . '/../../shiva-nav/header.php';
        $html = (string) ob_get_clean();

        self::assertSame('page value', $current);
        self::assertSame('page value', $h);
        self::assertSame('page value', $languages);
        self::assertStringContainsString('data-snav-version="' . Navigation::VERSION . '"', $html);
    }
}
