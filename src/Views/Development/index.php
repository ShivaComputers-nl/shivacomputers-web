<?php
/**
 * @var App\ViewModels\PageViewModel $page
 * @var App\Framework\PageTranslator $t
 * @var list<array{key: string, url: string}> $services
 * @var list<App\Models\Project> $projects
 */
?>
<section class="page-heading page-heading--development">
    <div class="wrap">
        <p class="division-label"><?= e($t->text('division.development')) ?></p>
        <h1><?= e($t->text('development.title')) ?></h1>
        <p class="lead"><?= e($t->text('development.lead')) ?></p>
    </div>
</section>

<section class="section section--white" aria-labelledby="development-services">
    <div class="wrap">
        <h2 class="section-heading" id="development-services"><?= e($t->text('development.services_heading')) ?></h2>
        <ul class="info-grid">
<?php foreach ($services as $service): ?>
            <li>
                <a class="info-card info-card--development" href="<?= e($service['url']) ?>">
                    <h3><?= e($t->text('development.' . $service['key'] . '.title')) ?></h3>
                    <p><?= e($t->text('development.' . $service['key'] . '.summary')) ?></p>
                    <span class="info-link"><?= e($t->text('division.read_more')) ?><span aria-hidden="true"> &#8594;</span></span>
                </a>
            </li>
<?php endforeach; ?>
        </ul>
    </div>
</section>

<section class="section" aria-labelledby="development-portfolio">
    <div class="wrap">
        <h2 class="section-heading" id="development-portfolio"><?= e($t->text('development.portfolio_heading')) ?></h2>
        <p class="section-intro"><?= e($t->text('portfolio.intro')) ?></p>
        <div class="project-grid">
<?php foreach ($projects as $project): ?>
            <?= $this->partial('Partials/project-card', compact('project', 'page', 't')) ?>
<?php endforeach; ?>
        </div>
        <p class="more-link"><a class="text-link" href="<?= e($page->url('/portfolio')) ?>"><?= e($t->text('home.portfolio_link')) ?></a></p>
    </div>
</section>

<section class="section section--white" aria-labelledby="development-approach">
    <div class="wrap">
        <h2 class="section-heading" id="development-approach"><?= e($t->text('development.approach_heading')) ?></h2>
        <ol class="steps">
<?php foreach ($t->items('development.approach') as $step): ?>
            <li><h3><?= e($step['title']) ?></h3><p><?= e($step['text']) ?></p></li>
<?php endforeach; ?>
        </ol>
    </div>
</section>

<?= $this->partial('Partials/cta', compact('page', 't')) ?>
