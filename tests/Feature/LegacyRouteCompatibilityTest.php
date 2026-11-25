<?php

declare(strict_types=1);

namespace App\Tests\Feature;

use App\Framework\Application;
use App\Framework\Request;
use App\Framework\Response;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Compatibility option A (SC-MD03): the existing /services page keeps answering 200 in both languages,
 * because the production smoke check in .github/workflows/deploy.yml expects exactly that. No 301 yet.
 */
final class LegacyRouteCompatibilityTest extends TestCase
{
    private function get(string $path): Response
    {
        /** @var Application $application */
        $application = require __DIR__ . '/../../config/bootstrap.php';

        return $application->handle(new Request('GET', $path));
    }

    public static function languages(): array
    {
        return ['en' => ['en'], 'nl' => ['nl']];
    }

    #[DataProvider('languages')]
    public function testServicesStaysACanonicalPageWithoutRedirect(string $language): void
    {
        $response = $this->get('/' . $language . '/services');
        $baseUrl = (require __DIR__ . '/../../config/app.php')['base_url'];

        self::assertSame(200, $response->status);
        self::assertArrayNotHasKey('Location', $response->headers);
        self::assertStringContainsString('rel="canonical" href="' . $baseUrl . '/' . $language . '/services"', $response->body);
    }

    #[DataProvider('languages')]
    public function testServicesKeepsTheRemoteSupportLinkAndPointsToTheNewOverview(string $language): void
    {
        $body = $this->get('/' . $language . '/services')->body;

        self::assertStringContainsString('href="/' . $language . '/remote-support"', $body);
        self::assertStringContainsString('href="/' . $language . '/software-development"', $body);
    }

    public function testDeploySmokeCheckPathsStillAnswer(): void
    {
        foreach (['/en', '/nl', '/en/services', '/en/remote-support', '/en/contact'] as $path) {
            self::assertSame(200, $this->get($path)->status, $path);
        }
    }

    public function testHelpShortcutsAreUnchanged(): void
    {
        foreach (['/help' => '/en/remote-support', '/hulp' => '/nl/remote-support'] as $path => $target) {
            $response = $this->get($path);
            self::assertSame(302, $response->status);
            self::assertSame($target, $response->headers['Location']);
            self::assertSame('no-store', $response->headers['Cache-Control']);
        }
    }

    public function testRootStillRedirectsToEnglish(): void
    {
        $response = $this->get('/');

        self::assertSame(302, $response->status);
        self::assertSame('/en', $response->headers['Location']);
    }
}
