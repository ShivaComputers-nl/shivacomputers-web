<?php
/**
 * @var App\ViewModels\PageViewModel $page
 * @var App\Framework\PageTranslator $t
 * @var string $overviewUrl
 */
?>
<section class="page-heading page-heading--support">
    <div class="wrap">
        <p class="division-label"><a href="<?= e($overviewUrl) ?>"><?= e($t->text('division.support')) ?></a></p>
        <h1><?= e($t->text('support.repair.title')) ?></h1>
        <p class="lead"><?= e($t->text('support.repair.lead')) ?></p>
    </div>
</section>

<section class="section section--white">
    <div class="wrap">
        <h2 class="section-heading"><?= e($t->text('support.repair.steps_heading')) ?></h2>
        <ol class="steps steps--support">
<?php foreach ($t->items('support.repair.steps') as $step): ?>
            <li><h3><?= e($step['title']) ?></h3><p><?= e($step['text']) ?></p></li>
<?php endforeach; ?>
        </ol>
        <p class="note"><?= e($t->text('support.repair.warning')) ?></p>
        <p class="more-link"><a class="text-link" href="<?= e($overviewUrl) ?>"><?= e($t->text('division.support_back')) ?></a></p>
    </div>
</section>

<?= $this->partial('Partials/cta', compact('page', 't')) ?>
