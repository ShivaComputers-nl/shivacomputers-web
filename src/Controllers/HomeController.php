<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Enums\Language;
use App\Enums\Page;
use App\Enums\Product;
use App\Framework\Response;
use App\Framework\Translator;
use App\Framework\View;
use App\Services\Interfaces\IOfferingService;
use App\Services\Interfaces\IProjectService;
use App\ViewModels\PageViewModelFactory;
use App\ViewModels\ProductLinks;

final class HomeController extends Controller
{
    public function __construct(
        View $view,
        PageViewModelFactory $pages,
        Translator $translator,
        private readonly IOfferingService $offerings,
        private readonly IProjectService $projects,
        private readonly ProductLinks $products,
    ) {
        parent::__construct($view, $pages, $translator);
    }

    // GET: /
    public function redirectToDefaultLanguage(): Response
    {
        return Response::redirect(Page::Home->path(Language::DEFAULT));
    }

    // GET: /{language}
    public function index(Language $language): Response
    {
        return $this->render($language, Page::Home, [
            // Two equal primary entries, always in this order.
            'divisions' => [
                ['key' => 'development', 'url' => Page::Development->path($language)],
                ['key' => 'support', 'url' => Page::Support->path($language)],
            ],
            // Support products link to their local introduction pages; external origins stay on those pages.
            'supportProducts' => [
                ['key' => 'assist', 'product' => Product::Assist, 'url' => $this->products->introductionUrl(Product::Assist, $language)],
                ['key' => 'recovery', 'product' => Product::Rescue, 'url' => $this->products->introductionUrl(Product::Rescue, $language)],
                ['key' => 'remote', 'product' => Product::RemoteSupport, 'url' => $this->products->introductionUrl(Product::RemoteSupport, $language)],
            ],
            'offerings' => $this->offerings->getAll($language),
            'projects' => $this->projects->getFeatured($language),
        ]);
    }
}
