<?php
/**
 * One of the two equal primary division entries on the home page.
 * @var App\Framework\PageTranslator $t
 * @var string $key 'development' or 'support' (fixed by the controller)
 * @var string $url fixed local route
 */
$id = 'division-' . $key;
?>
<article class="division-card division-card--<?= e($key) ?>" aria-labelledby="<?= e($id) ?>">
    <p class="division-card__label"><?= e($t->text('home.' . $key . '.label')) ?></p>
    <h2 id="<?= e($id) ?>"><?= e($t->text('division.' . $key)) ?></h2>
    <p class="division-card__text"><?= e($t->text('home.' . $key . '.text')) ?></p>
    <ul class="division-card__list">
<?php foreach ($t->items('home.' . $key . '.items') as $item): ?>
        <li><?= e($item['text']) ?></li>
<?php endforeach; ?>
    </ul>
    <a class="button division-card__cta" href="<?= e($url) ?>"><?= e($t->text('home.' . $key . '.cta')) ?></a>
</article>
