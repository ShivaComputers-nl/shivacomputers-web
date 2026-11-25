<?php
/**
 * Local introduction to a support product. $productUrl is the configured, validated product origin.
 * @var App\ViewModels\PageViewModel $page
 * @var App\Framework\PageTranslator $t
 * @var string $textKey
 * @var string $productUrl
 * @var string $overviewUrl
 */
?>
<section class="page-heading page-heading--support">
    <div class="wrap">
        <p class="division-label"><a href="<?= e($overviewUrl) ?>"><?= e($t->text('division.support')) ?></a></p>
        <h1><?= e($t->text($textKey . '.title')) ?></h1>
        <p class="status-line"><?= e($t->text('division.status')) ?>: <span class="status-tag"><?= e($t->text($textKey . '.status')) ?></span></p>
        <p class="lead"><?= e($t->text($textKey . '.lead')) ?></p>
    </div>
</section>

<section class="section section--white">
    <div class="wrap">
        <ul class="service-list">
<?php foreach ($t->items($textKey . '.sections') as $section): ?>
            <li><h2 class="service-list__title"><?= e($section['title']) ?></h2><p><?= e($section['text']) ?></p></li>
<?php endforeach; ?>
        </ul>
        <div class="actions">
            <a class="button button--support" href="<?= e($productUrl) ?>" rel="noopener"><?= e($t->text($textKey . '.link')) ?><span aria-hidden="true"> &#8599;</span></a>
            <a class="text-link" href="<?= e($page->url('/contact')) ?>"><?= e($t->text('cta.button')) ?></a>
        </div>
        <p class="more-link"><a class="text-link" href="<?= e($overviewUrl) ?>"><?= e($t->text('division.support_back')) ?></a></p>
    </div>
</section>

<?= $this->partial('Partials/cta', compact('page', 't')) ?>
