<?php

declare(strict_types=1);

namespace App\Tests\Feature;

use App\Framework\Application;
use App\Framework\Request;
use App\Framework\Response;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/** HOME-01–12: one brand, two equal divisions, portfolio and support products, honest status, both languages. */
final class HomepageArchitectureTest extends TestCase
{
    private function get(string $path): Response
    {
        /** @var Application $application */
        $application = require __DIR__ . '/../../config/bootstrap.php';

        return $application->handle(new Request('GET', $path));
    }

    /** The markup of one element, from its opening tag to the next closing tag of that name. */
    private static function element(string $body, string $opening, string $closing): string
    {
        $start = (int) strpos($body, $opening);

        return substr($body, $start, (int) strpos($body, $closing, $start) - $start);
    }

    public static function languages(): array
    {
        return [
            'English' => ['en', 'Building software. Solving computer problems.', 'Explore software development', 'Explore computer support'],
            'Dutch' => ['nl', 'Wij ontwikkelen software. Wij lossen computerproblemen op.', 'Ontdek onze softwarediensten', 'Ontdek onze computerondersteuning'],
        ];
    }

    #[DataProvider('languages')]
    public function testExactlyTwoEqualDivisionsInFixedOrder(string $language, string $title, string $developmentCta, string $supportCta): void
    {
        $body = $this->get('/' . $language)->body;
        $divisions = self::element($body, 'class="section divisions"', '</section>');

        self::assertSame(1, substr_count($body, '<h1'));
        self::assertStringContainsString('<h1>' . $title . '</h1>', $body);
        self::assertSame(2, substr_count($divisions, '<article class="division-card '));
        self::assertSame(2, substr_count($divisions, 'class="button division-card__cta"'));
        self::assertSame(2, substr_count($divisions, '<a class="button division-card__cta" href='), 'Both CTAs share one class set');
        self::assertSame(2, substr_count($divisions, '<h2 id="division-'));

        $development = strpos($divisions, 'division-card--development');
        $support = strpos($divisions, 'division-card--support');
        self::assertNotFalse($development);
        self::assertNotFalse($support);
        self::assertLessThan($support, $development);

        self::assertStringContainsString('href="/' . $language . '/software-development">' . $developmentCta . '</a>', $divisions);
        self::assertStringContainsString('href="/' . $language . '/computer-support">' . $supportCta . '</a>', $divisions);
    }

    public function testBothDivisionCardsHaveTheSameStructure(): void
    {
        $divisions = self::element($this->get('/en')->body, 'class="section divisions"', '</section>');
        preg_match_all('#<article class="division-card division-card--(\w+)".*?</article>#s', $divisions, $cards);

        self::assertCount(2, $cards[0]);
        $shape = static fn (string $card): string => (string) preg_replace(['#>[^<]*<#', '#"[^"]*"#'], ['><', '""'], $card);
        self::assertSame($shape($cards[0][0]), $shape($cards[0][1]), 'Same elements, classes aside from accent, and CTA');
        self::assertSame(4, substr_count($cards[0][0], '<li>'));
        self::assertSame(4, substr_count($cards[0][1], '<li>'));
    }

    #[DataProvider('languages')]
    public function testPortfolioAndSupportProductsAreSecondarySections(string $language): void
    {
        $body = $this->get('/' . $language)->body;
        $projects = self::element($body, 'id="home-projects"', '</section>');
        $products = self::element($body, 'id="home-products"', '</section>');

        self::assertGreaterThanOrEqual(1, substr_count($projects, '<article class="project">'));
        self::assertStringContainsString('href="/' . $language . '/portfolio"', $projects);
        self::assertSame(3, substr_count($products, 'class="product-card '));
        self::assertStringNotContainsString('https://', $products, 'Home page product links are local introductions');
    }

    public function testStatusIsShownAsTextAndHonest(): void
    {
        $products = self::element($this->get('/en')->body, 'id="home-products"', '</section>');

        self::assertSame(2, substr_count($products, '<span class="product-tag">In development</span>'));
        self::assertStringNotContainsStringIgnoringCase('guarantee', $products);
        self::assertStringNotContainsStringIgnoringCase('download', $products);
    }

    #[DataProvider('languages')]
    public function testNoInfrastructureAndNoScripts(string $language): void
    {
        $body = $this->get('/' . $language)->body;

        self::assertStringNotContainsStringIgnoringCase('infrastructu', $body);
        self::assertStringNotContainsString('<script src', $body);
        self::assertStringContainsString('application/ld+json', $body, 'Organisation data stays on the home page');
    }

    public function testSharedContactInvitationAndFooterRemain(): void
    {
        $body = $this->get('/nl')->body;

        self::assertStringContainsString('Vertel ons over uw softwareproject of computerprobleem.', $body);
        self::assertStringContainsString('href="/nl/contact"', $body);
        self::assertStringContainsString('href="/nl/remote-support"', substr($body, (int) strpos($body, '<footer')));
        self::assertStringContainsString('href="/nl/privacy"', substr($body, (int) strpos($body, '<footer')));
    }

    public function testDivisionCtaTargetsAnswer(): void
    {
        $body = $this->get('/en')->body;
        preg_match_all('#href="(/en[^"]*)"#', self::element($body, '<main', '</main>'), $links);

        foreach (array_unique($links[1]) as $link) {
            self::assertSame(200, $this->get($link)->status, $link);
        }
    }
}
