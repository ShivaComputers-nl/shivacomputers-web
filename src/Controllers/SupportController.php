<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Enums\Language;
use App\Enums\Page;
use App\Enums\Product;
use App\Framework\Response;
use App\Framework\Translator;
use App\Framework\View;
use App\ViewModels\PageViewModelFactory;
use App\ViewModels\ProductLinks;

/**
 * Pages of the Computer Repair & Support division.
 * Product pages are local introductions; the only outbound links are the configured, validated product origins.
 */
final class SupportController extends Controller
{
    public function __construct(
        View $view,
        PageViewModelFactory $pages,
        Translator $translator,
        private readonly ProductLinks $products,
    ) {
        parent::__construct($view, $pages, $translator);
    }

    // GET: /{language}/computer-support
    public function index(Language $language): Response
    {
        return $this->render($language, Page::Support, [
            'items' => [
                ['key' => 'assist', 'url' => Page::Assist->path($language)],
                ['key' => 'recovery', 'url' => Page::Recovery->path($language)],
                ['key' => 'remote', 'url' => Page::RemoteSupport->path($language)],
                ['key' => 'repair', 'url' => Page::RepairServices->path($language)],
            ],
        ]);
    }

    // GET: /{language}/computer-support/assist
    public function assist(Language $language): Response
    {
        return $this->product($language, Page::Assist, 'assist', Product::Assist);
    }

    // GET: /{language}/computer-support/recovery
    public function recovery(Language $language): Response
    {
        return $this->product($language, Page::Recovery, 'recovery', Product::Rescue);
    }

    // GET: /{language}/computer-support/repair-services
    public function repairServices(Language $language): Response
    {
        return $this->render($language, Page::RepairServices, [
            'overviewUrl' => Page::Support->path($language),
        ]);
    }

    private function product(Language $language, Page $page, string $key, Product $product): Response
    {
        return $this->render($language, $page, [
            'textKey' => 'support.' . $key,
            'productUrl' => $this->products->url($product, $language),
            'overviewUrl' => Page::Support->path($language),
        ]);
    }
}
