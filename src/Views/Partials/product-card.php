<?php
/**
 * A support product on the home page, linking to its local introduction page.
 * @var App\Framework\PageTranslator $t
 * @var string $key 'assist', 'recovery' or 'remote' (fixed by the controller)
 * @var App\Enums\Product $product
 * @var string $url fixed local route
 */
$id = 'product-' . $key;
?>
<a class="product-card product-card--<?= e($product->value) ?>" href="<?= e($url) ?>" aria-labelledby="<?= e($id) ?>">
    <span class="product-top">
        <span class="product-icon"><?= $this->partial('Partials/product-icon', ['product' => $product]) ?></span>
    </span>
    <span class="product-tag"><?= e($t->text('support.' . $key . '.status')) ?></span>
    <h3 id="<?= e($id) ?>"><?= e($t->text('support.' . $key . '.title')) ?></h3>
    <p><?= e($t->text('support.' . $key . '.summary')) ?></p>
    <span class="product-link"><?= e($t->text('division.read_more')) ?><span aria-hidden="true"> &#8594;</span></span>
</a>
