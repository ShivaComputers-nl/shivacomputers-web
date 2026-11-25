<?php

declare(strict_types=1);

namespace App\Tests\Feature;

use App\Framework\Application;
use App\Framework\Container;
use App\Framework\Request;
use App\Framework\Response;
use App\Framework\Router;
use App\Middleware\SecurityHeadersMiddleware;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use function FastRoute\simpleDispatcher;

/** The nine division pages: Software Design & Development and Computer Repair & Support. */
final class DivisionRoutesTest extends TestCase
{
    private const ROOT = __DIR__ . '/../..';

    /** suffix => [English H1, Dutch H1] */
    private const PAGES = [
        '/software-development' => ['Software Design &amp; Development', 'Softwareontwerp en -ontwikkeling'],
        '/software-development/websites' => ['Website development', 'Websiteontwikkeling'],
        '/software-development/web-applications' => ['Web applications', 'Webapplicaties'],
        '/software-development/custom-software' => ['Custom software', 'Software op maat'],
        '/software-development/automation' => ['Automation &amp; integration', 'Automatisering en integratie'],
        '/computer-support' => ['Computer Repair &amp; Support', 'Computerreparatie en ondersteuning'],
        '/computer-support/assist' => ['Shiva Assist', 'Shiva Assist'],
        '/computer-support/recovery' => ['Shiva Recovery', 'Shiva Recovery'],
        '/computer-support/repair-services' => ['Computer repair services', 'Reparatiediensten'],
    ];

    /** @param array<string, mixed> $overrides */
    private function get(string $path, array $overrides = [], string $method = 'GET'): Response
    {
        $config = $overrides + (require self::ROOT . '/config/app.php');
        $container = new Container();
        (require self::ROOT . '/config/services.php')($container, $config, realpath(self::ROOT));
        $application = new Application(
            new Router(simpleDispatcher(require self::ROOT . '/config/routes.php')),
            $container,
            [new SecurityHeadersMiddleware()],
        );

        return $application->handle(new Request($method, $path));
    }

    private function baseUrl(): string
    {
        return (require self::ROOT . '/config/app.php')['base_url'];
    }

    public static function pages(): array
    {
        $cases = [];
        foreach (self::PAGES as $suffix => [$english, $dutch]) {
            $cases['en' . $suffix] = ['en', $suffix, $english];
            $cases['nl' . $suffix] = ['nl', $suffix, $dutch];
        }

        return $cases;
    }

    #[DataProvider('pages')]
    public function testPageHasOneLocalisedHeadingAndCompleteMetadata(string $language, string $suffix, string $heading): void
    {
        $response = $this->get('/' . $language . $suffix);
        $body = $response->body;
        $base = $this->baseUrl();

        self::assertSame(200, $response->status);
        self::assertStringContainsString('<html lang="' . $language . '">', $body);
        self::assertSame(1, substr_count($body, '<h1'));
        self::assertStringContainsString('<h1>' . $heading . '</h1>', $body);
        self::assertStringContainsString('<title>' . $heading . ' | Shiva Computers</title>', $body);
        self::assertStringContainsString('rel="canonical" href="' . $base . '/' . $language . $suffix . '"', $body);
        self::assertStringContainsString('hreflang="nl" href="' . $base . '/nl' . $suffix . '"', $body);
        self::assertStringContainsString('hreflang="en" href="' . $base . '/en' . $suffix . '"', $body);
        self::assertStringContainsString('hreflang="x-default" href="' . $base . '/en' . $suffix . '"', $body);
        self::assertStringContainsString('<meta name="robots" content="index,follow">', $body);
        self::assertStringNotContainsString('application/ld+json', $body);
        self::assertStringNotContainsString('<script', $body);
        self::assertArrayHasKey('Content-Security-Policy', $response->headers);
    }

    #[DataProvider('pages')]
    public function testLanguageSwitchKeepsTheSamePage(string $language, string $suffix): void
    {
        $other = $language === 'en' ? 'nl' : 'en';

        self::assertStringContainsString('class="snav-lang" href="/' . $other . $suffix . '"', $this->get('/' . $language . $suffix)->body);
    }

    public function testTitlesAndDescriptionsAreUniquePerLanguage(): void
    {
        foreach (['en', 'nl'] as $language) {
            $titles = [];
            $descriptions = [];
            foreach ([...array_keys(self::PAGES), '', '/services', '/portfolio', '/about', '/contact', '/privacy', '/remote-support'] as $suffix) {
                $body = $this->get('/' . $language . $suffix)->body;
                preg_match('#<title>(.*?)</title>#', $body, $title);
                preg_match('#<meta name="description" content="(.*?)">#', $body, $description);
                $titles[] = $title[1];
                $descriptions[] = $description[1];
            }
            self::assertSame($titles, array_unique($titles), $language . ' titles');
            self::assertSame($descriptions, array_unique($descriptions), $language . ' descriptions');
        }
    }

    public function testDevelopmentOverviewLinksToEveryServiceAndThePortfolio(): void
    {
        $body = $this->get('/nl/software-development')->body;

        foreach (['websites', 'web-applications', 'custom-software', 'automation'] as $service) {
            self::assertStringContainsString('href="/nl/software-development/' . $service . '"', $body);
        }
        self::assertStringContainsString('href="/nl/portfolio"', $body);
        self::assertStringContainsString('href="/nl/contact"', $body);
    }

    public function testSupportOverviewGroupsTheThreeProductsAndRepairServices(): void
    {
        $body = $this->get('/en/computer-support')->body;

        self::assertSame(4, substr_count($body, 'class="info-card info-card--support"'));
        foreach (['/en/computer-support/assist', '/en/computer-support/recovery', '/en/remote-support', '/en/computer-support/repair-services'] as $link) {
            self::assertStringContainsString('href="' . $link . '"', $body);
        }
        self::assertStringNotContainsString('nfrastructure', $body);
    }

    public static function productPages(): array
    {
        return [
            'assist' => ['/en/computer-support/assist', 'https://assist.shivacomputers.nl/en', 'In development'],
            'recovery' => ['/nl/computer-support/recovery', 'https://rescue.shivacomputers.nl/nl', 'In ontwikkeling'],
        ];
    }

    #[DataProvider('productPages')]
    public function testProductIntroductionShowsStatusAndLinksOnlyToTheConfiguredOrigin(string $path, string $origin, string $status): void
    {
        $body = $this->get($path)->body;

        self::assertStringContainsString('<span class="status-tag">' . $status . '</span>', $body);
        self::assertStringContainsString('href="' . $origin . '" rel="noopener"', $body);
        self::assertStringNotContainsString('download', strtolower(strip_tags($body)));
    }

    public function testProductIntroductionUsesTheConfiguredOrigin(): void
    {
        $body = $this->get('/en/computer-support/recovery', ['product_sites' => ['rescue' => 'https://r.example.nl', 'assist' => 'https://a.example.nl']])->body;

        self::assertStringContainsString('href="https://r.example.nl/en" rel="noopener"', $body);
    }

    public function testUnsafeProductOriginStopsTheIntroductionPage(): void
    {
        $response = $this->get('/en/computer-support/assist', ['product_sites' => ['rescue' => 'https://rescue.shivacomputers.nl', 'assist' => 'javascript:alert(1)']]);

        self::assertSame(500, $response->status);
        self::assertStringNotContainsString('javascript:', $response->body);
    }

    public function testRepairPageWarnsAgainstSendingPasswords(): void
    {
        self::assertStringContainsString('Do not send passwords or sensitive files', $this->get('/en/computer-support/repair-services')->body);
        self::assertStringContainsString('Stuur geen wachtwoorden', $this->get('/nl/computer-support/repair-services')->body);
    }

    public function testTrailingSlashRedirectsToTheCleanDivisionUrl(): void
    {
        $response = $this->get('/en/computer-support/assist/');

        self::assertSame(301, $response->status);
        self::assertSame('/en/computer-support/assist', $response->headers['Location']);
    }

    public function testPostIsNotAllowedOnDivisionPages(): void
    {
        $response = $this->get('/en/software-development', [], 'POST');

        self::assertSame(405, $response->status);
        self::assertSame('GET, HEAD', $response->headers['Allow']);
    }
}
