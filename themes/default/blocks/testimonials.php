<?php defined('ZVELE_CMS') or die(); ?>
<?php
// Pole: title, subtitle, section_class, deco (array indexů pro deco_html), prvek (bool), photo, button_text, button_url
// items[] = {quote, author, role}
$sectionClass = $section_class ?? 'section--mint';
$deco         = $deco ?? null;
$usePrvek     = $prvek ?? false;
$photo        = $photo ?? null;
?>

<section class="section <?= e($sectionClass) ?>" style="position:relative;overflow:hidden" aria-label="Příběhy">
    <?php if ($usePrvek): ?>
    <img src="<?= asset('assets/brand/prvek_vertical.svg') ?>" class="prvek-v prvek-v--left" aria-hidden="true" alt="">
    <?php endif; ?>
    <?php if (!empty($deco)): echo deco_html($deco); endif; ?>
    <?php if (!empty($photo)): ?>
    <div class="deco-layer" aria-hidden="true">
        <div class="deco-item" style="bottom:-10px;right:-10px;opacity:0.7">
            <img src="<?= asset('assets/brand/' . e($photo)) ?>" width="220" alt="">
        </div>
    </div>
    <?php endif; ?>
    <div class="container">
        <?php if (!empty($title) || !empty($subtitle)): ?>
        <div class="section-header">
            <?php if (!empty($title)): ?><h2><?= e($title) ?></h2><?php endif; ?>
            <?php if (!empty($subtitle)): ?><p><?= e($subtitle) ?></p><?php endif; ?>
        </div>
        <?php endif; ?>
        <?php if (!empty($items)): ?>
        <div class="testimonials-grid">
            <?php foreach ($items as $item): ?>
            <div class="testimonial-card fade-up">
                <div class="testimonial-quote" aria-hidden="true">"</div>
                <p class="testimonial-text"><?= e($item['quote'] ?? '') ?></p>
                <div class="testimonial-author"><?= e($item['author'] ?? '') ?></div>
                <?php if (!empty($item['role'])): ?>
                <div class="testimonial-role"><?= e($item['role']) ?></div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
        <?php if (!empty($button_text) && !empty($button_url)): ?>
        <div style="text-align:center;margin-top:var(--space-10)">
            <a href="<?= e($button_url) ?>" class="btn btn-secondary"><?= e($button_text) ?></a>
        </div>
        <?php endif; ?>
    </div>
</section>
