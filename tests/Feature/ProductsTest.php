<?php

declare(strict_types=1);

namespace App\Tests\Feature;

use App\Enums\Product;
use App\Framework\Application;
use App\Framework\Container;
use App\Framework\Request;
use App\Framework\Response;
use App\Framework\Router;
use App\Middleware\SecurityHeadersMiddleware;
use App\ViewModels\ProductLinks;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use function FastRoute\simpleDispatcher;

/** The four products in the main menu and on the home page, in a fixed order. */
final class ProductsTest extends TestCase
{
    private const ROOT = __DIR__ . '/../..';

    /** @param array<string, mixed> $overrides */
    private function get(string $path, array $overrides = []): Response
    {
        $config = $overrides + (require self::ROOT . '/config/app.php');
        $container = new Container();
        (require self::ROOT . '/config/services.php')($container, $config, realpath(self::ROOT));
        $application = new Application(
            new Router(simpleDispatcher(require self::ROOT . '/config/routes.php')),
            $container,
            [new SecurityHeadersMiddleware()],
        );

        return $application->handle(new Request('GET', $path));
    }

    /** The desktop menu: everything between the first <nav and its closing tag. */
    private static function navigation(string $body): string
    {
        $start = (int) strpos($body, '<nav');

        return substr($body, $start, (int) strpos($body, '</nav>', $start) - $start);
    }

    /** @param list<string> $needles */
    private static function assertInOrder(array $needles, string $haystack): void
    {
        $last = -1;
        foreach ($needles as $needle) {
            $position = strpos($haystack, $needle);
            self::assertTrue($position !== false, 'Missing: ' . $needle);
            self::assertTrue($position > $last, 'Out of order: ' . $needle);
            $last = (int) $position;
        }
    }

    public static function languages(): array
    {
        return [
            'English' => ['en', 'Shiva Remote', 'What Shiva Computers offers'],
            'Dutch' => ['nl', 'Shiva Remote', 'Wat Shiva Computers biedt'],
        ];
    }

    #[DataProvider('languages')]
    public function testSharedMenuGroupsEverythingInFiveItems(string $language, string $remoteLabel): void
    {
        $menu = self::navigation($this->get('/' . $language)->body);

        self::assertSame(5, substr_count($menu, '<li><a class="snav-link') + substr_count($menu, '<li class="snav-group">'));
        // Shared menu 2.0.0 (P04): two equal groups, every link local; no infrastructure item.
        self::assertInOrder([
            'href="/' . $language . '"',
            '<summary class="snav-link">' . ($language === 'nl' ? 'Softwareontwikkeling' : 'Software Development') . '</summary>',
            'href="/' . $language . '/software-development"',
            'href="/' . $language . '/software-development/websites"',
            'href="/' . $language . '/software-development/web-applications"',
            'href="/' . $language . '/software-development/custom-software"',
            'href="/' . $language . '/software-development/automation"',
            'href="/' . $language . '/portfolio"',
            '<summary class="snav-link">' . ($language === 'nl' ? 'Computerondersteuning' : 'Computer Support') . '</summary>',
            'href="/' . $language . '/computer-support"',
            'href="/' . $language . '/computer-support/assist"',
            'href="/' . $language . '/computer-support/recovery"',
            'href="/' . $language . '/remote-support"><strong>' . $remoteLabel,
            'href="/' . $language . '/computer-support/repair-services"',
            'href="/' . $language . '/about"',
            'href="/' . $language . '/contact"',
        ], $menu);
        self::assertSame(11, substr_count($menu, '<li><a href='), 'Six development and five support links in the groups');
        self::assertStringNotContainsString('https://', $menu);
        self::assertStringNotContainsStringIgnoringCase('infrastructu', $menu);
    }

    #[DataProvider('languages')]
    public function testHomePageGroupsTheThreeSupportProductsInOrder(string $language): void
    {
        // Replaces the four-card assertion (P02): software is now a primary division; the three support
        // products link to their local introduction pages, which hold the configured external origins.
        $body = $this->get('/' . $language)->body;
        $section = substr($body, (int) strpos($body, 'class="secondary secondary--support products"'));

        self::assertStringContainsString($language === 'nl' ? 'Ondersteuningsproducten' : 'Support products', $section);
        self::assertSame(3, substr_count($section, 'class="product-card '));
        self::assertInOrder([
            'class="product-card product-card--assist" href="/' . $language . '/computer-support/assist"',
            'class="product-card product-card--rescue" href="/' . $language . '/computer-support/recovery"',
            'class="product-card product-card--remote" href="/' . $language . '/remote-support"',
        ], $section);
    }

    public function testDivisionsAppearBeforeTheSecondarySections(): void
    {
        $body = $this->get('/en')->body;

        self::assertInOrder(['class="hero', 'class="section divisions"', 'id="home-projects"', 'id="home-products"'], $body);
    }

    public function testDevelopmentStatusIsShownHonestly(): void
    {
        $body = $this->get('/en')->body;

        self::assertSame(2, substr_count($body, '<span class="product-tag">In development</span>'));
    }

    public static function currentPages(): array
    {
        return [
            'home' => ['/en', 'class="snav-link is-active" href="/en" aria-current="page">Home</a>'],
            'development overview' => ['/en/software-development', 'href="/en/software-development" aria-current="page"><strong>Overview'],
            'websites' => ['/nl/software-development/websites', 'href="/nl/software-development/websites" aria-current="page"><strong>Websites'],
            'support overview' => ['/nl/computer-support', 'href="/nl/computer-support" aria-current="page"><strong>Overzicht'],
            'assist introduction' => ['/en/computer-support/assist', 'href="/en/computer-support/assist" aria-current="page"><strong>Shiva Assist'],
            'recovery introduction' => ['/en/computer-support/recovery', 'href="/en/computer-support/recovery" aria-current="page"><strong>Shiva Recovery'],
            'repair services' => ['/en/computer-support/repair-services', 'href="/en/computer-support/repair-services" aria-current="page"><strong>Repair services'],
            'remote support' => ['/nl/remote-support', 'href="/nl/remote-support" aria-current="page"><strong>Shiva Remote'],
            'about' => ['/nl/about', 'href="/nl/about" aria-current="page">Over ons</a>'],
        ];
    }

    #[DataProvider('currentPages')]
    public function testMenuMarksTheCurrentPage(string $path, string $expected): void
    {
        self::assertStringContainsString($expected, self::navigation($this->get($path)->body));
    }

    public function testDropdownOfTheCurrentPageIsHighlighted(): void
    {
        $menu = self::navigation($this->get('/en/portfolio')->body);

        self::assertStringContainsString('<summary class="snav-link is-active">Software Development</summary>', $menu);
        self::assertStringContainsString('<summary class="snav-link">Computer Support</summary>', $menu);

        $support = self::navigation($this->get('/en/remote-support')->body);
        self::assertStringContainsString('<summary class="snav-link is-active">Computer Support</summary>', $support);
        self::assertStringContainsString('<summary class="snav-link">Software Development</summary>', $support);
    }

    public function testLegacyServicesPageMarksItsGroupWithoutClaimingANewPage(): void
    {
        // Compatibility option A: /services stays 200; it highlights its group but no link is "current".
        $menu = self::navigation($this->get('/en/services')->body);

        self::assertStringContainsString('<summary class="snav-link is-active">Software Development</summary>', $menu);
        self::assertStringNotContainsString('aria-current', $menu);
    }

    public function testEveryPageMarksAtMostOneLinkAsCurrent(): void
    {
        foreach (['/en', '/en/software-development/automation', '/nl/computer-support/recovery', '/nl/contact', '/en/privacy'] as $path) {
            self::assertLessThanOrEqual(1, substr_count(self::navigation($this->get($path)->body), 'aria-current="page"'), $path);
        }
    }

    public function testLogoLinksToTheHomePage(): void
    {
        self::assertStringContainsString('<a class="snav-brand" href="/nl"', $this->get('/nl/contact')->body);
    }

    public function testPhoneMenuHasEveryLinkAndNeedsNoJavaScript(): void
    {
        $body = $this->get('/nl/about')->body;
        $desktop = self::navigation($body);
        $phone = substr($body, (int) strpos($body, '<details class="snav-mobile">'));
        $phone = substr($phone, 0, (int) strpos($phone, '</details>'));

        self::assertStringContainsString('<summary>Menu</summary>', $phone);
        self::assertSame(substr_count($desktop, 'href='), substr_count($phone, 'href=') - 1, 'Phone menu = desktop links + language');
        self::assertStringNotContainsString('<script', $body);
    }

    public function testLanguageSwitchKeepsThePageAndIsWrittenInTheTargetLanguage(): void
    {
        $body = $this->get('/en/portfolio')->body;

        self::assertStringContainsString('<a class="snav-lang" href="/nl/portfolio" hreflang="nl" lang="nl" title="Bekijk deze pagina in het Nederlands">Nederlands</a>', $body);
    }

    public function testSharedStylesheetIsLoadedBeforeTheSiteStylesheet(): void
    {
        self::assertInOrder(['/assets/shiva-nav/shiva-nav.css', '/assets/css/site.css'], $this->get('/en')->body);
    }

    public function testProductSiteAddressesCanBeConfigured(): void
    {
        // The configured product origins now appear on the local introduction pages (P04).
        $sites = ['product_sites' => ['rescue' => 'https://r.example.nl', 'assist' => 'https://a.example.nl/']];

        self::assertStringContainsString('href="https://r.example.nl/en"', $this->get('/en/computer-support/recovery', $sites)->body);
        self::assertStringContainsString('href="https://a.example.nl/en"', $this->get('/en/computer-support/assist', $sites)->body);
    }

    public static function unsafeSiteUrls(): array
    {
        return [
            'plain http' => ['http://rescue.shivacomputers.nl'],
            'with a path' => ['https://rescue.shivacomputers.nl/evil'],
            'with a query' => ['https://rescue.shivacomputers.nl?x=1'],
            'with a fragment' => ['https://rescue.shivacomputers.nl#x'],
            'with credentials' => ['https://user@rescue.shivacomputers.nl'],
            'javascript' => ['javascript:alert(1)'],
            'relative' => ['//evil.example'],
            'empty' => [''],
        ];
    }

    #[DataProvider('unsafeSiteUrls')]
    public function testUnsafeSiteUrlsAreRejected(string $url): void
    {
        self::assertFalse(ProductLinks::isSafeSiteUrl($url));
    }

    public function testLocalHttpIsOnlyAllowedInDevelopment(): void
    {
        self::assertFalse(ProductLinks::isSafeSiteUrl('http://localhost:8082'));
        self::assertTrue(ProductLinks::isSafeSiteUrl('http://localhost:8082', true));
        self::assertTrue(ProductLinks::isSafeSiteUrl('https://rescue.shivacomputers.nl'));
        self::assertTrue(ProductLinks::isSafeSiteUrl('https://rescue.shivacomputers.nl/'));
    }

    public function testAnUnsafeSettingStopsTheSiteInsteadOfLinkingAnywhere(): void
    {
        // The site answers with its normal error page; the bad link never reaches a visitor.
        $response = $this->get('/en', ['product_sites' => ['rescue' => 'http://evil.example', 'assist' => 'https://assist.shivacomputers.nl']]);

        self::assertSame(500, $response->status);
        self::assertStringNotContainsString('evil.example', $response->body);
    }

    public function testEveryProductHasTextInBothLanguages(): void
    {
        foreach (['nl', 'en'] as $language) {
            $texts = require self::ROOT . '/translations/' . $language . '.php';
            foreach (Product::ordered() as $product) {
                foreach (['tag', 'title', 'text', 'link'] as $field) {
                    self::assertArrayHasKey('products.' . $product->value . '.' . $field, $texts);
                }
            }
        }
    }
}
