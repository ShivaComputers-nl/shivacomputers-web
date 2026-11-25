<?php
/**
 * @var App\ViewModels\PageViewModel $page
 * @var App\Framework\PageTranslator $t
 * @var list<array{key: string, url: string}> $items
 */
?>
<section class="page-heading page-heading--support">
    <div class="wrap">
        <p class="division-label"><?= e($t->text('division.support')) ?></p>
        <h1><?= e($t->text('support.title')) ?></h1>
        <p class="lead"><?= e($t->text('support.lead')) ?></p>
    </div>
</section>

<section class="section section--white" aria-labelledby="support-options">
    <div class="wrap">
        <h2 class="section-heading" id="support-options"><?= e($t->text('support.options_heading')) ?></h2>
        <ul class="info-grid">
<?php foreach ($items as $item): ?>
            <li>
                <a class="info-card info-card--support" href="<?= e($item['url']) ?>">
                    <span class="status-tag"><?= e($t->text('support.' . $item['key'] . '.status')) ?></span>
                    <h3><?= e($t->text('support.' . $item['key'] . '.title')) ?></h3>
                    <p><?= e($t->text('support.' . $item['key'] . '.summary')) ?></p>
                    <span class="info-link"><?= e($t->text('division.read_more')) ?><span aria-hidden="true"> &#8594;</span></span>
                </a>
            </li>
<?php endforeach; ?>
        </ul>
    </div>
</section>

<?= $this->partial('Partials/cta', compact('page', 't')) ?>
