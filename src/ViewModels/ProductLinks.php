<?php

declare(strict_types=1);

namespace App\ViewModels;

use App\Enums\Language;
use App\Enums\Page;
use App\Enums\Product;
use App\Framework\Translator;
use ShivaNav\Navigation;

// The address rule is shared with the menu kit, so both use exactly the same check.
require_once dirname(__DIR__, 2) . '/shiva-nav/Navigation.php';

/**
 * Resolves where each product lives, in the visitor's language:
 *   Software       → /{lang}/services
 *   Rescue OS      → https://rescue.shivacomputers.nl/{lang}
 *   Shiva Assist   → https://assist.shivacomputers.nl/{lang}
 *   Remote support → /{lang}/remote-support
 */
final class ProductLinks
{
    /** @param array<string, string> $siteUrls product value => base URL of its subdomain, e.g. 'rescue' => 'https://rescue.shivacomputers.nl' */
    public function __construct(
        private readonly Translator $translator,
        private readonly array $siteUrls,
        private readonly bool $allowLocalHttp = false,
    ) {
        foreach (Product::ordered() as $product) {
            if ($product->isExternal() && !self::isSafeSiteUrl($this->siteUrls[$product->value] ?? '', $this->allowLocalHttp)) {
                throw new \InvalidArgumentException(sprintf('Missing or unsafe site URL for product "%s"', $product->value));
            }
        }
    }

    public function url(Product $product, Language $language): string
    {
        $page = $product->page();

        return $page instanceof Page
            ? $page->path($language)
            : rtrim($this->siteUrls[$product->value], '/') . '/' . $language->value;
    }

    /**
     * The local introduction page of a product on this website (fixed route, never an external address).
     * Separate from url(), which keeps pointing to where the product itself lives.
     */
    public function introductionUrl(Product $product, Language $language): string
    {
        $page = match ($product) {
            Product::Software => Page::Development,
            Product::Rescue => Page::Recovery,
            Product::Assist => Page::Assist,
            Product::RemoteSupport => Page::RemoteSupport,
        };

        return $page->path($language);
    }

    /** @return list<ProductCard> */
    public function cards(Language $language): array
    {
        return array_map(
            fn (Product $product): ProductCard => new ProductCard(
                $product,
                $this->text($language, $product, 'tag'),
                $this->text($language, $product, 'title'),
                $this->text($language, $product, 'text'),
                $this->text($language, $product, 'link'),
                $this->url($product, $language),
                $product->isExternal(),
            ),
            Product::ordered(),
        );
    }

    /** Same rule as the shared menu: absolute https without path, query or credentials; http://localhost only in development. */
    public static function isSafeSiteUrl(string $url, bool $allowLocalHttp = false): bool
    {
        return Navigation::isSafeBaseUrl($url, $allowLocalHttp);
    }

    private function text(Language $language, Product $product, string $field): string
    {
        return $this->translator->text($language, 'products.' . $product->value . '.' . $field);
    }
}
