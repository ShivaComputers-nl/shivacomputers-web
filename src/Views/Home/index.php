<?php
/**
 * @var App\ViewModels\PageViewModel $page
 * @var App\Framework\PageTranslator $t
 * @var list<array{key: string, url: string}> $divisions
 * @var list<array{key: string, product: App\Enums\Product, url: string}> $supportProducts
 * @var list<App\Models\Project> $projects
 */
?>
<section class="hero hero--neutral">
    <div class="wrap">
        <div class="hero-main">
            <h1><?= e($t->text('home.title')) ?></h1>
            <p class="lead"><?= e($t->text('home.lead')) ?></p>
        </div>
    </div>
</section>

<section class="section divisions" aria-label="<?= e($t->text('home.divisions_label')) ?>">
    <div class="wrap division-grid">
<?php foreach ($divisions as $division): ?>
        <?= $this->partial('Partials/division-card', ['t' => $t, 'key' => $division['key'], 'url' => $division['url']]) ?>
<?php endforeach; ?>
    </div>
</section>

<div class="secondary-grid wrap">
    <section class="secondary secondary--development" aria-labelledby="home-projects">
        <h2 class="section-heading" id="home-projects"><?= e($t->text('home.projects_heading')) ?></h2>
        <p class="section-intro"><?= e($t->text('portfolio.intro')) ?></p>
        <div class="project-grid project-grid--compact">
<?php foreach ($projects as $project): ?>
            <?= $this->partial('Partials/project-card', compact('project', 'page', 't')) ?>
<?php endforeach; ?>
        </div>
        <p class="more-link"><a class="text-link" href="<?= e($page->url('/portfolio')) ?>"><?= e($t->text('home.portfolio_link')) ?></a></p>
    </section>

    <section class="secondary secondary--support products" aria-labelledby="home-products">
        <h2 class="section-heading" id="home-products"><?= e($t->text('home.products_heading')) ?></h2>
        <p class="section-intro"><?= e($t->text('home.products_intro')) ?></p>
        <ul class="product-grid product-grid--stacked">
<?php foreach ($supportProducts as $item): ?>
            <li><?= $this->partial('Partials/product-card', ['t' => $t, 'key' => $item['key'], 'product' => $item['product'], 'url' => $item['url']]) ?></li>
<?php endforeach; ?>
        </ul>
    </section>
</div>

<?= $this->partial('Partials/cta', compact('page', 't')) ?>
