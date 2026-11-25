<?php

declare(strict_types=1);

namespace App\Tests\Feature;

use App\Framework\Application;
use App\Framework\Request;
use App\Framework\Response;
use PHPUnit\Framework\TestCase;

final class SeoTest extends TestCase
{
    private function get(string $path): Response
    {
        /** @var Application $application */
        $application = require __DIR__ . '/../../config/bootstrap.php';

        return $application->handle(new Request('GET', $path));
    }

    private function baseUrl(): string
    {
        return (require __DIR__ . '/../../config/app.php')['base_url'];
    }

    public function testRobotsTxtPointsToTheSitemap(): void
    {
        $response = $this->get('/robots.txt');

        self::assertSame(200, $response->status);
        self::assertStringStartsWith('text/plain', $response->headers['Content-Type']);
        self::assertStringContainsString('User-agent: *', $response->body);
        self::assertStringContainsString('Sitemap: ' . $this->baseUrl() . '/sitemap.xml', $response->body);
    }

    public function testSitemapListsEveryPageInBothLanguages(): void
    {
        $response = $this->get('/sitemap.xml');

        self::assertSame(200, $response->status);
        self::assertStringStartsWith('application/xml', $response->headers['Content-Type']);

        $xml = simplexml_load_string($response->body);
        self::assertNotFalse($xml);
        $locations = [];
        foreach ($xml->url as $url) {
            $locations[] = (string) $url->loc;
        }

        // Exact transitional set (compatibility option A): 7 existing + 9 division pages, per language.
        $suffixes = ['', '/services', '/portfolio', '/about', '/contact', '/privacy', '/remote-support',
            '/software-development', '/software-development/websites', '/software-development/web-applications',
            '/software-development/custom-software', '/software-development/automation',
            '/computer-support', '/computer-support/assist', '/computer-support/recovery', '/computer-support/repair-services'];
        $expected = [];
        foreach ($suffixes as $suffix) {
            foreach (['nl', 'en'] as $language) {
                $expected[] = $this->baseUrl() . '/' . $language . $suffix;
            }
        }
        sort($expected);
        sort($locations);

        self::assertCount(32, $locations);
        self::assertSame($expected, $locations);
    }

    public function testHomePageDescribesTheBusinessForSearchEngines(): void
    {
        $body = $this->get('/en')->body;

        self::assertSame(1, preg_match('#<script type="application/ld\+json">(.+?)</script>#s', $body, $match));
        $data = json_decode($match[1], true, flags: JSON_THROW_ON_ERROR);

        self::assertSame('ProfessionalService', $data['@type']);
        self::assertSame('Shiva Computers', $data['name']);
        self::assertSame('NL', $data['address']['addressCountry']);
        self::assertStringContainsString('property="og:title"', $body);
    }

    public function testRobotsMetaAndCanonicalOnEverySitemapPage(): void
    {
        $xml = simplexml_load_string($this->get('/sitemap.xml')->body);
        foreach ($xml->url as $url) {
            $location = (string) $url->loc;
            $path = substr($location, strlen($this->baseUrl()));
            $body = $this->get($path)->body;

            self::assertStringContainsString('rel="canonical" href="' . $location . '"', $body, $path);
            self::assertStringContainsString('<meta name="robots" content="index,follow">', $body, $path);
            self::assertSame(3, substr_count($body, 'rel="alternate" hreflang='), $path);
            self::assertSame(3, count($url->children('http://www.w3.org/1999/xhtml')), $path);
        }
    }

    public function testNotFoundIsNotIndexedAndHasNoCanonical(): void
    {
        $body = $this->get('/en/computer-support/infrastructure')->body;

        self::assertStringContainsString('noindex', $body);
        self::assertStringNotContainsString('rel="canonical"', $body);
    }

    public function testOtherPagesHaveNoBusinessData(): void
    {
        self::assertStringNotContainsString('application/ld+json', $this->get('/en/services')->body);
    }
}
