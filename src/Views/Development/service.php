<?php
/**
 * One development service. $textKey is a fixed text group chosen by the controller, never request input.
 * @var App\ViewModels\PageViewModel $page
 * @var App\Framework\PageTranslator $t
 * @var string $textKey
 * @var string $overviewUrl
 */
?>
<section class="page-heading page-heading--development">
    <div class="wrap">
        <p class="division-label"><a href="<?= e($overviewUrl) ?>"><?= e($t->text('division.development')) ?></a></p>
        <h1><?= e($t->text($textKey . '.title')) ?></h1>
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
        <p class="more-link"><a class="text-link" href="<?= e($overviewUrl) ?>"><?= e($t->text('division.development_back')) ?></a></p>
    </div>
</section>

<?= $this->partial('Partials/cta', compact('page', 't')) ?>
