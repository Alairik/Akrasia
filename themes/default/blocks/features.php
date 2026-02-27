<?php defined('ZVELE_CMS') or die(); ?>
<?php
// Pole: title, subtitle, section_class, deco (array), prvek (bool), photo (filename)
// columns: '3' (default) nebo '4'; items[] = {icon_svg, title, text, link_text, link_url}
$cols         = $columns ?? '3';
$gridClass    = $cols === '4' ? 'cards-grid--4 cards-grid' : 'cards-grid';
$sectionClass = $section_class ?? '';
$deco         = $deco ?? null;
$usePrvek     = $prvek ?? false;
$photo        = $photo ?? null;
?>

<section class="section <?= e($sectionClass) ?>" style="position:relative;overflow:hidden">
    <?php if ($usePrvek): ?>
    <img src="<?= asset('assets/brand/prvek_vertical.svg') ?>" class="prvek-v prvek-v--left" aria-hidden="true" alt="">
    <?php endif; ?>
    <?php if (!empty($deco)): echo deco_html($deco); endif; ?>
    <?php if (!empty($photo)): ?>
    <div class="deco-layer" aria-hidden="true">
        <div class="deco-item" style="bottom:5%;right:18%;transform:rotate(-8deg);opacity:0.55">
            <img src="<?= asset('assets/brand/' . e($photo)) ?>" width="180" alt="">
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
        <div class="<?= $gridClass ?>">
            <?php foreach ($items as $item): ?>
            <div class="card fade-up">
                <?php if (!empty($item['icon_svg'])): ?>
                <div class="card-icon card-icon--navy" aria-hidden="true">
                    <?= $item['icon_svg'] ?>
                </div>
                <?php endif; ?>
                <?php if (!empty($item['title'])): ?><h3><?= e($item['title']) ?></h3><?php endif; ?>
                <?php if (!empty($item['text'])): ?><p><?= e($item['text']) ?></p><?php endif; ?>
                <?php if (!empty($item['link_text']) && !empty($item['link_url'])): ?>
                <a href="<?= e($item['link_url']) ?>" class="read-more" style="margin-top:var(--space-4)">
                    <?= e($item['link_text']) ?>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                </a>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>
