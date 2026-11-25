<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Enums\Language;
use App\Enums\Page;
use App\Framework\Response;
use App\Framework\Translator;
use App\Framework\View;
use App\Services\Interfaces\IProjectService;
use App\ViewModels\PageViewModelFactory;

/**
 * Pages of the Software Design & Development division.
 * Each handler maps to one fixed Page and one fixed text group; nothing is taken from the request.
 */
final class DevelopmentController extends Controller
{
    /** Service detail pages in display order => their text group in translations/. */
    private const SERVICES = [
        'websites' => Page::Websites,
        'web_applications' => Page::WebApplications,
        'custom_software' => Page::CustomSoftware,
        'automation' => Page::Automation,
    ];

    public function __construct(
        View $view,
        PageViewModelFactory $pages,
        Translator $translator,
        private readonly IProjectService $projects,
    ) {
        parent::__construct($view, $pages, $translator);
    }

    // GET: /{language}/software-development
    public function index(Language $language): Response
    {
        $services = [];
        foreach (self::SERVICES as $key => $page) {
            $services[] = ['key' => $key, 'url' => $page->path($language)];
        }

        return $this->render($language, Page::Development, [
            'services' => $services,
            'projects' => $this->projects->getFeatured($language),
        ]);
    }

    // GET: /{language}/software-development/websites
    public function websites(Language $language): Response
    {
        return $this->service($language, 'websites');
    }

    // GET: /{language}/software-development/web-applications
    public function webApplications(Language $language): Response
    {
        return $this->service($language, 'web_applications');
    }

    // GET: /{language}/software-development/custom-software
    public function customSoftware(Language $language): Response
    {
        return $this->service($language, 'custom_software');
    }

    // GET: /{language}/software-development/automation
    public function automation(Language $language): Response
    {
        return $this->service($language, 'automation');
    }

    private function service(Language $language, string $key): Response
    {
        return $this->render($language, self::SERVICES[$key], [
            'textKey' => 'development.' . $key,
            'overviewUrl' => Page::Development->path($language),
        ]);
    }
}
